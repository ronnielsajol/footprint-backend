<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = [
            [
                'title' => 'Metro Manila POL Deployment',
                'event_type' => 'pol_deployment',
                'description' => 'Regular POL deployment in Metro Manila area',
                'event_date' => now()->addDays(5),
                'location' => 'Metro Manila',
                'created_by' => 2, // POL Admin 1
                'status' => 'planned',
            ],
            [
                'title' => 'Cebu W ASC Deployment',
                'event_type' => 'w_asc_deployment',
                'description' => 'W ASC deployment in Cebu province',
                'event_date' => now()->addDays(10),
                'location' => 'Cebu City',
                'created_by' => 2, // POL Admin 1
                'status' => 'planned',
            ],
            [
                'title' => 'Davao POL Deployment',
                'event_type' => 'pol_deployment',
                'description' => 'POL deployment in Davao region',
                'event_date' => now()->subDays(5),
                'location' => 'Davao City',
                'created_by' => 3, // POL Admin 2
                'status' => 'completed',
            ],
            [
                'title' => 'Iloilo W ASC Deployment',
                'event_type' => 'w_asc_deployment',
                'description' => 'W ASC deployment in Iloilo area',
                'event_date' => now()->addDays(15),
                'location' => 'Iloilo City',
                'created_by' => 3, // POL Admin 2
                'status' => 'planned',
            ],
        ];

        foreach ($events as $eventData) {
            $event = Event::create($eventData);

            // Attach some VIPs to events
            if ($event->id === 1) {
                $event->vips()->attach([1, 2], ['remarks' => 'Primary VIP']);
            } elseif ($event->id === 2) {
                $event->vips()->attach([2, 4]);
            } elseif ($event->id === 3) {
                $event->vips()->attach([3]);
            }
        }
    }
}
