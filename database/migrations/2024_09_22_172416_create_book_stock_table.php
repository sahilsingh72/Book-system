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
        Schema::create('book_stock', function (Blueprint $table) {
            $table->id('book_stock_id');
            $table->unsignedBigInteger('user_id'); // Reference to admins table
            $table->unsignedBigInteger('book_id'); // Reference to books table
            $table->integer('stock_quantity');
            $table->date('stock_date')->nullable();
            $table->boolean('isconfirmed')->default(0); // 0: Pending, 1: Confirmed
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('admins')->onDelete('cascade');
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('book_stock');
    }
};
