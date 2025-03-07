<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('school_college_name')->nullable();
            $table->string('city')->nullable();
            $table->string('mobile_number')->unique();
            $table->text('full_address')->nullable();
            $table->enum('role', ['teacher', 'admin', 'student'])->default('student');
            $table->text('description')->nullable();
            $table->boolean('mentorship')->default(false);
            $table->boolean('community')->default(false);
            $table->string('profile_photo')->nullable();
            $table->string('profile_video')->nullable();
            $table->json('followers')->default(json_encode([]));
            $table->json('following')->default(json_encode([]));
            $table->json('blocked_users')->default(json_encode([]));
            $table->json('reported_users')->default(json_encode([]));
            $table->boolean('can_share_profile')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
