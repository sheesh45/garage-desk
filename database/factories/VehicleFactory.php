<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Vehicle>
 */
class VehicleFactory extends Factory
{
    protected $model = Vehicle::class;

    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'registration_number' => strtoupper(fake()->bothify('MH##??####')),
            'brand' => fake()->randomElement(['Honda', 'Hyundai', 'Maruti', 'Yamaha', 'Tata']),
            'model' => fake()->randomElement(['City', 'Activa', 'i20', 'Nexon', 'FZ']),
            'year' => fake()->numberBetween(2015, 2026),
            'vehicle_type' => fake()->randomElement(['car', 'bike']),
            'fuel_type' => fake()->randomElement(['petrol', 'diesel', 'electric']),
            'engine_number' => strtoupper(fake()->bothify('ENG####??')),
            'chassis_number' => strtoupper(fake()->bothify('CHS####??')),
            'odometer' => fake()->numberBetween(1000, 90000),
            'insurance_expiry' => now()->addMonths(fake()->numberBetween(1, 12)),
            'puc_expiry' => now()->addMonths(fake()->numberBetween(1, 6)),
            'last_service_at' => now()->subMonths(fake()->numberBetween(1, 6)),
            'next_service_due_at' => now()->addMonths(fake()->numberBetween(1, 4)),
            'qr_code' => strtoupper(fake()->bothify('VEH-####')),
        ];
    }
}
