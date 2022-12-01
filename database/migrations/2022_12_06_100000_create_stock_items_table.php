<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_items', function (Blueprint $table) {

            $table->id();
            $table->timestamps();

            $table->integer('stockable_id');
            $table->string('stockable_type');
            $table->integer('quantity');

            $table->bigInteger('stock_id')->unsigned()->nullable();
            $table->foreign('stock_id')
                ->references('id')
                ->on('stocks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_items');
    }
}
