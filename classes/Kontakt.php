<?php
namespace formular;
require_once('../dataBase/config.php');
use PDO;

class Kontakt{

    private $conn;

    public function __construct() {
        $this->connect();
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
        $sql = 'INSERT INTO comments(clientID, date, text) VALUES (?, NOW(), ?)';
        $statement = $this->conn->prepare($sql);

        try {
            print("is it statement?");//bug(
            $statement->execute([$userID, $text]);
            http_response_code(200);
            print("or header?");
            header("Location: http://localhost/sablona/thankyou.php");
            exit(); // important!
        } catch (\Exception $exception) {
            http_response_code(404);
            die('Chyba pri odosielaní správy do databázy!');
        }
    }

    public function __destruct() {
        $this->conn = null;
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
        $sql = 'SELECT clientID FROM client WHERE emailLocal = ? AND emailDomain = ? LIMIT 1';
        $testQuery = $this->conn->prepare($sql);
        try {
            $testResult = $testQuery->execute([$emailLocal, $emailDomain]);
            if (!$testResult) {return null;}
            return $testQuery->fetch(PDO::FETCH_ASSOC);
        } catch (\Exception $exception) {
            return null;
        }
    }

    private function createUser($firstName, $lastName, $emailLocal, $emailDomain) {
        $sqlAdd = 'INSERT INTO client (firstName, lastName, emailLocal, emailDomain) VALUES (?, ?, ?, ?)';
        $sqlGet = 'SELECT clientID FROM client WHERE emailLocal = ? LIMIT 1';
        $newUser = $this->conn->prepare($sqlAdd);
        $user = $this->conn->prepare($sqlGet);
        try {
            $newUser->execute([$firstName, $lastName, $emailLocal, $emailDomain]);
            $user->execute([$emailLocal, $emailDomain]);
            return $user->fetch();
        } catch (\Exception $exception) {
            return null;
        }
    }

    private function connect() {
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