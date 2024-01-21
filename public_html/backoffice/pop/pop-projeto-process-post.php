<?php
$url = basename($_SERVER["PHP_SELF"]);
$queryStr = $_SERVER["QUERY_STRING"];

if (isset($_POST['form'])) {
    $dtAtual = date('Y-m-d H:i:s');
    $redirect = ['redireciona' => FALSE, 'url' => 'pop-projetos.php', 'queryString' => '',];
    switch ($_POST['form']) {
        case 'editar-projeto':
            break;

        case 'arquivar-projeto':
            $idProjeto = $_POST['projeto'];
            if ($_POST['projeto']) {
                $retorno = $POPProjeto->arquivaProjeto($idProjeto, TRUE);
                if ($retorno) {
                    $redirect['redireciona'] = TRUE;
                    $redirect['url'] = 'pop-projetos.php';
                    $_SESSION["msg_sucesso"] = "Projeto arquivado com sucesso.";
                }
                else {
                    $redirect['redireciona'] = TRUE;
                    $redirect['url'] = 'pop-projeto.php?projeto=' . $idProjeto;
                    $_SESSION["msg_sucesso"] = "Houve algum erro ao arquivar o projeto.";
                }

            }
            else {
                $redirect['redireciona'] = TRUE;
                $redirect['url'] = 'pop-projeto.php?projeto=' . $idProjeto;
                $_SESSION["msg_sucesso"] = "Preencha todos os campos obrigatórios.";
            }
            break;
    }

    if ($redirect['redireciona']) {
        Utils::redirect( $redirect['url'] . '?' . $redirect['queryString']);
    }
}
