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

    public function request($sql, $params = null, $isFetchAll=false) {
        $statement = $this->conn->prepare($sql);
        if ($params !== null) {
            foreach ($params as $key => $value) {
                $statement->bindValue(":$key", $value);
            }
        }
        try {
            $result = $statement->execute();
            if (!$result) {return null;}
            if ($isFetchAll) {
                return $statement->fetchAll(PDO::FETCH_ASSOC);
            } else {
                return $statement->fetch(PDO::FETCH_ASSOC);
            }
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