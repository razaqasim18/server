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
        Schema::create('ticket_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_id');
            $table->integer('from_id')->nullable();
            $table
                ->integer('to_id')
                ->nullable()
                ->comment('null me to all admins');
            $table->text('message');
            $table->boolean('is_attachment')->default(false);
            $table->text('attachment')->nullable();
            $table->tinyInteger('user_type')->comment('link to to_id'); // inter link with to_id
            $table->boolean('is_seen')->default(false); // 0 not seen , 1 seen
            $table->timestamps();

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
        Schema::dropIfExists('ticket_details');
    }
};
