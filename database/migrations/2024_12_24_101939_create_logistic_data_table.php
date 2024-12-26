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
        Schema::create('logistic_data', function (Blueprint $table) {
            $table->id();
            $table->string('request_type')->nullable();
            $table->string('origin')->nullable();
            $table->string('destination')->nullable();
            $table->float('cargo_weight_kg')->nullable();
            $table->string('container_type')->nullable();
            $table->string('transport_mode')->nullable();
            $table->string('status')->nullable();
            $table->string('email_from')->nullable();
            $table->string('email_to')->nullable();
            $table->string('email_subject')->nullable();
            $table->datetime('email_date')->nullable();
            $table->text('email_body')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logistic_data');
    }
};
