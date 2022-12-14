<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {

            $table->id();
            $table->timestamps();

            $table->string('order_id');
            $table->integer('status')->nullable();

            $table->string('payment_id')->nullable();
            $table->integer('payment_status')->nullable();

            $table->string('fulfilment')->nullable();
            $table->integer('fulfilment_status')->nullable();
            $table->json('fulfilment_data')->nullable();

            $table->string('total')->nullable();
            $table->string('subtotal')->nullable();
            $table->string('reduction')->nullable();
            $table->string('fulfilment_cost')->nullable();

            $table->bigInteger('customer_id')->unsigned()->nullable();
            $table->foreign('customer_id')
                ->references('id')
                ->on('customers');
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
}
