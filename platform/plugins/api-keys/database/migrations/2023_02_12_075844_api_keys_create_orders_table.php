<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('symbol');
            $table->bigInteger('orderId');
            $table->bigInteger('orderListId')->nullable();
            $table->string('clientOrderId')->nullable();
            $table->decimal('price', 16, 8);
            $table->decimal('origQty', 16, 8)->nullable();
            $table->decimal('executedQty', 16, 8)->nullable();
            $table->decimal('cummulativeQuoteQty', 16, 8)->nullable();
            $table->string('status')->nullable();
            $table->string('timeInForce')->nullable();
            $table->string('type')->nullable();
            $table->string('side')->nullable();
            $table->decimal('stopPrice', 16, 8)->nullable();
            $table->decimal('icebergQty', 16, 8)->nullable();
            $table->bigInteger('time')->nullable();
            $table->bigInteger('updateTime')->nullable();
            $table->boolean('isWorking')->nullable();
            $table->bigInteger('workingTime')->nullable();
            $table->decimal('origQuoteOrderQty', 16, 8)->nullable();
            $table->string('selfTradePreventionMode')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
