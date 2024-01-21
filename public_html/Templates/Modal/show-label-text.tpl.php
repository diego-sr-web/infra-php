<?php
$conteudo = $data["conteudo"] ?? NULL;
$label = $data["titulo"] ?? NULL;
if (!isset($conteudo,$label)) {
    print_r($data);
    die("ERROR:".__FILE__);
}

?>
<dt><?= $label;?></dt>
<dd><?= $conteudo ?></dd>
