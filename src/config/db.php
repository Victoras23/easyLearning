<?php

class DataBase{
    private $host = 'easylearning-mysqldb-1';
//    private $host = 'localhost';
//    private $user = 'test';
//    private $pass = 'test';
    private $user = 'admin';
    private $pass = 'admin';
    private $db = 'easyLearning';
    private $port = 3306;
    private $options = array(
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        PDO::MYSQL_ATTR_SSL_CA => './data/certs/client-cert.pem',
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
    );

    public function connect (){
        $connection_string="mysql:host=$this->host;port=$this->port;dbname=$this->db";
        $connection = new PDO($connection_string , $this->user , $this->pass, $this->options);
        $connection->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);

        return $connection;
    }
}