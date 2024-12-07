<?php

use app\core\database\migrations\Schema;
use app\core\database\migrations\table\Table;

class M2024_11_16_09_04_51_create_products_table {
    public function up () {
        Schema::create('products', function (Table $table) {            
            $table->string('id')->primary();
            $table->string('name');
            $table->bool('inStock')->default(false);
            $table->text('description');
            $table->string('category');
            $table->string('brand');
            $table->string('__typename');
            $table->timesStamp();
        });
    } 

    public function down () {
        Schema::dropTable('products');
    } 
}