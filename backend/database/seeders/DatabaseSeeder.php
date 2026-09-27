<?php

namespace Database\Seeders;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Default Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@crm.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('password123'),
                'role' => User::ROLE_ADMIN,
            ]
        );

        // Default Sales Users
        $sales1 = User::firstOrCreate(
            ['email' => 'sales@crm.com'],
            [
                'name' => 'Sarah Connor (Sales)',
                'password' => Hash::make('password123'),
                'role' => User::ROLE_SALES_USER,
            ]
        );

        $sales2 = User::firstOrCreate(
            ['email' => 'john.sales@crm.com'],
            [
                'name' => 'John Miller (Sales)',
                'password' => Hash::make('password123'),
                'role' => User::ROLE_SALES_USER,
            ]
        );

        // Sample Leads
        $leadsData = [
            [
                'name' => 'Acme Corp Tech',
                'email' => 'contact@acmecorp.io',
                'phone' => '+1 (555) 019-2834',
                'company' => 'Acme Corporation',
                'source' => Lead::SOURCE_WEB,
                'status' => Lead::STATUS_WON, // Will trigger auto-conversion to Customer
                'assigned_to' => $sales1->id,
                'follow_up_date' => now()->addDays(2)->format('Y-m-d'),
                'notes' => 'Client agreed to enterprise tier package.',
            ],
            [
                'name' => 'Nexus Innovations',
                'email' => 'info@nexusinnovations.com',
                'phone' => '+1 (555) 014-9921',
                'company' => 'Nexus Ltd',
                'source' => Lead::SOURCE_ADS,
                'status' => Lead::STATUS_IN_PROGRESS,
                'assigned_to' => $sales2->id,
                'follow_up_date' => now()->addDays(5)->format('Y-m-d'),
                'notes' => 'Requested product demo call for standard tier.',
            ],
            [
                'name' => 'Starlight Media',
                'email' => 'hello@starlightmedia.com',
                'phone' => '+1 (555) 018-7744',
                'company' => 'Starlight Media Group',
                'source' => Lead::SOURCE_REFERRAL,
                'status' => Lead::STATUS_NEW,
                'assigned_to' => $sales1->id,
                'follow_up_date' => now()->addDays(1)->format('Y-m-d'),
                'notes' => 'Inbound referral from existing client.',
            ],
            [
                'name' => 'Global Logistics Inc',
                'email' => 'procurement@globallogistics.com',
                'phone' => '+1 (555) 012-3456',
                'company' => 'Global Logistics Inc',
                'source' => Lead::SOURCE_WEB,
                'status' => Lead::STATUS_WON, // Auto-converts to Customer
                'assigned_to' => $sales2->id,
                'follow_up_date' => now()->subDays(1)->format('Y-m-d'),
                'notes' => 'Annual contract signed and invoice sent.',
            ],
            [
                'name' => 'Apex Financial Services',
                'email' => 'leads@apexfinancial.com',
                'phone' => '+1 (555) 017-8899',
                'company' => 'Apex Financial',
                'source' => Lead::SOURCE_ADS,
                'status' => Lead::STATUS_LOST,
                'assigned_to' => $admin->id,
                'follow_up_date' => null,
                'notes' => 'Budget constraints for current quarter.',
            ],
            [
                'name' => 'Quantum Dynamics',
                'email' => 'ceo@quantumdynamics.org',
                'phone' => '+1 (555) 011-2233',
                'company' => 'Quantum Dynamics',
                'source' => Lead::SOURCE_REFERRAL,
                'status' => Lead::STATUS_IN_PROGRESS,
                'assigned_to' => $sales1->id,
                'follow_up_date' => now()->addDays(3)->format('Y-m-d'),
                'notes' => 'Reviewing proposal with technical evaluation team.',
            ],
            [
                'name' => 'Vortex Software',
                'email' => 'contact@vortexsoftware.dev',
                'phone' => '+1 (555) 016-5544',
                'company' => 'Vortex Softwares',
                'source' => Lead::SOURCE_WEB,
                'status' => Lead::STATUS_NEW,
                'assigned_to' => null,
                'follow_up_date' => now()->addDays(7)->format('Y-m-d'),
                'notes' => 'Filled out website contact form.',
            ],
        ];

        foreach ($leadsData as $data) {
            Lead::firstOrCreate(['email' => $data['email']], $data);
        }
    }
}
