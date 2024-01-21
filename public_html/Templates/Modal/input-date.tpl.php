<?php
$titulo = $data["titulo"] ?? "";
$campo = $data["campo"] ?? NULL;
$valor = $data["valor"] ?? "now";
$label = $data["label"] ?? "";

 if (!isset($campo)) {
    die("ERROR:".__FILE__);
}
?>
<div class="form-group">
    <label><?= $label; ?></label>
    <input type="text" class="datepicker" name="<?= $campo ?>" value="<?= Utils::printDate($valor); ?>">
</div>