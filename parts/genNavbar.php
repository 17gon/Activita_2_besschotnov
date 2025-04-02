<?php
function generateNavbar(): string {
    $rawMenu = generateMenu("navbar");
    $formedMenu = selectN(3, $rawMenu);
    $bakedMenu = "";
    $theme = $_GET["theme"];
    $insert = $theme === 'dark' ? '?theme=dark' : '?theme=white';

    foreach ($formedMenu as $menu => $menuData) {
        $bakedMenu .= '<li><a href="'.$menuData['path'].$insert.'">'.$menuData['name'].'</a></li>';
    }
    return $bakedMenu;
}

function selectN(int $n, array $menus): array {
    $ready = array();
    $dont = basename($_SERVER['PHP_SELF']);//I do not want to see a link to it's self
    if (count($menus) < $n) {$n = count($menus);}
    $count = 0;
    foreach ($menus as $key => $menu) {
        if ($count >= $n) break;
        if ($menu['path'] != $dont) {
            $ready[] = $menu;
            $count++;
        }
    }
    return $ready;
}

function generateMenu(string $type): array {
    $menu = array();
    if($type === "navbar") {
        $json = file_get_contents("data/navbar.json");
        $menu = json_decode($json, true);
    }
    return $menu;
}
