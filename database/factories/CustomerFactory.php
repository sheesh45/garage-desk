<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'mobile' => fake()->numerify('98########'),
            'email' => fake()->safeEmail(),
            'address' => fake()->address(),
            'gst_number' => fake()->optional()->bothify('GST#######'),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
