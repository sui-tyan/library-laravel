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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string("isbn")->default("none");
            $table->string("issn")->default("none");
            $table->text("title");
            $table->string("author");
            $table->text("publisher");
            $table->text("description");
            $table->integer("deweyDecimal");
            $table->string("categories");
            $table->integer("price");
            $table->integer("quantity");
            $table->date("publishedDate");
            $table->string("type");
            $table->string("status")->default("Available");
            $table->string("remarks");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('books');
    }
};
