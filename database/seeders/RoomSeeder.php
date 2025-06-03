<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now()->format('Y-m-d H:i:s');

        $rooms = [
            ['Standard Room', '5c655719-9b34-46e1-976e-2cf7496bf428', 'A basic room with essential amenities suitable for short stays.'],
            ['Deluxe Room', '4537bf65-92dc-4b97-818c-ef1d98514f95', 'A more spacious room with upgraded furnishings and a better view.'],
            ['Superior Room', '0ec5f93f-76fd-4ba3-a79e-20e8bac1fd1d', 'An enhanced version of the standard room offering extra comfort.'],
            ['Executive Room', '62a87f8b-581e-4ab7-b016-dbdfb8c7541e', 'Designed for business travelers, includes a work desk and added privacy.'],
            ['Suite', 'c421b4c2-50f0-4cab-8c6c-9c9e8a994c0a', 'A luxurious room with separate living and sleeping areas.'],
            ['Junior Suite', '291384c6-3bf9-4e6e-b6ae-861971bc6d0f', 'A smaller suite with an open-plan layout, offering added space and comfort.'],
            ['Family Room', '85e01010-6555-4d43-8f72-c8ece75730f3', 'A large room designed for families, often with multiple beds.'],
            ['Single Room', '5040b24e-38dc-4bed-8df9-1fd5025df6fe', 'A room with a single bed, ideal for solo travelers.'],
            ['Double Room', '5b75d3c3-f957-4da5-a61e-7b41d6b93288', 'A room with one double bed or two twin beds for two guests.'],
            ['Presidential Suite', '3b702eea-dff3-4dcb-bac4-11fc3771ecb6', 'The most luxurious suite, featuring premium amenities and expansive space.'],
        ];

        $data = [];

        foreach (range(1, 15) as $i) {
            $index = ($i - 1) % count($rooms); // Cycle through room types

            $data[] = [
                'id' => $i,
                'room_code' => 'ROOM-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'room_type_code' => $rooms[$index][1],
                'name' => $rooms[$index][0] . ' #' . $i,
                'description' => $rooms[$index][2],
                'created_at' => $now,
                'updated_at' => $now,
                'created_by' => 1,
                'modified_by' => null,
                'is_active' => 1,
            ];
        }

        DB::table('rooms')->insert($data);
    }
}
