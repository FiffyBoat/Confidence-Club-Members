<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardRoleViewTest extends TestCase
{
    use RefreshDatabase;

    public function test_collector_sees_collector_dashboard(): void
    {
        $collector = User::factory()->create([
            'role' => 'collector',
            'is_active' => true,
        ]);

        $response = $this->actingAs($collector)->get('/dashboard');

        $response->assertOk();
        $response->assertSee('Revenue Officer Dashboard');
    }

    public function test_non_admin_cannot_access_admin_panel(): void
    {
        $cashier = User::factory()->create([
            'role' => 'cashier',
            'is_active' => true,
        ]);

        $response = $this->actingAs($cashier)->get('/admin');
        $response->assertForbidden();
    }
}
