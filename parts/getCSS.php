<?php
function getCSS(){
    $json = file_get_contents("data/cssBind.json");
    $data = json_decode($json, true);
    $page = pathinfo($_SERVER['REQUEST_URI'], PATHINFO_FILENAME);
    $arrayCSS = $data['pages'][$page];
    foreach ($arrayCSS as $arr) {
        echo "<link rel='stylesheet' href='$arr'>";
    }
}
