<?php

namespace Tests\Feature;

use App\Models\Agency;
use App\Models\Property;
use App\Models\SiteVisit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected User $agencyUser;
    protected Agency $agency;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::updateOrCreate(
            ['email' => 'admin@estate.test'],
            [
                'name' => 'سەرپەرشتیاری سیستەم',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '07700000000',
            ]
        );

        $this->agencyUser = User::firstOrCreate(
            ['email' => 'test-agency@estate.test'],
            [
                'name' => 'نووسینگەی تێست',
                'password' => Hash::make('password'),
                'role' => 'agency',
                'phone' => '07701112233',
            ]
        );

        $this->agency = Agency::firstOrCreate(
            ['user_id' => $this->agencyUser->id],
            [
                'name' => 'نووسینگەی تاقیکاری',
                'slug' => 'test-agency-slug',
                'owner_name' => 'خاوەنکار تێست',
                'phone' => '07701112233',
                'address' => 'سلێمانی، شەقامی سالم',
                'city' => 'سلێمانی',
                'description' => 'نووسینگەی تاقیکاری بۆ تێستکردن',
                'verified' => false,
            ]
        );
    }

    public function test_guest_is_redirected_to_admin_login_when_accessing_dashboard(): void
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_agency_user_is_forbidden_from_admin_dashboard(): void
    {
        $response = $this->actingAs($this->agencyUser)->get(route('admin.dashboard'));
        $response->assertStatus(403);
    }

    public function test_admin_can_view_login_page(): void
    {
        $response = $this->get(route('admin.login'));
        $response->assertStatus(200);
        $response->assertSee('بەشی بەڕێوەبەری سیستەم');
    }

    public function test_admin_can_login_with_correct_credentials(): void
    {
        $response = $this->post(route('admin.login.post'), [
            'email' => 'admin@estate.test',
            'password' => 'admin123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($this->adminUser);
    }

    public function test_admin_can_login_from_main_unified_login_form(): void
    {
        $response = $this->post(route('agency.login.post'), [
            'email' => 'admin@estate.test',
            'password' => 'admin123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($this->adminUser);
    }

    public function test_admin_can_access_dashboard_and_view_metrics(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('داشبۆرد و ئامارەکان');
        $response->assertSee('سەرجەم خانووەکان');
        $response->assertSee('نووسینگەکانی خانوبەرە');
    }

    public function test_admin_can_view_agencies_and_toggle_verification(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.agencies.index'));
        $response->assertStatus(200);

        // Toggle verification
        $initialState = $this->agency->verified;
        $toggleResponse = $this->actingAs($this->adminUser)->post(route('admin.agencies.toggle-verify', $this->agency->id));
        $toggleResponse->assertRedirect();

        $this->agency->refresh();
        $this->assertEquals(!$initialState, $this->agency->verified);
    }

    public function test_admin_can_view_create_agency_page(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.agencies.create'));
        $response->assertStatus(200);
        $response->assertSee('تۆمارکردنی نووسینگەی نوێ بە دەستی');
    }

    public function test_admin_can_store_new_agency_manually(): void
    {
        $response = $this->actingAs($this->adminUser)->post(route('admin.agencies.store'), [
            'name' => 'نووسینگەی سەرپەرشتیار تاقیکاری',
            'owner_name' => 'بەڕێز ئاکۆ',
            'email' => 'ako-agency@estate.test',
            'password' => 'password123',
            'phone' => '07709998877',
            'address' => 'سلێمانی، شەقامی تووی مەلیك',
            'verified' => 1,
        ]);

        $response->assertRedirect(route('admin.agencies.index'));
        $this->assertDatabaseHas('users', ['email' => 'ako-agency@estate.test']);
        $this->assertDatabaseHas('agencies', ['name' => 'نووسینگەی سەرپەرشتیار تاقیکاری', 'verified' => 1]);
    }

    public function test_admin_can_view_properties_and_update_status(): void
    {
        $property = Property::first();
        if (!$property) {
            $property = Property::create([
                'agency_id' => $this->agency->id,
                'title' => 'خانووی تاقیکاری لە سلێمانی',
                'slug' => 'test-house-admin',
                'description' => 'خانوویەکی تاقیکاری',
                'rent_price' => 500,
                'property_type' => 'house',
                'bedrooms' => 3,
                'bathrooms' => 2,
                'area_sqm' => 150,
                'neighborhood' => 'سەرچنار',
                'city' => 'سلێمانی',
                'latitude' => 35.5685,
                'longitude' => 45.3950,
                'status' => 'available',
            ]);
        }

        $response = $this->actingAs($this->adminUser)->get(route('admin.properties.index'));
        $response->assertStatus(200);

        // Update status
        $statusResponse = $this->actingAs($this->adminUser)->patch(route('admin.properties.status', $property->id), [
            'status' => 'rented',
        ]);
        $statusResponse->assertRedirect();

        $property->refresh();
        $this->assertEquals('rented', $property->status);
    }

    public function test_admin_can_view_settings(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.settings.index'));
        $response->assertStatus(200);
        $response->assertSee('زانیاری ژینگە و سێرڤەر');
        $response->assertSee('گۆڕینی وشەی نهێنی سەرپەرشتیار');
    }

    public function test_admin_can_logout(): void
    {
        $response = $this->actingAs($this->adminUser)->post(route('admin.logout'));
        $response->assertRedirect(route('agency.login'));
        $this->assertGuest();
    }

    public function test_admin_can_view_visitor_analytics(): void
    {
        SiteVisit::create([
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0.0.0',
            'device_type' => 'desktop',
            'browser' => 'Chrome',
            'page_url' => 'http://127.0.0.1:8000/',
            'page_title' => 'ماڵپەڕی سەرەکی',
            'visited_at' => now(),
        ]);

        $response = $this->actingAs($this->adminUser)->get(route('admin.analytics.index'));
        $response->assertStatus(200);
        $response->assertSee('ئاماری سەردانیکەرانی ماڵپەڕ');
        $response->assertSee('کۆی گشتی سەردانەکان');
        $response->assertSee('سەردانیکەرانی جیاواز');
        $response->assertSee('ماڵپەڕی سەرەکی');
    }

    public function test_admin_can_toggle_property_featured_status(): void
    {
        $property = Property::first();
        if (!$property) {
            $property = Property::create([
                'agency_id' => $this->agency->id,
                'title' => 'خانووی تاقیکاری لە سلێمانی',
                'slug' => 'test-house-admin-featured',
                'description' => 'خانوویەکی تاقیکاری',
                'rent_price' => 500,
                'property_type' => 'house',
                'bedrooms' => 3,
                'bathrooms' => 2,
                'area_sqm' => 150,
                'neighborhood' => 'سەرچنار',
                'latitude' => 35.5685,
                'longitude' => 45.3950,
                'status' => 'available',
                'is_featured' => false,
            ]);
        }
        $initialState = (bool) $property->is_featured;

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.properties.toggle-featured', $property->id));

        $response->assertRedirect();
        $this->assertEquals(!$initialState, (bool) $property->fresh()->is_featured);
    }

    public function test_admin_can_view_vip_properties_page(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.vip.index'));
        $response->assertStatus(200);
        $response->assertSee('خانووە VIPیەکان');
    }

    public function test_admin_can_add_and_remove_property_from_vip_page(): void
    {
        $property = Property::create([
            'agency_id' => $this->agency->id,
            'title' => 'خانووی تاقیکاری تایبەت بۆ بەشی VIP',
            'slug' => 'test-house-vip-dedicated',
            'description' => 'خانوویەکی تاقیکاری',
            'rent_price' => 600,
            'property_type' => 'house',
            'bedrooms' => 3,
            'bathrooms' => 2,
            'area_sqm' => 150,
            'neighborhood' => 'سەرچنار',
            'latitude' => 35.5685,
            'longitude' => 45.3950,
            'status' => 'available',
            'is_featured' => false,
        ]);

        // Add to VIP
        $storeResponse = $this->actingAs($this->adminUser)
            ->post(route('admin.vip.store'), ['property_id' => $property->id]);
        $storeResponse->assertRedirect();
        $this->assertTrue((bool) $property->fresh()->is_featured);
        $this->assertNotNull($property->fresh()->featured_at);

        // Remove from VIP
        $destroyResponse = $this->actingAs($this->adminUser)
            ->delete(route('admin.vip.destroy', $property->id));
        $destroyResponse->assertRedirect();
        $this->assertFalse((bool) $property->fresh()->is_featured);
        $this->assertNull($property->fresh()->featured_at);
    }

    public function test_public_visit_is_automatically_recorded_by_middleware(): void
    {
        $initialCount = SiteVisit::count();

        $response = $this->get(route('home'));
        $response->assertStatus(200);

        $this->assertGreaterThan($initialCount, SiteVisit::count());
    }
}
