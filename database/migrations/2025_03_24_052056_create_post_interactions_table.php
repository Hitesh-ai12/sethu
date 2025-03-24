<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('post_interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->onDelete('cascade'); // Reference to posts
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Reference to users
            $table->string('action'); // Action type: like, comment, save, share
            $table->text('content')->nullable(); // For storing comment text
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('post_interactions');
    }
};
