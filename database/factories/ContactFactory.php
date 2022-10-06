<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Auth;

class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'birthdate' => $this->faker->date(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->sentence(10),
            'cc_number' => $this->faker->randomNumber(8, false),
            'cc_network' => $this->faker->word(5),
            'email' => $this->faker->unique()->safeEmail(),
            'user_id' => 1,
        ];
    }
}
