<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\RoomType;

class RoomTypeSeeder extends Seeder
{
    public function run(): void
    {
        $roomTypes = [
            ['name' => 'Standard Room', 'description' => 'A basic room with essential amenities suitable for short stays.'],
            ['name' => 'Deluxe Room', 'description' => 'A more spacious room with upgraded furnishings and a better view.'],
            ['name' => 'Superior Room', 'description' => 'An enhanced version of the standard room offering extra comfort.'],
            ['name' => 'Executive Room', 'description' => 'Designed for business travelers, includes a work desk and added privacy.'],
            ['name' => 'Suite', 'description' => 'A luxurious room with separate living and sleeping areas.'],
            ['name' => 'Junior Suite', 'description' => 'A smaller suite with an open-plan layout, offering added space and comfort.'],
            ['name' => 'Family Room', 'description' => 'A large room designed for families, often with multiple beds.'],
            ['name' => 'Single Room', 'description' => 'A room with a single bed, ideal for solo travelers.'],
            ['name' => 'Double Room', 'description' => 'A room with one double bed or two twin beds for two guests.'],
            ['name' => 'Presidential Suite', 'description' => 'The most luxurious suite, featuring premium amenities and expansive space.'],
        ];

        foreach ($roomTypes as $type) {
            RoomType::create([
                'room_type_code' => Str::uuid(),
                'name' => $type['name'],
                'description' => $type['description'],
                'created_by' => 1,
                'is_active' => true,
            ]);
        }
    }
}
