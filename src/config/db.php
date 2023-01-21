<?php

class DataBase{
    private $host = 'easylearning-mysqldb-1';
//    private $host = 'localhost';
    private $user = 'admin';
    private $pass = 'admin';
    private $db = 'easyLearning';
    private $port = 3306;

    public function connect (){
        $connection_string="mysql:host=$this->host;port=$this->port;dbname=$this->db";
        $connection = new PDO($connection_string , $this->user , $this->pass);
        $connection->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);

        return $connection;
    }
}