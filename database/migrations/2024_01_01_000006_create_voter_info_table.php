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
        Schema::create('voter_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('precinct_no')->nullable();
            $table->string('voters_id_no')->nullable();
            $table->boolean('is_registered')->default(false);
            $table->date('registration_date')->nullable();
            $table->string('voting_center')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voter_info');
    }
};
