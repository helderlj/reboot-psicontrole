<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Adding an admin user
        $user = \App\Models\User::factory()
            ->count(1)
            ->create([
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => \Hash::make('1234'),
            ]);

        $user = \App\Models\User::factory()
            ->count(1)
            ->create([
                'name' => 'Helder Lima',
                'email' => 'helder@email.com',
                'password' => \Hash::make('1234'),
            ]);

//        $this->call(AppointmentSeeder::class);
        $this->call(PatientSeeder::class);
        $this->call(ScheduleSeeder::class);
//        $this->call(ScheduleUnavailabilitySeeder::class);
        $this->call(ServiceSeeder::class);
        $this->call(UserSeeder::class);
    }
}
