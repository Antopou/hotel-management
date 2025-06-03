<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RoomTypeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'room_type_code' => Str::uuid(),
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'created_by' => 1,
            'modified_by' => null,
            'is_active' => true,
        ];
    }
}
