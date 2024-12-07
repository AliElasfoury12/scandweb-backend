<?php

use app\core\database\migrations\Schema;
use app\core\database\migrations\table\Table;

class M2024_11_16_09_07_38_create_attributes_table 	 {
    public function up () {
        Schema::create('attributes', function (Table $table) {            
            $table->string('product_id')
            ->foreign('product_id')
            ->references('id')
            ->on('products')->cascadeOnDelete();

            $table->string('id');
            $table->string('name');
            $table->string('type');
            $table->string('__typename');
            $table->timesStamp();
        });
    } 

    public function down () {
        Schema::dropTable('attributes');
    } 
}