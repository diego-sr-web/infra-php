<?php
require_once __DIR__ . "/../../autoloader.php";

$database = new Database();
$databaseTransactional = new Database(TRUE);
$usuario = new AdmUsuario($database);
$POPProjeto = new Projeto($database);
$POPElemento = new Elemento($database);
$POPChat = new Chat($database);
$BNCliente = new BNCliente($database);

require_once __DIR__ . '/../includes/is_logged.php';

$dadosUsuario = $usuario->getUserDataWithId($_SESSION['adm_usuario']);
$areasUsuario = $usuario->getAreasUsuario($_SESSION['adm_usuario']);

require_once __DIR__ . "/pop-projeto-process-post.php";

$infoProjeto = $POPProjeto->getProjectById($_GET['projeto']);
$infoTipoProjeto = $POPProjeto->getProjectTypeById($infoProjeto['projetoTipo']);
$cliente = $BNCliente->getDataWithId($infoProjeto['cliente']);

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

if (isset($_GET["tarefas"]) && $_GET["tarefas"] == TRUE) {
    $esconde_Botoes = FALSE;
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
        if (isset($value["elementoStatus"]) && $value["elementoStatus"] == 4) {
            if (isset($value["campos"]["responsavel"]) && $value["campos"]["responsavel"] == $_SESSION["adm_usuario"]) {
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

$chatProjeto = $POPChat->getProjetoChat($_GET['projeto']);
$tamanhoChat = count($chatProjeto);
$tmpChat = [];
if ($tamanhoChat == 0) {

}
elseif ($tamanhoChat < 2) {
    $tmpChat = $chatProjeto;
}
else {
    $t = $tamanhoChat;
    $tmpChat = [$chatProjeto[$t - 2], $chatProjeto[$t - 1]];

}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Projeto <?php echo $infoProjeto['nome']; ?> | POP Nerdweb</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="../css/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css"/>
    <link href="../css/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>
    <link href="../css/trumbowyg/trumbowyg.min.css" rel="stylesheet" type="text/css"/>
    <link href="../css/nivo/css_nivo_lightbox.css" rel="stylesheet" type="text/css"/>
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
            <?php require_once  __DIR__ . "/include/sucesso_error.php"?>

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-solid">
                        <div class="box-header">
                            <i class="fa fa-cubes"></i>
                            <h3 class="box-title">Dados do Projeto</h3>

                            <?php if ($infoProjeto['finalizado'] != 1) { ?>

                                <a class="btn btn-primary btn-flat btn-addnew border-0 js-getElemento"
                                   data-elemento="<?php echo $botao_briefing[0]["elemento"]; ?>"
                                   data-toggle="modal" data-backdrop="static"
                                   data-target="#modal-elemento" data-acao="adicionar-briefing"
                                   title="Briefing">
                                    <i class="fa fa-file-text pull-left"></i> Briefing
                                </a>

                                <a class="btn btn-primary btn-flat btn-addnew border-0 js-getElemento"
                                   data-elemento="<?php echo $botao_briefing[0]["elemento"]; ?>"
                                   data-toggle="modal" data-backdrop="static"
                                   data-target="#modal-elemento" data-acao="adicionar-material-extra"
                                   title="Adicionar Material Extra">
                                    <i class="fa fa-file-text pull-left"></i> Material Extra
                                </a>


                                <a class="btn btn-primary btn-flat btn-addnew border-0" href="#"
                                   data-projeto="<?php echo $infoProjeto['projeto']; ?>"
                                   data-target="#modal-arquivar-projeto" data-toggle="modal" data-backdrop="static">
                                    <i class="fa fa-archive pull-left font-16"></i> Arquivar Projeto
                                </a>
                                <a class="btn btn-primary btn-flat btn-addnew border-0" href="#"
                                   data-projeto="<?php echo $infoProjeto['projeto']; ?>"
                                   data-target="#modal-editar-projeto" data-toggle="modal" data-backdrop="static">
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
                                                <button class="btn btn-primary btn-flat btn-addnew border-0"
                                                        data-toggle="modal" data-backdrop="static"
                                                        data-target="#modal-adicionar-pagina"><i
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
                                foreach ($copiaElementosProjeto as $e) {
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
                                                <div class="col-lg-12 col-md-5 item-info-list">
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

<!-- MODAL EDITAR SITE - FORM editar-projeto -->
<div class="modal fade" id="modal-editar-projeto" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Editar projeto</h4>
            </div>
            <form action="" method="post">
                <input type="hidden" name="form" value="editar-projeto"/>
                <input type="hidden" name="projeto" value="<?php echo $_GET["projeto"]; ?>"/>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nome do Projeto</label>
                        <input type="text" name="nome" spellcheck="true" class="form-control"
                               value="<?php echo $infoProjeto['nome']; ?>"/>
                    </div>
                    <div class="form-group">
                        <label>Data de Entrada do Projeto</label>
                        <input type="text" name="dataEntrada" class="form-control datepicker" data-date-language="pt-BR"
                               data-provide="datepicker" data-date-format="yyyy-mm-dd"
                               value="<?php echo $infoProjeto['dataEntrada']; ?>"/>
                    </div>
                    <div class="form-group">
                        <label>Prazo Final de Entrega</label>
                        <input type="text" name="prazo" class="form-control datepicker" data-date-language="pt-BR"
                               data-provide="datepicker" data-date-format="yyyy-mm-dd"
                               value="<?php echo $infoProjeto['prazo']; ?>"/>
                    </div>
                </div>
                <div class="modal-footer clearfix">
                    <button type="button" class="btn btn-danger btn-flat border-0 pull-left" data-dismiss="modal"><i
                                class="fa fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary btn-flat border-0"><i class="fa fa-save"></i> Salvar
                    </button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- MODAL FINALIZAR/ARQUIVAR SITE - FORM arquivar-projeto -->
<div class="modal fade" id="modal-arquivar-projeto" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Finalizar/arquivar projeto - <?php echo $infoProjeto['nome']; ?></h4>
            </div>
            <form action="" method="post">
                <input type="hidden" name="form" value="arquivar-projeto"/>
                <input type="hidden" name="projeto" value="<?php echo $_GET['projeto']; ?>"/>
                <div class="modal-body">
                    <div class="txt-center" style="padding: 20px;">
                        Tem certeza que deseja finalizar o projeto <b><?php echo $infoProjeto['nome']; ?></b>?
                    </div>
                </div>
                <div class="modal-footer clearfix">
                    <button type="button" class="btn btn-danger btn-flat border-0 pull-left" data-dismiss="modal"><i
                                class="fa fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary btn-flat border-0"><i class="fa fa-check"></i>
                        Finalizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- MODAL GERAL ELEMENTO -->
<div class="modal fade" id="modal-elemento" tabindex="-1" role="dialog" aria-hidden="true"></div>

<!-- MODAL NOVA PÁGINA - FORM adicionar-pagina -->
<div class="modal fade" id="modal-adicionar-pagina" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Cadastrar nova página interna</h4>
            </div>
            <form action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="form" value="adicionar-pagina"/>
                <input type="hidden" name="projeto" value="<?php echo $_GET['projeto']; ?>"/>
                <div class="modal-body blocos-pagina">
                    <div class="bloco-pagina">
                        <div class="js-remove-pagina bt-remove-etapa"><i class="fa fa-remove"
                                                                         title="Remover Página"></i></div>
                        <div class="form-group">
                            <label>Nome da Página</label>
                            <input type="text" name="nome[]" spellcheck="true" class="form-control" required
                                   placeholder="Nome da Página"/>
                        </div>
                        <!--
                        <div class="form-group">
                            <label>Descrição da Página</label>
                            <textarea name="descricao[]" class="form-control textarea" placeholder="Descrição da Página" style="height:100px;"></textarea>
                        </div> -->
                        <input type="hidden" name="descricao[]" value=""/>
                    </div>

                    <button class="btn btn-primary btn-block btn-flat js-bloco-pagina btn-bloco-pagina" type="button">
                        Adicionar nova página
                    </button>


                    <!--
                    <div class="form-group">
                        <label>Layout</label>
                        <div class="input-group">
                            <span class="input-group-btn">
                                <span class="btn btn-primary btn-file btn-flat border-0">
                                    Procurar&hellip; <input type="file" class="upload-preview" name="imagem">
                                </span>
                            </span>
                            <input type="text" class="form-control" disabled="disabled" readonly>
                        </div>
                        <p class="help-block">Max. 5MB</p>
                        <img class="img-preview" style="width:100%; display:none;" src=""/>
                    </div>
                    <div class="form-group">
                        <label>Observações</label>
                        <textarea name="observacao" class="form-control" style="height:120px;"></textarea>
                    </div>
                    -->
                </div>
                <div class="modal-footer clearfix">
                    <button type="button" class="btn btn-danger btn-flat border-0 pull-left" data-dismiss="modal"><i
                                class="fa fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary btn-flat border-0"><i class="fa fa-save"></i> Salvar
                    </button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- BOTÃO VOLTAR PARA O TOPO -->
<a href="#top-of-page">
    <div class="back-to-top bt-footer-hidden" data-toggle="tooltip" data-placement="top" title="Voltar para o topo">
        <i class="fa fa-arrow-up"></i>
    </div>
</a>

<?php if ($fazerTarefa) { ?>
    <!--
    <div class="bottom-msg-fixed">
        <p>Você está fazendo a tarefa de <b>TAREFA TAREFA TAREFA</b>.</p>
    </div>
    -->
    <!-- BOTÃO FINALIZAR TAREFA -->
    <a href="pop-minhas-tarefas.php?finaliza_tarefa=<?php echo $_GET['tarefa']; ?>">
        <div class="bt-finaliza-tarefa bt-footer-hidden" data-toggle="tooltip" data-placement="top"
             title="Finalizar tarefa">
            <i class="fa fa-check"></i>
        </div>
    </a>
<?php } ?>

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
<script src="../js/plugins/nivo-lightbox/nivo-lightbox.js" type="text/javascript"></script>
<script src="../js/AdminLTE/app.js" type="text/javascript"></script>
<script src="../js/jqueryform.js" type="text/javascript"></script>
<script src="../js/backoffice.js" type="text/javascript"></script>
<script src="../js/pop.js" type="text/javascript"></script>

<script type="text/javascript">
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
                if (retorno.tipo == 'html') {
                    $(idModal).html(retorno.conteudo);
                    $(idModal).show();
                } else {
                    console.log(retorno);
                }
            })
            .fail(function (retorno) {
                console.log(retorno);
            });
    });

    $(document).on('click', '.js-getProjeto', function (event) {
        event.preventDefault();

        var idModal = $(this).attr('data-target');
        var idElemento = $(this).attr('data-elemento');
        var acao = $(this).attr('data-acao');

        $(idModal).hide();

        $.ajax({
            url: 'ajax/ajax-pop.php',
            type: 'POST',
            data: {
                secao: 'projeto',
                tipo: acao,
                projeto: idElemento
            }
        })
            .done(function (retorno) {
                if (retorno.tipo == 'html') {
                    $(idModal).html(retorno.conteudo);
                    $(idModal).show();
                } else {
                    console.log(retorno);
                }
            })
            .fail(function (retorno) {
                console.log(retorno);
            });
    });

    $(document).on('click', '.js-bloco-pagina', function (event) {
        var $button = $(this);
        var bloco = '<div class="bloco-pagina">' +
            '<div class="js-remove-pagina bt-remove-etapa"><i class="fa fa-remove" title="Remover Página"></i></div>' +
            '<div class="form-group">' +
            '<label>Nome da Página</label>' +
            '<input type="text" name="nome[]" spellcheck="true" class="form-control" required placeholder="Nome da Página" />' +
            '</div>' +
            '<div class="form-group">' +
            '<label>Descrição da Página</label>' +
            '<textarea name="descricao[]" class="form-control textarea" placeholder="Descrição da Página" style="height:100px;"></textarea>' +
            '</div>' +
            '</div>';

        $button.parent().find('.bloco-pagina:last-of-type').after(bloco);
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
            contentType: false,
        })
            .done(function (retorno) {
                if (retorno.tipo == 'html') {
                    form.parent().find('.chat').html(retorno.conteudo);
                    form.find('textarea[name=comentario]').val('');
                } else if (retorno.tipo == 'msg') {
                    alert(retorno.conteudo);
                }
            })
            .fail(function (retorno) {
            });
    });
</script>
</body>
</html>
