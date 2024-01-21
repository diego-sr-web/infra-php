<?php
$titulo = $data["titulo"] ?? "";
$campo = $data["campo"] ?? NULL;
$valor = $data["valor"] ?? "";
$placeholder = $data["placeholder"] ?? "";
if (!isset($campo)) {
    die("ERROR:".__FILE__);
}
?>
<div class="form-group">
    <label><?= $titulo ?></label>
    <input class="form-control" name="<?= $campo ?>" type="password" value="<?= $valor ?>" placeholder="<?= $placeholder; ?>"/>
</div>
