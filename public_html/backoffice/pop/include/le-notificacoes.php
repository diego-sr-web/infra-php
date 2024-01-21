<?php
$titleNotif = "";
if (isset($_SESSION["NUM_TAREFAS"])) {
    $titleNotif = "(" . $_SESSION["NUM_TAREFAS"] . ")";
}
