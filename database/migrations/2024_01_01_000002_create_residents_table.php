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
        Schema::create('residents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('household_id')->nullable();
            $table->foreignId('purok_id')->constrained();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('suffix')->nullable();
            $table->date('birthdate');
            $table->string('birthplace');
            $table->enum('sex', ['male', 'female']);
            $table->enum('civil_status', ['single', 'married', 'widowed', 'separated', 'annulled']);
            $table->string('citizenship')->default('Filipino');
            $table->string('religion')->nullable();
            $table->string('occupation')->nullable();
            $table->enum('employment_status', ['employed', 'self_employed', 'unemployed', 'student', 'retired'])->nullable();
            $table->string('educational_attainment')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('email')->nullable();
            $table->string('philhealth_no')->nullable();
            $table->string('sss_no')->nullable();
            $table->string('tin_no')->nullable();
            $table->enum('status', ['active', 'inactive', 'deceased', 'transferred'])->default('active');
            $table->boolean('is_4ps_beneficiary')->default(false);
            $table->boolean('is_pwd')->default(false);
            $table->boolean('is_senior_citizen')->default(false);
            $table->boolean('is_solo_parent')->default(false);
            $table->string('profile_photo')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('residents');
    }
};
