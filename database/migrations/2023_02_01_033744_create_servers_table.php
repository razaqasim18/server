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
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('data_center_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('ticket_id');
            $table->unsignedBigInteger('package_id');
            $table->string('server_ip')->nullable();
            $table->string('sale_price')->nullable();
            $table->string('server_cost')->nullable();
            $table->string('setup_cost')->nullable();
            $table->string('web_user')->nullable();
            $table->string('web_password')->nullable();
            $table->string('uuid')->nullable();
            $table->string('client_user')->nullable()->default('admin');
            $table->string('client_password')->nullable()->default('admin');
            $table->date('expired_at')->nullable();
            $table->boolean('is_expired')->default(0);
            $table->timestamps();

            $table
                ->foreign('data_center_id')
                ->references('id')
                ->on('data_centers')
                ->onDelete('cascade');
            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table
                ->foreign('ticket_id')
                ->references('id')
                ->on('tickets')
                ->onDelete('cascade');
            $table
                ->foreign('package_id')
                ->references('id')
                ->on('packages')
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
        Schema::dropIfExists('servers');
    }
};
