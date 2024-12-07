<?php

use app\core\database\migrations\Schema;
use app\core\database\migrations\table\Table;

class M2024_11_16_09_05_59_create_gallery_table {
    public function up () {
        Schema::create('gallery', function (Table $table) {            
            $table->id();
            $table->string('product_id');
            $table->foreign('product_id')->references('id')
            ->on('products')->cascadeOnDelete();

            $table->string('img') ;
            $table->timesStamp();
        });
    } 

    public function down () {
        Schema::dropTable('gallery');
    } 
}