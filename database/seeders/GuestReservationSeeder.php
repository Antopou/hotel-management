<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GuestReservation;

class GuestReservationSeeder extends Seeder
{
    public function run(): void
    {
        GuestReservation::factory()->count(10)->create();
    }
}
