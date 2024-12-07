<?php

use app\core\database\migrations\Schema;
use app\core\database\migrations\table\Table;

class M2024_11_16_09_02_49_create_categories_table {
    public function up () {
        Schema::create('categories', function (Table $table) {            
            $table->id();
            $table->string('name') ;
            $table->string('__typename');
            $table->timesStamp();
        });
    } 

    public function down () {
        Schema::dropTable('categories');
    } 
}