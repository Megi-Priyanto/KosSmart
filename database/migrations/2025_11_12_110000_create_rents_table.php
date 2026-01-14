<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rents', function (Blueprint $table) {
            $table->id();

            // Relasi
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();

            // Info sewa
            $table->date('start_date');
            $table->date('end_date')->nullable();

            $table->decimal('deposit_paid', 10, 2)->default(0);
            $table->decimal('monthly_rent', 10, 2);

            $table->enum('status', ['pending', 'active','checkout_requested','checked_out', 'completed', 'expired', 'cancelled'])
                ->default('pending');

            $table->text('notes')->nullable();

            // Tambahan dari migration kedua
            $table->text('admin_notes')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            // Index
            $table->index(['user_id', 'status']);
            $table->index(['room_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rents');
    }
};
