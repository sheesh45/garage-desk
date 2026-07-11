<?php

namespace Database\Seeders;

use App\Enums\JobCardPriority;
use App\Enums\JobCardStatus;
use App\Enums\PaymentStatus;
use App\Enums\ReminderType;
use App\Models\Customer;
use App\Models\InventoryItem;
use App\Models\Invoice;
use App\Models\JobCard;
use App\Models\Mechanic;
use App\Models\ServiceCatalog;
use App\Models\ServiceReminder;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class DemoGarageSeeder extends Seeder
{
    public function run(): void
    {
        $mechanicUser = User::where('email', 'mechanic@garageflow.test')->first();

        $mechanics = collect([
            Mechanic::create([
                'user_id' => $mechanicUser?->id,
                'name' => 'Aman Patel',
                'phone' => '9876543210',
                'skills' => ['Engine Repair', 'Electrical', 'Periodic Service'],
                'experience_years' => 6,
                'is_active' => true,
            ]),
            Mechanic::create([
                'name' => 'Rahul Mehta',
                'phone' => '9876501234',
                'skills' => ['Brake Service', 'Suspension', 'Alignment'],
                'experience_years' => 4,
                'is_active' => true,
            ]),
        ]);

        $supplier = Supplier::create([
            'name' => 'Prime Auto Spares',
            'contact_person' => 'Nisha',
            'phone' => '9988776655',
            'email' => 'orders@primeauto.test',
            'address' => 'Industrial Area, Pune',
        ]);

        $parts = collect([
            InventoryItem::create([
                'supplier_id' => $supplier->id,
                'sku' => 'ENG-OIL-5W30',
                'barcode' => '8901002003001',
                'name' => 'Engine Oil 5W30',
                'category' => 'Lubricants',
                'purchase_price' => 1100,
                'selling_price' => 1450,
                'quantity' => 24,
                'minimum_stock' => 8,
                'unit' => 'ltr',
            ]),
            InventoryItem::create([
                'supplier_id' => $supplier->id,
                'sku' => 'BRK-PAD-SET',
                'barcode' => '8901002003002',
                'name' => 'Brake Pad Set',
                'category' => 'Brakes',
                'purchase_price' => 1800,
                'selling_price' => 2400,
                'quantity' => 6,
                'minimum_stock' => 4,
                'unit' => 'set',
            ]),
        ]);

        $services = collect([
            ServiceCatalog::create([
                'name' => 'Oil Change',
                'description' => 'Engine oil replacement with inspection',
                'estimated_minutes' => 45,
                'base_cost' => 799,
                'required_parts' => ['Engine Oil 5W30'],
                'is_active' => true,
            ]),
            ServiceCatalog::create([
                'name' => 'Brake Service',
                'description' => 'Brake inspection and pad replacement',
                'estimated_minutes' => 90,
                'base_cost' => 1499,
                'required_parts' => ['Brake Pad Set'],
                'is_active' => true,
            ]),
            ServiceCatalog::create([
                'name' => 'Wheel Alignment',
                'description' => 'Four wheel alignment and balancing',
                'estimated_minutes' => 60,
                'base_cost' => 999,
                'required_parts' => [],
                'is_active' => true,
            ]),
        ]);

        Customer::factory()->count(6)->create()->each(function (Customer $customer) use ($mechanics, $parts, $services): void {
            $vehicle = Vehicle::factory()->create(['customer_id' => $customer->id]);

            $jobCard = JobCard::create([
                'job_number' => 'JOB-'.str_pad((string) ((JobCard::max('id') ?? 0) + 1), 5, '0', STR_PAD_LEFT),
                'customer_id' => $customer->id,
                'vehicle_id' => $vehicle->id,
                'mechanic_id' => $mechanics->random()->id,
                'complaint' => fake()->randomElement([
                    'Engine noise during startup',
                    'Brake squeal and reduced bite',
                    'Periodic service due',
                ]),
                'estimated_delivery_at' => now()->addDays(fake()->numberBetween(1, 3)),
                'priority' => fake()->randomElement(JobCardPriority::values()),
                'status' => fake()->randomElement(JobCardStatus::values()),
                'diagnosis' => fake()->sentence(),
                'repair_notes' => fake()->sentence(),
                'time_taken_minutes' => fake()->numberBetween(30, 180),
                'check_in_at' => now()->subHours(fake()->numberBetween(1, 48)),
                'completed_at' => fake()->boolean(40) ? now()->subHours(2) : null,
            ]);

            $selectedServices = $services->random(fake()->numberBetween(1, 2));
            foreach ($selectedServices as $service) {
                $jobCard->services()->attach($service->id, [
                    'quantity' => 1,
                    'unit_price' => $service->base_cost,
                    'total' => $service->base_cost,
                ]);
            }

            $selectedPart = $parts->random();
            $jobCard->parts()->attach($selectedPart->id, [
                'quantity' => 1,
                'unit_price' => $selectedPart->selling_price,
                'total' => $selectedPart->selling_price,
            ]);

            if (fake()->boolean(50)) {
                $labourTotal = $selectedServices->sum('base_cost');
                $partsTotal = $selectedPart->selling_price;

                $invoice = Invoice::create([
                    'job_card_id' => $jobCard->id,
                    'invoice_number' => 'INV-'.str_pad((string) ((Invoice::max('id') ?? 0) + 1), 5, '0', STR_PAD_LEFT),
                    'issued_at' => now()->subDays(fake()->numberBetween(0, 10)),
                    'labour_total' => $labourTotal,
                    'parts_total' => $partsTotal,
                    'discount_total' => 0,
                    'tax_total' => 180,
                    'grand_total' => $labourTotal + $partsTotal + 180,
                    'payment_status' => fake()->randomElement(PaymentStatus::values()),
                ]);

                foreach ($selectedServices as $service) {
                    $invoice->items()->create([
                        'type' => 'labour',
                        'description' => $service->name,
                        'quantity' => 1,
                        'unit_price' => $service->base_cost,
                        'total' => $service->base_cost,
                    ]);
                }

                $invoice->items()->create([
                    'type' => 'part',
                    'description' => $selectedPart->name,
                    'quantity' => 1,
                    'unit_price' => $selectedPart->selling_price,
                    'total' => $selectedPart->selling_price,
                ]);
            }

            ServiceReminder::create([
                'vehicle_id' => $vehicle->id,
                'customer_id' => $customer->id,
                'type' => fake()->randomElement(ReminderType::values()),
                'due_at' => now()->addDays(fake()->numberBetween(2, 20)),
                'channel' => 'database',
                'status' => 'pending',
                'message' => 'Upcoming maintenance reminder',
            ]);
        });
    }
}
