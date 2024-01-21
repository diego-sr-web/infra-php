<?php
if (isset($_POST['form'])) {
    $dtAtual = date('Y-m-d H:i:s');
    switch ($_POST['form']) {
        case 'nova-categoria':
            $dados_categoria = ['nome' => $_POST['nome'], 'icone' => 'fa-circle', 'cor' => $_POST['cor'], 'descricao' => $_POST['descricao']];
            $database->ngInsertPrepared('pop_ElementoCategoria', $dados_categoria);
            $_SESSION["msg_sucesso"] = "Categoria criada com sucesso.";
            break;
    }
}
