<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. KOS_INFO TABLE
        Schema::table('kos_info', function (Blueprint $table) {
            if (!Schema::hasColumn('kos_info', 'tempat_kos_id')) {
                $table->foreignId('tempat_kos_id')
                    ->after('id')
                    ->constrained('tempat_kos')
                    ->onDelete('cascade');
                
                $table->index('tempat_kos_id');
            }
        });

        // 2. ROOMS TABLE
        Schema::table('rooms', function (Blueprint $table) {
            if (!Schema::hasColumn('rooms', 'tempat_kos_id')) {
                $table->foreignId('tempat_kos_id')
                    ->after('id')
                    ->constrained('tempat_kos')
                    ->onDelete('cascade');
                
                $table->index('tempat_kos_id');
            }
        });

        // 3. RENTS TABLE
        Schema::table('rents', function (Blueprint $table) {
            if (!Schema::hasColumn('rents', 'tempat_kos_id')) {
                $table->foreignId('tempat_kos_id')
                    ->after('id')
                    ->constrained('tempat_kos')
                    ->onDelete('cascade');
                
                $table->index('tempat_kos_id');
            }
        });

        // 4. BILLINGS TABLE
        Schema::table('billings', function (Blueprint $table) {
            if (!Schema::hasColumn('billings', 'tempat_kos_id')) {
                $table->foreignId('tempat_kos_id')
                    ->after('id')
                    ->constrained('tempat_kos')
                    ->onDelete('cascade');
                
                $table->index('tempat_kos_id');
            }
        });

        // 5. PAYMENTS TABLE
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'tempat_kos_id')) {
                $table->foreignId('tempat_kos_id')
                    ->after('id')
                    ->constrained('tempat_kos')
                    ->onDelete('cascade');
                
                $table->index('tempat_kos_id');
            }
        });

        // 6. NOTIFICATIONS TABLE
        Schema::table('notifications', function (Blueprint $table) {
            if (!Schema::hasColumn('notifications', 'tempat_kos_id')) {
                $table->foreignId('tempat_kos_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('tempat_kos')
                    ->onDelete('cascade');
                
                $table->index('tempat_kos_id');
            }
        });
    }

    public function down(): void
    {
        // KOS_INFO
        Schema::table('kos_info', function (Blueprint $table) {
            if (Schema::hasColumn('kos_info', 'tempat_kos_id')) {
                $table->dropForeign(['tempat_kos_id']);
                $table->dropColumn('tempat_kos_id');
            }
        });

        // ROOMS
        Schema::table('rooms', function (Blueprint $table) {
            if (Schema::hasColumn('rooms', 'tempat_kos_id')) {
                $table->dropForeign(['tempat_kos_id']);
                $table->dropColumn('tempat_kos_id');
            }
        });

        // RENTS
        Schema::table('rents', function (Blueprint $table) {
            if (Schema::hasColumn('rents', 'tempat_kos_id')) {
                $table->dropForeign(['tempat_kos_id']);
                $table->dropColumn('tempat_kos_id');
            }
        });

        // BILLINGS
        Schema::table('billings', function (Blueprint $table) {
            if (Schema::hasColumn('billings', 'tempat_kos_id')) {
                $table->dropForeign(['tempat_kos_id']);
                $table->dropColumn('tempat_kos_id');
            }
        });

        // PAYMENTS
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'tempat_kos_id')) {
                $table->dropForeign(['tempat_kos_id']);
                $table->dropColumn('tempat_kos_id');
            }
        });

        // NOTIFICATIONS
        Schema::table('notifications', function (Blueprint $table) {
            if (Schema::hasColumn('notifications', 'tempat_kos_id')) {
                $table->dropForeign(['tempat_kos_id']);
                $table->dropColumn('tempat_kos_id');
            }
        });
    }
};