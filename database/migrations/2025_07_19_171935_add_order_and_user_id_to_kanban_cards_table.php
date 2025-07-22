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
        Schema::table('kanban_cards', function (Blueprint $table) {
            $table->unsignedInteger('order')->default(0);
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('kanban_cards', function (Blueprint $table) {
            $table->dropColumn('order');
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
