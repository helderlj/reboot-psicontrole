<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Service::create([
            'name' => 'Avaliação I',
            'duration' => '60'
        ]);
        Service::create([
            'name' => 'Avaliação II',
            'duration' => '90'
        ]);
        Service::create([
            'name' => 'Atendimento I',
            'duration' => '60'
        ]);
        Service::create([
            'name' => 'Atendimento II',
            'duration' => '90'
        ]);
        Service::create([
            'name' => 'Reposição I',
            'duration' => '60'
        ]);
        Service::create([
            'name' => 'Reposição II',
            'duration' => '90'
        ]);
    }
}
