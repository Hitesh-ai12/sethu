<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nickname')->nullable()->after('name');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('nickname');
            $table->date('dob')->nullable()->after('gender');
            $table->string('profile_image')->nullable()->after('dob');
            $table->text('subject')->nullable()->after('profile_image');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nickname', 'gender', 'dob', 'profile_image', 'subject']);
        });
    }
};
