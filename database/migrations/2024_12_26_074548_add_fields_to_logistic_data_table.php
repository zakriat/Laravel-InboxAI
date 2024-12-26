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
            $table->string('message')->nullable()->after('status');
            $table->string('cargo_type')->nullable()->after('cargo_weight_kg');
            $table->text('additional_requirements')->nullable()->after('destination');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logistic_data', function (Blueprint $table) {
            $table->dropColumn('message');
            $table->dropColumn('cargo_type');
            $table->dropColumn('additional_requirements');
        });
    }
};
