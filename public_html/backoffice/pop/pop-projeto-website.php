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
    Utils::redirect("pop-minhas-tarefas.php");
}

$infoTipoProjeto = $POPProjeto->getProjectTypeById($infoProjeto['projetoTipo']);
$cliente = $BNCliente->getDataWithId($infoProjeto['cliente']);
$alertasProjeto = $POPAlerta->getAlertasProjeto($infoProjeto['projeto']);

if (isset($infoTipoProjeto['identifier']) && strpos($infoTipoProjeto['identifier'], 'WEBSITE') === FALSE) {
    Utils::redirect('pop-projeto.php?projeto=' . $_GET["projeto"]);
}
$elementosProjeto = $POPElemento->getAllElements(['projeto'], [$infoProjeto['projeto']]);
$itensProjeto = $tarefasProjeto = [];

$auxTarefas = $POPElemento->filtraElementosPorBase($elementosProjeto, 1);

foreach ($auxTarefas as $tmp) {
    if ($tmp['campos']['dtFim'] == NULL) {
        $tarefasProjeto[] = $tmp;
    }
}

foreach ($elementosProjeto as $aux) {
    $tipoElem = $POPElemento->getElementTypeByElementId($aux['elemento']);
}

$telaTarefas = FALSE;
$fazerTarefa = FALSE;
if ((isset($_GET['tarefa']) && $_GET['tarefa'])) {
    $fazerTarefa = TRUE;
}
$briefing = [];
$identidadeVisual = [];
$estrutura = [];
$paginas = [];
$home = [];
$dominio = [];
$botao_briefing = [];
$inserir_modulo = [];
$inserir_conteudo = [];
$recortar_layout = [];
$montar_html = [];
$coletar_conteudo = [];

$size = count($elementosProjeto);
$copiaElementosProjeto = $elementosProjeto;

$esconde_Botoes = TRUE;
if (isset($_GET["debug"])) {
    $esconde_Botoes = FALSE;
}

$montar_html_link = NULL;
$recortar_layout_link = NULL;
$id_visual_link = NULL;

if ((isset($_GET["tarefas"]) && $_GET["tarefas"] == TRUE) || (isset($_GET["trf"]) && $_GET["trf"])) {
    if (isset($_GET["tarefas"])) {
        $esconde_Botoes = FALSE;
    }

    foreach ($elementosProjeto as $key => $value) {
        if (isset($value["elementoTipo"]) && $value["elementoTipo"] == 61) {
            $botao_briefing[] = $value;
        }
        if (isset($value["elementoTipo"]) && $value["elementoTipo"] == 68) {
            $botao_conteudo[] = $value;
        }
        if (isset($value["elementoTipo"]) && $value["elementoTipo"] == 73) {
            $botao_modulos[] = $value;
        }

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

        if (isset($value["elementoTipo"]) && $value["elementoTipo"] == 69) {
            $recortar_layout_link = $value["campos"]["link"];
        }
        if (isset($value["elementoTipo"]) && $value["elementoTipo"] == 70) {
            $montar_html_link = $value["campos"]["link_2"];
        }
        if (isset($value["elementoTipo"]) && $value["elementoTipo"] == 62) {
            $id_visual_link = $value["campos"]["arquivos"];
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

if (!isset($botao_conteudo[0])) {
    foreach ($elementosProjeto as $key => $value) {
        if (isset($value["elementoTipo"]) && $value["elementoTipo"] == 68) {
            $botao_conteudo[] = $value;
        }
        if (isset($value["elementoTipo"]) && $value["elementoTipo"] == 73) {
            $botao_modulos[] = $value;
        }
    }
}

for ($i = 0; $i < $size; $i++) {
    $unset = FALSE;
    $tipo = "";
    if (isset($elementosProjeto[$i]["elementoTipo"])) {
        $tipo = $elementosProjeto[$i]["elementoTipo"];
    }
    if ($tipo == 61) {
        $briefing[] = $elementosProjeto[$i];
        $unset = TRUE;
    }
    if ($tipo == 62) {
        $identidadeVisual[] = $elementosProjeto[$i];
        $id_visual_link = $identidadeVisual[0]["campos"]["arquivos"];
        $unset = TRUE;
    }
    if ($tipo == 64) {
        $estrutura[] = $elementosProjeto[$i];
        $unset = TRUE;
    }

    if ($tipo == 67 || $tipo == 72 || $tipo == 81) {
        $paginas[] = $elementosProjeto[$i];

        $unset = TRUE;
    }

    if ($tipo == 63 || $tipo == 65) {
        $dominio[] = $elementosProjeto[$i];
        $unset = TRUE;
    }

    if ($tipo == 73 || $tipo == 75) {
        $inserir_modulo[] = $elementosProjeto[$i];
        $unset = TRUE;
    }

    if ($tipo == 68 || $tipo == 80 || $tipo == 76) {
        $inserir_conteudo[] = $elementosProjeto[$i];
        $unset = TRUE;
    }

    if ($tipo == 69) {
        $recortar_layout[] = $elementosProjeto[$i];
        $recortar_layout_link = $elementosProjeto[$i]["campos"]["link"];
        $unset = TRUE;
    }

    if ($tipo == 70) {
        $montar_html[] = $elementosProjeto[$i];
        $montar_html_link = $elementosProjeto[$i]["campos"]["link_2"];
        $unset = TRUE;
    }

    if ($unset) {
        unset($elementosProjeto[$i]);
    }
}

if (isset($montar_html[0])) {
    if ($montar_html[0]["campos"]["link"] == NULL) {
        $montar_html[0]["campos"]["link"] = $recortar_layout_link;
    }
}

foreach ($elementosProjeto as $key => $value) {
    if ($value["elementoTipo"] == 74 && $value["campos"]["url"] == NULL) {
        $elementosProjeto[$key]["campos"]["url"] = $montar_html_link;
    }
}

foreach ($paginas as $key => $value) {
    if ($paginas[$key]["campos"]["link_2"] == NULL) {
        $paginas[$key]["campos"]["link_2"] = $id_visual_link;
    }
}

if (!isset($botao_briefing[0])) {
    $botao_briefing = $briefing;
}
// APAGANDO TUDO Q NAO EH BRIEFING NEM PAGINA
//$elementosProjeto = [];

// Processamento dos botoes de adicionar/remover/aprovar/reprovar
require_once __DIR__ . "/pop-processa-modal.php";

$chat = new Chat($database);


/**
 * Limpa os campos extras do elemento, precisa achar um lugar melhor pra deixar essa funcao
 *
 * @param array $tipo
 * @param array $extra
 *
 * @return array
 */
function limpaExtras($tipo, $extra) {
    $sub_TMP = [];
    $sub_I = [];
    $nomesCampos = $tipo['nomesCampos'];
    // Limpa o conteudo que estao contidos nos tipos de elemento e
    // nos elementos base, separa os tipos extras ( imgs e etc que sao adicionais, de
    // kda instancia de elemento )
    foreach ($tipo['campos'] as $chave => $valor) {
        $tmp_subelemento['nome'] = $chave;
        $tmp_subelemento['tipo'] = $valor;
        $tmp_subelemento['valor'] = $extra[$chave];
        switch ($tmp_subelemento['nome']) {
            case "prazo":
            case "dtFim":
            case "dtInicio":
                break;
            case "Etapa":
            case "area":
            case "Dia":
                switch ($tmp_subelemento["nome"]) {
                    case "Etapa":
                        $sub_I[0] = $tmp_subelemento;
                        break;
                    case "area":
                        $sub_I[1] = $tmp_subelemento;
                        break;
                    case "Dia":
                        $sub_I[3] = $tmp_subelemento;
                        break;
                }
                break;
            default:
                $sub_TMP[] = $tmp_subelemento;
                break;
        }
        unset($extra[$chave]);
    }

    $subE = $sub_I;
    ksort($sub_TMP);
    foreach ($sub_TMP as $tmp) {
        $subE[] = $tmp;
    }
    $retorno[0] = $subE;
    $retorno[1] = $nomesCampos;
    return $retorno;
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $titleNotif; ?>Projeto <?php echo $infoProjeto['nome']; ?> | POP Nerdweb</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="../css/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css"/>
    <link href="../css/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>
    <link href="../css/trumbowyg/trumbowyg.min.css" rel="stylesheet" type="text/css"/>
    <link href="../js/plugins/light-gallery/css/lightgallery.css" rel="stylesheet" type="text/css"/>
    <link href="../css/ionicons.css" rel="stylesheet" type="text/css"/>
    <link href="../css/AdminLTE.css" rel="stylesheet" type="text/css"/>
    <link href="../css/pop.css" rel="stylesheet" type="text/css"/>
</head>
<body class="skin-black <?php echo (!$telaTarefas) ? 'fixed' : ''; ?>">
<?php if (!$telaTarefas) { ?>
    <?php require_once __DIR__ . '/include/head.php'; ?>
<?php } ?>

<div id="top-of-page" class="wrapper row-offcanvas row-offcanvas-left">
    <!-- Left side column. contains the logo and sidebar -->
    <?php if (!$telaTarefas) { ?>
        <aside class="left-side sidebar-offcanvas">
            <?php require_once __DIR__ . '/include/sidebar.php'; ?>
        </aside>
    <?php } ?>

    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side <?php echo (!$telaTarefas) ? '' : 'strech'; ?>">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Projeto <?php echo $infoProjeto['nome']; ?></h1>

            <ol class="breadcrumb">
                <?php if (FALSE) { ?>
                    <li><a href="pop-projetos.php"><i class="fa fa-folder-open"></i> Projetos</a></li>
                    <li class="active">Projeto <?php echo $infoProjeto['nome']; ?></li>
                <?php } else { ?>
                    <li><a href="pop-minhas-tarefas.php"><i class="fa fa-folder-open"></i> Minhas Tarefas</a></li>
                    <!--<li class="active">Projeto <?php echo $infoProjeto['nome']; ?></li>-->
                <?php } ?>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <?php
                require_once __DIR__ . "/include/alertas-projeto.php";
                require_once __DIR__ . "/include/sucesso_error.php";
            ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-solid">
                        <div class="box-header">
                            <i class="fa fa-cubes"></i>
                            <h3 class="box-title">Dados do Projeto</h3>

                            <?php if ($infoProjeto['finalizado'] != 1) { ?>
                                <!--
                                <a class="btn btn-flat btn-addnew btn-success border-0" href="pop-minhas-tarefas.php?finaliza_tarefa=">
                                    <i class="fa fa-check pull-left font-16"></i> Finalizar Tarefa
                                </a>
                                -->
                                <a class="btn btn-primary btn-flat btn-addnew border-0 js-getElemento"
                                   data-elemento="<?php echo $botao_briefing[0]["elemento"]; ?>"
                                   data-toggle="modal" data-backdrop="static" data-target="#modal-elemento"
                                   data-acao="adicionar-briefing" title="Briefing">
                                    <i class="fa fa-file-text pull-left"></i> Briefing
                                </a>
                                <a class="btn btn-primary btn-flat btn-addnew border-0 js-getElemento"
                                   data-elemento="<?php echo $botao_briefing[0]["elemento"]; ?>"
                                   data-toggle="modal" data-backdrop="static" data-target="#modal-elemento"
                                   data-acao="adicionar-material-extra" title="Adicionar Material Extra">
                                    <i class="fa fa-file-text pull-left"></i> Material Extra
                                </a>
                                <?php if (!isset($_GET["tarefas"])) { ?>
                                    <a class="btn btn-primary btn-flat btn-addnew border-0 js-getProjeto" href="#"
                                       data-projeto="<?php echo $infoProjeto['projeto']; ?>"
                                       data-target="#modal-projeto" data-acao="arquivar-projeto" data-toggle="modal"
                                       data-backdrop="static">
                                        <i class="fa fa-archive pull-left font-16"></i> Arquivar Projeto
                                    </a>
                                <?php } ?>
                                <a class="btn btn-primary btn-flat btn-addnew border-0 js-getProjeto" href="#"
                                   data-projeto="<?php echo $infoProjeto['projeto']; ?>"
                                   data-target="#modal-projeto" data-acao="editar-projeto" data-toggle="modal"
                                   data-backdrop="static">
                                    <i class="fa fa-pencil pull-left font-16"></i> Editar dados do Projeto
                                </a>
                            <?php } ?>
                        </div>

                        <?php
                        $dataEntrada = new date('dataEntrada', $infoProjeto['dataEntrada'], $database);
                        $prazo = new date('prazo', $infoProjeto['prazo'], $database);
                        ?>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-4">
                                    <dl class="dl-horizontal">
                                        <dt>Tipo de Projeto</dt>
                                        <dd class="dado-destaque"
                                            style="color: <?php echo $infoTipoProjeto['cor']; ?>"><?php echo $infoTipoProjeto['nome']; ?></dd>
                                        <br/>

                                        <dt>Cliente</dt>
                                        <dd><?php echo ($cliente) ? $cliente['nomeFantasia'] : 'Cliente não cadastrado'; ?></dd>
                                        <br/>

                                        <dt>Data de Entrada</dt>
                                        <dd><?php echo $dataEntrada->retornaHtmlExibicao(); ?></dd>
                                        <br/>

                                        <dt>Prazo Final</dt>
                                        <dd><?php echo $prazo->retornaHtmlExibicao(); ?></dd>
                                    </dl>
                                </div>
                                <div class="col-sm-7">
                                    <dl class="dl-horizontal">
                                        <dt>Etapas Em Andamento:</dt>
                                        <?php foreach ($tarefasProjeto as $tarefa) {
                                            // Tarefas q nao estao finalizadas
                                            $arrayStatus = [8, 9, 12, 13, 14, 16];
                                            if (!in_array($tarefa['elementoStatus'], $arrayStatus)) {
                                                if (isset($tarefa["campos"]["Etapa"])) {
                                                    $etapa_nome = $database->ngSelectPrepared("pop_ElementoTipoSubEtapa", ["etapa" => $tarefa["campos"]["Etapa"], "elementoTipo" => $tarefa["elementoTipo"]], "nome", "", 1);
                                                    $nome_tarefa = $etapa_nome["nome"];
                                                }
                                                $tipoTarefa = $POPElemento->getElementTypeById($tarefa['elementoTipo']);
                                                if ($tarefa["elementoTipo"] == 72) {
                                                    $nome_tarefa = $nome_tarefa . " - " . $tarefa["campos"]["Nome"];
                                                }
                                                else {
                                                    if (isset($tarefa["campos"]["Etapa"])) {
                                                        $etapa_nome = $database->ngSelectPrepared("pop_ElementoTipoSubEtapa", ["etapa" => $tarefa["campos"]["Etapa"], "elementoTipo" => $tarefa["elementoTipo"]], "nome", "", 1);
                                                        $nome_tarefa = $etapa_nome["nome"];
                                                    }
                                                    else {
                                                        $nome_tarefa = $tipoTarefa['nome'];
                                                    }
                                                }
                                                //var_dump($tarefa);

                                                ?>
                                                <dd class="dado-destaque">
                                                    <div class="row">
                                                        <div class="col-sm-3">
                                                            <?php $areaResponsavel = new area('area', $tarefa['campos']['area'], $database); ?>
                                                            <?php echo $areaResponsavel->retornaHtmlExibicao(); ?>
                                                        </div>
                                                        <div class="col-sm-7">
                                                            <?php echo $nome_tarefa; ?>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <?php
                                                            $responsavel = new responsavel('responsavel', $tarefa['campos']['responsavel'], $database);
                                                            echo $responsavel->retornaHtmlExibicao();
                                                            ?>
                                                        </div>

                                                    </div>
                                                </dd>
                                            <?php }
                                        } ?>
                                        <br/>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-solid">
                        <div class="box-header">
                            <i class="fa fa-th"></i>
                            <h3 class="box-title">Lista de Tarefas</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-12 filtros">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="acao-bts">
                                                <button class="btn btn-primary btn-flat btn-addnew border-0 js-getProjeto"
                                                        data-toggle="modal" data-backdrop="static"
                                                        data-projeto="<?php echo $infoProjeto['projeto']; ?>"
                                                        data-target="#modal-projeto" data-acao="nova-pagina-website"><i
                                                            class="fa fa-plus"></i> Adicionar Página Interna
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-sm-8">
                                        </div>
                                    </div>
                                </div><!-- /filtros -->
                            </div><!-- /row filtros -->
                            <div class="row">
                                <?php
                                //
                                // AQUI EH O BRIEFING E OUTRAS PARTE DE COLETA DE INFORMACAO
                                //
                                foreach ($briefing as $e) {
                                    $extra = $e["campos"];
                                    $status = $POPElemento->getElementStatusById($e["elementoStatus"]);
                                    $tipo = $POPElemento->getElementTypeById($e["elementoTipo"]);
                                    $ret = limpaExtras($tipo, $extra);
                                    $subE = $ret[0];
                                    $nomesCampos = $ret[1];
                                    ?>
                                    <div class="col-sm-12 item">
                                        <div class="item-box">
                                            <div class="row">
                                                <div class="col-lg-7 col-md-5 item-info-list">
                                                    <div class="row">
                                                        <div class="item-status stamp"
                                                             style="background-color:<?php echo $status["cor"]; ?>">
                                                            <i class="fa fa-clock-o"></i> <?php echo $status['nome']; ?>
                                                            <span style="float:right"><?php echo $e["elemento"]; ?></span>
                                                        </div>
                                                        <?php
                                                        $tipoTarefa = $POPElemento->getElementTypeById($e['elementoTipo']);
                                                        ?>
                                                        <div class="col-sm-4 item-info">
                                                            <div class="item-info-box">
                                                                <h4>Tarefa</h4>
                                                                <?php echo "<p>" . $tipoTarefa['nome'] . "</p>"; ?>
                                                            </div>
                                                        </div>
                                                        <?php
                                                        foreach ($subE as $field) {
                                                            if ($field["nome"] === "briefing" || $field["nome"] === "extras" || $field["nome"] === "arquivos") {
                                                                continue;
                                                            }
                                                            $nome = $nomesCampos[$field['nome']] ?? ($field["nome"] . ":404:");
                                                            ?>
                                                            <div class="col-sm-4 item-info">
                                                                <div class="item-info-box">
                                                                    <h4><?php echo $nome; ?></h4>
                                                                    <?php
                                                                    $class = $field['tipo'];
                                                                    if (class_exists($class)) {
                                                                        /** @var nomeTipo $var */
                                                                        $var = new $class($field['nome'], $field['valor'], $database, ["elemento" => $e["elemento"]]);
                                                                        echo $var->retornaHtmlExibicao();
                                                                    }
                                                                    else {
                                                                        echo "<p>Tipo " . $field['nome'] . " NAO Encontrado</p>";
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-4">
                                                    <div class="box-header">
                                                        <h4 class="box-title">Chat</h4>
                                                    </div>
                                                    <div class="box-body chat historico-post" id="chat-box">
                                                        <?php
                                                        $chatProjeto = $POPChat->getProjetoChat($e['projeto'], $e['elemento']);
                                                        $tamanhoChat = count($chatProjeto);
                                                        $tmpChat = [];
                                                        if ($tamanhoChat < 2) {
                                                            $tmpChat = $chatProjeto;
                                                        }
                                                        else {
                                                            $t = $tamanhoChat;
                                                            $tmpChat = [$chatProjeto[$t - 2], $chatProjeto[$t - 1]];
                                                        }

                                                        foreach ($tmpChat as $itemChat) {
                                                            $var = new responsavel('primeiro-msg', $itemChat['responsavel'], $database);
                                                            ?>
                                                            <div class="item">
                                                                <?php echo $var->retornaHtmlExibicao(); ?>
                                                                <p class="message">
                                                                    <a href="#" class="name">
                                                                        <small title="<?php echo date('d/m/Y - H:i:s', strtotime($itemChat['data'])); ?>"
                                                                               class="text-muted pull-right">
                                                                            <i class="fa fa-clock-o"></i> <?php echo Utils::printDate(date('Y-m-d H:i:s', strtotime($itemChat['data']))); ?>
                                                                        </small>
                                                                        <?php echo $var->retornaNomeExibicao(); ?>
                                                                    </a>
                                                                    <?php
                                                                    $conteudo = Utils::insert_href(nl2br(strip_tags($itemChat['conteudo'])));
                                                                    echo $conteudo; ?><br>
                                                                    <br>
                                                                </p>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="ver-historico">
                                                        <a href="#" class="dpb js-getElemento"
                                                           data-elemento="<?php echo $e['elemento']; ?>"
                                                           data-toggle="modal" data-backdrop="static"
                                                           data-target="#modal-elemento"
                                                           data-acao="ver-historico-projeto">Todas Mensagens</a>
                                                        <a href="#" class="dpb js-getElemento"
                                                           data-elemento="<?php echo $e['elemento']; ?>"
                                                           data-toggle="modal" data-backdrop="static"
                                                           data-target="#modal-elemento"
                                                           data-acao="ver-historico-elemento-completo">Ver log</a>
                                                    </div>

                                                    <form class="form-comentario" action="" method="post">
                                                        <input name="projeto" value="<?php echo $e['projeto']; ?>"
                                                               type="hidden">
                                                        <input name="elemento" value="<?php echo $e['elemento']; ?>"
                                                               type="hidden">

                                                        <div class="form-group">
                                                            <label>Comentar</label>
                                                            <textarea name="comentario"
                                                                      placeholder="Digite um comentário..." rows="3"
                                                                      class="form-control"></textarea>
                                                        </div>
                                                        <div id="msg-erro"></div>
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <button class="btn btn-primary btn-flat border-0 pull-right"
                                                                        type="submit">Enviar comentário
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="col-lg-2 col-md-3" <?php if ($esconde_Botoes) {
                                                    echo 'style="display: none;"';
                                                } ?>>
                                                    <div class="item-btns">
                                                        <button class="btn btn-block btn-flat btn-primary border-0 js-getElemento"
                                                                data-elemento="<?php echo $botao_briefing[0]["elemento"]; ?>"
                                                                data-toggle="modal" data-backdrop="static"
                                                                data-target="#modal-elemento"
                                                                data-acao="adicionar-briefing"
                                                                title="<?php echo $POPElemento->adicionarOuAlterar($botao_briefing[0], 'briefing', 'Briefing'); ?>">
                                                            <i class="fa fa-file-text pull-left"></i> <?php echo $POPElemento->adicionarOuAlterar($botao_briefing[0], 'briefing', 'Briefing	'); ?>
                                                        </button>
                                                        <?php if (in_array($extra['area'], AREAS_CONTATO_CLIENTE) && $e['elementoStatus'] != 3) { ?>
                                                            <button class="btn btn-flat btn-block btn-cliente border-0 js-getElemento"
                                                                    data-elemento="<?php echo $e['elemento']; ?>"
                                                                    data-toggle="modal" data-backdrop="static"
                                                                    data-acao="aguardar-cliente"
                                                                    data-target="#modal-elemento">
                                                                <i class="fa fa-clock-o pull-left"></i> Enviado/Aguardar
                                                                Cliente
                                                            </button>
                                                        <?php } ?>
                                                        <button class="btn btn-block btn-flat btn-success border-0 js-getElemento"
                                                                data-elemento="<?php echo $e["elemento"]; ?>"
                                                                data-toggle="modal" data-backdrop="static"
                                                                data-target="#modal-elemento"
                                                                data-acao="aprovar-elemento" title="Finalizar Tarefa">
                                                            <i class="fa fa-thumbs-up pull-left"></i> Finalizar Tarefa
                                                        </button>
                                                        <button class="btn btn-block btn-flat btn-danger border-0 js-getElemento"
                                                                data-elemento="<?php echo $e["elemento"]; ?>"
                                                                data-toggle="modal" data-backdrop="static"
                                                                data-target="#modal-elemento"
                                                                data-acao="reprovar-elemento" title="Reprovar Tarefa">
                                                            <i class="fa fa-thumbs-down pull-left"></i> Reprovar Tarefa
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- /item -->
                                <?php }


                                foreach ($identidadeVisual as $e) {
                                    $extra = $e["campos"];
                                    $status = $POPElemento->getElementStatusById($e["elementoStatus"]);
                                    $tipo = $POPElemento->getElementTypeById($e["elementoTipo"]);
                                    $ret = limpaExtras($tipo, $extra);
                                    $subE = $ret[0];
                                    $nomesCampos = $ret[1];
                                    ?>
                                    <div class="col-sm-12 item">
                                        <div class="item-box">
                                            <div class="row">
                                                <div class="col-lg-7 col-md-5 item-info-list">
                                                    <div class="row">
                                                        <div class="item-status stamp"
                                                             style="background-color:<?php echo $status["cor"]; ?>">
                                                            <i class="fa fa-clock-o"></i> <?php echo $status['nome']; ?>
                                                            <span style="float:right"><?php echo $e["elemento"]; ?></span>
                                                        </div>
                                                        <?php
                                                        $tipoTarefa = $POPElemento->getElementTypeById($e['elementoTipo']);
                                                        ?>
                                                        <div class="col-sm-4 item-info">
                                                            <div class="item-info-box">
                                                                <h4>Tarefa</h4>
                                                                <?php echo "<p>" . $tipoTarefa['nome'] . "</p>"; ?>
                                                            </div>
                                                        </div>
                                                        <?php
                                                        foreach ($subE as $field) {
                                                            $nome = $nomesCampos[$field['nome']] ?? ($field["nome"] . ":404:");
                                                            ?>
                                                            <div class="col-sm-4 item-info">
                                                                <div class="item-info-box">
                                                                    <h4><?php echo $nome; ?></h4>
                                                                    <?php
                                                                    $class = $field['tipo'];
                                                                    if (class_exists($class)) {
                                                                        /** @var nomeTipo $var */
                                                                        $var = new $class($field['nome'], $field['valor'], $database, ["elemento" => $e["elemento"]]);
                                                                        echo $var->retornaHtmlExibicao();
                                                                    }
                                                                    else {
                                                                        echo "<p>Tipo " . $field['nome'] . " NAO Encontrado</p>";
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-4">
                                                    <div class="box-header">
                                                        <h4 class="box-title">Chat</h4>
                                                    </div>
                                                    <div class="box-body chat historico-post" id="chat-box">
                                                        <?php
                                                        $chatProjeto = $POPChat->getProjetoChat($e['projeto'], $e['elemento']);
                                                        $tamanhoChat = count($chatProjeto);
                                                        $tmpChat = [];
                                                        if ($tamanhoChat < 2) {
                                                            $tmpChat = $chatProjeto;
                                                        }
                                                        else {
                                                            $t = $tamanhoChat;
                                                            $tmpChat = [$chatProjeto[$t - 2], $chatProjeto[$t - 1]];
                                                        }

                                                        foreach ($tmpChat as $itemChat) {
                                                            $var = new responsavel('primeiro-msg', $itemChat['responsavel'], $database);
                                                            ?>
                                                            <div class="item">
                                                                <?php echo $var->retornaHtmlExibicao(); ?>
                                                                <p class="message">
                                                                    <a href="#" class="name">
                                                                        <small title="<?php echo date('d/m/Y - H:i:s', strtotime($itemChat['data'])); ?>"
                                                                               class="text-muted pull-right">
                                                                            <i class="fa fa-clock-o"></i> <?php echo Utils::printDate(date('Y-m-d H:i:s', strtotime($itemChat['data']))); ?>
                                                                        </small>
                                                                        <?php echo $var->retornaNomeExibicao(); ?>
                                                                    </a>
                                                                    <?php
                                                                    $conteudo = Utils::insert_href(nl2br(strip_tags($itemChat['conteudo'])));
                                                                    echo $conteudo; ?><br>
                                                                    <br>
                                                                </p>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="ver-historico">
                                                        <a href="#" class="dpb js-getElemento"
                                                           data-elemento="<?php echo $e['elemento']; ?>"
                                                           data-toggle="modal" data-backdrop="static"
                                                           data-target="#modal-elemento"
                                                           data-acao="ver-historico-projeto">Todas Mensagens</a>
                                                        <a href="#" class="dpb js-getElemento"
                                                           data-elemento="<?php echo $e['elemento']; ?>"
                                                           data-toggle="modal" data-backdrop="static"
                                                           data-target="#modal-elemento"
                                                           data-acao="ver-historico-elemento-completo">Ver log</a>
                                                    </div>

                                                    <form class="form-comentario" action="" method="post">
                                                        <input name="projeto" value="<?php echo $e['projeto']; ?>"
                                                               type="hidden">
                                                        <input name="elemento" value="<?php echo $e['elemento']; ?>"
                                                               type="hidden">

                                                        <div class="form-group">
                                                            <label>Comentar</label>
                                                            <textarea name="comentario"
                                                                      placeholder="Digite um comentário..." rows="3"
                                                                      class="form-control"></textarea>
                                                        </div>
                                                        <div id="msg-erro"></div>
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <button class="btn btn-primary btn-flat border-0 pull-right"
                                                                        type="submit">Enviar comentário
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="col-lg-2 col-md-3" <?php if ($esconde_Botoes) {
                                                    echo 'style="display: none;"';
                                                } ?>>
                                                    <div class="item-btns">
                                                        <button class="btn btn-block btn-flat btn-primary border-0 js-getElemento"
                                                                data-elemento="<?php echo $e["elemento"]; ?>"
                                                                data-toggle="modal" data-backdrop="static"
                                                                data-target="#modal-elemento"
                                                                data-acao="adicionar-identidade-visual"
                                                                title="Adicionar ID Visual">
                                                            <i class="fa fa-image pull-left"></i> ID Visual
                                                        </button>
                                                        <?php if (in_array($extra['area'], AREAS_CONTATO_CLIENTE) && $e['elementoStatus'] != 3) { ?>
                                                            <button class="btn btn-flat btn-block btn-cliente border-0 js-getElemento"
                                                                    data-elemento="<?php echo $e['elemento']; ?>"
                                                                    data-toggle="modal" data-backdrop="static"
                                                                    data-acao="aguardar-cliente"
                                                                    data-target="#modal-elemento">
                                                                <i class="fa fa-clock-o pull-left"></i> Enviado/Aguardar
                                                                Cliente
                                                            </button>
                                                        <?php } ?>
                                                        <button class="btn btn-block btn-flat btn-success border-0 js-getElemento"
                                                                data-elemento="<?php echo $e["elemento"]; ?>"
                                                                data-toggle="modal" data-backdrop="static"
                                                                data-target="#modal-elemento"
                                                                data-acao="aprovar-elemento" title="Finalizar Tarefa">
                                                            <i class="fa fa-thumbs-up pull-left"></i> Finalizar Tarefa
                                                        </button>
                                                        <button class="btn btn-block btn-flat btn-danger border-0 js-getElemento"
                                                                data-elemento="<?php echo $e["elemento"]; ?>"
                                                                data-toggle="modal" data-backdrop="static"
                                                                data-target="#modal-elemento"
                                                                data-acao="reprovar-elemento" title="Reprovar Tarefa">
                                                            <i class="fa fa-thumbs-down pull-left"></i> Reprovar Tarefa
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- /item -->
                                <?php }


                                foreach ($estrutura as $e) {
                                    $extra = $e["campos"];
                                    $status = $POPElemento->getElementStatusById($e["elementoStatus"]);
                                    $tipo = $POPElemento->getElementTypeById($e["elementoTipo"]);
                                    $ret = limpaExtras($tipo, $extra);
                                    $subE = $ret[0];
                                    $nomesCampos = $ret[1];
                                    ?>
                                    <div class="col-sm-12 item">
                                        <div class="item-box">
                                            <div class="row">
                                                <div class="col-lg-7 col-md-5 item-info-list">
                                                    <div class="row">
                                                        <div class="item-status stamp"
                                                             style="background-color:<?php echo $status["cor"]; ?>">
                                                            <i class="fa fa-clock-o"></i> <?php echo $status['nome']; ?>
                                                            <span style="float:right"><?php echo $e["elemento"]; ?></span>
                                                        </div>
                                                        <?php
                                                        $tipoTarefa = $POPElemento->getElementTypeById($e['elementoTipo']);
                                                        foreach ($subE as $field) {
                                                            $nome = $nomesCampos[$field['nome']] ?? ($field["nome"] . ":404:");
                                                            ?>
                                                            <div class="col-sm-4 item-info">
                                                                <div class="item-info-box">
                                                                    <h4><?php echo $nome; ?></h4>
                                                                    <?php
                                                                    $class = $field['tipo'];
                                                                    if (class_exists($class)) {
                                                                        /** @var nomeTipo $var */
                                                                        $var = new $class($field['nome'], $field['valor'], $database, ["elemento" => $e["elemento"]]);
                                                                        echo $var->retornaHtmlExibicao();
                                                                    }
                                                                    else {
                                                                        echo "<p>Tipo " . $field['nome'] . " NAO Encontrado</p>";
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-4">
                                                    <div class="box-header">
                                                        <h4 class="box-title">Chat</h4>
                                                    </div>
                                                    <div class="box-body chat historico-post" id="chat-box">
                                                        <?php
                                                        $chatProjeto = $POPChat->getProjetoChat($e['projeto'], $e['elemento']);
                                                        $tamanhoChat = count($chatProjeto);
                                                        $tmpChat = [];
                                                        if ($tamanhoChat < 2) {
                                                            $tmpChat = $chatProjeto;
                                                        }
                                                        else {
                                                            $t = $tamanhoChat;
                                                            $tmpChat = [$chatProjeto[$t - 2], $chatProjeto[$t - 1]];
                                                        }

                                                        foreach ($tmpChat as $itemChat) {
                                                            $var = new responsavel('primeiro-msg', $itemChat['responsavel'], $database);
                                                            ?>
                                                            <div class="item">
                                                                <?php echo $var->retornaHtmlExibicao(); ?>
                                                                <p class="message">
                                                                    <a href="#" class="name">
                                                                        <small title="<?php echo date('d/m/Y - H:i:s', strtotime($itemChat['data'])); ?>"
                                                                               class="text-muted pull-right">
                                                                            <i class="fa fa-clock-o"></i> <?php echo Utils::printDate(date('Y-m-d H:i:s', strtotime($itemChat['data']))); ?>
                                                                        </small>
                                                                        <?php echo $var->retornaNomeExibicao(); ?>
                                                                    </a>
                                                                    <?php
                                                                    $conteudo = Utils::insert_href(nl2br(strip_tags($itemChat['conteudo'])));
                                                                    echo $conteudo; ?><br>
                                                                    <br>
                                                                </p>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="ver-historico">
                                                        <a href="#" class="dpb js-getElemento"
                                                           data-elemento="<?php echo $e['elemento']; ?>"
                                                           data-toggle="modal" data-backdrop="static"
                                                           data-target="#modal-elemento"
                                                           data-acao="ver-historico-projeto">Todas Mensagens</a>
                                                        <a href="#" class="dpb js-getElemento"
                                                           data-elemento="<?php echo $e['elemento']; ?>"
                                                           data-toggle="modal" data-backdrop="static"
                                                           data-target="#modal-elemento"
                                                           data-acao="ver-historico-elemento-completo">Ver log</a>
                                                    </div>

                                                    <form class="form-comentario" action="" method="post">
                                                        <input name="projeto" value="<?php echo $e['projeto']; ?>"
                                                               type="hidden">
                                                        <input name="elemento" value="<?php echo $e['elemento']; ?>"
                                                               type="hidden">

                                                        <div class="form-group">
                                                            <label>Comentar</label>
                                                            <textarea name="comentario"
                                                                      placeholder="Digite um comentário..." rows="3"
                                                                      class="form-control"></textarea>
                                                        </div>
                                                        <div id="msg-erro"></div>
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <button class="btn btn-primary btn-flat border-0 pull-right"
                                                                        type="submit">Enviar comentário
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="col-lg-2 col-md-3" <?php if ($esconde_Botoes) {
                                                    echo 'style="display: none;"';
                                                } ?>>
                                                    <div class="item-btns">
                                                        <button class="btn btn-block btn-flat btn-primary border-0 js-getElemento"
                                                                data-elemento="<?php echo $e["elemento"]; ?>"
                                                                data-toggle="modal" data-backdrop="static"
                                                                data-target="#modal-elemento"
                                                                data-acao="adicionar-estrutura-nerdpress"
                                                                title="<?php echo $POPElemento->adicionarOuAlterar($e, 'dominio', 'Estrutura'); ?>">
                                                            <i class="fa fa-database pull-left"></i><?php echo $POPElemento->adicionarOuAlterar($e, 'dominio', 'Estrutura'); ?>
                                                        </button>
                                                        <?php if (in_array($extra['area'], AREAS_CONTATO_CLIENTE) && $e['elementoStatus'] != 3) { ?>
                                                            <button class="btn btn-flat btn-block btn-cliente border-0 js-getElemento"
                                                                    data-elemento="<?php echo $e['elemento']; ?>"
                                                                    data-toggle="modal" data-backdrop="static"
                                                                    data-acao="aguardar-cliente"
                                                                    data-target="#modal-elemento">
                                                                <i class="fa fa-clock-o pull-left"></i> Enviado/Aguardar
                                                                Cliente
                                                            </button>
                                                        <?php } ?>
                                                        <button class="btn btn-block btn-flat btn-success border-0 js-getElemento"
                                                                data-elemento="<?php echo $e["elemento"]; ?>"
                                                                data-toggle="modal" data-backdrop="static"
                                                                data-target="#modal-elemento"
                                                                data-acao="aprovar-elemento" title="Finalizar Tarefa">
                                                            <i class="fa fa-thumbs-up pull-left"></i> Finalizar Tarefa
                                                        </button>
                                                        <button class="btn btn-block btn-flat btn-danger border-0 js-getElemento"
                                                                data-elemento="<?php echo $e["elemento"]; ?>"
                                                                data-toggle="modal" data-backdrop="static"
                                                                data-target="#modal-elemento"
                                                                data-acao="reprovar-elemento" title="Reprovar Tarefa">
                                                            <i class="fa fa-thumbs-down pull-left"></i> Reprovar Tarefa
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- /item -->
                                <?php }


                                foreach ($dominio as $e) {
                                    $extra = $e["campos"];
                                    $status = $POPElemento->getElementStatusById($e["elementoStatus"]);
                                    $tipo = $POPElemento->getElementTypeById($e["elementoTipo"]);
                                    $ret = limpaExtras($tipo, $extra);
                                    $subE = $ret[0];
                                    $nomesCampos = $ret[1];
                                    ?>
                                    <div class="col-sm-12 item">
                                        <div class="item-box">
                                            <div class="row">
                                                <div class="col-lg-7 col-md-5 item-info-list">
                                                    <div class="row">
                                                        <div class="item-status stamp"
                                                             style="background-color:<?php echo $status["cor"]; ?>">
                                                            <i class="fa fa-clock-o"></i> <?php echo $status['nome']; ?>
                                                            <span style="float:right"><?php echo $e["elemento"]; ?></span>
                                                        </div>
                                                        <?php
                                                        $tipoTarefa = $POPElemento->getElementTypeById($e['elementoTipo']);
                                                        ?>
                                                        <!--
														<div class="col-sm-4 item-info">
															<div class="item-info-box">
																<h4>Tarefa</h4>
																<?php echo "<p>" . $tipoTarefa['nome'] . "</p>"; ?>
															</div>
														</div>
														-->
                                                        <?php
                                                        foreach ($subE as $field) {
                                                            $nome = $nomesCampos[$field['nome']] ?? ($field["nome"] . ":404:");
                                                            ?>
                                                            <div class="col-sm-4 item-info">
                                                                <div class="item-info-box">
                                                                    <h4><?php echo $nome; ?></h4>
                                                                    <?php
                                                                    $class = $field['tipo'];
                                                                    if (class_exists($class)) {
                                                                        /** @var nomeTipo $var */
                                                                        $var = new $class($field['nome'], $field['valor'], $database, ["elemento" => $e["elemento"]]);
                                                                        echo $var->retornaHtmlExibicao();
                                                                    }
                                                                    else {
                                                                        echo "<p>Tipo " . $field['nome'] . " NAO Encontrado</p>";
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-4">
                                                    <div class="box-header">
                                                        <h4 class="box-title">Chat</h4>
                                                    </div>
                                                    <div class="box-body chat historico-post" id="chat-box">
                                                        <?php
                                                        $chatProjeto = $POPChat->getProjetoChat($e['projeto'], $e['elemento']);
                                                        $tamanhoChat = count($chatProjeto);
                                                        $tmpChat = [];
                                                        if ($tamanhoChat < 2) {
                                                            $tmpChat = $chatProjeto;
                                                        }
                                                        else {
                                                            $t = $tamanhoChat;
                                                            $tmpChat = [$chatProjeto[$t - 2], $chatProjeto[$t - 1]];
                                                        }

                                                        foreach ($tmpChat as $itemChat) {
                                                            $var = new responsavel('primeiro-msg', $itemChat['responsavel'], $database);
                                                            ?>
                                                            <div class="item">
                                                                <?php echo $var->retornaHtmlExibicao(); ?>
                                                                <p class="message">
                                                                    <a href="#" class="name">
                                                                        <small title="<?php echo date('d/m/Y - H:i:s', strtotime($itemChat['data'])); ?>"
                                                                               class="text-muted pull-right">
                                                                            <i class="fa fa-clock-o"></i> <?php echo Utils::printDate(date('Y-m-d H:i:s', strtotime($itemChat['data']))); ?>
                                                                        </small>
                                                                        <?php echo $var->retornaNomeExibicao(); ?>
                                                                    </a>
                                                                    <?php
                                                                    $conteudo = Utils::insert_href(nl2br(strip_tags($itemChat['conteudo'])));
                                                                    echo $conteudo; ?><br>
                                                                    <br>
                                                                </p>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="ver-historico">
                                                        <a href="#" class="dpb js-getElemento"
                                                           data-elemento="<?php echo $e['elemento']; ?>"
                                                           data-toggle="modal" data-backdrop="static"
                                                           data-target="#modal-elemento"
                                                           data-acao="ver-historico-projeto">Todas Mensagens</a>
                                                        <a href="#" class="dpb js-getElemento"
                                                           data-elemento="<?php echo $e['elemento']; ?>"
                                                           data-toggle="modal" data-backdrop="static"
                                                           data-target="#modal-elemento"
                                                           data-acao="ver-historico-elemento-completo">Ver log</a>
                                                    </div>

                                                    <form class="form-comentario" action="" method="post">
                                                        <input name="projeto" value="<?php echo $e['projeto']; ?>"
                                                               type="hidden">
                                                        <input name="elemento" value="<?php echo $e['elemento']; ?>"
                                                               type="hidden">

                                                        <div class="form-group">
                                                            <label>Comentar</label>
                                                            <textarea name="comentario"
                                                                      placeholder="Digite um comentário..." rows="3"
                                                                      class="form-control"></textarea>
                                                        </div>
                                                        <div id="msg-erro"></div>
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <button class="btn btn-primary btn-flat border-0 pull-right"
                                                                        type="submit">Enviar comentário
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="col-lg-2 col-md-3" <?php if ($esconde_Botoes) {
                                                    echo 'style="display: none;"';
                                                } ?>>
                                                    <div class="item-btns">
                                                        <button class="btn btn-block btn-flat btn-primary border-0 js-getElemento"
                                                                data-elemento="<?php echo $e["elemento"]; ?>"
                                                                data-toggle="modal" data-backdrop="static"
                                                                data-target="#modal-elemento"
                                                                data-acao="adicionar-estrutura-aws"
                                                                title="<?php echo $POPElemento->adicionarOuAlterar($e, 'dominio', 'Estrutura'); ?>">
                                                            <i class="fa fa-database pull-left"></i><?php echo $POPElemento->adicionarOuAlterar($e, 'dominio', 'Estrutura'); ?>
                                                        </button>
                                                        <button class="btn btn-block btn-flat btn-primary border-0 js-getElemento"
                                                                data-elemento="<?php echo $e["elemento"]; ?>"
                                                                data-toggle="modal" data-backdrop="static"
                                                                data-target="#modal-elemento"
                                                                data-acao="adicionar-listagem-emails"
                                                                title="<?php echo $POPElemento->adicionarOuAlterar($e, 'dominio', 'Email'); ?>">
                                                            <i class="fa fa-database pull-left"></i><?php echo $POPElemento->adicionarOuAlterar($e, 'dominio', 'Email'); ?>
                                                        </button>
                                                        <?php if (in_array($extra['area'], AREAS_CONTATO_CLIENTE) && $e['elementoStatus'] != 3) { ?>
                                                            <button class="btn btn-flat btn-block btn-cliente border-0 js-getElemento"
                                                                    data-elemento="<?php echo $e['elemento']; ?>"
                                                                    data-toggle="modal" data-backdrop="static"
                                                                    data-acao="aguardar-cliente"
                                                                    data-target="#modal-elemento">
                                                                <i class="fa fa-clock-o pull-left"></i> Enviado/Aguardar
                                                                Cliente
                                                            </button>
                                                        <?php } ?>
                                                        <button class="btn btn-block btn-flat btn-success border-0 js-getElemento"
                                                                data-elemento="<?php echo $e["elemento"]; ?>"
                                                                data-toggle="modal" data-backdrop="static"
                                                                data-target="#modal-elemento"
                                                                data-acao="aprovar-elemento" title="Finalizar Tarefa">
                                                            <i class="fa fa-thumbs-up pull-left"></i> Finalizar Tarefa
                                                        </button>
                                                        <button class="btn btn-block btn-flat btn-danger border-0 js-getElemento"
                                                                data-elemento="<?php echo $e["elemento"]; ?>"
                                                                data-toggle="modal" data-backdrop="static"
                                                                data-target="#modal-elemento"
                                                                data-acao="reprovar-elemento" title="Reprovar Tarefa">
                                                            <i class="fa fa-thumbs-down pull-left"></i> Reprovar Tarefa
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- /item -->
                                <?php }

                                //
                                // AS PAGINAS TANTO HOME QTO INTERNAS SE ENCONTRAM NESSA
                                //
                                // CATEGORIA
                                //
                                foreach ($paginas as $e) {
                                    $extra = $e["campos"];

                                    $status = $POPElemento->getElementStatusById($e["elementoStatus"]);
                                    $tipo = $POPElemento->getElementTypeById($e["elementoTipo"]);

                                    $ret = limpaExtras($tipo, $extra);
                                    $subE = $ret[0];
                                    $nomesCampos = $ret[1];
                                    if ($e["elementoTipo"] == 72) {
                                        $etapa = $subE[0]["valor"];
                                        $eTipo = $e["elementoTipo"];
                                        $etapa_nome = $database->ngSelectPrepared("pop_ElementoTipoSubEtapa", ["etapa" => $etapa, "elementoTipo" => $eTipo], "nome", "", 1);

                                        $nome_pagina = "";
                                        foreach ($subE as $key => $value) {
                                            if ($subE[$key]["nome"] == "Nome") {
                                                $nome_pagina = $subE[$key]["valor"];
                                                unset($subE[$key], $nomesCampos["Nome"]);
                                            }
                                        }
                                        $etapa_nome = $etapa_nome["nome"] . " - " . $nome_pagina;
                                        $subE[0]["valor"] = $etapa_nome;
                                        $subE[0]["tipo"] = "text";
                                        //var_dump($subE, $nomesCampos);
                                    }
                                    ?>
                                    <div class="col-sm-12 item">
                                        <div class="item-box">
                                            <div class="row">
                                                <div class="col-lg-7 col-md-5 item-info-list">
                                                    <div class="row">
                                                        <div class="item-status stamp"
                                                             style="background-color:<?php echo $status["cor"]; ?>">
                                                            <i class="fa fa-clock-o"></i> <?php echo $status['nome']; ?>
                                                            <span style="float:right"><?php echo $e["elemento"]; ?></span>
                                                        </div>
                                                        <?php
                                                        $tipoTarefa = $POPElemento->getElementTypeById($e['elementoTipo']);
                                                        foreach ($subE as $field) {
                                                            $nome = $nomesCampos[$field['nome']] ?? ($field["nome"] . ":404:");
                                                            ?>
                                                            <div class="col-sm-4 item-info">
                                                                <div class="item-info-box">
                                                                    <h4><?php echo $nome; ?></h4>
                                                                    <?php
                                                                    $class = $field['tipo'];
                                                                    if (class_exists($class)) {
                                                                        /** @var nomeTipo $var */
                                                                        $var = new $class($field['nome'], $field['valor'], $database, ["elemento" => $e["elemento"]]);
                                                                        echo $var->retornaHtmlExibicao();
                                                                    }
                                                                    else {
                                                                        echo "<p>Tipo " . $field['nome'] . "NAO Encontrado</p>";
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-4">
                                                    <div class="box-header">
                                                        <h4 class="box-title">Chat</h4>
                                                    </div>
                                                    <div class="box-body chat historico-post" id="chat-box">
                                                        <?php
                                                        $chatProjeto = $POPChat->getProjetoChat($e['projeto'], $e['elemento']);
                                                        $tamanhoChat = count($chatProjeto);
                                                        $tmpChat = [];
                                                        if ($tamanhoChat < 2) {
                                                            $tmpChat = $chatProjeto;
                                                        }
                                                        else {
                                                            $t = $tamanhoChat;
                                                            $tmpChat = [$chatProjeto[$t - 2], $chatProjeto[$t - 1]];
                                                        }

                                                        foreach ($tmpChat as $itemChat) {
                                                            $var = new responsavel('primeiro-msg', $itemChat['responsavel'], $database);
                                                            ?>
                                                            <div class="item">
                                                                <?php echo $var->retornaHtmlExibicao(); ?>
                                                                <p class="message">
                                                                    <a href="#" class="name">
                                                                        <small title="<?php echo date('d/m/Y - H:i:s', strtotime($itemChat['data'])); ?>"
                                                                               class="text-muted pull-right">
                                                                            <i class="fa fa-clock-o"></i> <?php echo Utils::printDate(date('Y-m-d H:i:s', strtotime($itemChat['data']))); ?>
                                                                        </small>
                                                                        <?php echo $var->retornaNomeExibicao(); ?>
                                                                    </a>
                                                                    <?php
                                                                    $conteudo = Utils::insert_href(nl2br(strip_tags($itemChat['conteudo'])));
                                                                    echo $conteudo; ?><br>
                                                                    <br>
                                                                </p>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="ver-historico">
                                                        <a href="#" class="dpb js-getElemento"
                                                           data-elemento="<?php echo $e['elemento']; ?>"
                                                           data-toggle="modal" data-backdrop="static"
                                                           data-target="#modal-elemento"
                                                           data-acao="ver-historico-projeto">Todas Mensagens</a>
                                                        <a href="#" class="dpb js-getElemento"
                                                           data-elemento="<?php echo $e['elemento']; ?>"
                                                           data-toggle="modal" data-backdrop="static"
                                                           data-target="#modal-elemento"
                                                           data-acao="ver-historico-elemento-completo">Ver log</a>
                                                    </div>

                                                    <form class="form-comentario" action="" method="post">
                                                        <input name="projeto" value="<?php echo $e['projeto']; ?>"
                                                               type="hidden">
                                                        <input name="elemento" value="<?php echo $e['elemento']; ?>"
                                                               type="hidden">

                                                        <div class="form-group">
                                                            <label>Comentar</label>
                                                            <textarea name="comentario"
                                                                      placeholder="Digite um comentário..." rows="3"
                                                                      class="form-control"></textarea>
                                                        </div>
                                                        <div id="msg-erro"></div>
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <button class="btn btn-primary btn-flat border-0 pull-right"
                                                                        type="submit">Enviar comentário
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="col-lg-2 col-md-3" <?php if ($esconde_Botoes) {
                                                    echo 'style="display: none;"';
                                                } ?>>
                                                    <div class="item-btns">
                                                        <?php if ($e["elementoTipo"] == 67) { ?>
                                                            <button class="btn btn-block btn-flat btn-primary border-0 js-getElemento"
                                                                    data-elemento="<?php echo $e['elemento']; ?>"
                                                                    data-toggle="modal" data-backdrop="static"
                                                                    data-target="#modal-elemento"
                                                                    data-acao="adicionar-wireframe"
                                                                    title="<?php echo $POPElemento->adicionarOuAlterar($e, 'wireframe', 'Wireframe'); ?>">
                                                                <i class="fa fa-image pull-left"></i> <?php echo $POPElemento->adicionarOuAlterar($e, 'wireframe', 'Wireframe'); ?>
                                                            </button>
                                                        <?php } ?>
                                                        <button class="btn btn-block btn-flat btn-primary border-0 js-getElemento"
                                                                data-elemento="<?php echo $e['elemento']; ?>"
                                                                data-toggle="modal" data-backdrop="static"
                                                                data-target="#modal-elemento"
                                                                data-acao="adicionar-layout"
                                                                title="<?php echo $POPElemento->adicionarOuAlterar($e, 'layout', 'Layout'); ?>">
                                                            <i class="fa fa-image pull-left"></i> <?php echo $POPElemento->adicionarOuAlterar($e, 'layout', 'Layout'); ?>
                                                        </button>
                                                        <button class="btn btn-block btn-flat btn-primary border-0 js-getElemento"
                                                                data-elemento="<?php echo $e['elemento']; ?>"
                                                                data-toggle="modal" data-backdrop="static"
                                                                data-target="#modal-elemento"
                                                                data-acao="adicionar-diagramacao"
                                                                title="<?php echo $POPElemento->adicionarOuAlterar($e, 'linkpos', 'Diagramação'); ?>">
                                                            <i class="fa fa-file-text pull-left"></i> <?php echo $POPElemento->adicionarOuAlterar($e, 'linkpos', 'Diagramação'); ?>
                                                        </button>
                                                        <?php if (in_array($extra['area'], AREAS_CONTATO_CLIENTE) && $e['elementoStatus'] != 3) { ?>
                                                            <button class="btn btn-flat btn-block btn-cliente border-0 js-getElemento"
                                                                    data-elemento="<?php echo $e['elemento']; ?>"
                                                                    data-toggle="modal" data-backdrop="static"
                                                                    data-acao="aguardar-cliente"
                                                                    data-target="#modal-elemento">
                                                                <i class="fa fa-clock-o pull-left"></i> Enviado/Aguardar
                                                                Cliente
                                                            </button>
                                                        <?php } ?>
                                                        <button class="btn btn-block btn-flat btn-success border-0 js-getElemento"
                                                                data-elemento="<?php echo $e['elemento']; ?>"
                                                                data-toggle="modal" data-backdrop="static"
                                                                data-target="#modal-elemento"
                                                                data-acao="aprovar-elemento" title="Finalizar Tarefa">
                                                            <i class="fa fa-thumbs-up pull-left"></i> Finalizar Tarefa
                                                        </button>
                                                        <button class="btn btn-block btn-flat btn-danger border-0 js-getElemento"
                                                                data-elemento="<?php echo $e['elemento']; ?>"
                                                                data-toggle="modal" data-backdrop="static"
                                                                data-target="#modal-elemento"
                                                                data-acao="reprovar-elemento" title="Reprovar Tarefa">
                                                            <i class="fa fa-thumbs-down pull-left"></i> Reprovar Tarefa
                                                        </button>
                                                        <button class="btn btn-block btn-flat btn-danger border-0 js-getElemento"
                                                                data-elemento="<?php echo $e['elemento']; ?>"
                                                                data-toggle="modal" data-backdrop="static"
                                                                data-target="#modal-elemento"
                                                                data-acao="apagar-elemento" title="Apagar Tarefa">
                                                            <i class="fa fa-times pull-left"></i> Apagar Tarefa
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- /item -->
                                <?php }


                                //
                                // Geracao de modulos
                                //
                                // CATEGORIA
                                //
                                foreach ($inserir_modulo as $e) {
                                    $extra = $e["campos"];
                                    $status = $POPElemento->getElementStatusById($e["elementoStatus"]);
                                    $tipo = $POPElemento->getElementTypeById($e["elementoTipo"]);

                                    $ret = limpaExtras($tipo, $extra);
                                    $subE = $ret[0];
                                    $nomesCampos = $ret[1];

                                    //var_dump($e);
                                    ?>
                                    <div class="col-sm-12 item">
                                        <div class="item-box">
                                            <div class="row">
                                                <div class="col-lg-7 col-md-5 item-info-list">
                                                    <div class="row">
                                                        <div class="item-status stamp"
                                                             style="background-color:<?php echo $status["cor"]; ?>">
                                                            <i class="fa fa-clock-o"></i> <?php echo $status['nome']; ?>
                                                            <span style="float:right"><?php echo $e["elemento"]; ?></span>
                                                        </div>
                                                        <?php
                                                        $tipoTarefa = $POPElemento->getElementTypeById($e['elementoTipo']);
                                                        if ($tipoTarefa["elementoTipo"] == 75) {
                                                            $nome = $tipoTarefa['nome'];
                                                        }
                                                        else {
                                                            $nome = "";
                                                        }
                                                        if ($nome != "") {
                                                            ?>
                                                            <div class="col-sm-4 item-info">
                                                                <div class="item-info-box">
                                                                    <h4>Tarefa</h4>
                                                                    <?php echo "<p>" . $nome . "</p>"; ?>
                                                                </div>
                                                            </div>

                                                            <?php
                                                        }
                                                        $tipoTarefa = $POPElemento->getElementTypeById($e['elementoTipo']);
                                                        foreach ($subE as $field) {
                                                            if ($field["nome"] === "lista") {
                                                                continue;
                                                            }

                                                            $nome = $nomesCampos[$field['nome']] ?? ($field["nome"] . ":404:");
                                                            ?>
                                                            <div class="col-sm-4 item-info">
                                                                <div class="item-info-box">
                                                                    <h4><?php echo $nome; ?></h4>
                                                                    <?php
                                                                    $class = $field['tipo'];
                                                                    if (class_exists($class)) {
                                                                        /** @var nomeTipo $var */
                                                                        $var = new $class($field['nome'], $field['valor'], $database, ["elemento" => $e["elemento"]]);
                                                                        echo $var->retornaHtmlExibicao();
                                                                    }
                                                                    else {
                                                                        echo "<p>Tipo " . $field['nome'] . "NAO Encontrado</p>";
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-4">
                                                    <div class="box-header">
                                                        <h4 class="box-title">Chat</h4>
                                                    </div>
                                                    <div class="box-body chat historico-post" id="chat-box">
                                                        <?php
                                                        $chatProjeto = $POPChat->getProjetoChat($e['projeto'], $e['elemento']);
                                                        $tamanhoChat = count($chatProjeto);
                                                        $tmpChat = [];
                                                        if ($tamanhoChat < 2) {
                                                            $tmpChat = $chatProjeto;
                                                        }
                                                        else {
                                                            $t = $tamanhoChat;
                                                            $tmpChat = [$chatProjeto[$t - 2], $chatProjeto[$t - 1]];
                                                        }

                                                        foreach ($tmpChat as $itemChat) {
                                                            $var = new responsavel('primeiro-msg', $itemChat['responsavel'], $database);
                                                            ?>
                                                            <div class="item">
                                                                <?php echo $var->retornaHtmlExibicao(); ?>
                                                                <p class="message">
                                                                    <a href="#" class="name">
                                                                        <small title="<?php echo date('d/m/Y - H:i:s', strtotime($itemChat['data'])); ?>"
                                                                               class="text-muted pull-right">
                                                                            <i class="fa fa-clock-o"></i> <?php echo Utils::printDate(date('Y-m-d H:i:s', strtotime($itemChat['data']))); ?>
                                                                        </small>
                                                                        <?php echo $var->retornaNomeExibicao(); ?>
                                                                    </a>
                                                                    <?php
                                                                    $conteudo = Utils::insert_href(nl2br(strip_tags($itemChat['conteudo'])));
                                                                    echo $conteudo; ?><br>
                                                                    <br>
                                                                </p>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="ver-historico">
                                                        <a href="#" class="dpb js-getElemento"
                                                           data-elemento="<?php echo $e['elemento']; ?>"
                                                           data-toggle="modal" data-backdrop="static"
                                                           data-target="#modal-elemento"
                                                           data-acao="ver-historico-projeto">Todas Mensagens</a>
                                                        <a href="#" class="dpb js-getElemento"
                                                           data-elemento="<?php echo $e['elemento']; ?>"
                                                           data-toggle="modal" data-backdrop="static"
                                                           data-target="#modal-elemento"
                                                           data-acao="ver-historico-elemento-completo">Ver log</a>
                                                    </div>

                                                    <form class="form-comentario" action="" method="post">
                                                        <input name="projeto" value="<?php echo $e['projeto']; ?>"
                                                               type="hidden">
                                                        <input name="elemento" value="<?php echo $e['elemento']; ?>"
                                                               type="hidden">

                                                        <div class="form-group">
                                                            <label>Comentar</label>
                                                            <textarea name="comentario"
                                                                      placeholder="Digite um comentário..." rows="3"
                                                                      class="form-control"></textarea>
                                                        </div>
                                                        <div id="msg-erro"></div>
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <button class="btn btn-primary btn-flat border-0 pull-right"
                                                                        type="submit">Enviar comentário
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="col-lg-2 col-md-3" <?php if ($esconde_Botoes) {
                                                    echo 'style="display: none;"';
                                                } ?>>
                                                    <div class="item-btns">
                                                        <button class="btn btn-block btn-flat btn-primary border-0 js-getElemento"
                                                                data-elemento="<?php echo $botao_modulos[0]["elemento"]; ?>"
                                                                data-toggle="modal" data-backdrop="static"
                                                                data-target="#modal-elemento"
                                                                data-acao="adicionar-lista-modulos"
                                                                title="<?php echo $POPElemento->adicionarOuAlterar($botao_modulos[0]["elemento"], 'lista', 'Lista de Módulos '); ?>">
                                                            <i class="fa fa-image pull-left"></i> <?php echo $POPElemento->adicionarOuAlterar($botao_modulos[0]["elemento"], 'lista', 'Lista de Módulos '); ?>
                                                        </button>
                                                        <?php if (in_array($extra['area'], AREAS_CONTATO_CLIENTE) && $e['elementoStatus'] != 3) { ?>
                                                            <button class="btn btn-flat btn-block btn-cliente border-0 js-getElemento"
                                                                    data-elemento="<?php echo $e['elemento']; ?>"
                                                                    data-toggle="modal" data-backdrop="static"
                                                                    data-acao="aguardar-cliente"
                                                                    data-target="#modal-elemento">
                                                                <i class="fa fa-clock-o pull-left"></i> Enviado/Aguardar
                                                                Cliente
                                                            </button>
                                                        <?php } ?>
                                                        <button class="btn btn-block btn-flat btn-success border-0 js-getElemento"
                                                                data-elemento="<?php echo $e['elemento']; ?>"
                                                                data-toggle="modal" data-backdrop="static"
                                                                data-target="#modal-elemento"
                                                                data-acao="aprovar-elemento" title="Finalizar Tarefa">
                                                            <i class="fa fa-thumbs-up pull-left"></i> Finalizar Tarefa
                                                        </button>
                                                        <button class="btn btn-block btn-flat btn-danger border-0 js-getElemento"
                                                                data-elemento="<?php echo $e['elemento']; ?>"
                                                                data-toggle="modal" data-backdrop="static"
                                                                data-target="#modal-elemento"
                                                                data-acao="reprovar-elemento" title="Reprovar Tarefa">
                                                            <i class="fa fa-thumbs-down pull-left"></i> Reprovar Tarefa
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- /item -->
                                <?php }


                                //
                                // Geracao de conteudo
                                //
                                // CATEGORIA
                                //
                                foreach ($inserir_conteudo as $e) {
                                    $extra = $e["campos"];
                                    $status = $POPElemento->getElementStatusById($e["elementoStatus"]);
                                    $tipo = $POPElemento->getElementTypeById($e["elementoTipo"]);
                                    $ret = limpaExtras($tipo, $extra);
                                    $subE = $ret[0];
                                    $nomesCampos = $ret[1];
                                    ?>
                                    <div class="col-sm-12 item">
                                        <div class="item-box">
                                            <div class="row">
                                                <div class="col-lg-7 col-md-5 item-info-list">
                                                    <div class="row">
                                                        <div class="item-status stamp"
                                                             style="background-color:<?php echo $status["cor"]; ?>">
                                                            <i class="fa fa-clock-o"></i> <?php echo $status['nome']; ?>
                                                            <span style="float:right"><?php echo $e["elemento"]; ?></span>
                                                        </div>
                                                        <?php
                                                        $tipoTarefa = $POPElemento->getElementTypeById($e['elementoTipo']);
                                                        ?>
                                                        <div class="col-sm-4 item-info">
                                                            <div class="item-info-box">
                                                                <h4>Tarefa</h4>
                                                                <?php echo "<p>" . $tipoTarefa['nome'] . "</p>"; ?>
                                                            </div>
                                                        </div>
                                                        <?php
                                                        $tipoTarefa = $POPElemento->getElementTypeById($e['elementoTipo']);
                                                        foreach ($subE as $field) {
                                                            if ($field["nome"] === "conteudo") {
                                                                continue;
                                                            }
                                                            $nome = $nomesCampos[$field['nome']] ?? ($field["nome"] . ":404:");
                                                            ?>
                                                            <div class="col-sm-4 item-info">
                                                                <div class="item-info-box">
                                                                    <h4><?php echo $nome; ?></h4>
                                                                    <?php
                                                                    $class = $field['tipo'];
                                                                    if (class_exists($class)) {
                                                                        /** @var nomeTipo $var */
                                                                        $var = new $class($field['nome'], $field['valor'], $database, ["elemento" => $e["elemento"]]);
                                                                        echo $var->retornaHtmlExibicao();
                                                                    }
                                                                    else {
                                                                        echo "<p>Tipo " . $field['nome'] . "NAO Encontrado</p>";
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-4">
                                                    <div class="box-header">
                                                        <h4 class="box-title">Chat</h4>
                                                    </div>
                                                    <div class="box-body chat historico-post" id="chat-box">
                                                        <?php
                                                        $chatProjeto = $POPChat->getProjetoChat($e['projeto'], $e['elemento']);
                                                        $tamanhoChat = count($chatProjeto);
                                                        $tmpChat = [];
                                                        if ($tamanhoChat < 2) {
                                                            $tmpChat = $chatProjeto;
                                                        }
                                                        else {
                                                            $t = $tamanhoChat;
                                                            $tmpChat = [$chatProjeto[$t - 2], $chatProjeto[$t - 1]];
                                                        }

                                                        foreach ($tmpChat as $itemChat) {
                                                            $var = new responsavel('primeiro-msg', $itemChat['responsavel'], $database);
                                                            ?>
                                                            <div class="item">
                                                                <?php echo $var->retornaHtmlExibicao(); ?>
                                                                <p class="message">
                                                                    <a href="#" class="name">
                                                                        <small title="<?php echo date('d/m/Y - H:i:s', strtotime($itemChat['data'])); ?>"
                                                                               class="text-muted pull-right">
                                                                            <i class="fa fa-clock-o"></i> <?php echo Utils::printDate(date('Y-m-d H:i:s', strtotime($itemChat['data']))); ?>
                                                                        </small>
                                                                        <?php echo $var->retornaNomeExibicao(); ?>
                                                                    </a>
                                                                    <?php
                                                                    $conteudo = Utils::insert_href(nl2br(strip_tags($itemChat['conteudo'])));
                                                                    echo $conteudo; ?><br>
                                                                    <br>
                                                                </p>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="ver-historico">
                                                        <a href="#" class="dpb js-getElemento"
                                                           data-elemento="<?php echo $e['elemento']; ?>"
                                                           data-toggle="modal" data-backdrop="static"
                                                           data-target="#modal-elemento"
                                                           data-acao="ver-historico-projeto">Todas Mensagens</a>
                                                        <a href="#" class="dpb js-getElemento"
                                                           data-elemento="<?php echo $e['elemento']; ?>"
                                                           data-toggle="modal" data-backdrop="static"
                                                           data-target="#modal-elemento"
                                                           data-acao="ver-historico-elemento-completo">Ver log</a>
                                                    </div>

                                                    <form class="form-comentario" action="" method="post">
                                                        <input name="projeto" value="<?php echo $e['projeto']; ?>"
                                                               type="hidden">
                                                        <input name="elemento" value="<?php echo $e['elemento']; ?>"
                                                               type="hidden">

                                                        <div class="form-group">
                                                            <label>Comentar</label>
                                                            <textarea name="comentario"
                                                                      placeholder="Digite um comentário..." rows="3"
                                                                      class="form-control"></textarea>
                                                        </div>
                                                        <div id="msg-erro"></div>
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <button class="btn btn-primary btn-flat border-0 pull-right"
                                                                        type="submit">Enviar comentário
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="col-lg-2 col-md-3" <?php if ($esconde_Botoes) {
                                                    echo 'style="display: none;"';
                                                } ?>>
                                                    <div class="item-btns">
                                                        <button class="btn btn-block btn-flat btn-primary border-0 js-getElemento"
                                                                data-elemento="<?php echo $botao_conteudo[0]["elemento"]; ?>"
                                                                data-toggle="modal" data-backdrop="static"
                                                                data-target="#modal-elemento"
                                                                data-acao="adicionar-lista-de-conteudo"
                                                                title="<?php echo $POPElemento->adicionarOuAlterar($botao_conteudo[0]["elemento"], 'conteudo', 'Lista de Conteúdo '); ?>">
                                                            <i class="fa fa-image pull-left"></i> <?php echo $POPElemento->adicionarOuAlterar($botao_conteudo[0]["elemento"], 'conteudo', 'Lista de Conteudo '); ?>
                                                        </button>
                                                        <?php if (in_array($extra['area'], AREAS_CONTATO_CLIENTE) && $e['elementoStatus'] != 3) { ?>
                                                            <button class="btn btn-flat btn-block btn-cliente border-0 js-getElemento"
                                                                    data-elemento="<?php echo $e['elemento']; ?>"
                                                                    data-toggle="modal" data-backdrop="static"
                                                                    data-acao="aguardar-cliente"
                                                                    data-target="#modal-elemento">
                                                                <i class="fa fa-clock-o pull-left"></i> Enviado/Aguardar
                                                                Cliente
                                                            </button>
                                                        <?php } ?>
                                                        <button class="btn btn-block btn-flat btn-success border-0 js-getElemento"
                                                                data-elemento="<?php echo $e['elemento']; ?>"
                                                                data-toggle="modal" data-backdrop="static"
                                                                data-target="#modal-elemento"
                                                                data-acao="aprovar-elemento" title="Finalizar Tarefa">
                                                            <i class="fa fa-thumbs-up pull-left"></i> Finalizar Tarefa
                                                        </button>
                                                        <button class="btn btn-block btn-flat btn-danger border-0 js-getElemento"
                                                                data-elemento="<?php echo $e['elemento']; ?>"
                                                                data-toggle="modal" data-backdrop="static"
                                                                data-target="#modal-elemento"
                                                                data-acao="reprovar-elemento" title="Reprovar Tarefa">
                                                            <i class="fa fa-thumbs-down pull-left"></i> Reprovar Tarefa
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- /item -->
                                <?php }


                                foreach ($recortar_layout as $e) {
                                    $extra = $e["campos"];
                                    $status = $POPElemento->getElementStatusById($e["elementoStatus"]);
                                    $tipo = $POPElemento->getElementTypeById($e["elementoTipo"]);
                                    $ret = limpaExtras($tipo, $extra);
                                    $subE = $ret[0];
                                    $nomesCampos = $ret[1];
                                    ?>
                                    <div class="col-sm-12 item">
                                        <div class="item-box">
                                            <div class="row">
                                                <div class="col-lg-7 col-md-5 item-info-list">
                                                    <div class="row">
                                                        <div class="item-status stamp"
                                                             style="background-color:<?php echo $status["cor"]; ?>">
                                                            <i class="fa fa-clock-o"></i> <?php echo $status['nome']; ?>
                                                            <span style="float:right"><?php echo $e["elemento"]; ?></span>
                                                        </div>
                                                        <?php
                                                        $tipoTarefa = $POPElemento->getElementTypeById($e['elementoTipo']);
                                                        ?>
                                                        <div class="col-sm-4 item-info">
                                                            <div class="item-info-box">
                                                                <h4>Tarefa</h4>
                                                                <?php echo "<p>" . $tipoTarefa['nome'] . "</p>"; ?>
                                                            </div>
                                                        </div>
                                                        <?php
                                                        foreach ($subE as $field) {
                                                            $nome = $nomesCampos[$field['nome']] ?? ($field["nome"] . ":404:");
                                                            ?>
                                                            <div class="col-sm-4 item-info">
                                                                <div class="item-info-box">
                                                                    <h4><?php echo $nome; ?></h4>
                                                                    <?php
                                                                    $class = $field['tipo'];
                                                                    if (class_exists($class)) {
                                                                        /** @var nomeTipo $var */
                                                                        $var = new $class($field['nome'], $field['valor'], $database, ["elemento" => $e["elemento"]]);
                                                                        echo $var->retornaHtmlExibicao();
                                                                    }
                                                                    else {
                                                                        echo "<p>Tipo " . $field['nome'] . " NAO Encontrado</p>";
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-4">
                                                    <div class="box-header">
                                                        <h4 class="box-title">Chat</h4>
                                                    </div>
                                                    <div class="box-body chat historico-post" id="chat-box">
                                                        <?php
                                                        $chatProjeto = $POPChat->getProjetoChat($e['projeto'], $e['elemento']);
                                                        $tamanhoChat = count($chatProjeto);
                                                        $tmpChat = [];
                                                        if ($tamanhoChat < 2) {
                                                            $tmpChat = $chatProjeto;
                                                        }
                                                        else {
                                                            $t = $tamanhoChat;
                                                            $tmpChat = [$chatProjeto[$t - 2], $chatProjeto[$t - 1]];
                                                        }

                                                        foreach ($tmpChat as $itemChat) {
                                                            $var = new responsavel('primeiro-msg', $itemChat['responsavel'], $database);
                                                            ?>
                                                            <div class="item">
                                                                <?php echo $var->retornaHtmlExibicao(); ?>
                                                                <p class="message">
                                                                    <a href="#" class="name">
                                                                        <small title="<?php echo date('d/m/Y - H:i:s', strtotime($itemChat['data'])); ?>"
                                                                               class="text-muted pull-right">
                                                                            <i class="fa fa-clock-o"></i> <?php echo Utils::printDate(date('Y-m-d H:i:s', strtotime($itemChat['data']))); ?>
                                                                        </small>
                                                                        <?php echo $var->retornaNomeExibicao(); ?>
                                                                    </a>
                                                                    <?php
                                                                    $conteudo = Utils::insert_href(nl2br(strip_tags($itemChat['conteudo'])));
                                                                    echo $conteudo; ?><br>
                                                                    <br>
                                                                </p>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="ver-historico">
                                                        <a href="#" class="dpb js-getElemento"
                                                           data-elemento="<?php echo $e['elemento']; ?>"
                                                           data-toggle="modal" data-backdrop="static"
                                                           data-target="#modal-elemento"
                                                           data-acao="ver-historico-projeto">Todas Mensagens</a>
                                                        <a href="#" class="dpb js-getElemento"
                                                           data-elemento="<?php echo $e['elemento']; ?>"
                                                           data-toggle="modal" data-backdrop="static"
                                                           data-target="#modal-elemento"
                                                           data-acao="ver-historico-elemento-completo">Ver log</a>
                                                    </div>

                                                    <form class="form-comentario" action="" method="post">
                                                        <input name="projeto" value="<?php echo $e['projeto']; ?>"
                                                               type="hidden">
                                                        <input name="elemento" value="<?php echo $e['elemento']; ?>"
                                                               type="hidden">

                                                        <div class="form-group">
                                                            <label>Comentar</label>
                                                            <textarea name="comentario"
                                                                      placeholder="Digite um comentário..." rows="3"
                                                                      class="form-control"></textarea>
                                                        </div>
                                                        <div id="msg-erro"></div>
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <button class="btn btn-primary btn-flat border-0 pull-right"
                                                                        type="submit">Enviar comentário
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="col-lg-2 col-md-3" <?php if ($esconde_Botoes) {
                                                    echo 'style="display: none;"';
                                                } ?>>
                                                    <div class="item-btns">
                                                        <button class="btn btn-block btn-flat btn-primary border-0 js-getElemento"
                                                                data-elemento="<?php echo $e["elemento"]; ?>"
                                                                data-toggle="modal" data-backdrop="static"
                                                                data-target="#modal-elemento"
                                                                data-acao="adicionar-arquivos-recortados"
                                                                title="Adicionar Arquivos Recortados">
                                                            <i class="fa fa-image pull-left"></i> Arquivos Recortados
                                                        </button>
                                                        <?php if (in_array($extra['area'], AREAS_CONTATO_CLIENTE) && $e['elementoStatus'] != 3) { ?>
                                                            <button class="btn btn-flat btn-block btn-cliente border-0 js-getElemento"
                                                                    data-elemento="<?php echo $e['elemento']; ?>"
                                                                    data-toggle="modal" data-backdrop="static"
                                                                    data-acao="aguardar-cliente"
                                                                    data-target="#modal-elemento">
                                                                <i class="fa fa-clock-o pull-left"></i> Enviado/Aguardar
                                                                Cliente
                                                            </button>
                                                        <?php } ?>
                                                        <button class="btn btn-block btn-flat btn-success border-0 js-getElemento"
                                                                data-elemento="<?php echo $e["elemento"]; ?>"
                                                                data-toggle="modal" data-backdrop="static"
                                                                data-target="#modal-elemento"
                                                                data-acao="aprovar-elemento" title="Finalizar Tarefa">
                                                            <i class="fa fa-thumbs-up pull-left"></i> Finalizar Tarefa
                                                        </button>
                                                        <button class="btn btn-block btn-flat btn-danger border-0 js-getElemento"
                                                                data-elemento="<?php echo $e["elemento"]; ?>"
                                                                data-toggle="modal" data-backdrop="static"
                                                                data-target="#modal-elemento"
                                                                data-acao="reprovar-elemento" title="Reprovar Tarefa">
                                                            <i class="fa fa-thumbs-down pull-left"></i> Reprovar Tarefa
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- /item -->
                                <?php }


                                foreach ($montar_html as $e) {
                                    $extra = $e["campos"];
                                    $status = $POPElemento->getElementStatusById($e["elementoStatus"]);
                                    $tipo = $POPElemento->getElementTypeById($e["elementoTipo"]);
                                    $ret = limpaExtras($tipo, $extra);
                                    $subE = $ret[0];
                                    $nomesCampos = $ret[1];
                                    ?>
                                    <div class="col-sm-12 item">
                                        <div class="item-box">
                                            <div class="row">
                                                <div class="col-lg-7 col-md-5 item-info-list">
                                                    <div class="row">
                                                        <div class="item-status stamp"
                                                             style="background-color:<?php echo $status["cor"]; ?>">
                                                            <i class="fa fa-clock-o"></i> <?php echo $status['nome']; ?>
                                                            <span style="float:right"><?php echo $e["elemento"]; ?></span>
                                                        </div>
                                                        <?php
                                                        $tipoTarefa = $POPElemento->getElementTypeById($e['elementoTipo']);
                                                        ?>
                                                        <div class="col-sm-4 item-info">
                                                            <div class="item-info-box">
                                                                <h4>Tarefa</h4>
                                                                <?php echo "<p>" . $tipoTarefa['nome'] . "</p>"; ?>
                                                            </div>
                                                        </div>
                                                        <?php
                                                        foreach ($subE as $field) {
                                                            $nome = $nomesCampos[$field['nome']] ?? ($field["nome"] . ":404:");
                                                            ?>
                                                            <div class="col-sm-4 item-info">
                                                                <div class="item-info-box">
                                                                    <h4><?php echo $nome; ?></h4>
                                                                    <?php
                                                                    $class = $field['tipo'];
                                                                    if (class_exists($class)) {
                                                                        /** @var nomeTipo $var */
                                                                        $var = new $class($field['nome'], $field['valor'], $database, ["elemento" => $e["elemento"]]);
                                                                        echo $var->retornaHtmlExibicao();
                                                                    }
                                                                    else {
                                                                        echo "<p>Tipo " . $field['nome'] . " NAO Encontrado</p>";
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-4">
                                                    <div class="box-header">
                                                        <h4 class="box-title">Chat</h4>
                                                    </div>
                                                    <div class="box-body chat historico-post" id="chat-box">
                                                        <?php
                                                        $chatProjeto = $POPChat->getProjetoChat($e['projeto'], $e['elemento']);
                                                        $tamanhoChat = count($chatProjeto);
                                                        $tmpChat = [];
                                                        if ($tamanhoChat < 2) {
                                                            $tmpChat = $chatProjeto;
                                                        }
                                                        else {
                                                            $t = $tamanhoChat;
                                                            $tmpChat = [$chatProjeto[$t - 2], $chatProjeto[$t - 1]];
                                                        }

                                                        foreach ($tmpChat as $itemChat) {
                                                            $var = new responsavel('primeiro-msg', $itemChat['responsavel'], $database);
                                                            ?>
                                                            <div class="item">
                                                                <?php echo $var->retornaHtmlExibicao(); ?>
                                                                <p class="message">
                                                                    <a href="#" class="name">
                                                                        <small title="<?php echo date('d/m/Y - H:i:s', strtotime($itemChat['data'])); ?>"
                                                                               class="text-muted pull-right">
                                                                            <i class="fa fa-clock-o"></i> <?php echo Utils::printDate(date('Y-m-d H:i:s', strtotime($itemChat['data']))); ?>
                                                                        </small>
                                                                        <?php echo $var->retornaNomeExibicao(); ?>
                                                                    </a>
                                                                    <?php
                                                                    $conteudo = Utils::insert_href(nl2br(strip_tags($itemChat['conteudo'])));
                                                                    echo $conteudo; ?><br>
                                                                    <br>
                                                                </p>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="ver-historico">
                                                        <a href="#" class="dpb js-getElemento"
                                                           data-elemento="<?php echo $e['elemento']; ?>"
                                                           data-toggle="modal" data-backdrop="static"
                                                           data-target="#modal-elemento"
                                                           data-acao="ver-historico-projeto">Todas Mensagens</a>
                                                        <a href="#" class="dpb js-getElemento"
                                                           data-elemento="<?php echo $e['elemento']; ?>"
                                                           data-toggle="modal" data-backdrop="static"
                                                           data-target="#modal-elemento"
                                                           data-acao="ver-historico-elemento-completo">Ver log</a>
                                                    </div>

                                                    <form class="form-comentario" action="" method="post">
                                                        <input name="projeto" value="<?php echo $e['projeto']; ?>"
                                                               type="hidden">
                                                        <input name="elemento" value="<?php echo $e['elemento']; ?>"
                                                               type="hidden">

                                                        <div class="form-group">
                                                            <label>Comentar</label>
                                                            <textarea name="comentario"
                                                                      placeholder="Digite um comentário..." rows="3"
                                                                      class="form-control"></textarea>
                                                        </div>
                                                        <div id="msg-erro"></div>
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <button class="btn btn-primary btn-flat border-0 pull-right"
                                                                        type="submit">Enviar comentário
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="col-lg-2 col-md-3" <?php if ($esconde_Botoes) {
                                                    echo 'style="display: none;"';
                                                } ?>>
                                                    <div class="item-btns">
                                                        <button class="btn btn-block btn-flat btn-primary border-0 js-getElemento"
                                                                data-elemento="<?php echo $e["elemento"]; ?>"
                                                                data-toggle="modal" data-backdrop="static"
                                                                data-target="#modal-elemento"
                                                                data-acao="adicionar-url-teste"
                                                                title="<?php echo $POPElemento->adicionarOuAlterar($e, 'link_2', 'URL de Teste'); ?>">
                                                            <i class="fa fa-image pull-left"></i> <?php echo $POPElemento->adicionarOuAlterar($e, 'link_2', 'URL de Teste'); ?>
                                                        </button>
                                                        <?php if (in_array($extra['area'], AREAS_CONTATO_CLIENTE) && $e['elementoStatus'] != 3) { ?>
                                                            <button class="btn btn-flat btn-block btn-cliente border-0 js-getElemento"
                                                                    data-elemento="<?php echo $e['elemento']; ?>"
                                                                    data-toggle="modal" data-backdrop="static"
                                                                    data-acao="aguardar-cliente"
                                                                    data-target="#modal-elemento">
                                                                <i class="fa fa-clock-o pull-left"></i> Enviado/Aguardar
                                                                Cliente
                                                            </button>
                                                        <?php } ?>
                                                        <button class="btn btn-block btn-flat btn-success border-0 js-getElemento"
                                                                data-elemento="<?php echo $e["elemento"]; ?>"
                                                                data-toggle="modal" data-backdrop="static"
                                                                data-target="#modal-elemento"
                                                                data-acao="aprovar-elemento" title="Finalizar Tarefa">
                                                            <i class="fa fa-thumbs-up pull-left"></i> Finalizar Tarefa
                                                        </button>
                                                        <button class="btn btn-block btn-flat btn-danger border-0 js-getElemento"
                                                                data-elemento="<?php echo $e["elemento"]; ?>"
                                                                data-toggle="modal" data-backdrop="static"
                                                                data-target="#modal-elemento"
                                                                data-acao="reprovar-elemento" title="Reprovar Tarefa">
                                                            <i class="fa fa-thumbs-down pull-left"></i> Reprovar Tarefa
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- /item -->
                                <?php }


                                //
                                // TODOS OS OUTROS TIPOS DE ELEMENTO
                                //
                                // SE ECONTRAM NESSA CATEGORIA E SAO
                                //
                                // EXIBIDOS NO FINAL
                                //
                                foreach ($elementosProjeto as $e) {
                                    $extra = $e["campos"];
                                    $status = $POPElemento->getElementStatusById($e["elementoStatus"]);
                                    $tipo = $POPElemento->getElementTypeById($e["elementoTipo"]);

                                    $ret = limpaExtras($tipo, $extra);
                                    $subE = $ret[0];
                                    $nomesCampos = $ret[1];
                                    ?>
                                    <div class="col-sm-12 item">
                                        <div class="item-box">
                                            <div class="row">
                                                <div class="col-lg-7 col-md-5 item-info-list">
                                                    <div class="row">
                                                        <div class="item-status stamp"
                                                             style="background-color:<?php echo $status["cor"]; ?>">
                                                            <i class="fa fa-clock-o"></i> <?php echo $status['nome']; ?>
                                                            <span style="float:right"><?php echo $e["elemento"]; ?></span>
                                                        </div>
                                                        <!--
                                                        <div class="col-sm-12 item-info">
                                                            <div class="item-info-box">
                                                                <h4>Dados Base</h4>
                                                                <p>Inicio: DD/MM/AAAA</p>
                                                                <p>Ultima Atualizacao: DD/MM/AAAA</p>
                                                            </div>
                                                        </div>
                                                        -->
                                                        <?php
                                                        $tipoTarefa = $POPElemento->getElementTypeById($e['elementoTipo']);
                                                        ?>
                                                        <div class="col-sm-4 item-info">
                                                            <div class="item-info-box">
                                                                <h4>Tarefa</h4>
                                                                <?php echo "<p>" . $tipoTarefa['nome'] . "</p>"; ?>
                                                            </div>
                                                        </div>

                                                        <?php
                                                        // Conteudo dos tipos de elemento + bases
                                                        foreach ($subE as $field) {
                                                            $nome = $nomesCampos[$field['nome']] ?? ($field["nome"] . ":404:");
                                                            ?>
                                                            <div class="col-sm-4 item-info">
                                                                <div class="item-info-box">
                                                                    <h4><?php echo $nome; ?></h4>
                                                                    <?php
                                                                    $class = $field['tipo'];
                                                                    if (class_exists($class)) {
                                                                        /** @var nomeTipo $var */
                                                                        $var = new $class($field['nome'], $field['valor'], $database, ["elemento" => $e["elemento"]]);
                                                                        echo $var->retornaHtmlExibicao();
                                                                    }
                                                                    else {
                                                                        echo "<p>Tipo " . $field['nome'] . "NAO Encontrado</p>";
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        <?php } ?>

                                                        <?php
                                                        // conteudo adicional dos elementos ( coisas q sao geradas
                                                        // dinamicamente e nao estao contidas em tipos e bases
                                                        foreach ($extra as $field) {
                                                            /*var_dump($field);
                                                            //exit;
                                                            ?>
                                                            <div class="col-sm-4 item-info">
                                                                <div class="item-info-box">
                                                                    <h4>Nome da Informação</h4>
                                                                    <p>OI</p>
                                                                </div>
                                                            </div>
                                                        <?php */
                                                        } ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-4">
                                                    <div class="box-header">
                                                        <h4 class="box-title">Chat</h4>
                                                    </div>
                                                    <div class="box-body chat historico-post" id="chat-box">
                                                        <?php
                                                        $chatProjeto = $POPChat->getProjetoChat($e['projeto'], $e['elemento']);
                                                        $tamanhoChat = count($chatProjeto);
                                                        $tmpChat = [];
                                                        if ($tamanhoChat < 2) {
                                                            $tmpChat = $chatProjeto;
                                                        }
                                                        else {
                                                            $t = $tamanhoChat;
                                                            $tmpChat = [$chatProjeto[$t - 2], $chatProjeto[$t - 1]];
                                                        }

                                                        foreach ($tmpChat as $itemChat) {
                                                            $var = new responsavel('primeiro-msg', $itemChat['responsavel'], $database);
                                                            ?>
                                                            <div class="item">
                                                                <?php echo $var->retornaHtmlExibicao(); ?>
                                                                <p class="message">
                                                                    <a href="#" class="name">
                                                                        <small title="<?php echo date('d/m/Y - H:i:s', strtotime($itemChat['data'])); ?>"
                                                                               class="text-muted pull-right">
                                                                            <i class="fa fa-clock-o"></i> <?php echo Utils::printDate(date('Y-m-d H:i:s', strtotime($itemChat['data']))); ?>
                                                                        </small>
                                                                        <?php echo $var->retornaNomeExibicao(); ?>
                                                                    </a>
                                                                    <?php
                                                                    $conteudo = Utils::insert_href(nl2br(strip_tags($itemChat['conteudo'])));
                                                                    echo $conteudo; ?><br>
                                                                    <br>
                                                                </p>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="ver-historico">
                                                        <a href="#" class="dpb js-getElemento"
                                                           data-elemento="<?php echo $e['elemento']; ?>"
                                                           data-toggle="modal" data-backdrop="static"
                                                           data-target="#modal-elemento"
                                                           data-acao="ver-historico-projeto">Todas Mensagens</a>
                                                        <a href="#" class="dpb js-getElemento"
                                                           data-elemento="<?php echo $e['elemento']; ?>"
                                                           data-toggle="modal" data-backdrop="static"
                                                           data-target="#modal-elemento"
                                                           data-acao="ver-historico-elemento-completo">Ver log</a>
                                                    </div>

                                                    <form class="form-comentario" action="" method="post">
                                                        <input name="projeto" value="<?php echo $e['projeto']; ?>"
                                                               type="hidden">
                                                        <input name="elemento" value="<?php echo $e['elemento']; ?>"
                                                               type="hidden">

                                                        <div class="form-group">
                                                            <label>Comentar</label>
                                                            <textarea name="comentario"
                                                                      placeholder="Digite um comentário..." rows="3"
                                                                      class="form-control"></textarea>
                                                        </div>
                                                        <div id="msg-erro"></div>
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <button class="btn btn-primary btn-flat border-0 pull-right"
                                                                        type="submit">Enviar comentário
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="col-lg-2 col-md-3" <?php if ($esconde_Botoes) {
                                                    echo 'style="display: none;"';
                                                } ?>>
                                                    <div class="item-btns">
                                                        <?php if (in_array($extra['area'], AREAS_CONTATO_CLIENTE) && $e['elementoStatus'] != 3) { ?>
                                                            <button class="btn btn-flat btn-block btn-cliente border-0 js-getElemento"
                                                                    data-elemento="<?php echo $e['elemento']; ?>"
                                                                    data-toggle="modal" data-backdrop="static"
                                                                    data-acao="aguardar-cliente"
                                                                    data-target="#modal-elemento">
                                                                <i class="fa fa-clock-o pull-left"></i> Enviado/Aguardar
                                                                Cliente
                                                            </button>
                                                        <?php } ?>
                                                        <button class="btn btn-block btn-flat btn-success border-0 js-getElemento"
                                                                data-elemento="<?php echo $e["elemento"]; ?>"
                                                                data-toggle="modal" data-backdrop="static"
                                                                data-target="#modal-elemento"
                                                                data-acao="aprovar-elemento" title="Finalizar Tarefa">
                                                            <i class="fa fa-thumbs-up pull-left"></i> Finalizar Tarefa
                                                        </button>
                                                        <button class="btn btn-block btn-flat btn-danger border-0 js-getElemento"
                                                                data-elemento="<?php echo $e["elemento"]; ?>"
                                                                data-toggle="modal" data-backdrop="static"
                                                                data-target="#modal-elemento"
                                                                data-acao="reprovar-elemento" title="Reprovar Tarefa">
                                                            <i class="fa fa-thumbs-down pull-left"></i> Reprovar Tarefa
                                                        </button>
                                                        <!--
														<button class="btn btn-block btn-flat btn-danger border-0 js-getElemento" data-elemento="<?php echo $e["elemento"]; ?>" data-toggle="modal"
														        data-backdrop="static" data-target="#modal-elemento" data-acao="apagar-elemento">
															<i class="fa fa-times pull-left"></i> Apagar Tarefa
														</button>
														-->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- /item -->
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </aside>
</div>

<!-- MODAL GERAL PROJETO -->
<div class="modal fade" id="modal-projeto" tabindex="-1" role="dialog" aria-hidden="true"></div>

<!-- MODAL GERAL ELEMENTO -->
<div class="modal fade" id="modal-elemento" tabindex="-1" role="dialog" aria-hidden="true"></div>

<!-- BOTÃO VOLTAR PARA O TOPO -->
<a href="#top-of-page">
    <div class="back-to-top bt-footer-hidden" data-toggle="tooltip" data-placement="top" title="Voltar para o topo">
        <i class="fa fa-arrow-up"></i>
    </div>
</a>

<?php if ($fazerTarefa) { ?>
    <!-- BOTÃO FINALIZAR TAREFA -->
    <a href="pop-minhas-tarefas.php?finaliza_tarefa=<?php echo $_GET['tarefa']; ?>">
        <div class="bt-finaliza-tarefa bt-footer-hidden" data-toggle="tooltip" data-placement="top"
             title="Finalizar tarefa">
            <i class="fa fa-check"></i>
        </div>
    </a>
<?php } ?>

<script type="text/javascript">
    var idUsuarioLogado = '<?php echo $dadosUsuario["usuarioNerdweb"]; ?>';
</script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="//code.jquery.com/ui/1.11.1/jquery-ui.min.js" type="text/javascript"></script>
<script src="../js/plugins/bootstrap/bootstrap.min.js" type="text/javascript"></script>
<script src="../js/plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="../js/plugins/datepicker/locales/bootstrap-datepicker.pt-BR.js" type="text/javascript"></script>
<script src="../js/plugins/iCheck/icheck.min.js" type="text/javascript"></script>
<script src="../js/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="../js/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
<script src="../js/plugins/trumbowyg/trumbowyg.min.js" type="text/javascript"></script>
<script src="../js/plugins/masonry/masonry.js" type="text/javascript"></script>
<script src="../js/plugins/masonry/imagesloaded.js" type="text/javascript"></script>
<script src="../js/plugins/light-gallery/js/lightgallery.min.js" type="text/javascript"></script>
<script src="../js/plugins/light-gallery/modules/lg-zoom.min.js" type="text/javascript"></script>
<script src="../js/plugins/light-gallery/modules/lg-video.min.js" type="text/javascript"></script>
<script src="../js/AdminLTE/app.js" type="text/javascript"></script>
<script src="../js/jqueryform.js" type="text/javascript"></script>
<script src="../js/backoffice.js" type="text/javascript"></script>
<script src="../js/pop.js" type="text/javascript"></script>

<script type="text/javascript">
    var lastModal = lastElement = lastProject = '';

    $(document).ready(function () {
        var $grid = $('.item-box .row .row').imagesLoaded(function () {
            // init Masonry after all images have loaded
            $grid.masonry({
                itemSelector: '.item-info',
                stamp: '.stamp'
            });
        });
    });

    // clique nos botões que pegam informações do elemento
    $(document).on('click', '.js-getElemento', function (event) {
        event.preventDefault();

        var idModal = $(this).attr('data-target');
        var idElemento = $(this).attr('data-elemento');
        var acao = $(this).attr('data-acao');

        var loadModal = true;
        if (acao === lastModal && idElemento === lastElement) {
            loadModal = false;
        }
        lastModal = acao;
        lastElement = idElemento;
        lastProject = '';

        if (loadModal) {
            $(idModal).hide();
            $.ajax({
                url: 'ajax/ajax-pop.php',
                type: 'POST',
                data: {
                    secao: 'elemento',
                    tipo: acao,
                    elemento: idElemento
                }
            })
                .done(function (retorno) {
                    if (retorno.tipo === 'html') {
                        $(idModal).html(retorno.conteudo);
                        $(idModal).show();
                    } else {
                        console.log(retorno);
                    }
                })
                .fail(function (retorno) {
                    console.log(retorno);
                });
        }
    });

    $(document).on('click', '.js-getProjeto', function (event) {
        event.preventDefault();

        var idModal = $(this).attr('data-target');
        var idProjeto = $(this).attr('data-projeto');
        var acao = $(this).attr('data-acao');

        var loadModal = true;
        if (acao === lastModal && idProjeto === lastProject) {
            loadModal = false;
        }
        lastModal = acao;
        lastProject = idProjeto;
        lastElement = '';

        if (loadModal) {
            $(idModal).hide();
            $.ajax({
                url: 'ajax/ajax-pop.php',
                type: 'POST',
                data: {
                    secao: 'projeto',
                    tipo: acao,
                    projeto: idProjeto
                }
            })
                .done(function (retorno) {
                    if (retorno.tipo === 'html') {
                        $(idModal).html(retorno.conteudo);
                        $(idModal).show();
                    } else {
                        console.log(retorno);
                    }
                })
                .fail(function (retorno) {
                    console.log(retorno);
                });
        }
    });

    $(document).on('click', '.js-bloco-pagina', function (event) {
        var $button = $(this);
        var blocoPagina = $button.parent().find('.bloco-pagina:last-of-type').clone();

        $button.parent().find('.bloco-pagina:last-of-type').after(blocoPagina[0].outerHTML);
    });

    // remove etapas e campos no clique no X
    $(document).on('click', '.js-remove-pagina', function (event) {
        event.preventDefault();
        $(this).parent().remove();
    });

    // submit do formulário de comentários
    $(document).on('submit', '.form-comentario', function (event) {
        event.preventDefault();
        var dados = new FormData(this);
        dados.append('secao', 'projeto');
        dados.append('form', 'salva-comentario');
        //var dados = $(this).serializeArray();
        var form = $(this);

        $.ajax({
            url: 'ajax/ajax-pop.php',
            type: 'POST',
            data: dados,
            processData: false,
            contentType: false
        })
            .done(function (retorno) {
                if (retorno.tipo === 'html') {
                    form.parent().find('.chat').html(retorno.conteudo);
                    form.find('textarea[name=comentario]').val('');
                } else if (retorno.tipo === 'msg') {
                    alert(retorno.conteudo);
                }
            })
            .fail(function (retorno) {
            });
    });
</script>
</body>
</html>
