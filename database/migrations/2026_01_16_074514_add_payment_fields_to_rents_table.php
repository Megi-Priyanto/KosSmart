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
        Schema::table('rents', function (Blueprint $table) {
            // Payment method fields
            $table->string('payment_method')->nullable()->after('notes');
            $table->string('payment_sub_method')->nullable()->after('payment_method');
            
            // DP payment status
            $table->enum('dp_payment_status', ['pending', 'approved', 'rejected'])
                ->default('pending')
                ->after('payment_sub_method');
            
            // DP verification fields
            $table->boolean('dp_paid')->default(false)->after('dp_payment_status');
            $table->timestamp('dp_verified_at')->nullable()->after('dp_paid');
            $table->foreignId('dp_verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->after('dp_verified_at');
            $table->text('dp_rejection_reason')->nullable()->after('dp_verified_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rents', function (Blueprint $table) {
            $table->dropForeign(['dp_verified_by']);
            $table->dropColumn([
                'payment_method',
                'payment_sub_method',
                'dp_payment_status',
                'dp_paid',
                'dp_verified_at',
                'dp_verified_by',
                'dp_rejection_reason',
            ]);
        });
    }
};