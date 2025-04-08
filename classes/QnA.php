<?php
namespace qna;

error_reporting(E_ALL);
ini_set('display_errors', "On");
use Database;

require_once(__ROOT__."/classes/Database.php");
require_once(__ROOT__.'/dataBase/config.php');

class QnA extends Database {
    private $connection;

    public function __construct() {
        $this->connect();

        $this->connection = $this->getConnection();
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
            $this->connection->beginTransaction();
            $param = array('question' => $question, 'answer' => $answer);
            $this->request($sql, $param);
            $this->connection->commit();
        }catch (Exception $e) {
            echo "Chyba pri vkladaní dát do databázy: " . $e->getMessage();
            $this->connection->rollback();
        }
    }

    public function getQuestionAnswer() {
        $sql = "SELECT question, answer FROM qna";
        $result = array();
        try {
            $result = $this->request($sql, null,true);
        }catch (Exception $e) {
            echo "While fetching from db: " . $e->getMessage();
            $this->connection->rollback();
        } finally {
            $this->connection = null;
        }
        return $result;
    }
}

