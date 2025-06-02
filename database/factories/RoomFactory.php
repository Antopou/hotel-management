<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\RoomType;

class RoomFactory extends Factory
{
    public function definition(): array
    {
        return [
            'room_code' => Str::uuid(),
            'room_type_code' => RoomType::inRandomOrder()->first()?->room_type_code ?? Str::uuid(),
            'name' => 'Room ' . $this->faker->numberBetween(100, 999),
            'description' => $this->faker->sentence(),
            'created_by' => 1,
            'modified_by' => null,
            'is_active' => true,
        ];
    }
}
