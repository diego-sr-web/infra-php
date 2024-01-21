<?php
$titulo = $data["titulo"] ?? NULL;
$acao = $data["acao"] ?? NULL;
if (!isset($titulo,$acao)){
    print_r($data);
    die("ERROR:".__FILE__);
}
?>
<i class="material-icons btn-close modal-close right">close</i>
<div class="modal-content">
    <h4 class="modal-title"><?= $titulo?></h4>
<form action="" method="post">
    <input name="form" type="hidden" value="<?= $acao ?>"/>
    <div class="modal-body">
