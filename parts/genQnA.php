<?php
define('__ROOT__', dirname(__FILE__, 2));
include_once("classes/QnA.php");
use qna\QnA;

function bakeQnA() {
    //includeData();
    $content = rawQnA();
    foreach($content as $row=>$qa) {
        echo '<div class="accordion">';
        echo '<div class="question">'.$qa["question"].'</div>';
        echo '<div class="answer">'.$qa["answer"].'</div>';
        echo '</div>';
    }
}

function rawQnA(): array {
    $qnA = new QnA();
    return $qnA->getQuestionAnswer();
}

function includeData() {
    $qnA = new QnA();
    $qnA->insertAllQnA();
}