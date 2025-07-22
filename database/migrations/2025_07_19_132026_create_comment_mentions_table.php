<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentMentionsTable extends Migration
{
    public function up()
    {
        Schema::create('comment_mentions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kanban_comment_id')->constrained('kanban_comments')->onDelete('cascade'); // Relasi ke tabel kanban_comments
            $table->foreignId('mentioned_user_id')->constrained('users')->onDelete('cascade'); // Relasi ke tabel users
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('comment_mentions');
    }
}
