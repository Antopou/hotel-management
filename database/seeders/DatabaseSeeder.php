<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            GuestSeeder::class,
            RoomTypeSeeder::class,
            RoomSeeder::class,
            GuestReservationSeeder::class,
            GuestCheckinSeeder::class,
                // $this->call(UserSeeder::class);
        ]);
    }

}
