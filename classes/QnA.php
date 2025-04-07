<?php

namespace qna;

define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/dataBase/config.php');
use PDO;
use PDOException;

class QnA{
    private $conn;

    public function __construct() {
        $this->connect();
        if ($this->conn === null) {
            die("Chyba: pripojenie k databáze nebolo úspešne vytvorené.");
        }
    }

    public function insertAllQnA() {
        $json = json_decode(file_get_contents(__ROOT__.'/data/qna.json'), true);
        foreach ($json as $qna=>$data) {
            $question = $data["quest"];
            $answer = $data["answer"];
            $this->insertQnA($question, $answer);
        }
    }

    function insertQnA($question, $answer) {
        $sql = "INSERT IGNORE INTO qna (question, answer) VALUES (:question, :answer)";
        try {
            $this->conn->beginTransaction();
            $statement = $this->conn->prepare($sql);

            $statement->bindParam(':question', $question);
            $statement->bindParam(':answer', $answer);
            $statement->execute();

            $this->conn->commit();
            //echo "Dáta boli vložené";
        }catch (Exception $e) {
            echo "Chyba pri vkladaní dát do databázy: " . $e->getMessage();
            $this->conn->rollback();
        }
    }

    public function getQuestionAnswer() {
        $sql = "SELECT question, answer FROM qna WHERE 1";
        $result = array();
        try {
            $statement = $this->conn->prepare($sql);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        }catch (Exception $e) {
            echo "While fetching from db: " . $e->getMessage();
            $this->conn->rollback();
        } finally {
            $this->conn = null;
        }
        return $result;
    }

    private function connect() {
        $config = DATABASE;
        $dsn = 'mysql:host=' . $config['HOST'] . ';dbname=' .
            $config['DBNAME'] . ';port=' . $config['PORT'];
        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        );
        try {
            $this->conn = new PDO(
                $dsn,
                $config['USER_NAME'],
                $config['PASSWORD'],
                $options
            );
        } catch (PDOException $e) {
            die("Chyba pripojenia: " . $e->getMessage());
        }
    }
}

