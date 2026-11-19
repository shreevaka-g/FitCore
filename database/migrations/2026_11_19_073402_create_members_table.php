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
        Schema::create('members', function (Blueprint $table) {
            $table->id();

            $table->string('member_code')->unique();

            $table->string('name');
            $table->string('email')->nullable()->unique();
            $table->string('contact_number', 20);

            $table->text('address')->nullable();

            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();

            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_number', 20)->nullable();

            $table->string('profile_photo_path')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
