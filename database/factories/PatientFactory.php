<?php

namespace Database\Factories;

use App\Models\Patient;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class PatientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Patient::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber,
            'phone_alt' => $this->faker->phoneNumber,
            'birthday' => $this->faker->date,
            'starting_date' => $this->faker->date,
            'is_active' => $this->faker->boolean,
            'summary' => $this->faker->text,
            'fee' => $this->faker->randomNumber(0),
            'frequency' => array_rand(
                array_flip(['semanal', 'quinzenal', 'mensal']),
                1
            ),
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
