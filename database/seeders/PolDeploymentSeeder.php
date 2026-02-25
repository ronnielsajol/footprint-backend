<?php

namespace Database\Seeders;

use App\Models\PolDeployment;
use Illuminate\Database\Seeder;

class PolDeploymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $deployments = [
            [
                'event_name' => 'Metro Manila POL Deployment 2025',
                'exact_venue' => 'Quezon City Hall',
                'province' => 'Metro Manila',
                'lgu' => 'Quezon City',
                'barangay' => 'Barangay Batasan Hills',
                'region' => 'NCR',
                'district' => 'District 1',
                'deployment_month' => 1,
                'deployment_year' => 2025,
                'turnover_date' => '2025-01-15',
                'pol_officer' => 'Police Colonel Juan Dela Cruz',
                'category' => 'Regular POL Deployment',
                'asc_type' => 'actual',
                'llc' => 'LLC Representative 1',
                'psc' => 'PSC Representative 1',
                'proponent' => 'PCR Philippine',
                'sector_recipient' => 'Local Government',
                'count' => 150,
                'unit' => 'persons',
                'donation_summary' => 'Medical supplies, food packs, educational materials',
                'amount' => 500000.00,
                'source' => 'Private',
                'remarks' => 'Successfully completed POL deployment with full attendance',
                'created_by' => 2, // POL Admin 1
            ],
            [
                'event_name' => 'Cebu POL Deployment 2025',
                'exact_venue' => 'Cebu City Hall',
                'province' => 'Cebu',
                'lgu' => 'Cebu City',
                'barangay' => 'Barangay Lahug',
                'region' => 'Region VII',
                'district' => 'District 2',
                'deployment_month' => 2,
                'deployment_year' => 2025,
                'turnover_date' => '2025-02-10',
                'pol_officer' => 'Police Major Maria Santos',
                'category' => 'Special POL Deployment',
                'asc_type' => 'actual',
                'llc' => 'LLC Representative 2',
                'psc' => 'PSC Representative 2',
                'proponent' => 'DSWD',
                'sector_recipient' => 'Community',
                'count' => 200,
                'unit' => 'households',
                'donation_summary' => 'Livelihood packages, training materials, food assistance',
                'amount' => 750000.00,
                'source' => 'DSWD-AICS',
                'remarks' => 'Major deployment with multiple partner agencies',
                'created_by' => 2, // POL Admin 1
            ],
            [
                'event_name' => 'Davao POL Deployment 2024',
                'exact_venue' => 'Davao City Convention Center',
                'province' => 'Davao del Sur',
                'lgu' => 'Davao City',
                'barangay' => 'Barangay Poblacion',
                'region' => 'Region XI',
                'district' => 'District 1',
                'deployment_month' => 12,
                'deployment_year' => 2024,
                'turnover_date' => '2024-12-20',
                'pol_officer' => 'Police Lieutenant Colonel Antonio Garcia',
                'category' => 'Regular POL Deployment',
                'asc_type' => 'actual',
                'llc' => 'LLC Representative 3',
                'psc' => 'PSC Representative 3',
                'proponent' => 'DOLE',
                'sector_recipient' => 'Workers',
                'count' => 180,
                'unit' => 'beneficiaries',
                'donation_summary' => 'Employment assistance, skills training, job placement',
                'amount' => 600000.00,
                'source' => 'DOLE-TUPAD',
                'remarks' => 'Year-end deployment with excellent turnout',
                'created_by' => 3, // POL Admin 2
            ],
            [
                'event_name' => 'Iloilo POL Deployment 2025',
                'exact_venue' => 'Iloilo Provincial Capitol',
                'province' => 'Iloilo',
                'lgu' => 'Iloilo City',
                'barangay' => 'Barangay City Proper',
                'region' => 'Region VI',
                'district' => 'District 1',
                'deployment_month' => 3,
                'deployment_year' => 2025,
                'turnover_date' => null, // Planned, not yet executed
                'pol_officer' => 'Police Captain Roberto Fernandez',
                'category' => 'Regular POL Deployment',
                'asc_type' => 'virtual',
                'llc' => 'LLC Representative 4',
                'psc' => 'PSC Representative 4',
                'proponent' => 'DOH',
                'sector_recipient' => 'Healthcare',
                'count' => 120,
                'unit' => 'patients',
                'donation_summary' => 'Medical services, free medicines, health checkups',
                'amount' => 450000.00,
                'source' => 'DOH-MAIFIP',
                'remarks' => 'Planned deployment for March 2025',
                'created_by' => 3, // POL Admin 2
            ]
        ];

        foreach ($deployments as $deploymentData) {
            $deployment = PolDeployment::create($deploymentData);

            // Attach VIPs to some deployments
            if ($deployment->id === 1) {
                $deployment->vips()->attach([1, 2], ['remarks' => 'Key stakeholder']);
            } elseif ($deployment->id === 2) {
                $deployment->vips()->attach([2, 3]);
            } elseif ($deployment->id === 3) {
                $deployment->vips()->attach([1, 4], ['remarks' => 'Regional coordinator']);
            }
        }
    }
}
