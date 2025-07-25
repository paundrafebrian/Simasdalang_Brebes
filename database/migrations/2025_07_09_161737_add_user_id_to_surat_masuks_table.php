<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('surat_masuks', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->after('id');

            // Tambahkan foreign key jika mau
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

        /**
         * Reverse the migrations.
         */
    public function down()
    {
        Schema::table('surat_masuks', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
