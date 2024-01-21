<?php
$conteudo = $data["conteudo"] ?? NULL;
if (!isset($conteudo)) {
    print_r($data);
    die("ERROR:".__FILE__);
}
?>
<div class="txt-center" style="padding: 20px;"><?= $conteudo ?></div>
