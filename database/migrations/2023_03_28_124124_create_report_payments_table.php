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
        Schema::create('report_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('server_id');
            $table->string('server_ip')->nullable();
            $table->integer('amount')->default(0);
            $table
                ->tinyInteger('status')
                ->default(0)
                ->comment('0 deducted,1 added'); // 0 deducted, 1 added
            $table
                ->string('approve_by')
                ->default(0)
                ->comment('0 system'); // 0 deducted, 1 added

            $table->dateTime('created_at');
            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table
                ->foreign('server_id')
                ->references('id')
                ->on('servers')
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
        Schema::dropIfExists('report_payments');
    }
};
