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
        Schema::table('book_stock', function (Blueprint $table) {
            $table->unsignedBigInteger('book_request_id')->nullable(); // Add book_request_id column

            // Set up foreign key constraint
            $table->foreign('book_request_id')->references('id')->on('book_requests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('book_stock', function (Blueprint $table) {
            // Drop the foreign key and column if the migration is rolled back
            $table->dropForeign(['book_request_id']);
            $table->dropColumn('book_request_id');
        });
    }
};
