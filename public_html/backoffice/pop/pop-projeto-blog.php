<?php
$identifier = "POP_PROJETO_PRODUCAO_BLOG";
require_once __DIR__ . "/pop-projeto-include.php";

$auxTarefas = $POPElemento->filtraElementosPorBase($elementosProjeto, 1);

foreach ($auxTarefas as $tmp) {
    if ($tmp['campos']['dtFim'] == NULL) {
        $tarefasProjeto[] = $tmp;
    }
}

foreach ($elementosProjeto as $aux) {
    $tipoElem = $POPElemento->getElementTypeByElementId($aux['elemento']);
}
$fazerTarefa = FALSE;
if ((isset($_GET['tarefa']) && $_GET['tarefa'])) {
    $fazerTarefa = TRUE;
}
require_once __DIR__ . "/pop-processa-modal.php";
?>
<!DOCTYPE html>
<html>
<head>

    <?php require_once __DIR__ . "/pop-projeto-include-header.php"; ?>

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
            <h1>Projeto <?php echo $infoProjeto['nome']; ?></h1>

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
                                <?php if (!isset($_GET["tarefas"])) { ?>
                                    <a class="btn btn-primary btn-flat btn-addnew border-0 js-getProjeto" href="#"
                                       data-projeto="<?php echo $infoProjeto['projeto']; ?>"
                                       data-target="#modal-projeto"
                                       data-acao="arquivar-projeto" data-toggle="modal" data-backdrop="static">
                                        <i class="fa fa-archive pull-left font-16"></i> Arquivar Projeto
                                    </a>
                                <?php } ?>
                                <a class="btn btn-primary btn-flat btn-addnew border-0 js-getProjeto" href="#"
                                   data-projeto="<?php echo $infoProjeto['projeto']; ?>" data-target="#modal-projeto"
                                   data-acao="editar-projeto" data-toggle="modal" data-backdrop="static">
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
                            <i class="fa fa-th"></i>
                            <h3 class="box-title">Lista de Tarefas</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-12 filtros">
                                    <div class="row">
                                        <div class="col-sm-10">
                                            <div class="acao-bts">
                                                <!--
                                                <a class="btn btn-primary btn-flat btn-addnew border-0 js-getProjeto" href="#" data-projeto="<?php echo $infoProjeto['projeto']; ?>" data-target="#modal-projeto"
                                                   data-acao="editar-projeto" data-toggle="modal" data-backdrop="static">
                                                    <i class="fa fa-pencil pull-left font-16"></i> Editar dados do Projeto
                                                </a>

    -->
                                                <button class="btn btn-primary btn-flat btn-add-post border-0 js-getProjeto"
                                                        data-toggle="modal" data-backdrop="static"
                                                        data-projeto="<?php echo $infoProjeto['projeto']; ?>"
                                                        data-target="#modal-projeto"
                                                        data-acao="novo-post-blog"
                                                ><i class="fa fa-plus"></i> Novo Texto
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-sm-2"></div>
                                    </div>
                                </div><!-- /filtros -->
                            </div><!-- /row filtros -->
                            <div class="row">
                                <?php foreach ($elementosProjeto as $e) {
                                    $extra = $e["campos"];
                                    $status = $POPElemento->getElementStatusById($e["elementoStatus"]);
                                    $tipo = $POPElemento->getElementTypeById($e["elementoTipo"]);
                                    $ret = $POPElemento->limpaExtras($tipo, $extra);
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
                                                        foreach ($subE as $field) {
                                                            $nome = $field["nome"] . ":404:";
                                                            if (isset($nomesCampos[$field['nome']])) {
                                                                $nome = $nomesCampos[$field['nome']];//. " + " . $field['nome'] ;
                                                            }
                                                            ?>
                                                            <div class="col-sm-4 item-info">
                                                                <div class="item-info-box">
                                                                    <h4><?php echo $nome; ?></h4>
                                                                    <?php
                                                                    $class = $field['tipo'];
                                                                    if (class_exists($class)) {
                                                                        /** @var nomeTipo $var */
                                                                        $var = new $class($field['nome'], $field['valor'], $database);
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
                                                        <?php if (in_array($extra['area'], AREAS_CONTATO_CLIENTE) && $e['elementoStatus'] != 3) { ?>
                                                            <button class="btn btn-flat btn-block btn-cliente border-0 js-getElemento"
                                                                    data-elemento="<?php echo $e['elemento']; ?>"
                                                                    data-toggle="modal"
                                                                    data-backdrop="static" data-acao="aguardar-cliente"
                                                                    data-target="#modal-elemento">
                                                                <i class="fa fa-clock-o pull-left"></i> Enviado/Aguardar
                                                                Cliente
                                                            </button>
                                                        <?php } ?>

                                                        <?php if ($e["elementoTipo"] == 107) { ?>
                                                            <button class="btn btn-block btn-flat btn-primary border-0 js-getElemento"
                                                                    data-elemento="<?php echo $e['elemento']; ?>"
                                                                    data-toggle="modal"
                                                                    data-backdrop="static" data-target="#modal-elemento"
                                                                    data-acao="adicionar-info-post-blog"
                                                                    title="Aprovar">
                                                                <i class="fa fa-pencil pull-left"></i> Adicionar
                                                                Conteudos
                                                            </button>
                                                        <?php } ?>


                                                        <button class="btn btn-block btn-flat btn-success border-0 js-getElemento"
                                                                data-elemento="<?php echo $e['elemento']; ?>"
                                                                data-toggle="modal"
                                                                data-backdrop="static" data-target="#modal-elemento"
                                                                data-acao="aprovar-elemento" title="Aprovar">
                                                            <i class="fa fa-thumbs-up pull-left"></i> Aprovar
                                                        </button>
                                                        <?php
                                                        if (isset($e["campos"]["Etapa"])) {
                                                            $array_reprova = [159, 161, 163, 164, 166, 167, 175];
                                                            if (in_array($e["campos"]["Etapa"], $array_reprova)) {
                                                                ?>
                                                                <button class="btn btn-block btn-flat btn-danger border-0 js-getElemento"
                                                                        data-elemento="<?php echo $e['elemento']; ?>"
                                                                        data-toggle="modal"
                                                                        data-backdrop="static"
                                                                        data-target="#modal-elemento"
                                                                        data-acao="reprovar-elemento" title="Reprovar">
                                                                    <i class="fa fa-thumbs-down pull-left"></i> Reprovar
                                                                </button>
                                                                <?php
                                                            }
                                                        } ?>
                                                        <button class="btn btn-block btn-flatborder-0 js-getElemento"
                                                                style="background-color: #cccccc !important;"
                                                                data-elemento="<?php echo $e['elemento']; ?>"
                                                                data-toggle="modal" data-backdrop="static"
                                                                data-target="#modal-elemento"
                                                                data-acao="apagar-elemento"
                                                                title="Apagar Tarefa">
                                                            <i class="fa fa-times pull-left"></i> Apagar Post
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
        </section>
    </aside>
</div>


<?php require_once __DIR__ . "/pop-projeto-include-footer.php"; ?>
<?php require_once __DIR__ . "/pop-projeto-include-footer-script.php"; ?>

<script type="text/javascript">
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
</script>
</body>
</html>
