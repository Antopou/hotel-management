<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class GuestReservationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'reservation_code' => Str::uuid(),
            'guest_code' => \App\Models\Guest::inRandomOrder()->first()?->guest_code ?? Str::uuid(),
            'room_code' => \App\Models\Room::inRandomOrder()->first()?->room_code ?? Str::uuid(),
            'checkin_date' => $this->faker->dateTimeBetween('now', '+1 days'),
            'checkout_date' => $this->faker->dateTimeBetween('+2 days', '+7 days'),
            'cancelled_date' => null,
            'reason' => null,
            'rate' => $this->faker->numberBetween(50, 300),
            'total_payment' => $this->faker->numberBetween(100, 1500),
            'payment_method' => $this->faker->randomElement(['Cash', 'Credit Card', 'Online']),
            'number_of_guest' => $this->faker->numberBetween(1, 4),
            'is_checkin' => false,
            'created_by' => 1,
            'modified_by' => null,
            'is_active' => true,
        ];
    }
}
