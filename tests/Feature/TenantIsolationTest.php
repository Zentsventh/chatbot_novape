<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TenantIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_agent_cannot_create_a_deal_for_another_tenants_contact(): void
    {
        [$agent, $foreignTenantId] = $this->createTwoTenants();
        $foreignContact = Contact::create(['tenant_id' => $foreignTenantId, 'name' => 'Cliente ajeno']);
        Sanctum::actingAs($agent);

        $this->postJson('/api/deals', [
            'title' => 'Venta ajena',
            'value' => 100,
            'contact_id' => $foreignContact->id,
        ])->assertForbidden();

        $this->assertDatabaseMissing('deals', ['title' => 'Venta ajena']);
    }

    public function test_an_agent_cannot_start_a_team_chat_with_another_tenant(): void
    {
        [$agent, $foreignTenantId] = $this->createTwoTenants();
        $foreignAgent = User::create([
            'tenant_id' => $foreignTenantId,
            'name' => 'Agente ajeno',
            'email' => 'foreign@example.test',
            'password' => 'password123',
        ]);
        Sanctum::actingAs($agent);

        $this->postJson('/api/team-chat/conversations', ['user_id' => $foreignAgent->id])
            ->assertForbidden();

        $this->assertDatabaseCount('team_conversations', 0);
    }

    private function createTwoTenants(): array
    {
        $planId = DB::table('subscription_plans')->insertGetId([
            'name' => 'Plan de pruebas',
            'slug' => 'plan-pruebas',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $tenantIds = [];
        foreach (['uno', 'dos'] as $name) {
            $tenantIds[] = DB::table('tenants')->insertGetId([
                'subscription_plan_id' => $planId,
                'company_name' => $name,
                'slug' => $name,
                'contact_email' => $name.'@example.test',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $agent = User::create([
            'tenant_id' => $tenantIds[0],
            'name' => 'Agente propio',
            'email' => 'agent@example.test',
            'password' => 'password123',
        ]);

        return [$agent, $tenantIds[1]];
    }
}
