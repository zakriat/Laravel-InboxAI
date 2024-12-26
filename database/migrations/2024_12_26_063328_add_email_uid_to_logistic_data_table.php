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
        Schema::table('logistic_data', function (Blueprint $table) {
            if (!Schema::hasColumn('logistic_data', 'email_uid')) {
                $table->string('email_uid')->unique()->after('id'); // Add UID with a unique constraint
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logistic_data', function (Blueprint $table) {
            if (Schema::hasColumn('logistic_data', 'email_uid')) {
                $table->dropUnique(['email_uid']);
                $table->dropColumn('email_uid'); // Remove UID column if it exists
            }
        });
    }
};
