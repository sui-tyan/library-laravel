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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string("isbn")->default("none");
            $table->string("issn")->default("none");
            $table->text("title");
            $table->string("categories");
            $table->integer("bookID");
            $table->string("borrowerID");
            $table->string("borrower");
            $table->string("borrowerAddress");
            $table->string("borrowerContactNumber");
            $table->string("borrowerCourseAndYear");
            $table->string("borrowerDepartment");
            $table->string("librarian")->default("none-assigned");
            $table->date("dateBorrowed");
            $table->date("dueDate");
            $table->string('bookPrice');
            $table->string("status");
            $table->string("purpose");
            $table->date("dateReturned")->nullable();
            $table->string("remarks")->default("ongoing");
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('transactions');

    }
};
