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
            $table->enum('status', ['Pending','Ditolak', 'Diterima'])->default('Pending');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('surat_masuks', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
