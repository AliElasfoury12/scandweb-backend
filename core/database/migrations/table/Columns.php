<?php 

namespace app\core\database\migrations\table;

trait Columns
{
    public array $query = ['add' => [], 'drop' => []];

    public function getLastItem () 
    {
        return end($this->query['add']);
    }

    public function setLastItem ($value)
    {
        $this->query['add'][count(   $this->query['add']) - 1] = $value;
    }

    public function bigInt ($name)
    {
        $this->query['add'][] = "$name BIGINT NOT NULL" ;
        return $this ;
    }

    public function bool ($column ) {
        $this->query['add'][] = "$column BOOLEAN NOT NULL" ;
        return $this ;
    }

    public function foreignId ($name)// post_id
    {
        $this->query['add'][] = "$name BIGINT(20) UNSIGNED NOT NULL" ;
        return $this ;
    }

    public function id ($name = 'id') 
    {
        $this->query['add'][] = "$name BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY" ;
        return $this ;
    }

    public function int ($name)
    {
        $this->query['add'][] = "$name INT" ;
        return $this ;
    }

    public function string ($name, $length = 255) {
        $this->query['add'][] = "$name VARCHAR ($length) NOT NULL" ;
        return $this ;
    }

    public function text ($name) {
        $this->query['add'][] = "$name TEXT  NOT NULL" ;
        return $this ;
    }

    public function timesStamp () {
        $this->query['add'][] = "updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
         created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP" ;
        return $this ;
    }
    
}
