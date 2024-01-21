<?php
$campo = $data['campo'] ?? "";
$labelForm = $data['labelForm'] ?? "";
$labelCheck = $data['labelCheck'] ?? "";
$value = $data['value'] ?? "";
$indiceArray = $data['indice'] ?? "";
$listaArray = $data['array'] ?? "";

if (!isset($campo)) {
    die("ERROR:" . __FILE__);
}
?>
<style>
    .group-areas label {
        display: block;
    }

    .label-areas {
        font-size: 1rem;
        margin-top: 20px;
        margin-bottom: 20px;
    }
</style>

<div class="form-group group-areas">
    <label class="label-areas"> <?= $labelForm; ?></label>
    <?php if (!empty($listaArray)) {
        foreach ($listaArray as $array) { ?>
            <label>
                <input type="checkbox" name="<?= sprintf('%s[]', $campo); ?>" value="<?= $array[$value] ?>"
                    <?= isset($array['isChecked']) ? "checked" : "";  ?>/>
                <span><?= $array[$labelCheck] ?></span>
            </label>
        <?php } ?>
    <?php } ?>
</div>
