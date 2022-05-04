<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ScheduleUnavailability;

class ScheduleUnavailabilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ScheduleUnavailability::factory()
            ->count(5)
            ->create();
    }
}
