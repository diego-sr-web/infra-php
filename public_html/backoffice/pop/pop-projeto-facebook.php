<?php
$identifier = "POP_PROJETO_CAMPANHA_FACEBOOK";
require_once __DIR__ . "/pop-projeto-include.php";

$listaCategorias = $POPElemento->getCategoryList();
$mesCampanha = '';

if (isset($infoProjeto['campos']['mes']) && $infoProjeto['campos']['mes']) {
    $mesCampanha = str_replace('/', '-', $infoProjeto['campos']['mes']);
    $mesCampanha = '01-' . $mesCampanha;
}

$jsProgramacaoCampanha = $categoriasProgramacao = [];

if (isset($infoProjeto['campos']['planejamento']) && $infoProjeto['campos']['planejamento']) {
    $programacaoCampanha = json_decode($infoProjeto['campos']['planejamento'], TRUE);
    if ($programacaoCampanha !== '[]') {
        foreach ($programacaoCampanha as $progCamp) {
            if ($progCamp) {
                $infoCategoria = $POPElemento->getCategoryById($progCamp['categoria']);

                //categorias usadas na campanha, usar no select de criação de novos posts
                if (!in_array($infoCategoria, $categoriasProgramacao)) {
                    $categoriasProgramacao[] = $infoCategoria;
                }

                // programação da campanha para o calendário
                /** @var string $dataProg */
                $dataProg = date('m-d-Y', strtotime($progCamp['data']));
                $diaProg = date('d', strtotime($progCamp['data']));
                // html que estará no dia do calendário
                $jsProgramacaoCampanha[$dataProg] = '<div style=\"background-color:' . $infoCategoria['cor'] . ';\"></div><div data-index=\"' . (int)$diaProg . '\" class=\"day-actions\"><a class=\"js-remove-programacao\" title=\"Apagar\"><i class=\"fa fa-close\"></i></a></div>';
            }
        }
    }
}
else {
    $programacaoCampanha = [];
}

$jsProgramacaoCampanha = json_encode($jsProgramacaoCampanha);

$referencia = [];
foreach ($elementosProjeto as $elemento) {
    if ($elemento["elementoTipo"] == 37) {
        $referencia = $elemento;
    }
}

$auxTarefas = $POPElemento->filtraElementosPorBase($elementosProjeto, 1);

foreach ($auxTarefas as $tmp) {
    if (!isset($tmp['campos']['dtFim'])) {
        $tmp['campos']['dtFim'] = NULL;
    }
    if ($tmp['campos']['dtFim'] == NULL) {
        $tarefasProjeto[] = $tmp;
    }
}

$tarefasProjeto = $POPElemento->agrupaTarefas($tarefasProjeto);
$tarefasProjeto = $tarefasProjeto['tarefas'];

foreach ($elementosProjeto as $aux) {
    $tipoElem = $POPElemento->getElementTypeByElementId($aux['elemento']);
}
$fazerTarefa = FALSE;
if ((isset($_GET['tarefa']) && $_GET['tarefa'])) {
    $fazerTarefa = TRUE;
}
foreach ($elementosProjeto as $key => $value) {
    $elementosProjeto[$key]["sort"] = 0;
    if (isset($value["campos"]["Dia"])) {
        $elementosProjeto[$key]["sort"] = strtotime($value["campos"]["Dia"]);
    }
}

$elementosProjeto = Utils::sksort($elementosProjeto, "sort", TRUE);
// Processamento dos botoes de adicionar/remover/aprovar/reprovar
require_once __DIR__ . "/pop-processa-modal.php";
?><!DOCTYPE html>
<html>
<head>

    <?php require_once __DIR__ . "/pop-projeto-include-header.php"; ?>

    <link href="../css/calendario/calendar.css" rel="stylesheet" type="text/css"/>
    <link href="../css/calendario/custom_2.css" rel="stylesheet" type="text/css"/>

</head>
<body class="skin-black fixed">
<?php require_once __DIR__ . '/include/head.php'; ?>
<div id="top-of-page" class="wrapper row-offcanvas row-offcanvas-left">
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="left-side sidebar-offcanvas">
        <?php require_once __DIR__ . '/include/sidebar.php'; ?>
    </aside>
    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Projeto <?php echo $infoProjeto['nome'] ?></h1>

            <ol class="breadcrumb">
                <li><a href="pop-minhas-tarefas.php"><i class="fa fa-folder-open"></i> Minhas Tarefas</a></li>
                <!--<li class="active">Projeto <?php echo $infoProjeto['nome']; ?></li>-->
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
                                <div class="btn-group" style="float: right;">
                                    <button data-toggle="dropdown"
                                            class="btn btn-primary btn-flat btn-addnew dropdown-toggle btn-dropdown border-0"
                                            style="float:right;margin-top:10px" type="button">
                                        <i class="fa fa-print pull-left font-16"></i>Imprimir &nbsp;<span
                                                class="caret"></span>
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <ul role="menu" class="dropdown-menu with-submenu dropdown-imprimir">
                                        <li>
                                            <a href="pop-projeto-facebook-impressao.php?projeto=<?php echo $infoProjeto['projeto']; ?>"
                                               target="_blank">Para aprovação (Grid)</a></li>
                                        <li>
                                            <a href="pop-projeto-facebook-impressao.php?projeto=<?php echo $infoProjeto['projeto']; ?>&completo"
                                               target="_blank">Lista completa (Grid)</a></li>
                                        <li>
                                            <a href="pop-projeto-facebook-impressao-lista.php?projeto=<?php echo $infoProjeto['projeto']; ?>"
                                               target="_blank">Para aprovação (Lista)</a></li>
                                        <li>
                                            <a href="pop-projeto-facebook-impressao-lista.php?projeto=<?php echo $infoProjeto['projeto']; ?>&completo"
                                               target="_blank">Lista completa (Lista)</a></li>
                                    </ul>
                                </div>
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
                                        <dt>Tipo de Projeto:</dt>
                                        <dd class="dado-destaque"
                                            style="color: <?php echo $infoTipoProjeto['cor']; ?>"><?php echo $infoTipoProjeto['nome']; ?></dd>
                                        <br/>
                                        <dt>Cliente:</dt>
                                        <dd><?php echo ($cliente) ? $cliente['nomeFantasia'] : 'Cliente não cadastrado'; ?></dd>
                                        <br/>
                                        <dt>Data de Entrada:</dt>
                                        <dd><?php echo $dataEntrada->retornaHtmlExibicao(); ?></dd>
                                        <br/>
                                        <dt>Prazo Final:</dt>
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
                                                $tipoTarefa = $POPElemento->getElementTypeById($tarefa['elementoTipo']);
                                                /** @var array $infoSubetapa */
                                                $infoSubetapa = FALSE;
                                                if (isset($tarefa['campos']['Etapa'])) {
                                                    $infoSubetapa = $POPElemento->get_SubEtapa_Info($tarefa['elementoTipo'], $tarefa['campos']['Etapa']);
                                                }
                                                ?>
                                                <dd class="dado-destaque">
                                                    <div class="row">
                                                        <div class="col-sm-5">
                                                            <?php echo $infoSubetapa ? $infoSubetapa['nome'] : $tipoTarefa['nome']; ?>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <?php $areaResponsavel = new area('area', $tarefa['campos']['area'], $database); ?>
                                                            <?php echo $areaResponsavel->retornaHtmlExibicao(); ?>
                                                        </div>
                                                        <div class="col-sm-3">
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
                            <i class="fa fa-calendar"></i>
                            <h3 class="box-title">Planejamento da Campanha</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="custom-calendar-wrap" style="width: 100%; float: left;">
                                        <div id="custom-inner" class="custom-inner">
                                            <div class="custom-header clearfix">
                                                <h2 id="custom-month" class="custom-month"></h2>
                                                <h3 id="custom-year" class="custom-year"></h3>
                                            </div>
                                            <div id="calendar" class="fc-calendar-container"></div>
                                        </div>
                                        <button style="margin-top: 5px;"
                                                class="btn btn-flat btn-block btn-primary js-getProjeto"
                                                data-toggle="modal" data-backdrop="static"
                                                data-projeto="<?php echo $infoProjeto['projeto']; ?>"
                                                data-target="#modal-projeto" data-acao="novo-programacao-facebook">
                                            Adicionar categoria às datas selecionadas
                                        </button>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12" style="margin-bottom: 10px;">Legenda (categorias):</div>
                                <?php
                                if (isset($categoriasProgramacao) && $categoriasProgramacao) {
                                    foreach ($categoriasProgramacao as $categoria) { ?>
                                        <div class="col-sm-2">
                                            <i class="fa <?php echo $categoria["icone"]; ?>"
                                               style="font-size: 20px; color: <?php echo $categoria["cor"]; ?>;"></i>
                                            <span style="margin-left:5px;"><?php echo $categoria["nome"]; ?></span>
                                        </div>
                                        <?php
                                    }
                                } else { ?>
                                <div class="col-sm-12">
                                    <p class="txt-center">Não há categorias para exibir, faça o planejamento da
                                        campanha.</p>
                                    <div>
                                        <?php } ?>
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
                                    <h3 class="box-title">Posts da Campanha</h3>
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-sm-12 filtros">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="acao-bts">
                                                        <button class="btn btn-primary btn-flat btn-addnew border-0 js-getProjeto"
                                                                data-toggle="modal" data-backdrop="static"
                                                                data-target="#modal-projeto"
                                                                data-acao="novo-post-facebook"
                                                                data-projeto="<?php echo $infoProjeto['projeto']; ?>"><i
                                                                    class="fa fa-plus"></i>
                                                            Adicionar Novo Post
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="col-sm-8"></div>
                                            </div>
                                        </div><!-- /filtros -->
                                    </div><!-- /row filtros -->
                                    <div class="row">
                                        <?php foreach ($elementosProjeto as $e) {
                                            $extra = $e["campos"];
                                            $status = $POPElemento->getElementStatusById($e["elementoStatus"]);
                                            $tipo = $POPElemento->getElementTypeById($e["elementoTipo"]);
                                            $areaElemento = $e["campos"]['area'];

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
                                            foreach ($sub_TMP as $tmp) {
                                                $subE[] = $tmp;
                                            }
                                            ksort($subE);
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
                                                                // Conteudo dos tipos de elemento + bases
                                                                foreach ($subE as $field) {
                                                                    ?>
                                                                    <div class="col-sm-4 item-info">
                                                                        <div class="item-info-box">
                                                                            <h4><?php echo $nomesCampos[$field['nome']]; ?></h4>
                                                                            <?php
                                                                            $class = $field['tipo'];
                                                                            //																				var_dump($field);

                                                                            if (class_exists($class)) {
                                                                                /** @var nomeTipo $var */
                                                                                $var = new $class($field['nome'], $field['valor'], $database, ["elemento" => $e["elemento"]]);
                                                                                echo $var->retornaHtmlExibicao();

                                                                            }
                                                                            else {
                                                                                echo "<p>Tipo Nao Encontrado</p>";
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
                                                                }
                                                                ?>
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
                                                                   data-acao="ver-historico-elemento-completo">Ver
                                                                    log</a>
                                                            </div>

                                                            <form class="form-comentario" action="" method="post">
                                                                <input name="projeto"
                                                                       value="<?php echo $e['projeto']; ?>"
                                                                       type="hidden">
                                                                <input name="elemento"
                                                                       value="<?php echo $e['elemento']; ?>"
                                                                       type="hidden">

                                                                <div class="form-group">
                                                                    <label>Comentar</label>
                                                                    <textarea name="comentario"
                                                                              placeholder="Digite um comentário..."
                                                                              rows="3" class="form-control"></textarea>
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
                                                                <!--
																<button class="btn btn-block btn-flat btn-primary border-0 js-getElemento" data-elemento="<?php //echo $referencia['elemento']; ?>"
																	data-toggle="modal" data-backdrop="static" data-target="#modal-elemento" data-acao="ver-referencias" title="Ver Referências">
																	<i class="fa fa-pencil pull-left"></i> Ver Referências
																</button>
																-->
                                                                <button class="btn btn-block btn-flat btn-primary border-0 js-getElemento"
                                                                        data-elemento="<?php echo $e['elemento']; ?>"
                                                                        data-toggle="modal" data-backdrop="static"
                                                                        data-target="#modal-elemento"
                                                                        data-acao="editar-elemento-facebook"
                                                                        title="Editar Planejamento">
                                                                    <i class="fa fa-pencil pull-left"></i> Editar
                                                                    Planejamento
                                                                </button>
                                                                <button class="btn btn-block btn-flat btn-primary border-0 js-getElemento"
                                                                        data-elemento="<?php echo $e['elemento']; ?>"
                                                                        data-toggle="modal" data-backdrop="static"
                                                                        data-target="#modal-elemento"
                                                                        data-acao="adicionar-imagem"
                                                                        title="<?php echo $POPElemento->adicionarOuAlterar($e, 'image', 'Imagem'); ?>">
                                                                    <i class="fa fa-image pull-left"></i> <?php echo $POPElemento->adicionarOuAlterar($e, 'image', 'Imagem'); ?>
                                                                </button>
                                                                <button class="btn btn-block btn-flat btn-primary border-0 js-getElemento"
                                                                        data-elemento="<?php echo $e['elemento']; ?>"
                                                                        data-toggle="modal" data-backdrop="static"
                                                                        data-target="#modal-elemento"
                                                                        data-acao="adicionar-texto-post"
                                                                        title="<?php echo $POPElemento->adicionarOuAlterar($e, 'Texto', 'Texto'); ?>">
                                                                    <i class="fa fa-file-text pull-left"></i> <?php echo $POPElemento->adicionarOuAlterar($e, 'Texto', 'Texto'); ?>
                                                                </button>
                                                                <?php if (in_array($areaElemento, AREAS_CONTATO_CLIENTE) && $e['elementoStatus'] != 3) { ?>
                                                                    <button class="btn btn-flat btn-block btn-cliente border-0 js-getElemento"
                                                                            data-elemento="<?php echo $e['elemento']; ?>"
                                                                            data-toggle="modal"
                                                                            data-backdrop="static"
                                                                            data-acao="aguardar-cliente"
                                                                            data-target="#modal-elemento">
                                                                        <i class="fa fa-clock-o pull-left"></i>
                                                                        Enviado/Aguardar Cliente
                                                                    </button>
                                                                <?php } ?>
                                                                <button class="btn btn-block btn-flat btn-success border-0 js-getElemento"
                                                                        data-elemento="<?php echo $e['elemento']; ?>"
                                                                        data-toggle="modal" data-backdrop="static"
                                                                        data-target="#modal-elemento"
                                                                        data-acao="aprovar-elemento"
                                                                        title="Finalizar Tarefa">
                                                                    <i class="fa fa-thumbs-up pull-left"></i> Finalizar
                                                                    Tarefa
                                                                </button>
                                                                <button class="btn btn-block btn-flat btn-danger border-0 js-getElemento"
                                                                        data-elemento="<?php echo $e['elemento']; ?>"
                                                                        data-toggle="modal" data-backdrop="static"
                                                                        data-target="#modal-elemento"
                                                                        data-acao="reprovar-elemento"
                                                                        title="Reprovar Tarefa">
                                                                    <i class="fa fa-thumbs-down pull-left"></i> Reprovar
                                                                    Tarefa
                                                                </button>
                                                                <button class="btn btn-block btn-flat btn-danger border-0 js-getElemento"
                                                                        data-elemento="<?php echo $e['elemento']; ?>"
                                                                        data-toggle="modal" data-backdrop="static"
                                                                        data-target="#modal-elemento"
                                                                        data-acao="apagar-elemento"
                                                                        title="Apagar Tarefa">
                                                                    <i class="fa fa-times pull-left"></i> Apagar Tarefa
                                                                </button>
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
                </div>
            </div>
        </section>
    </aside>
</div>

<?php require_once __DIR__ . "/pop-projeto-include-footer.php"; ?>

<script src="../js/plugins/calendario/modernizr.custom.js" type="text/javascript"></script>
<script src="../js/plugins/calendario/jquery.calendario.js" type="text/javascript"></script>

<?php require_once __DIR__ . "/pop-projeto-include-footer-script.php"; ?>

<script type="text/javascript">
    var programacaoDefault = '<?php echo $jsProgramacaoCampanha; ?>';
    programacaoDefault = JSON.parse(programacaoDefault);
    var mesCampanha = '<?php if ((isset($mesCampanha) && $mesCampanha)) {
        echo date('m', strtotime($mesCampanha));
    } else {
        echo 1;
    }?>';
    var anoCampanha = '<?php if ((isset($mesCampanha) && $mesCampanha)) {
        echo date('Y', strtotime($mesCampanha));
    } else {
        echo 2016;
    }?>';

    var projeto = '<?php echo $infoProjeto['projeto']; ?>';
    var datasSelecionadas = [];
    var elementosSelecionados = [];
    var programacao = '<?php echo json_encode($programacaoCampanha); ?>';
    programacao = JSON.parse(programacao);
    var divActions = '<div class="day-actions">' +
        '<a class="js-remove-programacao" href="#" title="Apagar"><i class="fa fa-close"></i></a>' +
        '</div>';

    /* funcionalidades do calendário */
    $(function () {
        var transEndEventNames = {
                'WebkitTransition': 'webkitTransitionEnd',
                'MozTransition': 'transitionend',
                'OTransition': 'oTransitionEnd',
                'msTransition': 'MSTransitionEnd',
                'transition': 'transitionend'
            },
            transEndEventName = transEndEventNames[Modernizr.prefixed('transition')],
            $wrapper = $('#custom-inner'),
            $calendar = $('#calendar'),
            cal = $calendar.calendario({
                onDayClick: function ($el, $contentEl, dateProperties) {
                    if (!$el.hasClass('fc-content')) {
                        var dataEvento = dateProperties.year + '-' + dateProperties.month + '-' + dateProperties.day;

                        if (!datasSelecionadas[dateProperties.day] && !elementosSelecionados[dateProperties.day]) {
                            datasSelecionadas[dateProperties.day] = {data: dataEvento};
                            elementosSelecionados[dateProperties.day] = $el;
                            $el.addClass('data-selecionada');
                        } else {
                            datasSelecionadas.splice(dateProperties.day, 1);
                            elementosSelecionados.splice(dateProperties.day, 1);
                            $el.removeClass('data-selecionada');
                        }
                    }
                },
                caldata: programacaoDefault,
                weeks: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
                weekabbrs: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
                months: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                monthabbrs: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                displayWeekAbbr: true,
                startIn: 0,
                month: mesCampanha,
                year: anoCampanha
            }),

            $month = $('#custom-month').html(cal.getMonthName()),
            $year = $('#custom-year').html(cal.getYear());
    });


    /* ********************* SUBMITS ********************* */
    // adicionar categoria à data
    $(document).on('submit', '#js-add-programacao', function (e) {
        e.preventDefault();
        var categoria = $(this).find('select[name=categoriaCampanha]').val();
        var categoriaInfo;

        $.ajax({
            url: 'ajax/ajax-pop.php',
            type: 'POST',
            data: {
                secao: 'categoria',
                tipo: 'ver-categoria',
                categoria: categoria
            }
        })
            .done(function (retorno) {
                categoriaInfo = retorno;
                categoriaInfo = JSON.parse(categoriaInfo.conteudo);

                // coloca categoria noo array com a data e insere no array de programacao
                datasSelecionadas.forEach(function (data, index) {
                    data.categoria = categoria;
                    programacao[index] = data;
                });

                // faz as maracutaias com o dia do calendário, pra aparecer a cor e o icone de excluir
                elementosSelecionados.forEach(function ($el, index) {
                    $el.append('<div class="day-event" data-index="' + index + '"><div style="background-color:' + categoriaInfo.cor + '"></div>' + divActions + '</div>');
                    $el.find('.day-actions').attr('data-index', index);
                    $el.addClass('fc-content');
                    $el.removeClass('data-selecionada');
                });

                // limpa as variáveis
                datasSelecionadas = [];
                elementosSelecionados = [];

                // salva o conteudo
                quickSave();

                // esconde a modal
                $('#modal-projeto').modal('hide');
                $('#js-add-programacao')[0].reset();

            })
            .fail(function (retorno) {
                console.log(retorno);
            });
    });

    /* ********************* CLICKS ********************* */
    // apagar categoria da data
    $(document).on('click', '.js-remove-programacao', function (e) {
        e.preventDefault();
        e.stopPropagation();

        // pega o indice do array que está num atributo
        var $elementoData = $(this).parent().parent().parent();
        var indice = $elementoData.find('.day-actions').attr('data-index');

        // faz as coisas necessárias para apagar o conteudo e remover os html
        delete programacao[indice];
        $elementoData.removeClass('fc-content');
        $elementoData.find('.day-event').remove();

        quickSave();
    });

    $(document).on('click', '.js-bloco-pagina', function (event) {
        var $button = $(this);
        var blocoPost = $button.parent().find('.bloco-pagina:last-of-type').clone();
        $button.parent().find('.bloco-pagina:last-of-type').after(blocoPost[0].outerHTML);
    });

    // remove etapas e campos no clique no X
    $(document).on('click', '.js-remove-pagina', function (event) {
        event.preventDefault();
        $(this).parent().remove();
    });

    // salva o json no banco
    function quickSave() {
        $.ajax({
            url: 'ajax/ajax-pop.php',
            type: 'POST',
            data: {
                secao: 'projeto',
                tipo: 'salva-programacao',
                programacao: programacao,
                projeto: projeto
            }
        })
            .done(function (retorno) {
                //console.log(retorno);
            })
            .fail(function (retorno) {
                console.log('ERRO:');
                console.log(retorno);
            });
    }
</script>
</body>
</html>
