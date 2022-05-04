<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\ScheduleUnavailability;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduleUnavailabilityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ScheduleUnavailability::class;

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
            'schedule_id' => \App\Models\Schedule::factory(),
        ];
    }
}
