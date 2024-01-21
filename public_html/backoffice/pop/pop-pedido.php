<?php
require_once __DIR__ . "/../../autoloader.php";

$database = new Database();
$usuario = new AdmUsuario($database);
$POPProjeto = new Projeto($database);
$POPElemento = new Elemento($database);
$chat = new Chat($database);
$BNCliente = new BNCliente($database);

require_once __DIR__ . '/../includes/is_logged.php';
require_once __DIR__ . '/include/le-notificacoes.php';

$listaAreas = $usuario->listArea();
$listaPrioridade = $POPElemento->getPrioridadeList();

if (isset($_POST["form"])) {
    if ($_POST["form"] === "assumir-tarefa" || $_POST["form"] === "editar-tarefa") {
        require_once __DIR__ . "/pop-minhas-tarefas-post.php";
    }
    else {
        require_once __DIR__ . "/pop-processa-modal.php";
    }
}
if (!isset($_GET["eid"]) && $_GET["eid"] != "") {
    Utils::redirect("pop-minhas-tarefas.php");
}
$array_pedidos = [59, 82, 105];
$eid = $_GET["eid"];
$dados_elemento = $POPElemento->getElementById($eid);
if (!isset($dados_elemento["elementoTipo"]) || !in_array($dados_elemento["elementoTipo"], $array_pedidos)) {
    Utils::redirect("pop-minhas-tarefas.php");
}

$nome = $dados_elemento["campos"]["Nome"] ?? "";
$cliente = $dados_elemento["campos"]["Cliente"] ?? "";
$produto = $dados_elemento["campos"]["Produto"] ?? "";
$observacao = $dados_elemento["campos"]["Observacoes"] ?? "";
$descricao = $dados_elemento["campos"]["Descricao"] ?? "";
$etapa = $dados_elemento["campos"]["Etapa"] ?? "";
$areaPedido = $POPElemento->getAreaById($dados_elemento["campos"]["area"]);
if (!isset($areaPedido["nome"])) {
    $areaPedido["nome"] = "";
}
if (!isset($areaPedido["cor"])) {
    $areaPedido["cor"] = "";
}
$areaOriginal = $POPElemento->getAreaById($dados_elemento["campos"]["De_Area"]);
$areaDestino = $POPElemento->getAreaById($dados_elemento["campos"]["Para_Area"]);
$extras = [];
foreach ($dados_elemento["campos"] as $key => $value) {
    if (stripos($key, "xt_arquivo") !== FALSE) {
        $indice = explode("_", $key)[2];
        $extras[$indice]["link"] = $value;
        $extras[$indice]["observacao"] = $dados_elemento["campos"]["xt_Observacao_Arquivo_" . $indice];
        $extras[$indice]["responsavel"] = $dados_elemento["campos"]["xt_responsavel_" . $indice];
        unset($dados_elemento["campos"][$key], $dados_elemento["campos"]["xt_Observacao_Arquivo_" . $indice], $dados_elemento["campos"]["xt_responsavel_" . $indice]);
    }
}

$chat_list = $chat->getElementChat($eid);
$responsavel = new responsavel('resp', $_SESSION["adm_usuario"], $database);
$responsavel_de = new responsavel('resp-de', $dados_elemento["campos"]["responsavel_de"], $database);
$responsavel_para = new responsavel('resp-para', $dados_elemento["campos"]["responsavel"], $database);

$usuario_nome = $responsavel->retornaNomeExibicao();
$usuario_avatar = $responsavel->retornaHtmlExibicao();
$listaUsuarios = $usuario->getUsuariosArea($dados_elemento['campos']['Para_Area']);
?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $titleNotif; ?>Pedido | POP Nerdweb</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="../css/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css"/>
    <link href="../css/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>
    <link href="../js/plugins/light-gallery/css/lightgallery.css" rel="stylesheet" type="text/css"/>
    <link href="../css/ionicons.css" rel="stylesheet" type="text/css"/>
    <link href="../css/AdminLTE.css" rel="stylesheet" type="text/css"/>
    <link href="../css/pop.css" rel="stylesheet" type="text/css"/>
    <link href="../css/trumbowyg/trumbowyg.min.css" rel="stylesheet" type="text/css"/>
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
        <section class="content-header">
            <h1>Pedido</h1>

            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-exchange"></i> Pedidos</a></li>
                <li><a href="pop-meus-pedidos.php">Meus Pedidos</a></li>
                <li class="active">Pedido</li>
            </ol>
        </section>
        <section class="content">
            <?php require_once __DIR__ . "/include/sucesso_error.php"; ?>

            <div class="row">
                <div class="col-md-7">
                    <div class="box box-solid">
                        <div class="box-header">
                            <h3 class="box-title">#<?php echo $eid; ?></h3>
                            <?php


                            if (($dados_elemento['elementoTipo'] == 82 || $dados_elemento['elementoTipo'] == 105) &&
                                // se foi quem criou, ou admin, ou estiver na área de atendimento, pode arquivar e editar
                                ($dados_elemento['campos']['responsavel_de'] == $_SESSION['adm_usuario'] || $isUserAdmin || in_array($areaPedido['area'], AREAS_CONTATO_CLIENTE))) { ?>

                                <button class="btn btn-flat btn-primary btn-addnew border-0 js-getTarefa"
                                        data-toggle="modal" data-backdrop="static" data-target="#modal-pedido"
                                        data-acao="arquivar-pedido"
                                        data-tarefa="<?php echo $dados_elemento["elemento"]; ?>"
                                        title="Arquivar Pedido"><i class="fa fa-times"></i> Arquivar Pedido
                                </button>

                                <button class="btn btn-primary btn-flat btn-addnew border-0 js-getTarefa"
                                        data-toggle="modal" data-backdrop="static" data-target="#modal-pedido"
                                        data-acao="editar-pedido"
                                        data-tarefa="<?php echo $dados_elemento["elemento"]; ?>"><i
                                            class="fa fa-pencil"></i> Editar Pedido
                                </button>

                                <?php if ($dados_elemento["campos"]["responsavel"] === NULL) { ?>
                                    <button class="btn btn-primary btn-flat btn-addnew border-0 js-getTarefa"
                                            data-toggle="modal" data-backdrop="static" data-target="#modal-pedido"
                                            data-acao="assumir-tarefa"
                                            data-tarefa="<?php echo $dados_elemento["elemento"]; ?>"><i
                                                class="fa fa-plus"></i> Assumir Pedido
                                    </button>
                                    <?php
                                }
                                ?>
                                    <button class="btn btn-primary btn-flat btn-addnew border-0 js-getTarefa"
                                            data-toggle="modal" data-backdrop="static" data-target="#modal-pedido"
                                            data-acao="editar-tarefa"
                                            data-tarefa="<?php echo $dados_elemento["elemento"]; ?>"><i
                                                class="fa fa-user-plus"></i> Delegar Pedido
                                    </button>
                                <?php

                            }
                            if ($dados_elemento['elementoTipo'] == 59 && $isUserAdmin) { ?>
                                <button class="btn btn-flat btn-primary btn-addnew border-0 js-getTarefa"
                                        data-toggle="modal" data-backdrop="static" data-target="#modal-pedido"
                                        data-acao="arquivar-pedido"
                                        data-tarefa="<?php echo $dados_elemento["elemento"]; ?>"
                                        title="Arquivar Pedido"><i class="fa fa-times"></i> Arquivar Pedido
                                </button>
                            <?php } ?>
                        </div>
                        <div class="box-body">
                            <dl class="dl-horizontal">

                                <dt>Pedido:</dt>
                                <dd><?php echo $nome; ?></dd>
                                <br/>
                                <dt>Cliente:</dt>
                                <dd><?php echo $cliente; ?></dd>
                                <br/>
                                <dt>Produto:</dt>
                                <dd><?php echo $produto; ?></dd>
                                <br/>
                                <dt>Descrição:</dt>
                                <dd><?php echo Utils::insert_href(nl2br($descricao)); ?></dd>
                                <br/>
                                <dt>Observações:</dt>
                                <dd><?php echo Utils::insert_href(nl2br($observacao)); ?></dd>
                                <br/>
                                <dt>Data de Entrada:</dt>
                                <dd><?php echo date('d/m/Y', strtotime($dados_elemento['dataCriacao'])); ?></dd>
                                <br/>
                                <dt>Prazo:</dt>
                                <dd><?php echo date('d/m/Y', strtotime($dados_elemento['campos']['prazo'])) ?? '-'; ?></dd>
                                <br>
                                <dt>Área que Pediu:</dt>
                                <dd><span class="dado-destaque"
                                          style="color: <?php echo $areaOriginal['cor']; ?>;"><?php echo $areaOriginal["nome"]; ?></span>
                                </dd>
                                <br>
                                <dt>Área destino:</dt>
                                <dd><span class="dado-destaque"
                                          style="color: <?php echo $areaDestino['cor']; ?>;"><?php echo $areaDestino["nome"]; ?></span>
                                </dd>
                                <br>
                                <dt>Etapa:</dt>
                                <dd><?php echo $POPElemento->getNomeEtapa($etapa); ?></dd>
                            </dl>
                            <div class="row">
                                <div class="col-xs-6">
                                    <dl class="dl-horizontal">
                                        <dt>Quem Pediu:</dt>
                                        <dd><?php echo $responsavel_de->retornaHtmlExibicao(); ?></dd>
                                        <br/>
                                    </dl>
                                </div>
                                <div class="col-xs-6">
                                    <dl class="dl-horizontal">
                                        <dt>Responsável</dt>
                                        <dd><?php echo $responsavel_para->retornaHtmlExibicao(); ?></dd>
                                        <br/>
                                        <dt>Área Atual</dt>
                                        <dd><span class="dado-destaque"
                                                  style="color: <?php echo $areaPedido['cor']; ?>;"><?php echo $areaPedido["nome"]; ?></span>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <button class="btn btn-flat btn-block btn-primary border-0 js-getElemento"
                                            data-elemento="<?php echo $eid; ?>" data-toggle="modal"
                                            data-backdrop="static" data-acao="adicionar-arquivos"
                                            data-target="#modal-pedido">
                                        Adicionar Arquivo ao Pedido
                                    </button>
                                </div>
                                <div class="col-xs-6">
                                    <div class="row">
                                        <?php if (in_array($areaPedido['area'], AREAS_CONTATO_CLIENTE) && $dados_elemento['elementoStatus'] != 3) { ?>
                                            <div class="col-xs-12 mb-10">
                                                <button class="btn btn-flat btn-block btn-cliente border-0 js-getElemento"
                                                        data-elemento="<?php echo $eid; ?>" data-toggle="modal"
                                                        data-backdrop="static" data-acao="aguardar-cliente"
                                                        data-target="#modal-pedido">
                                                    <i class="fa fa-clock-o"></i>&nbsp;&nbsp;Enviado/Aguardar Cliente
                                                </button>
                                            </div>
                                        <?php } ?>
                                        <div class="col-xs-6">
                                            <button class="btn btn-flat btn-block <?php if ($etapa == 70 || $etapa == 67) {
                                                echo "btn-warning";
                                            } else {
                                                echo "btn-success";
                                            } ?> border-0 js-getElemento" data-elemento="<?php echo $eid; ?>"
                                                    data-toggle="modal" data-backdrop="static"
                                                    data-acao="aprovar-elemento" data-target="#modal-pedido">
                                                <i class="fa fa-thumbs-up"></i> <?php if ($etapa == 70) {
                                                    echo "Finalizar Pedido";
                                                } elseif ($etapa == 67) {
                                                    echo "Iniciar fluxo";
                                                } else {
                                                    echo "Aprovar Pedido";
                                                } ?>
                                            </button>
                                        </div>
                                        <?php if ($etapa != 67) { ?>
                                            <div class="col-xs-6">
                                                <button class="btn btn-flat btn-block btn-danger border-0 js-getElemento"
                                                        data-elemento="<?php echo $eid; ?>" data-toggle="modal"
                                                        data-backdrop="static" data-acao="reprovar-elemento"
                                                        data-target="#modal-pedido">
                                                    <i class="fa fa-thumbs-down"></i> Reprovar Pedido
                                                </button>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="box-header">
                            <h3 class="box-title">Arquivos adicionados</h3>
                        </div>
                        <div class="box-body">

                            <?php foreach ($extras as $extra) {
                                $responsavel_arquivo = new responsavel('avatar-arquivo', $extra["responsavel"], $database);
                                $tmp = explode(".", $extra["link"]);

                                $ext = $tmp[count($tmp) - 1];
                                $is_image = FALSE;
                                $array_imagens = ['jpg', 'png', 'jpeg', 'gif'];
                                if (in_array($ext, $array_imagens)) {
                                    $is_image = TRUE;
                                }
                                ?>
                                <div class="row pedido-arquivo">
                                    <div class="col-sm-5">
                                        <?php if ($is_image) { ?>
                                            <a class="open-lightbox" target="_blank"
                                               href="<?php echo $extra["link"]; ?>">
                                                <img src="<?php echo $extra["link"]; ?>" width="350" height="250">
                                            </a>
                                        <?php } else { ?>
                                            <div>
                                                <a href="<?php echo $extra["link"]; ?>" target="_blank">
                                                    <i class="fa fa-file fa-5x"></i>
                                                </a>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="col-sm-7">
                                        <?php
                                        echo $responsavel_arquivo->retornaHtmlExibicao();
                                        $filePath = str_ireplace(
                                            $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["SERVER_NAME"],
                                            "/home2/pop/public_html",
                                            $extra["link"]);
                                        if (file_exists($filePath) && !is_dir($filePath)) {
                                            $date = date('m-d-Y', filemtime($filePath));
                                        }
                                        else {
                                            $date = "DATA NAO ENCONTRADA";
                                        }

                                        echo "<p><b>Data do upload:</b></p><p>" . $date . "</p>";
                                        ?>
                                        <p><b>Observação</b></p>
                                        <p>
                                            <?php
                                            echo nl2br($extra["observacao"]);
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            <?php } ?>

                        </div>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="box box-solid">
                        <div class="box-header">
                            <h3 class="box-title">Chat</h3>
                        </div>
                        <div class="box-body chat historico-post" id="chat-box">

                            <?php if (isset($chat_list[0])) {

                                foreach ($chat_list as $item) {
                                    $responsavel_id = $item["responsavel"];
                                    $conteudo = Utils::insert_href(nl2br($item["conteudo"]));

                                    $responsavel = new responsavel('primeiro-msg', $responsavel_id, $database);
                                    $data = date('m-d-Y', strtotime($item['data']));

                                    ?>
                                    <div class="item">
                                        <?php echo $responsavel->retornaHtmlExibicao(); ?>
                                        <p class="message">
                                            <a href="#" class="name">
                                                <small title="<?php echo $data; ?>" class="text-muted pull-right"><i
                                                            class="fa fa-clock-o"></i>
                                                    <?php echo Utils::printDate(date('Y-m-d H:i:s', strtotime($item['data']))); ?>
                                                </small>
                                                <?php echo $responsavel->retornaNomeExibicao(); ?>
                                            </a>
                                            <?php echo $conteudo; ?>
                                            <br/>
                                        </p>
                                    </div>
                                    <?php
                                }
                            }
                            else {
                                ?>

                                <!-- SEM COMENTÁRIOS -->
                                <div class="sem-comentarios item" style="text-align:center; border-bottom:none;">Pedido
                                    ainda sem comentários
                                </div>

                            <?php } ?>
                        </div>
                        <div class="box-body">
                            <form class="form-comentario" action="" method="post">
                                <input type="hidden" name="elemento" value="<?php echo $eid; ?>"/>
                                <div class="form-group">
                                    <label>Comentar</label>
                                    <textarea name="comentario" placeholder="Digite um comentário..." rows="3"
                                              class="form-control comentario-campo"></textarea>
                                </div>
                                <div id="msg-erro"></div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <button class="btn btn-primary btn-flat border-0 pull-right" type="submit">
                                            Enviar comentário
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </aside>
</div>

<!-- MODAL PEDIDO GERAL -->
<div class="modal fade" id="modal-pedido" tabindex="-1" role="dialog" aria-hidden="true"></div>

<script type="text/javascript">
    var idUsuarioLogado = '<?php echo $dadosUsuario["usuarioNerdweb"]; ?>';
</script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="//code.jquery.com/ui/1.11.1/jquery-ui.min.js" type="text/javascript"></script>
<script src="../js/plugins/bootstrap/bootstrap.min.js" type="text/javascript"></script>
<script src="../js/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>
<script src="../js/plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="../js/plugins/datepicker/locales/bootstrap-datepicker.pt-BR.js" type="text/javascript"></script>
<script src="../js/plugins/iCheck/icheck.min.js" type="text/javascript"></script>
<script src="../js/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="../js/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
<script src="../js/plugins/light-gallery/js/lightgallery.min.js" type="text/javascript"></script>
<script src="../js/plugins/light-gallery/modules/lg-zoom.min.js" type="text/javascript"></script>
<script src="../js/plugins/light-gallery/modules/lg-video.min.js" type="text/javascript"></script>
<script src="../js/AdminLTE/app.js" type="text/javascript"></script>
<script src="../js/backoffice.js" type="text/javascript"></script>
<script src="../js/pop.js" type="text/javascript"></script>
<script src="../js/plugins/trumbowyg/trumbowyg.min.js" type="text/javascript"></script>

<script type="text/javascript">
    var base_url = '<?php echo POP_URL; ?>';

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

    // salvar comentário na modal
    $(document).on('submit', '.form-comentario', function (event) {
        event.preventDefault();
        var dados = new FormData(this);
        dados.append('secao', 'elemento');
        dados.append('form', 'salva-comentario');
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
                    //console.log(dados);
                    var comentario = $(".comentario-campo").val();
                    var nome = "<?php echo $usuario_nome;?>";
                    var avatar = '<?php echo $usuario_avatar;?>';

                    //console.log(comentario);

                    $("#chat-box").append('' +
                        '<div class="item">' +
                        '	' + avatar +
                        '		<p class="message">' +
                        '			<a href="#" class="name">' +
                        '				<small title="" class="text-muted pull-right"><i class="fa fa-clock-o"></i>...</small>' +
                        nome +
                        '			</a>' +
                        '					' + comentario.replace(/\n/g, "<br />") +
                        '				<br/>' +
                        '		</p>' +
                        '</div>');
                    $(".comentario-campo").val("");
                } else if (retorno.tipo == 'msg') {
                    console.log(retorno.conteudo);
                }
            })
            .fail(function (retorno) {
                //console.log("deu erro")
            });
    });

    $(document).on('click', '.js-getTarefa', function (event) {
        event.preventDefault();

        var idModal = $(this).attr('data-target');
        var idTarefa = $(this).attr('data-tarefa');
        var acao = $(this).attr('data-acao');

        $(idModal).hide();

        $.ajax({
            url: 'ajax/ajax-pop.php',
            type: 'POST',
            data: {
                secao: 'tarefa',
                tipo: acao,
                tarefa: idTarefa
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
</script>
</body>
</html>
