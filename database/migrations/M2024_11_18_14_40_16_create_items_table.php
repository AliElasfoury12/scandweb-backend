<?php

use app\core\database\migrations\Schema;
use app\core\database\migrations\table\Table;

class M2024_11_18_14_40_16_create_items_table  {
    public function up () {
        Schema::create('items', function (Table $table) { 
            $table->string('product_id')
            ->foreign('product_id')
            ->references('id')
            ->on('products')->cascadeOnDelete();

            $table->string('attribute_id');
            $table->string('displayValue');
            $table->string('value');
            $table->string('id');
            $table->string('__typename');
            $table->timesStamp();
        });
    } 

    public function down () {
        Schema::dropTable('items');
    } 
}