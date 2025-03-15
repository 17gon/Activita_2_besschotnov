<?php
function bakePortfolio() {
    $portfolio = rawPortfolio();
    $nRows = 2;$nCols = 4;

    for ($i = 0; $i < $nRows; $i++) {
        echo "<div class='row'>";
        for ($j = 1; $j <= $nCols; $j++) {
            $count = $i * $nRows + $j;
            $data = $portfolio["portfolio".$count];
            $desc = $data["description"];
            $href = $data["href"];

            $img ='style="background-image: url(img/portfolio/portfolio'.$count.'.jpg);" ';
            $link = 'onclick="location.href=\''.$href.'\'"';
            $disc = '<div><p>'.$desc.'</p></div>';
            $part = '<div class="col-25 portfolio text-white text-center" '.$img.$link.'>'.$disc.'</div>';

            echo $part;
        }
        echo "</div>";
    }
}

function rawPortfolio(): array {
    $json = file_get_contents("data/imgInfo.json");
    return json_decode($json, true)["portfolio"];
}