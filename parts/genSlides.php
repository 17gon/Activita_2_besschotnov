<?php
function generateSlides($dir)
{
    $files = glob($dir."/*.jpg");
    $json = file_get_contents("data/banners.json");
    $data = json_decode($json, true);
    $text = $data["banner_txt"];

    foreach ($files as $file) {
        echo '<div class="slide fade">';
        echo '<img src="'.$file.'">';
        echo '<div class="slide-text">';
        echo $text[basename($file)];
        echo '</div>';
        echo '</div>';
    }
    echo '<a id="prev" class="prev">❮</a>';
    echo '<a id="next" class="next">❯</a>';
}