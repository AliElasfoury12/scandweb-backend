<?php

use app\core\database\migrations\Schema;
use app\core\database\migrations\table\Table;

class M2024_11_18_14_44_43_create_prices_table  {
    public function up () {
        Schema::create('prices', function (Table $table) {            
            $table->id();
            $table->string('product_id');
            $table->foreign('product_id')->references('id')
            ->on('products')->cascadeOnDelete();

            $table->string('amount');
            $table->json('currency');
            $table->string('__typename');
            $table->timesStamp();
        });
    } 

    public function down () {
        Schema::dropTable('prices');
    } 
}