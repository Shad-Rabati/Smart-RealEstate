<?php

namespace Tests\Feature;

use App\Models\Agency;
use App\Models\Property;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RealEstateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }
    public function test_home_page_loads_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('خانوبەرەی');
        $response->assertSee('سلێمانی');
        $response->assertSee('VIP');
    }

    public function test_newly_featured_property_appears_first_in_vip_showcase(): void
    {
        cache()->flush();
        $property = Property::where('status', 'available')->first();
        $this->assertNotNull($property);

        // Mark as VIP now
        $property->update([
            'title' => 'خانووی نایابی VIP تایبەت بە سلێمانی',
            'is_featured' => true,
            'featured_at' => now(),
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('خانووی نایابی VIP تایبەت بە سلێمانی');
        $vipProps = $response->viewData('vipProperties');
        $this->assertEquals($property->id, $vipProps->first()->id);
    }

    public function test_properties_search_page_loads_successfully(): void
    {
        $response = $this->get(route('properties.index'));
        $response->assertStatus(200);
        $response->assertSee('گەڕان');
        $response->assertSee('نەخشە');
    }

    public function test_map_api_returns_geojson_and_pins(): void
    {
        $response = $this->get('/api/properties/map');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'count',
            'properties' => [
                '*' => [
                    'id',
                    'title',
                    'status',
                    'status_color',
                    'status_hex',
                    'latitude',
                    'longitude',
                    'agency',
                ]
            ]
        ]);
    }

    public function test_property_details_page_loads(): void
    {
        $property = Property::first();
        $this->assertNotNull($property);

        $response = $this->get('/properties/' . $property->slug);
        $response->assertStatus(200);
        $response->assertSee($property->title);
    }

    public function test_property_with_video_tour_displays_video_section(): void
    {
        $property = Property::first();
        $this->assertNotNull($property);

        $property->update([
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        ]);

        $response = $this->get('/properties/' . $property->slug);
        $response->assertStatus(200);
        $response->assertSee('گەشتی ڤیدیۆیی');
        $response->assertSee('https://www.youtube.com/embed/dQw4w9WgXcQ');
    }

    public function test_agencies_directory_and_single_agency_load(): void
    {
        $agency = Agency::first();
        $this->assertNotNull($agency);

        $response = $this->get('/agencies');
        $response->assertStatus(200);
        $response->assertSee($agency->name);

        $responseSingle = $this->get('/agencies/' . $agency->slug);
        $responseSingle->assertStatus(200);
        $responseSingle->assertSee($agency->name);
    }

    public function test_agency_auth_and_dashboard(): void
    {
        $user = User::where('email', 'rebin@estate.test')->first();
        $this->assertNotNull($user);

        // Guest cannot access dashboard
        $responseGuest = $this->get('/agency/dashboard');
        $responseGuest->assertRedirect('/agency/login');

        // Authenticated agency can access dashboard
        $responseAuth = $this->actingAs($user)->get('/agency/dashboard');
        $responseAuth->assertStatus(200);
        $responseAuth->assertSee('پانێڵی فەرمی نووسینگە');
    }

    public function test_distance_calculation(): void
    {
        $property = Property::first();
        $distance = $property->distanceTo(35.5558, 45.4351);
        $this->assertIsFloat($distance);
        $this->assertGreaterThanOrEqual(0, $distance);
    }

    public function test_agency_registration_creates_unverified_agency_and_redirects_to_pending_screen(): void
    {
        $response = $this->post('/agency/register', [
            'name' => 'سەردار فەتاح',
            'agency_name' => 'نووسینگەی سەردار',
            'email' => 'sardar@estate.test',
            'phone' => '07705554433',
            'address' => 'سلێمانی، ڕاپەڕین',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirect(route('agency.pending'));
        $this->assertDatabaseHas('agencies', [
            'name' => 'نووسینگەی سەردار',
            'verified' => false,
        ]);

        $unverifiedUser = User::where('email', 'sardar@estate.test')->first();
        $this->assertNotNull($unverifiedUser);

        // Blocked from dashboard
        $dashResponse = $this->actingAs($unverifiedUser)->get('/agency/dashboard');
        $dashResponse->assertRedirect(route('agency.pending'));

        // Blocked from adding properties
        $createPropResponse = $this->actingAs($unverifiedUser)->get('/agency/properties/create');
        $createPropResponse->assertRedirect(route('agency.pending'));

        // Pending page loads with the notice
        $pendingPage = $this->actingAs($unverifiedUser)->get(route('agency.pending'));
        $pendingPage->assertStatus(200);
        $pendingPage->assertSee('تکایە چاوەڕوان بە');
        $pendingPage->assertSee('لە ماوەیەکی کەمدا لەلایەن سەرپەرشتیارەکانەوە نووسینگەکەت تۆمار و پەسەند دەکرێت');
    }

    public function test_newly_registered_agency_receives_initial_credits(): void
    {
        $response = $this->post(route('agency.register.post'), [
            'name' => 'خاوەنکار تێست',
            'agency_name' => 'نووسینگەی پێشکەوتوو',
            'email' => 'advanced@estate.test',
            'phone' => '07705556677',
            'whatsapp' => '07705556677',
            'address' => 'سلێمانی، توی مەلیک',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('agency.pending'));

        $agency = Agency::where('name', 'نووسینگەی پێشکەوتوو')->first();
        $this->assertNotNull($agency);
        $this->assertEquals(2, $agency->credits);
        $this->assertDatabaseHas('credit_transactions', [
            'agency_id' => $agency->id,
            'amount' => 2,
            'type' => 'initial_gift',
        ]);
    }

    public function test_property_creation_deducts_credits(): void
    {
        $agencyUser = User::where('email', 'rebin@estate.test')->first();
        $agency = $agencyUser->agency;
        $agency->update(['credits' => 3, 'verified' => true]);

        $initialCredits = $agency->credits;

        $response = $this->actingAs($agencyUser)->post(route('agency.properties.store'), [
            'title' => 'خانووی تاقیکاری کریدت',
            'property_type' => 'house',
            'status' => 'available',
            'rent_price' => 500,
            'price_currency' => 'USD',
            'rent_period' => 'monthly',
            'bedrooms' => 3,
            'bathrooms' => 2,
            'area_sqm' => 180,
            'neighborhood' => 'سەرچنار',
            'street_address' => 'شەقامی سەرچنار',
            'latitude' => 35.5685,
            'longitude' => 45.3950,
        ]);

        $response->assertRedirect(route('agency.dashboard'));
        $agency->refresh();
        $this->assertEquals($initialCredits - 1, $agency->credits);
    }

    public function test_agency_with_zero_credits_is_prevented_from_creating_properties(): void
    {
        $agencyUser = User::where('email', 'rebin@estate.test')->first();
        $agency = $agencyUser->agency;
        $agency->update(['credits' => 0, 'verified' => true]);

        // Trying to view create page
        $createResponse = $this->actingAs($agencyUser)->get(route('agency.properties.create'));
        $createResponse->assertRedirect(route('agency.credits'));
        $createResponse->assertSessionHas('error');

        // Trying to submit store
        $storeResponse = $this->actingAs($agencyUser)->post(route('agency.properties.store'), [
            'title' => 'خانووی ڕێپێنەدراو',
            'property_type' => 'house',
            'status' => 'available',
            'rent_price' => 400,
            'price_currency' => 'USD',
            'rent_period' => 'monthly',
            'bedrooms' => 2,
            'bathrooms' => 1,
            'area_sqm' => 120,
            'neighborhood' => 'سەرچنار',
            'latitude' => 35.5685,
            'longitude' => 45.3950,
        ]);

        $storeResponse->assertRedirect(route('agency.credits'));
        $this->assertDatabaseMissing('properties', ['title' => 'خانووی ڕێپێنەدراو']);
    }

    public function test_admin_can_update_agency_credits_and_change_settings(): void
    {
        $admin = User::where('email', 'admin@estate.test')->first();
        $agency = Agency::first();

        // 1. Admin adds credits to agency
        $response = $this->actingAs($admin)->post(route('admin.agencies.credits', $agency->id), [
            'mode' => 'add',
            'amount' => 10,
            'note' => 'حەواڵەی FastPay کرا',
        ]);
        $response->assertRedirect();
        $agency->refresh();
        $this->assertGreaterThanOrEqual(10, $agency->credits);

        // 2. Admin updates system settings
        $settingsResponse = $this->actingAs($admin)->put(route('admin.settings.system'), [
            'initial_free_credits' => 3,
            'credits_per_property' => 1,
            'fastpay_number' => '07701234567',
            'fib_account' => 'IQ123FIB',
            'admin_whatsapp' => '07709998877',
            'admin_phone' => '07709998877',
            'payment_instructions' => 'تکایە پەیوەندی بکەن',
        ]);
        $settingsResponse->assertRedirect();
        $this->assertEquals(3, (int) \App\Models\SystemSetting::get('initial_free_credits'));
    }

    public function test_token_mismatch_redirects_gracefully_with_friendly_error(): void
    {
        $req = \Illuminate\Http\Request::create('/agency/login', 'POST', ['email' => 'test@test.com']);
        $req->headers->set('Referer', 'http://localhost/agency/login');
        
        $exception = new \Symfony\Component\HttpKernel\Exception\HttpException(419, 'CSRF token mismatch.');
        $handler = app(\Illuminate\Contracts\Debug\ExceptionHandler::class);
        $response = $handler->render($req, $exception);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('http://localhost/agency/login', $response->getTargetUrl());
    }

    public function test_hotels_index_and_single_hotel_load_successfully(): void
    {
        $hotel = \App\Models\Hotel::first();
        if (!$hotel) {
            $hotel = \App\Models\Hotel::create([
                'name' => 'ئوتێل تاقیکردنەوە',
                'stars' => 5,
                'price_per_night' => 100,
                'price_currency' => 'USD',
                'neighborhood' => 'بەختیاری',
                'latitude' => 35.5510,
                'longitude' => 45.4120,
                'phone' => '07701112233',
                'status' => 'active',
            ]);
        }

        // Test public hotels list
        $response = $this->get(route('hotels.index'));
        $response->assertStatus(200);
        $response->assertSee($hotel->name);
        $response->assertSee('ئوتێل و شوێنی مانەوەی گەشتیاری');

        // Test filter by stars
        $filterResponse = $this->get(route('hotels.index', ['stars' => $hotel->stars]));
        $filterResponse->assertStatus(200);
        $filterResponse->assertSee($hotel->name);

        // Test hotels live AJAX filter response
        $ajaxResponse = $this->getJson(route('hotels.index', ['ajax' => 1, 'stars' => $hotel->stars]));
        $ajaxResponse->assertStatus(200);
        $ajaxResponse->assertJsonStructure([
            'success',
            'count',
            'total',
            'html',
            'map_hotels',
        ]);
        $this->assertTrue($ajaxResponse->json('success'));
        // Test multi-select amenities filtering
        $multiAmenityResponse = $this->getJson(route('hotels.index', [
            'ajax' => 1,
            'amenities' => ['pool', 'breakfast'],
        ]));
        $multiAmenityResponse->assertStatus(200);
        $this->assertTrue($multiAmenityResponse->json('success'));

        // Test single hotel detail
        $singleResponse = $this->get(route('hotels.show', $hotel->slug));
        $singleResponse->assertStatus(200);
        $singleResponse->assertSee($hotel->name);
        $singleResponse->assertSee((string) $hotel->price_per_night);
    }

    public function test_admin_can_manage_hotels(): void
    {
        $admin = \App\Models\User::where('email', 'admin@estate.test')->first();

        // 1. Admin views hotels index
        $indexResponse = $this->actingAs($admin)->get(route('admin.hotels.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('لیستی ئوتێلەکانی سلێمانی');

        // 2. Admin creates a new hotel
        $createResponse = $this->actingAs($admin)->post(route('admin.hotels.store'), [
            'name' => 'ئوتێلی نوێی تاقیکردنەوە',
            'stars' => 4,
            'price_per_night' => 85,
            'price_currency' => 'USD',
            'neighborhood' => 'سەرچنار',
            'address' => 'شەقامی سەرچنار',
            'latitude' => 35.5685,
            'longitude' => 45.3950,
            'phone' => '07709876543',
            'whatsapp' => '07709876543',
            'amenities' => ['wifi', 'pool', 'breakfast'],
            'description' => 'ئوتێلێکی زۆر شاز',
            'status' => 'active',
        ]);
        $createResponse->assertRedirect(route('admin.hotels.index'));
        $this->assertDatabaseHas('hotels', ['name' => 'ئوتێلی نوێی تاقیکردنەوە']);

        $hotel = \App\Models\Hotel::where('name', 'ئوتێلی نوێی تاقیکردنەوە')->first();

        // 3. Admin updates hotel
        $updateResponse = $this->actingAs($admin)->put(route('admin.hotels.update', $hotel->id), [
            'name' => 'ئوتێلی نوێی تاقیکردنەوە (نوێکراوە)',
            'stars' => 5,
            'price_per_night' => 90,
            'price_currency' => 'USD',
            'neighborhood' => 'سەرچنار',
            'latitude' => 35.5685,
            'longitude' => 45.3950,
            'phone' => '07709876543',
            'status' => 'active',
        ]);
        $updateResponse->assertRedirect(route('admin.hotels.index'));
        $this->assertDatabaseHas('hotels', ['name' => 'ئوتێلی نوێی تاقیکردنەوە (نوێکراوە)']);

        // 4. Admin toggles status
        $toggleResponse = $this->actingAs($admin)->post(route('admin.hotels.toggle-status', $hotel->id));
        $toggleResponse->assertRedirect();
        $hotel->refresh();
        $this->assertEquals('inactive', $hotel->status);

        // 5. Admin deletes hotel
        $deleteResponse = $this->actingAs($admin)->delete(route('admin.hotels.destroy', $hotel->id));
        $deleteResponse->assertRedirect(route('admin.hotels.index'));
        $this->assertDatabaseMissing('hotels', ['id' => $hotel->id]);
    }
}
