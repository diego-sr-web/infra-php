<?php
$nomeCampo = $data["campo"] ?? NULL;
$valorCampo = $data["valor"] ?? NULL;
if(!isset($nomeCampo, $valorCampo)) {
    die("ERROR:".__FILE__);
}
?><input name="<?= $nomeCampo?>" type="hidden" value="<?= $valorCampo ?>"/>
