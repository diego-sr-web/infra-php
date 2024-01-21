<?php
require_once __DIR__ . "/../../autoloader.php";

$database = new Database();
$usuario = new AdmUsuario($database);
$POPProjeto = new Projeto($database);
$POPElemento = new Elemento($database);

$BNCliente = new BNCliente($database);
$listaClientes = $BNCliente->listAll("", "nomeFantasia");

require_once __DIR__ . '/../includes/is_logged.php';
require_once __DIR__ . '/include/le-notificacoes.php';

$listaPrioridade = $POPElemento->getPrioridadeList();
$listaAreas = $usuario->listArea();
$listaUsuarios = $usuario->listAll("", "nome", FALSE);

if (isset($_POST["form"])) {
    require_once __DIR__ . "/pop-processa-modal.php";
}
$_GET['projeto'] = 13;

$infoProjeto = $POPProjeto->getProjectById($_GET['projeto']);
$infoTipoProjeto = $POPProjeto->getProjectTypeById($infoProjeto['projetoTipo']);

$finalizados = FALSE;
if (isset($_GET['finalizados']) && $_GET['finalizados']) {
    $finalizados = TRUE;
}

$statusFinalizado = [8, 9, 14, 15, 16];

if ($finalizados) {
    $elementosProjeto = $POPElemento->getAllElements(['projeto'], [$_GET['projeto']]);
    $elementosProjeto = $POPElemento->filtraCampoValor($elementosProjeto, 'elementoStatus', $statusFinalizado, FALSE);
}
else {
    $elementosProjeto = $POPElemento->getAllElements(['projeto'], [$_GET['projeto']], TRUE);
}

$elementosProjeto = $POPElemento->filtraCampoValor($elementosProjeto, 'responsavel_de', $_SESSION['adm_usuario'], FALSE);

if ($elementosProjeto != []) {
    Utils::sksort($elementosProjeto, 'elemento', TRUE);
}
//exit;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $titleNotif; ?>Pedidos | POP Nerdweb</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="../css/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css"/>
    <link href="../css/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>
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
            <h1>Pedidos</h1>

            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-exchange"></i> Pedidos</a></li>
                <li class="active">Meus Pedidos</li>
            </ol>

            <div class="page-filter">
                <ul>
                    <li <?php
                    if ((!isset($_GET['finalizados']))) {
                        echo 'class="active"';
                    }
                    ?>><a
                                href="pop-meus-pedidos.php">PEDIDOS ATIVOS</a></li>
                    <li <?php if (isset($_GET['finalizados']) && $_GET['finalizados'] == 1) {
                        echo 'class="active"';
                    }
                    ?>>
                        <a href="pop-meus-pedidos.php?finalizados=1">PEDIDOS FINALIZADOS</a></li>
                </ul>
            </div>
        </section>

        <section class="content">
            <?php require_once __DIR__ . "/include/sucesso_error.php"; ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-solid">
                        <div class="box-header">
                            <?php if ($finalizados) { ?>
                                <h3 class="box-title"><i class="fa fa-exchange"></i> &nbsp;Meus Pedidos Finalizados</h3>
                            <?php } else { ?>
                                <h3 class="box-title"><i class="fa fa-exchange"></i> &nbsp;Meus Pedidos Ativos</h3>
                            <?php } ?>
                        </div>

                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-12 filtros">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="acao-bts">
                                                <button class="btn btn-primary btn-flat border-0 js-getTarefa"
                                                        data-target="#modal-pedido" data-acao="novo-pedido"
                                                        data-toggle="modal" data-backdrop="static">
                                                    <i class="fa fa-plus"></i> Adicionar novo pedido
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="filtro-btns">

                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /filtros -->
                            </div><!-- /row filtros -->
                        </div>
                        <div class="box-body table-responsive no-padding">
                            <table class="table table-striped table-hover tabela-meus-pedidos">
                                <thead>
                                <tr>
                                    <th style="width:50px;">Tipo</th>
                                    <th>Projeto</th>
                                    <th>Cliente</th>
                                    <th>Pedido</th>
                                    <th>De</th>
                                    <th>Depto</th>
                                    <th>Para</th>
                                    <th>Área</th>
                                    <th>Prazo</th>
                                    <th>Prioridade</th>
                                    <th>Status</th>
                                    <th style="width: 60px;">Ações</th>
                                </tr>
                                </thead>
                                <?php
                                foreach ($elementosProjeto as $e) {
                                    $projeto = $POPProjeto->getProjectById($e['projeto']);
                                    $tipoProjeto = $POPProjeto->getProjectTypeByProjectId($projeto['projeto']);
                                    $status = $POPElemento->getElementStatusById($e["elementoStatus"]);
                                    $prioridade = $POPElemento->getPrioridadeById($e["campos"]["prioridade"]);
                                    $areaDe = $POPElemento->getAreaById($e["campos"]["De_Area"]);
                                    $area = $POPElemento->getAreaById($e["campos"]["area"]);

                                    $areaResponsavel = new area('area', $e['campos']['area'], $database);
                                    $prazo = new date('prazo', $e['campos']['prazo'], $database);
                                    $prioridade = new prioridade('prioridade', $e['campos']['prioridade'], $database);
                                    $status = new status('elementoStatus', $e['elementoStatus'], $database);

                                    if (!isset($area["nome"])) {
                                        $area["nome"] = "";
                                    }
                                    if (!isset($area["cor"])) {
                                        $area["cor"] = "";
                                    }

                                    ?>
                                    <tr class="tarefa-box">
                                        <td><span class="icone-tipo"
                                                  style="background-color: <?php echo $tipoProjeto['cor']; ?>;"><i
                                                        class="fa <?php echo $tipoProjeto['icone']; ?>"></i></span><span
                                                    style="display: none;"><?php echo $tipoProjeto['nome']; ?></span>
                                        </td>
                                        <td><?php echo $e["campos"]["Produto"]; ?></td>
                                        <td><?php echo $e["campos"]["Cliente"]; ?></td>
                                        <td><?php echo $e["campos"]["Nome"] ?></td>
                                        <td>
                                            <?php
                                            $responsavel = new responsavel('responsavel_de', $e['campos']['responsavel_de'], $database);
                                            echo $responsavel->retornaHtmlExibicao();
                                            ?>
                                        </td>
                                        <td><?php if ($areaDe) { ?><span class="dado-destaque"
                                                                         style="color: <?php echo $areaDe['cor']; ?>;"><?php echo $areaDe["nome"] ?></span><?php } ?>
                                        </td>
                                        <td>
                                            <?php
                                            $responsavel = new responsavel('responsavel', $e['campos']['responsavel'], $database);
                                            echo $responsavel->retornaHtmlExibicao();
                                            ?>
                                        </td>
                                        <td><?php echo $areaResponsavel->retornaHtmlExibicao(); ?></td>
                                        <td><?php echo $prazo->retornaHtmlExibicaoPrazo(); ?></td>
                                        <td><?php echo $prioridade->retornaHtmlExibicao(); ?></td>
                                        <td><?php echo $status->retornaHtmlExibicao(); ?></td>
                                        <td>
                                            <div class="projeto-btns" style="width: 100px">
                                                <a href="pop-pedido.php?eid=<?php echo $e["elemento"]; ?>"
                                                   class="btn btn-flat btn-primary border-0" data-toggle="tooltip"
                                                   title="Ver Pedido">
                                                    <i class="fa fa-search"></i>
                                                </a>
                                                <button class="btn btn-flat btn-primary border-0 js-getTarefa"
                                                        data-toggle="modal" data-backdrop="static"
                                                        data-target="#modal-pedido" data-acao="arquivar-pedido"
                                                        data-tarefa="<?php echo $e["elemento"]; ?>"
                                                        title="Arquivar Pedido"><i class="fa fa-times"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table><!-- /row pedidos -->
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
<script src="../js/AdminLTE/app.js" type="text/javascript"></script>
<script src="../js/backoffice.js" type="text/javascript"></script>
<script src="../js/pop.js" type="text/javascript"></script>

<script type="text/javascript">
    var base_url = '<?php echo POP_URL; ?>';

    $(".tabela-meus-pedidos").dataTable({
        "bPaginate": false,
        //"bLengthChange": false,
        "bFilter": true,
        "aaSorting": [[10, "asc"]]
    });
    var lastModal = '';
    $(document).on('click', '.js-getTarefa', function (event) {
        event.preventDefault();

        var idModal = $(this).attr('data-target');
        var idTarefa = $(this).attr('data-tarefa');
        var acao = $(this).attr('data-acao');

        $(idModal).hide();

        var loadModal = true;
        if (acao === lastModal) {
            loadModal = false;
        }
        lastModal = acao;
        if (loadModal) {
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
        }
    });
</script>
</body>
</html>
