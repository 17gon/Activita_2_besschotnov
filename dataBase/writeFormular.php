<?php
define('__ROOT__', dirname(__FILE__, 2));
require_once(__ROOT__."/classes/Kontakt.php");
use formular\Kontakt;

$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$email = $_POST['email'];
$text = $_POST['sprava'];

$kontakt = new Kontakt();
$userID = $kontakt->getUserOrCreate($firstName, $lastName, $email, $text);
if ($userID === "Error message") {
    http_response_code(400);
    exit("Invalid input.");
}
// userID is good
$kontakt->writeComment($userID, $text);
//i think next code was useless so i DoNotRepeat