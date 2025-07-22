<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kanban_cards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('activity_id');
            $table->unsignedBigInteger('kanban_column_id');
            $table->string('title')->nullable(); // Optional: nama langkah
            $table->text('notes')->nullable(); // Optional: catatan tambahan
            $table->timestamps();

            $table->foreign('activity_id')->references('id')->on('activities')->onDelete('cascade');
            $table->foreign('kanban_column_id')->references('id')->on('kanban_columns')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kanban_cards');
    }
};
