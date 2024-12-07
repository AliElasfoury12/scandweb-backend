<?php

use app\core\database\migrations\Schema;
use app\core\database\migrations\table\Table;

class className
{
    public function up () {
        Schema::create('tableName', function (Table $table) {            
            $table->id();
            $table->timesStamp();
        });
    } 

    public function down () {
        Schema::dropTable('tableName');
    } 
}