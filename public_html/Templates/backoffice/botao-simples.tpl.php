<?php
$cor = $data["cor"] ?? "";
$style = $data["style"] ?? "";
$acao = $data["acao"] ?? NULL;
$id = $data["id"] ?? "";
$icone = $data["icone"] ?? NULL;
$nomeIcone = $data["nomeIcone"] ?? NULL;
$textoBotao = $data["textoBotao"] ?? NULL;
$secao = $data["secao"] ?? NULL;
if (!isset($acao, $secao, $icone)) {
    die("ERRO AO GERAR O BOTAO");
}
?>
<button class="waves-effect waves-light btn js-modal blue darken-3"
        data-toggle="modal"
        data-target="#modal-backoffice"
        data-secao="<?= $secao ?>"
        data-acao="<?= $acao ?>"
        data-id="<?= $id ?>"
        style="margin-left: 5px;<?= $style ?>">
    <i class="<?= $icone ?>"><?= $nomeIcone ?></i>
    <?= $textoBotao; ?>
</button>
