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
        Schema::table('users', function (Blueprint $table) {
            //
            
            $table->string('studentID')->default("none");
            $table->string('firstName');
            $table->string('middleName');
            $table->string('lastName');
            $table->string('address')->default("none");
            $table->string('gender')->default("none");
            $table->string('contactNumber')->default("none");
            $table->string('courseAndYear')->default("none");
            $table->string('department')->default("none");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropColumn('studentID');
            $table->dropColumn('firstName');
            $table->dropColumn('middleName');
            $table->dropColumn('lastName');
            $table->dropColumn('address');
            $table->dropColumn('gender');
            $table->dropColumn('contactNumber');
            $table->dropColumn('courseAndYear');
            $table->dropColumn('department');
        });
    }
};
