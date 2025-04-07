<?php

//define('__ROOT__', dirname(__FILE__, 2));
require_once(__ROOT__.'/dataBase/config.php');

class Database {
    private $conn;

    public function __construct() {
        $this->connect();
    }

    public function __destruct() {
        $this->conn = null;
    }

    public function getConnection() {
        return $this->conn;
    }

    public function requestList(...$sql) {
        $sqlArray = array();
        foreach ($sql as $query) {
            $sqlArray[] = $query;
        }

        $outFetch = array();
        try {
            foreach ($sqlArray as $query) {
                 $result = $query->execute();
                 if (!$result) {return null;}
                 $fetch = $query->fetch(PDO::FETCH_ASSOC);
                 $outFetch[] = $fetch;
            }
            return $outFetch;
        } catch (\Exception $exception) {
            return $exception;
        }
    }

    protected function connect() {
        $config = DATABASE;

        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        );

        try {
            $this->conn = new PDO('mysql:host='
                .$config['HOST']
                .';dbname='
                .$config['DBNAME']
                .';port='
                .$config['PORT'],
                $config['USER_NAME'],
                $config['PASSWORD'], $options);
        } catch (PDOException $e) {
            die("Chyba pripojenia: " . $e->getMessage());
        }
    }

}