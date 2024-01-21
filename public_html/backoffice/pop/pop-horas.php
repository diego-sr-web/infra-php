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
$eid = $_GET["eid"] ?? 0;
if ($eid === 0) {
    Utils::redirect("pop-minhas-tarefas.php");
}

$dados_elemento = $POPElemento->getElementById($eid);
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

$usuario_nome = $responsavel->retornaNomeExibicao();
$usuario_avatar = $responsavel->retornaHtmlExibicao();
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
            <h1>Cliente: <?php echo $dados_elemento["campos"]["cliente"] ?></h1>

            <ol class="breadcrumb">
                <li><a href="pop-minhas-tarefas.php"><i class="fa fa-exchange"></i> Minhas Tarefas</a></li>
            </ol>
        </section>
        <section class="content">
            <?php require_once __DIR__ . "/include/sucesso_error.php"; ?>

            <div class="row">
                <div class="col-md-7">
                    <div class="box box-solid">
                        <div class="box-header">
                            <div class="col-xs-12">
                                <h3 class="box-title">
                                    #<?php echo $dados_elemento["elemento"] . " | " . $dados_elemento["campos"]["titulo"]; ?></h3>
                            </div>
                        </div>
                        <div class="box-body">
                            <dl class="dl-horizontal">
                                <dt>Data do Apontamento:</dt>
                                <dd><?php echo date('Y-m-d', strtotime($dados_elemento["dataAtualizacao"])); ?></dd>
                                <br/>
                            </dl>
                            <dl class="dl-horizontal">
                                <dt>Tempo trabalhado:</dt>
                                <dd><?php echo $dados_elemento["campos"]["tempo"]; ?> Minutos</dd>
                                <br/>
                            </dl>
                            <dl class="dl-horizontal">
                                <dt>Descricao:</dt>
                                <dd><?php echo $dados_elemento["campos"]["descricao"]; ?></dd>
                                <br/>
                            </dl>
                            <dl class="dl-horizontal">
                                <dt>Responsavel:</dt>
                                <dd><?php echo $responsavel->retornaHtmlExibicao(); ?></dd>
                                <br/>
                            </dl>
                        </div>
                        <?php if (!empty($extras)) { ?>
                            <div class="box-header">
                                <h3 class="box-title">Anexos</h3>
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
                                            <p><b>Observação do arquivo</b></p>
                                            <p>
                                                <?php
                                                echo nl2br($extra["observacao"]);
                                                ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php } ?>

                            </div>
                        <?php } ?>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="box box-solid">
                        <div class="box-header">
                            <h3 class="box-title">Chat do Job</h3>
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
                                                    há <?php echo Utils::printDate(date('Y-m-d H:i:s', strtotime($item['data']))); ?>
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
                                <div class="sem-comentarios item" style="text-align:center; border-bottom:none;">Job
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

<script type="text/javascript">
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
                        '				<small title="" class="text-muted pull-right"><i class="fa fa-clock-o"></i> há 0h</small>' +
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
</script>
</body>
</html>
