<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentInteractionsTable extends Migration
{
    public function up()
    {
        Schema::create('comment_interactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('comment_id'); // ID of the comment being interacted with
            $table->unsignedBigInteger('user_id');   // User performing the action
            $table->enum('action', ['like', 'reply']); // Either 'like' or 'reply'
            $table->text('content')->nullable();     // Content for replies (optional for likes)
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('comment_id')->references('id')->on('post_interactions')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('comment_interactions');
    }
}
