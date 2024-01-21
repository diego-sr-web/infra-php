<?php
require_once __DIR__ . "/../../autoloader.php";

$database = new Database();
$usuario = new AdmUsuario($database);
$POPProjeto = new Projeto($database);
$POPElemento = new Elemento($database);
$POPChat = new Chat($database);
$POPAlerta = new Alerta($database);
$BNCliente = new BNCliente($database);

require_once __DIR__ . '/../includes/is_logged.php';
require_once __DIR__ . '/include/le-notificacoes.php';
require_once __DIR__ . "/pop-projeto-process-post.php";

$infoProjeto = $POPProjeto->getProjectById($_GET['projeto']);
if (!isset($infoProjeto["projeto"])) {
    Utils::redirect("/backoffice/pop/pop-minhas-tarefas.php");
}

$infoTipoProjeto = $POPProjeto->getProjectTypeById($infoProjeto['projetoTipo']);
$cliente = $BNCliente->getDataWithId($infoProjeto['cliente']);
$alertasProjeto = $POPAlerta->getAlertasProjeto($infoProjeto['projeto']);
if (count($alertasProjeto) > 0) {
    $alertasProjeto = [$alertasProjeto[count($alertasProjeto) - 1]];
}
// Hack pra poder desenvolver a nova pagina de projetos, escapa do redirect convencional
if (!isset($skipRedirectDev)) {
    if (!isset($identifier)) {
        $identifier = "";
    }

    if (isset($infoTipoProjeto["identifier"])) {
        $projIdentifier = $infoTipoProjeto["identifier"];
        $query_string = '';

        if (isset($_GET['tarefas']) && $_GET['tarefas'] == TRUE) {
            $query_string .= '&tarefas=' . $_GET['tarefas'];
        }
        if (isset($_GET['trf']) && $_GET['trf'] == TRUE) {
            $query_string .= '&trf=' . $_GET['trf'];
        }
        $urlParams = "?projeto=" . $_GET["projeto"] . $query_string;
        $scriptName = basename($_SERVER["SCRIPT_NAME"]);

        if ($projIdentifier === "POP_PROJETO_CAMPANHA_FACEBOOK" && $scriptName !== "pop-projeto-facebook.php") {
            Utils::redirect('pop-projeto-facebook.php' . $urlParams);
        }
        elseif ($projIdentifier === "POP_PROJETO_CRIACAO_DE_WEBSITES" && $scriptName !== "pop-projeto-website.php") {
            Utils::redirect('pop-projeto-website.php' . $urlParams);
        }
        elseif ($projIdentifier === "POP_PROJETO_FLUXO_DE_MEDIA" && $scriptName !== "pop-projeto-media.php") {
            Utils::redirect('pop-projeto-media.php' . $urlParams);
        }
        elseif ($projIdentifier === "EMAIL_MARKETING" && $scriptName !== "pop-projeto-mkt.php") {
            Utils::redirect( 'pop-projeto-mkt.php' . $urlParams);
        }
        elseif ($projIdentifier === "POP_PROJETO_EMAIL_MARKETING_V2" && $scriptName !== "pop-projeto-mkt-v2.php") {
            Utils::redirect('pop-projeto-mkt-v2.php' . $urlParams);
        }
        elseif ($scriptName !== "pop-projeto-mkt-v3.php" &&
                in_array($projIdentifier,["POP_PROJETO_EMAIL_MARKETING_V3",   "POP_PROJETO_EMAIL_MARKETING_V4.1", "POP_PROJETO_EMAIL_MARKETING_V4.2",
                                          "POP_PROJETO_EMAIL_MARKETING_V3.1", "POP_PROJETO_EMAIL_MARKETING_V3.2"] )) {
            Utils::redirect('pop-projeto-mkt-v3.php' . $urlParams);
        }
        elseif ($projIdentifier === "POP_PROJETO_MATERIAL_GRAFICO" && $scriptName !== "pop-projeto-grafico.php") {
            Utils::redirect('pop-projeto-grafico.php' . $urlParams);
        }
        elseif ($projIdentifier === "POP_PROJETO_MATERIAL_GRAFICO_V2" && $scriptName !== "pop-projeto-grafico-v3.php") {
            Utils::redirect('pop-projeto-grafico-v3.php' . $urlParams);
        }
        elseif ($projIdentifier === "POP_PROJETO_PRODUCAO_BLOG" && $scriptName !== "pop-projeto-blog.php") {
            Utils::redirect('pop-projeto-blog.php' . $urlParams);
        }
        elseif ($projIdentifier === "POP_PROJETO_SOCIAL_MEDIA_v3" && $scriptName !== "pop-projeto-social.php") {
            Utils::redirect('pop-projeto-social.php' . $urlParams);
        }
        elseif ($projIdentifier === "POP_PROJETO_FLUXO_DE_MIDIA_V3" && $scriptName !== "pop-projeto-media-v3.php") {
            Utils::redirect('pop-projeto-media-v3.php' . $urlParams);
        }

    }
}

$elementosProjeto = $POPElemento->getAllElements(['projeto'], [$infoProjeto['projeto']]);
$elementosProjeto_2 = $elementosProjeto;
$itensProjeto = $tarefasProjeto = [];

$esconde_Botoes = TRUE;
if (isset($_GET["debug"])) {
    $esconde_Botoes = FALSE;
}


if ((isset($_GET["tarefas"]) && $_GET["tarefas"] == TRUE) || (isset($_GET["trf"]) && $_GET["trf"])) {
    if (isset($_GET["tarefas"])) {
        $esconde_Botoes = FALSE;
    }

    foreach ($elementosProjeto as $key => $value) {
        $limpa = TRUE;

        if ((isset($_GET["tarefas"], $value["elementoStatus"]) && $value["elementoStatus"] == 3) || $value["elementoStatus"] == 4) {
            if (isset($value["campos"]["responsavel"]) && $value["campos"]["responsavel"] == $_SESSION["adm_usuario"]) {
                $limpa = FALSE;
            }
        }
        elseif (isset($_GET["trf"])) {
            $aux_el = $POPElemento->getElementById($_GET["trf"]);
            if ($value['elementoTipo'] == $aux_el['elementoTipo'] && $value['elementoStatus'] < 8 && $aux_el['campos']['area'] == $value['campos']['area']) {
                $limpa = FALSE;
            }
        }

        if ($limpa) {
            unset($elementosProjeto[$key]);
        }
    }
    if ($elementosProjeto === []) {
        $_SESSION["msg_sucesso"] = "Todas as Tarefas pendentes foram concluidas, escolha novas tarefas";
        Utils::redirect("pop-minhas-tarefas.php");
    }
}
