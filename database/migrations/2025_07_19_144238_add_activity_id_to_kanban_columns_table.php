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
        Schema::table('kanban_columns', function (Blueprint $table) {
            $table->foreignId('activity_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kanban_columns', function (Blueprint $table) {
            $table->dropForeign(['activity_id']);
            $table->dropColumn('activity_id');
        });
    }
};
