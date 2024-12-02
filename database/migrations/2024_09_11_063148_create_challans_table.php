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
        Schema::create('challans', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('book_request_id')->unsigned(); // Links to book_requests table
            $table->string('challan_number')->unique(); // Unique number for each challan
            $table->date('challan_date'); // Date when the challan is generated
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('book_request_id')->references('id')->on('book_requests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('challans');
    }
};
