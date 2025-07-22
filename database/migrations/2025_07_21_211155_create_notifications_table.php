<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // ID pengguna yang menerima notifikasi
            $table->text('message'); // Pesan notifikasi
            $table->string('status')->default('unread'); // Status apakah sudah dibaca atau belum
            $table->timestamps();

            // Menambahkan foreign key (opsional, jika pengguna ada di tabel users)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
