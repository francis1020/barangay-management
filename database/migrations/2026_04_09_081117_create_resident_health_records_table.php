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
        Schema::create('resident_health_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->constrained()->cascadeOnDelete();
            $table->string('blood_type')->nullable();
            $table->decimal('height', 5, 2)->nullable()->comment('in cm');
            $table->decimal('weight', 5, 2)->nullable()->comment('in kg');
            $table->text('allergies')->nullable();
            $table->text('medical_conditions')->nullable();
            $table->text('medications')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resident_health_records');
    }
};
