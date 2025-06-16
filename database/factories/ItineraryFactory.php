<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Itinerary;
use App\Models\Group;
use App\Models\User;
use HasFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\=Itinerary>
 */
class ItineraryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        return [
            'group_id' => Group::inRandomOrder()->value('id'),
            'created_by' => User::inRandomOrder()->value('id'),
            'title' => $this->faker->sentence,
            'start_date' => $this->faker->date,
            'end_date' => $this->faker->date,
            'initial_place_name' => $this->faker->city,
            'initial_latitude' => $this->faker->latitude,
            'initial_longitude' => $this->faker->longitude,
        ];
    }
}
