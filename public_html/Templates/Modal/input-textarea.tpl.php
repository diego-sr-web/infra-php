<?php
$titulo = $data["titulo"] ?? "";
$campo = $data["campo"] ?? NULL;
$valor = $data["valor"] ?? "";
if (!isset($campo)) {
    die("ERROR:".__FILE__);
}
?>
<div class="form-group">
    <label><?= $titulo ?></label>
    <textarea class="form-control" name="<?= $campo ?>"><?= $valor ?></textarea>
</div>