<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('book_requests', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('requested_by'); // ID of the user making the request
            $table->bigInteger('requested_from'); // ID of the user receiving the request
            $table->bigInteger('book_id'); // ID of the book being requested
            $table->integer('quantity'); // Quantity of books requested
            $table->string('status')->default('pending'); // Status of the request
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('book_requests');
    }
}
