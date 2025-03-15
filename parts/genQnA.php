<?php
function bakeQnA() {
    $content = rawQnA();
    foreach($content as $row=>$qa) {
        echo '<div class="accordion">';
        echo '<div class="question">'.$qa["quest"].'</div>';
        echo '<div class="answer">'.$qa["answer"].'</div>';
        echo '</div>';
    }
}

function rawQnA(): array {
    $json = file_get_contents("data/qna.json");
    return json_decode($json, true);
}