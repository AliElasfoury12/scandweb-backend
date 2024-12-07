<?php

use app\core\database\migrations\Schema;
use app\core\database\migrations\table\Table;

class M0001_migrations 
{
    public function up () {
        Schema::create('migrations', function (Table $table) {            
            $table->id();
            $table->string('migration');
            $table->timesStamp();
        });
    } 

    public function down () {
        Schema::dropTable('migrations');
    } 
}