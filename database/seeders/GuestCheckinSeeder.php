<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GuestCheckin;

class GuestCheckinSeeder extends Seeder
{
    public function run(): void
    {
        GuestCheckin::factory()->count(10)->create();
    }
}
