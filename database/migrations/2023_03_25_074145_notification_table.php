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
        //
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('studentID');
            $table->string('borrowerName');
            $table->string('borrowedBookTitle');
            $table->string('borrowedBookID');
            $table->string('borrowedContent');
            $table->integer('isSeen')->default(0);
            $table->integer('isSeenStudent')->default(0);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('notifications');
    }
};
