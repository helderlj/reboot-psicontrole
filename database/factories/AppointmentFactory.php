<?php

namespace Database\Factories;

use App\Models\Appointment;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Appointment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'start_time' => $this->faker->time,
            'end_time' => $this->faker->time,
            'uuid' => $this->faker->uuid,
            'token' => $this->faker->text(255),
            'date' => $this->faker->date,
            'patient_id' => \App\Models\Patient::factory(),
            'schedule_id' => \App\Models\Schedule::factory(),
            'service_id' => \App\Models\Service::factory(),
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
