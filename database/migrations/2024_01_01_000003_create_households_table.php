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
        Schema::create('households', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purok_id')->constrained()->cascadeOnDelete();
            $table->foreignId('head_resident_id')->nullable();
            $table->string('household_no')->unique();
            $table->string('address');
            $table->enum('ownership_status', ['owned', 'rented', 'shared', 'informal_settler']);
            $table->enum('housing_type', ['concrete', 'semi_concrete', 'wood', 'makeshift']);
            $table->enum('income_class', ['poor', 'low_income', 'middle', 'high'])->nullable();
            $table->decimal('monthly_income', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('households');
    }
};
