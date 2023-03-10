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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->comment('admins or users table'); //admin or user table
            $table->string('title'); //admin or user table
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('priority_id');
            $table->boolean('package_id')->nullable()->comment('if department id is 3 than not empty');
            $table->tinyInteger('package_price')->nullable()->comment('if department id is 3 than not empty');
            $table
                ->tinyInteger('status')
                ->default(0)
                ->comment('0 open,1 closed'); // 0 opening, 1 closed
            // $table->boolean("is_seen")->default(false); // 0 not seen , 1 seen
            $table
                ->integer('is_answer')
                ->nullable()
                ->comment('answered by from admins'); // 0 not seen , 1 seen
            $table
                ->tinyInteger('user_type')
                ->default(0)
                ->comment('0 admins,1 users'); // 0 admin , 1 user
            $table->timestamps();

            $table
                ->foreign('department_id')
                ->references('id')
                ->on('departments')
                ->onDelete('cascade');
            $table
                ->foreign('priority_id')
                ->references('id')
                ->on('priorities')
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
        Schema::dropIfExists('tickets');
    }
};
