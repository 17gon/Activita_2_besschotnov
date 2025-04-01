<?php

$json = file_get_contents("C:/xampp/htdocs/sablona/data/dataBaseCredent.json");
$data = json_decode($json, true);

$host = $data['host'];
$port = $data['port'];
$user = $data['user'];
$pass = $data['pass'];
$dbname = $data['dbname'];

$options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
);

try {
    $conn = new PDO("mysql:host=".$host.";dbname=".$dbname.";port=".$port, $user, $pass, $options);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$firstName = $_GET['firstName'];
$lastName = $_GET['lastName'];
$email = $_GET['email'];
$text = $_GET['sprava'];

if ($firstName == "" || $email == "" || $text == "") { die("Error message"); }//not empty
if (strlen($firstName)>25 || strlen($lastName)>25) { die("Error message"); }//not too long

$emailParts = explode('@', $email);
$emailLocal = $emailParts[0];
$emailDomain = $emailParts[1];
if ($emailLocal == "" || strlen($emailLocal)>63) { die("Error message"); }//not an "@anything" and not impossible length
if ($emailDomain == "" || strlen($emailDomain)>255) { die("Error message"); }//same what before but for domain part

$testQuery = $conn->prepare(
    'SELECT clientID FROM client WHERE emailLocal = ? AND emailDomain = ? LIMIT 1'
);
$testResult = $testQuery->execute([$emailLocal, $emailDomain]);
if (!$testResult) {die("Error message");}

$result = $testQuery->fetch(PDO::FETCH_ASSOC);
if (!$result) {
    $newUser = $conn->prepare(
        'INSERT INTO client (firstName, lastName, emailLocal, emailDomain) 
     VALUES (?, ?, ?, ?)'
    );
    $newUser->execute([$firstName, $lastName, $emailLocal, $emailDomain]);
    $user = $conn->prepare("SELECT clientID FROM client WHERE emailLocal = ? AND emailDomain = ? LIMIT 1");
    $user->execute([$emailLocal, $emailDomain]);
    $result = $user->fetch();
}
$userID = $result["clientID"];
$setQuery = $conn->prepare(
    'INSERT INTO comments(clientID, date, text) VALUES (?, NOW(), ?)'
);
$setQuery->execute([$userID, $text]);

header("Location: http://localhost/sablona/thankyou.php");
$conn = null;
?>