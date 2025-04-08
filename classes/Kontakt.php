<?php
namespace formular;
error_reporting(E_ALL);
ini_set('display_errors', "On");
use Database;
use Exception;

require_once(__ROOT__."/classes/Database.php");
require_once(__ROOT__.'/dataBase/config.php');

class Kontakt extends Database {

    protected $connection;

    public function __construct() {
        $this->connect();

        $this->connection = $this->getConnection();
    }

    public function getUserOrCreate($firstName, $lastName, $email, $text): String {
        if(!$this->checkInput($firstName, $lastName, $email, $text)) { return "Error message"; }

        $emailSequence = explode('@', $email);
        if (count($emailSequence) !== 2) {
            http_response_code(400);
            return "Invalid email format";
        }
        $emailLocal = $emailSequence[0];
        $emailDomain = $emailSequence[1];

        $result = $this->getOrNull($emailLocal, $emailDomain);
        if ($result == null) {//user exist?
            $result = $this->createUser($firstName, $lastName, $emailLocal, $emailDomain);
        }
        if ($result == null) {//something went wrong?
            return http_response_code(400);
        }
        return $result["clientID"];
    }

    public function writeComment($userID, $text) {
        $sql = 'INSERT INTO comments(clientID, date, text) VALUES (:userID, NOW(), :text)';
        $params = array();
        $params['userID'] = $userID;
        $params['text'] = $text;
        $result = $this->request($sql, $params);
        if ($result != Exception::class) {//idk, must work correctly, but not null, because somehow when good response can be null
            http_response_code(200);
            header("Location: http://localhost/sablona/thankyou.php");
        } else {
            http_response_code(404);
            die('Chyba pri odosielaní správy do databázy!');
        }
    }

    public function __destruct() {
        $this->connection = null;
    }

    private function checkInput($firstName, $lastName, $email, $text): bool {
        if ($firstName == "" || $email == "" || $text == "") { return false; }//not empty
        if (strlen($firstName)>25 || strlen($lastName)>25) { return false; }//not too long

        $emailSequence = explode('@', $email);
        $emailLocal = $emailSequence[0];
        $emailDomain = $emailSequence[1];
        if ($emailLocal == "" || strlen($emailLocal)>63) { return false; }//not an "@anything" and not impossible length
        if ($emailDomain == "" || strlen($emailDomain)>255) { return false; }//same what before but for domain part

        return true;
    }

    private function getOrNull($emailLocal, $emailDomain) {
        $sql = 'SELECT clientID FROM client WHERE emailLocal = :emailLocal AND emailDomain = :emailDomain LIMIT 1';
        $testQuery = $this->connection->prepare($sql);
        $params = array('emailLocal' => $emailLocal, 'emailDomain' => $emailDomain);
        return $this->requestList($testQuery, $params);
    }

    private function createUser($firstName, $lastName, $emailLocal, $emailDomain) {
        $sqlAdd = 'INSERT INTO client (firstName, lastName, emailLocal, emailDomain) VALUES (:fN, :lN, :eL, :eD)';
        $paramsNew = array('fN' => $firstName, 'lN' => $lastName, 'eL' => $emailLocal, 'eD' => $emailDomain);
        $this->request($sqlAdd, $paramsNew);

        $sqlGet = 'SELECT clientID FROM client WHERE emailLocal = :eL AND emailDomain = :eD LIMIT 1';
        $user = $this->connection->prepare($sqlGet);
        $paramsGet = array('eL' => $emailLocal, 'eD' => $emailDomain);

        return $this->request($paramsGet, $sqlGet)[1];//first hasn't result
    }

}