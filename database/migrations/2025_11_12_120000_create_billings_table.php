<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('billings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('rent_id')->constrained('rents')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('room_id')->constrained('rooms')->cascadeOnDelete();

            // Tambahan untuk DP & Pelunasan
            $table->enum('tipe', ['dp', 'pelunasan', 'bulanan']);
            $table->decimal('jumlah', 12, 2)->nullable();
            $table->string('keterangan')->nullable();

            // Informasi Periode
            $table->string('billing_period');
            $table->integer('billing_year');
            $table->integer('billing_month');

            // Komponen Biaya
            $table->decimal('rent_amount', 12, 2);
            $table->decimal('electricity_cost', 12, 2)->default(0);
            $table->decimal('water_cost', 12, 2)->default(0);
            $table->decimal('maintenance_cost', 12, 2)->default(0);
            $table->decimal('other_costs', 12, 2)->default(0);
            $table->text('other_costs_description')->nullable();

            // Total & Diskon
            $table->decimal('subtotal', 12, 2);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2);

            // Waktu & Status Pembayaran
            $table->date('due_date');
            $table->date('paid_date')->nullable();
            $table->enum('status', ['unpaid', 'pending', 'paid', 'overdue'])
                ->default('unpaid');

            // Catatan
            $table->text('admin_notes')->nullable();
            $table->text('user_notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['user_id', 'status']);
            $table->index(['billing_year', 'billing_month']);
            $table->index('due_date');

            // Unique: per rent hanya boleh ada 1 billing per bulan
            $table->unique(['rent_id', 'billing_year', 'billing_month', 'tipe']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('billings');
    }
};
