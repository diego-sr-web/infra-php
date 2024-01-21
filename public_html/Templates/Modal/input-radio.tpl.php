<?php
$titulo = $data["titulo"] ?? NULL;
$nomeCampo = $data["campo"] ?? NULL;
$valorCampo = $data["conteudo"] ?? NULL;
if(!isset($nomeCampo, $valorCampo) || !is_array($valorCampo)) {
    die("ERROR:".__FILE__);
}
?>
<div class="input-field col s12">
    <label class="label-static"><?= $titulo; ?></label>
    <?php
    foreach ($valorCampo as $item) {
        $checked = $item["checked"] ?? ""
    ?>
        <p>
            <label>
                <input class="with-gap"  name="<?= $nomeCampo;?>" type="radio" value="<?= $item["valor"]; ?>" <?= $checked; ?> />
                <span><?= $item["valor"]; ?></span>
            </label>
        </p>
    <?php
    }
    ?>
</div>
