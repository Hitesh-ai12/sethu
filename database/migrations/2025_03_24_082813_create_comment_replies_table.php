<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentRepliesTable extends Migration
{
    public function up()
    {
        Schema::create('comment_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comment_id')->constrained('post_interactions')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('content');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('comment_replies');
    }
}
