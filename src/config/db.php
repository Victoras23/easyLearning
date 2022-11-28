<?php

class DataBase{
    private $host = 'localhost';
    private $user = 'admin';
    private $pass = 'admin';
    private $db = 'easyLearning';

    public function connect (){
        $connection_string="mysql:host=$this->host;dbname=$this->db";
        $connection = new PDO($connection_string , $this->user , $this->pass);
        $connection->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);

        return $connection;
    }
}