<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('mobile')->index();
            $table->string('email')->nullable()->index();
            $table->text('address')->nullable();
            $table->string('gst_number')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('vehicles', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('registration_number')->unique();
            $table->string('brand')->index();
            $table->string('model');
            $table->unsignedSmallInteger('year')->nullable();
            $table->string('vehicle_type')->default('car');
            $table->string('fuel_type')->nullable();
            $table->string('engine_number')->nullable();
            $table->string('chassis_number')->nullable();
            $table->unsignedInteger('odometer')->default(0);
            $table->date('insurance_expiry')->nullable();
            $table->date('puc_expiry')->nullable();
            $table->date('last_service_at')->nullable();
            $table->date('next_service_due_at')->nullable();
            $table->string('qr_code')->nullable();
            $table->timestamps();
        });

        Schema::create('mechanics', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->json('skills')->nullable();
            $table->unsignedTinyInteger('experience_years')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('service_catalog', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('estimated_minutes')->default(60);
            $table->decimal('base_cost', 12, 2)->default(0);
            $table->json('required_parts')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('suppliers', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('contact_person')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });

        Schema::create('inventory_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
            $table->string('sku')->unique();
            $table->string('barcode')->nullable()->unique();
            $table->string('name');
            $table->string('category')->nullable()->index();
            $table->decimal('purchase_price', 12, 2)->default(0);
            $table->decimal('selling_price', 12, 2)->default(0);
            $table->integer('quantity')->default(0);
            $table->integer('minimum_stock')->default(0);
            $table->string('unit')->default('pcs');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('job_cards', function (Blueprint $table): void {
            $table->id();
            $table->string('job_number')->unique();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('mechanic_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('complaint');
            $table->dateTime('estimated_delivery_at')->nullable();
            $table->string('priority')->default('normal')->index();
            $table->string('status')->default('pending')->index();
            $table->text('diagnosis')->nullable();
            $table->text('repair_notes')->nullable();
            $table->unsignedInteger('time_taken_minutes')->default(0);
            $table->text('inspection_notes')->nullable();
            $table->text('delivery_notes')->nullable();
            $table->dateTime('check_in_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('delivered_at')->nullable();
            $table->timestamps();
        });

        Schema::create('job_card_service', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('job_card_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_catalog_id')->constrained('service_catalog')->cascadeOnDelete();
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('job_card_part', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('job_card_id')->constrained()->cascadeOnDelete();
            $table->foreignId('inventory_item_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('job_card_photos', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('job_card_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->string('caption')->nullable();
            $table->timestamps();
        });

        Schema::create('stock_movements', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('inventory_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('job_card_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type')->index();
            $table->integer('quantity');
            $table->string('reference')->nullable();
            $table->text('notes')->nullable();
            $table->dateTime('moved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('invoices', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('job_card_id')->constrained()->cascadeOnDelete();
            $table->string('invoice_number')->unique();
            $table->dateTime('issued_at');
            $table->decimal('labour_total', 12, 2)->default(0);
            $table->decimal('parts_total', 12, 2)->default(0);
            $table->decimal('discount_total', 12, 2)->default(0);
            $table->decimal('tax_total', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2)->default(0);
            $table->string('payment_status')->default('pending')->index();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('invoice_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->string('description');
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('method');
            $table->string('reference')->nullable();
            $table->dateTime('paid_at');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('service_reminders', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('type')->index();
            $table->dateTime('due_at');
            $table->dateTime('sent_at')->nullable();
            $table->string('channel')->default('database');
            $table->string('status')->default('pending');
            $table->text('message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_reminders');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('stock_movements');
        Schema::dropIfExists('job_card_photos');
        Schema::dropIfExists('job_card_part');
        Schema::dropIfExists('job_card_service');
        Schema::dropIfExists('job_cards');
        Schema::dropIfExists('inventory_items');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('service_catalog');
        Schema::dropIfExists('mechanics');
        Schema::dropIfExists('vehicles');
        Schema::dropIfExists('customers');
    }
};
