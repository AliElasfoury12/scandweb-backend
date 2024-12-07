<?php

use app\core\database\migrations\Schema;
use app\core\database\migrations\table\Table;

class M2024_12_03_23_40_27_create_orders_table  {
    public function up () {
        Schema::create('orders', function (Table $table) {            
            $table->bigInt('id')->unsigned();

            $table->string('product_id');
            $table->foreign('product_id')->references('id')
            ->on('products')->cascadeOnDelete();
            
            $table->json('attributes');
            $table->int('quantity');
            $table->timesStamp();
        });
    } 

    public function down () {
        Schema::dropTable('orders');
    } 
}