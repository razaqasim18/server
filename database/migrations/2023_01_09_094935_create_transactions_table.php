<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('payment_methods_id');
            $table->unsignedBigInteger('ticket_id');
            $table->integer('amount');
            $table->string('transactionid')->nullable();
            $table->string('description')->nullable();
            $table->string('image')->nullable();

            $table->string('nameoncard')->nullable();
            $table->string('email')->nullable();
            $table->string('country')->nullable();
            $table->string('company')->nullable();
            $table->string('address')->nullable();
            $table->string('website')->nullable();
            $table->string('phone')->nullable();

            $table
                ->string('status', 10)
                ->default('0')
                ->comment('0 pending,1 accepted,-1 rejected');
            $table->timestamps();

            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table
                ->foreign('payment_methods_id')
                ->references('id')
                ->on('payment_methods')
                ->onDelete('cascade');
            $table
                ->foreign('ticket_id')
                ->references('id')
                ->on('tickets')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
