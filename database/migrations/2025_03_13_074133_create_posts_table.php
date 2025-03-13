<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('content')->nullable();
            $table->string('image')->nullable();
            $table->string('youtube_link')->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('posts');
    }
};
