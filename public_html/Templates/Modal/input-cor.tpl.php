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
    <div class="input-group input-<?=$campo?>">
        <input class="form-control" name="<?=$campo?>" type="color" value="<?=$valor?>"/>
        <span class="input-group-addon"><i></i></span>
    </div>
</div>

