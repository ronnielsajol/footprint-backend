<?php

namespace Database\Seeders;

use App\Models\WAscDeployment;
use Illuminate\Database\Seeder;

class WAscDeploymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $deployments = [
            [
                'exact_venue' => 'Quezon City Sports Complex',
                'province' => 'Metro Manila',
                'city_municipality' => 'Quezon City',
                'barangay' => 'Barangay Commonwealth',
                'region' => 'NCR',
                'district' => 'District 1',
                'deployment_month' => 1,
                'deployment_year' => 2025,
                'exact_date' => '2025-01-20',
                'event_tagging' => 'Women ASC Community Engagement',
                'has_socials' => true,
                'has_sortie' => true,
                'asc_attended' => true,
                'llc_attended' => false,
                'psc_attended' => true,
                'pol_activities' => [
                    'Medical consultation and free medicines',
                    'Legal assistance and counseling',
                    'Free haircut and beauty services',
                    'Skills training registration'
                ],
                'sector' => 'Kababaihan',
                'remarks' => 'Very successful W ASC deployment with high attendance',
                'created_by' => 2, // POL Admin 1
            ],
            [
                'exact_venue' => 'Cebu City Elementary School',
                'province' => 'Cebu',
                'city_municipality' => 'Cebu City',
                'barangay' => 'Barangay Guadalupe',
                'region' => 'Region VII',
                'district' => 'District 2',
                'deployment_month' => 2,
                'deployment_year' => 2025,
                'exact_date' => '2025-02-15',
                'event_tagging' => 'W ASC Education Focus',
                'has_socials' => true,
                'has_sortie' => false,
                'asc_attended' => true,
                'llc_attended' => true,
                'psc_attended' => false,
                'pol_activities' => [
                    'School supplies distribution',
                    'Educational workshops',
                    'Parent-teacher conference'
                ],
                'sector' => 'Youth',
                'remarks' => 'Partnership with Department of Education',
                'created_by' => 2, // POL Admin 1
            ],
            [
                'exact_venue' => 'Davao City Covered Court',
                'province' => 'Davao del Sur',
                'city_municipality' => 'Davao City',
                'barangay' => 'Barangay Agdao',
                'region' => 'Region XI',
                'district' => 'District 1',
                'deployment_month' => 12,
                'deployment_year' => 2024,
                'exact_date' => '2024-12-18',
                'event_tagging' => 'W ASC Livelihood Program',
                'has_socials' => false,
                'has_sortie' => true,
                'asc_attended' => true,
                'llc_attended' => true,
                'psc_attended' => true,
                'pol_activities' => [
                    'Livelihood training sessions',
                    'Microfinance orientation',
                    'Business registration assistance'
                ],
                'sector' => 'MSMEs',
                'remarks' => 'Focused on women entrepreneurs',
                'created_by' => 3, // POL Admin 2
            ],
            [
                'exact_venue' => 'Iloilo City Gymnasium',
                'province' => 'Iloilo',
                'city_municipality' => 'Iloilo City',
                'barangay' => 'Barangay Arevalo',
                'region' => 'Region VI',
                'district' => 'District 1',
                'deployment_month' => 3,
                'deployment_year' => 2025,
                'exact_date' => '2025-03-10',
                'event_tagging' => 'W ASC Health Campaign',
                'has_socials' => true,
                'has_sortie' => true,
                'asc_attended' => false,
                'llc_attended' => false,
                'psc_attended' => true,
                'pol_activities' => [
                    'Health screening and consultation',
                    'Nutrition education',
                    'Maternal health services'
                ],
                'sector' => 'BHW',
                'remarks' => 'Joint activity with Barangay Health Workers',
                'created_by' => 3, // POL Admin 2
            ]
        ];

        foreach ($deployments as $deploymentData) {
            $deployment = WAscDeployment::create($deploymentData);

            // Add officers for each deployment
            if ($deployment->id === 1) {
                $deployment->officers()->createMany([
                    ['officer_name' => 'PLTCOL Maria Santos'],
                    ['officer_name' => 'PMAJ Ana Reyes'],
                    ['officer_name' => 'PCPT Rosa Gonzales'],
                ]);
                // Attach VIPs
                $deployment->vips()->attach([2, 4], ['remarks' => 'Guest speaker']);
            } elseif ($deployment->id === 2) {
                $deployment->officers()->createMany([
                    ['officer_name' => 'PLTCOL Elizabeth Cruz'],
                    ['officer_name' => 'PMAJ Carmen Flores'],
                ]);
                // Attach VIPs
                $deployment->vips()->attach([1, 3]);
            } elseif ($deployment->id === 3) {
                $deployment->officers()->createMany([
                    ['officer_name' => 'PLTCOL Jennifer Tan'],
                    ['officer_name' => 'PMAJ Theresa Lim'],
                    ['officer_name' => 'PCPT Michelle Ong'],
                ]);
                // Attach VIPs
                $deployment->vips()->attach([3, 4], ['remarks' => 'Resource person']);
            } elseif ($deployment->id === 4) {
                $deployment->officers()->createMany([
                    ['officer_name' => 'PLTCOL Patricia Ramos'],
                    ['officer_name' => 'PMAJ Linda Silva'],
                ]);
                // Attach VIPs
                $deployment->vips()->attach([1, 2, 4]);
            }
        }
    }
}
