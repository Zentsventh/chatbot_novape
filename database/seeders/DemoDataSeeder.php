<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\Appointment;
use App\Models\ChatbotKnowledge;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

use App\Models\SubscriptionPlan;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // 0. Create Plan
        $plan = SubscriptionPlan::firstOrCreate(
            ['slug' => 'pro-plan'],
            ['name' => 'Pro Plan', 'price' => 29.99, 'max_agents' => 5, 'max_channels' => 3, 'has_crm' => true, 'has_appointments' => true]
        );

        // 1. Create Tenant
        $tenant = Tenant::firstOrCreate(
            ['slug' => 'novape'],
            ['company_name' => 'Novape Hosting', 'contact_email' => 'admin@novape.com', 'subscription_plan_id' => $plan->id, 'status' => 'active']
        );

        // 2. Create User
        $user = User::firstOrCreate(
            ['email' => 'admin@novape.com'],
            [
                'name' => 'Admin Novape',
                'password' => Hash::make('password'),
                'tenant_id' => $tenant->id,
            ]
        );

        // 3. Create Contacts
        $contacts = [
            ['name' => 'Eduardo Aguilar', 'company' => 'Novape Hosting', 'phone_number' => '+5215512345678', 'email' => 'eduardo@novape.com', 'metadata' => json_encode(['channel' => 'whatsapp'])],
            ['name' => 'Valeria Gómez', 'company' => 'Agencia Creativa', 'phone_number' => '+34600112233', 'email' => 'valeria.g@creativa.es', 'metadata' => json_encode(['channel' => 'messenger'])],
            ['name' => 'Carlos Rodríguez', 'company' => 'Tech Solutions LLC', 'phone_number' => '+13055550100', 'email' => 'crodriguez@techsol.com', 'metadata' => json_encode(['channel' => 'whatsapp'])],
            ['name' => 'Ana Martínez', 'company' => 'Freelance', 'phone_number' => '+573009876543', 'email' => 'ana.martinez@gmail.com', 'metadata' => json_encode(['channel' => 'instagram'])],
        ];

        foreach ($contacts as $cData) {
            $cData['tenant_id'] = $tenant->id;
            Contact::firstOrCreate(['email' => $cData['email']], $cData);
        }

        // 4. Create Deals
        $deals = [
            ['title' => 'Renovación Hosting Anual', 'value' => 450, 'stage' => 'lead', 'contact_id' => Contact::where('email', 'valeria.g@creativa.es')->first()->id],
            ['title' => 'Migración Servidor Dedicado', 'value' => 1200, 'stage' => 'lead', 'contact_id' => Contact::where('email', 'crodriguez@techsol.com')->first()->id],
            ['title' => 'Asesoría de Seguridad', 'value' => 300, 'stage' => 'prospect', 'contact_id' => Contact::where('email', 'ana.martinez@gmail.com')->first()->id],
            ['title' => 'Plan Reseller 50 Cuentas', 'value' => 850, 'stage' => 'negotiation', 'contact_id' => Contact::where('email', 'valeria.g@creativa.es')->first()->id],
            ['title' => 'Integración Chatbot', 'value' => 600, 'stage' => 'negotiation', 'contact_id' => Contact::where('email', 'crodriguez@techsol.com')->first()->id],
            ['title' => 'VPS Alta Disponibilidad', 'value' => 2500, 'stage' => 'won', 'contact_id' => Contact::where('email', 'eduardo@novape.com')->first()->id],
        ];

        foreach ($deals as $dData) {
            $dData['tenant_id'] = $tenant->id;
            Deal::firstOrCreate(['title' => $dData['title']], $dData);
        }

        // 5. Create Appointments
        $appointments = [
            ['title' => 'Demo Chatbot', 'scheduled_at' => Carbon::now()->setHour(9)->setMinute(0), 'contact_id' => Contact::where('email', 'crodriguez@techsol.com')->first()->id],
            ['title' => 'Reunión de Equipo', 'scheduled_at' => Carbon::now()->setHour(11)->setMinute(0), 'contact_id' => Contact::where('email', 'eduardo@novape.com')->first()->id],
            ['title' => 'Llamada de Cierre', 'scheduled_at' => Carbon::now()->setHour(15)->setMinute(30), 'contact_id' => Contact::where('email', 'valeria.g@creativa.es')->first()->id],
        ];

        foreach ($appointments as $aData) {
            $aData['tenant_id'] = $tenant->id;
            Appointment::firstOrCreate(['title' => $aData['title']], $aData);
        }

        // 6. Create Chatbot Knowledge
        ChatbotKnowledge::firstOrCreate(
            ['tenant_id' => $tenant->id],
            [
                'bot_name' => 'NovaBot',
                'system_prompt' => 'Eres un asistente virtual de ventas para Novape Hosting. Eres amable, conciso y ayudas a vender servidores.',
                'ai_temperature' => 0.7,
                'is_bot_active' => true
            ]
        );

    }
}
