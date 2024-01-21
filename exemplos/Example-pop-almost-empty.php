<?php
require_once __DIR__ . "/../../autoloader.php";

$database = new Database();
//$databaseTransactional = new Database(TRUE);
$usuario = new AdmUsuario($database);
$POPProjeto = new Projeto($database);
$POPElemento = new Elemento($database);

require_once __DIR__ . '/../includes/is_logged.php';

$dadosUsuario = $usuario->getUserDataWithId($_SESSION['adm_usuario']);
$areasUsuario = $usuario->getAreasUsuario($_SESSION['adm_usuario']);

$areasId = [];
foreach ($areasUsuario as $area) {
    $areasId[] = $area['area'];
}

if (isset($_POST['form'])) {
    $dtAtual = date('Y-m-d H:i:s');

    $redirect = ['redireciona' => FALSE, 'url' => 'pop-historico.php', 'queryString' => '',];

    switch ($_POST['form']) {
        case '':
            break;

        default:
            break;
    }

    if ($redirect['redireciona']) {
        Utils::redirect($redirect["url"]);
    }
}

$arrayQueryString = $_GET;
$queryString = http_build_query($arrayQueryString);
$ordens = [1 => ['nome' => 'ID', 'campo' => 'projeto', 'nivel' => 0], 2 => ['nome' => 'Nome', 'campo' => 'nome', 'nivel' => 0], 3 => ['nome' => 'Prazo', 'campo' => 'prazo', 'nivel' => 0], 4 => ['nome' => 'Prioridade', 'campo' => 'prioridade', 'nivel' => 0], 5 => ['nome' => 'Área', 'campo' => 'area', 'nivel' => 0],];
$filtros = [1 => ['nome' => 'Projeto', 'campo' => 'projeto', 'nivel' => 0], 2 => ['nome' => 'Ação', 'campo' => 'acao', 'nivel' => 0], 3 => ['nome' => 'Responsável', 'campo' => 'responsavel', 'nivel' => 0],];
$order = $ordens[$_GET['ordem']]['campo'] ?? 'prioridade';;
$telaTarefas = FALSE;
$fazerTarefa = FALSE;
if ((isset($_GET['tarefa']) && $_GET['tarefa'])) {
    $fazerTarefa = TRUE;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Feed de ações | POP Nerdweb</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="../css/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css"/>
    <link href="../css/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>
    <link href="../css/ionicons.css" rel="stylesheet" type="text/css"/>
    <link href="../css/AdminLTE.css" rel="stylesheet" type="text/css"/>
    <link href="../css/pop.css" rel="stylesheet" type="text/css"/>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script type="text/javascript" src="//oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script type="text/javascript" src="//oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
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
            <h1>Histórico de Ações</h1>
        </section>

        <section class="content">
            <?php require_once __DIR__ . "/include/sucesso_error.php"; ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-solid box-primary">
                        <div class="box-header">
                            <h3 class="box-title"><i class="fa fa-history"></i> &nbsp;Feed de Ações</h3>
                        </div>

                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-12 filtros">
                                    <div class="filtro-btns">
                                        <div class="btn-group">
                                            <span class="filtro-label"><i class="fa fa-filter"></i> Filtrar por</span>
                                            <button data-toggle="dropdown"
                                                    class="btn btn-flat dropdown-toggle btn-dropdown" type="button">
                                                Selecione... &nbsp;&nbsp;&nbsp; <span class="caret"></span>
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <ul role="menu" class="dropdown-menu with-submenu">
                                                <?php
                                                /*
                                                foreach ($filtros as $key => $filtro) {
                                                    $aux = $arrayQueryString;
                                                    $aux['filtro'] = $key;
                                                    echo '<li><a href="?'. http_build_query($aux) .'">'. $filtro['nome'] .'</a></li>';
                                                }
                                                */
                                                ?>
                                                <li>
                                                    <a href="#">Projeto</a>
                                                    <ul class="dropdown-submenu">
                                                        <li><a href="#">Projeto 1</a></li>
                                                        <li><a href="#">Projeto 2</a></li>
                                                        <li><a href="#">Projeto 3</a></li>
                                                    </ul>
                                                </li>
                                                <li>
                                                    <a href="#">Ação</a>
                                                    <ul class="dropdown-submenu">
                                                        <li><a href="#">Ação 1</a></li>
                                                        <li><a href="#">Ação 2</a></li>
                                                        <li><a href="#">Ação 3</a></li>
                                                    </ul>
                                                </li>
                                                <li>
                                                    <a href="#">Responsável</a>
                                                    <ul class="dropdown-submenu">
                                                        <li><a href="#">Responsável 1</a></li>
                                                        <li><a href="#">Responsável 2</a></li>
                                                        <li><a href="#">Responsável 3</a></li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="btn-group">
                                            <span class="filtro-label"><i class="fa fa-sort"></i> Ordenar por</span>
                                            <button data-toggle="dropdown"
                                                    class="btn btn-flat dropdown-toggle btn-dropdown" type="button">
                                                <?php echo $ordens[$_GET['ordem']]['nome'] ?? 'Selecione...'; ?>
                                                &nbsp;&nbsp;&nbsp; <span
                                                        class="caret"></span>
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <ul role="menu" class="dropdown-menu">
                                                <?php
                                                foreach ($ordens as $key => $ordem) {
                                                    $aux = $arrayQueryString;
                                                    $aux['ordem'] = $key;
                                                    echo '<li><a href="?' . http_build_query($aux) . '">' . $ordem['nome'] . '</a></li>';
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div><!-- /filtros -->
                            </div><!-- /row filtros -->
                            <div class="row">
                                <div class="col-sm-12 historico">
                                    <div class="historico-box">
                                        <div class="row">
                                            <div class="col-sm-2">
                                                <div class="historico-data">
                                                    <p><span>00/00/2016 00:00</span></p>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="historico-info">
                                                    <h3>Projeto 1<span>NOME DO CLIENTE</span></h3>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="historico-info">
                                                    <p>Elemento</p>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="historico-info">
                                                    <p><span style="color: blue;">NOME DA AÇÃO</span></p>
                                                </div>
                                            </div>
                                            <div class="col-sm-1">
                                                <div class="historico-responsavel">
                                                    <img class="avatar-responsavel"
                                                         src=" http://pop.nerdweb.dyndns.org/backoffice/uploads/usuarios/giovane-ferreira.png "
                                                         title="Giovane Ferreira">
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="historico-btns">
                                                    <a href="pop-projeto.php?projeto=3"
                                                       class="btn btn-flat bg-navy border-0"><i
                                                                class="fa fa-paper-plane"></i> &nbsp;VER PROJETO</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 historico">
                                    <div class="historico-box">
                                        <div class="row">
                                            <div class="col-sm-2">
                                                <div class="historico-data">
                                                    <p><span>00/00/2016 00:00</span></p>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="historico-info">
                                                    <h3>Projeto 1<span>NOME DO CLIENTE</span></h3>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="historico-info">
                                                    <p>Elemento</p>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="historico-info">
                                                    <p><span style="color: blue;">NOME DA AÇÃO</span></p>
                                                </div>
                                            </div>
                                            <div class="col-sm-1">
                                                <div class="historico-responsavel">
                                                    <img class="avatar-responsavel"
                                                         src=" http://pop.nerdweb.dyndns.org/backoffice/uploads/usuarios/giovane-ferreira.png "
                                                         title="Giovane Ferreira">
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="historico-btns">
                                                    <a href="pop-projeto.php?projeto=3"
                                                       class="btn btn-flat bg-navy border-0"><i
                                                                class="fa fa-paper-plane"></i> &nbsp;VER PROJETO</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 historico">
                                    <div class="historico-box">
                                        <div class="row">
                                            <div class="col-sm-2">
                                                <div class="historico-data">
                                                    <p><span>00/00/2016 00:00</span></p>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="historico-info">
                                                    <h3>Projeto 1<span>NOME DO CLIENTE</span></h3>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="historico-info">
                                                    <p>Elemento</p>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="historico-info">
                                                    <p><span style="color: blue;">NOME DA AÇÃO</span></p>
                                                </div>
                                            </div>
                                            <div class="col-sm-1">
                                                <div class="historico-responsavel">
                                                    <img class="avatar-responsavel"
                                                         src=" http://pop.nerdweb.dyndns.org/backoffice/uploads/usuarios/giovane-ferreira.png "
                                                         title="Giovane Ferreira">
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="historico-btns">
                                                    <a href="pop-projeto.php?projeto=3"
                                                       class="btn btn-flat bg-navy border-0"><i
                                                                class="fa fa-paper-plane"></i> &nbsp;VER PROJETO</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 historico">
                                    <div class="historico-box">
                                        <div class="row">
                                            <div class="col-sm-2">
                                                <div class="historico-data">
                                                    <p><span>00/00/2016 00:00</span></p>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="historico-info">
                                                    <h3>Projeto 1<span>NOME DO CLIENTE</span></h3>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="historico-info">
                                                    <p>Elemento</p>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="historico-info">
                                                    <p><span style="color: blue;">NOME DA AÇÃO</span></p>
                                                </div>
                                            </div>
                                            <div class="col-sm-1">
                                                <div class="historico-responsavel">
                                                    <img class="avatar-responsavel"
                                                         src=" http://pop.nerdweb.dyndns.org/backoffice/uploads/usuarios/giovane-ferreira.png "
                                                         title="Giovane Ferreira">
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="historico-btns">
                                                    <a href="pop-projeto.php?projeto=3"
                                                       class="btn btn-flat bg-navy border-0"><i
                                                                class="fa fa-paper-plane"></i> &nbsp;VER PROJETO</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 historico">
                                    <div class="historico-box">
                                        <div class="row">
                                            <div class="col-sm-2">
                                                <div class="historico-data">
                                                    <p><span>00/00/2016 00:00</span></p>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="historico-info">
                                                    <h3>Projeto 1<span>NOME DO CLIENTE</span></h3>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="historico-info">
                                                    <p>Elemento</p>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="historico-info">
                                                    <p><span style="color: blue;">NOME DA AÇÃO</span></p>
                                                </div>
                                            </div>
                                            <div class="col-sm-1">
                                                <div class="historico-responsavel">
                                                    <img class="avatar-responsavel"
                                                         src=" http://pop.nerdweb.dyndns.org/backoffice/uploads/usuarios/giovane-ferreira.png "
                                                         title="Giovane Ferreira">
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="historico-btns">
                                                    <a href="pop-projeto.php?projeto=3"
                                                       class="btn btn-flat bg-navy border-0"><i
                                                                class="fa fa-paper-plane"></i> &nbsp;VER PROJETO</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 historico">
                                    <div class="historico-box">
                                        <div class="row">
                                            <div class="col-sm-2">
                                                <div class="historico-data">
                                                    <p><span>00/00/2016 00:00</span></p>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="historico-info">
                                                    <h3>Projeto 1<span>NOME DO CLIENTE</span></h3>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="historico-info">
                                                    <p>Elemento</p>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="historico-info">
                                                    <p><span style="color: blue;">NOME DA AÇÃO</span></p>
                                                </div>
                                            </div>
                                            <div class="col-sm-1">
                                                <div class="historico-responsavel">
                                                    <img class="avatar-responsavel"
                                                         src=" http://pop.nerdweb.dyndns.org/backoffice/uploads/usuarios/giovane-ferreira.png "
                                                         title="Giovane Ferreira">
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="historico-btns">
                                                    <a href="pop-projeto.php?projeto=3"
                                                       class="btn btn-flat bg-navy border-0"><i
                                                                class="fa fa-paper-plane"></i> &nbsp;VER PROJETO</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 historico">
                                    <div class="historico-box">
                                        <div class="row">
                                            <div class="col-sm-2">
                                                <div class="historico-data">
                                                    <p><span>00/00/2016 00:00</span></p>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="historico-info">
                                                    <h3>Projeto 1<span>NOME DO CLIENTE</span></h3>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="historico-info">
                                                    <p>Elemento</p>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="historico-info">
                                                    <p><span style="color: blue;">NOME DA AÇÃO</span></p>
                                                </div>
                                            </div>
                                            <div class="col-sm-1">
                                                <div class="historico-responsavel">
                                                    <img class="avatar-responsavel"
                                                         src=" http://pop.nerdweb.dyndns.org/backoffice/uploads/usuarios/giovane-ferreira.png "
                                                         title="Giovane Ferreira">
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="historico-btns">
                                                    <a href="pop-projeto.php?projeto=3"
                                                       class="btn btn-flat bg-navy border-0"><i
                                                                class="fa fa-paper-plane"></i> &nbsp;VER PROJETO</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 historico">
                                    <div class="historico-box">
                                        <div class="row">
                                            <div class="col-sm-2">
                                                <div class="historico-data">
                                                    <p><span>00/00/2016 00:00</span></p>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="historico-info">
                                                    <h3>Projeto 1<span>NOME DO CLIENTE</span></h3>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="historico-info">
                                                    <p>Elemento</p>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="historico-info">
                                                    <p><span style="color: blue;">NOME DA AÇÃO</span></p>
                                                </div>
                                            </div>
                                            <div class="col-sm-1">
                                                <div class="historico-responsavel">
                                                    <img class="avatar-responsavel"
                                                         src=" http://pop.nerdweb.dyndns.org/backoffice/uploads/usuarios/giovane-ferreira.png "
                                                         title="Giovane Ferreira">
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="historico-btns">
                                                    <a href="pop-projeto.php?projeto=3"
                                                       class="btn btn-flat bg-navy border-0"><i
                                                                class="fa fa-paper-plane"></i> &nbsp;VER PROJETO</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12" style="text-align: center;">
                                    <div class="btn-group paginacao">
                                        <a href="?pagina=1" class="btn btn-default btn-flat"><i
                                                    class="fa fa-angle-double-left"></i></a><a
                                                class="btn btn-default btn-flat"
                                                href=""><strong>1</strong></a><a href="?pagina=1"
                                                                                 class="btn btn-default btn-flat"><i
                                                    class="fa fa-angle-double-right"></i></a>
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


<!-- MODAL TAREFA GERAL -->
<div class="modal fade" id="modal-tarefa" tabindex="-1" role="dialog" aria-hidden="true"></div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="//code.jquery.com/ui/1.11.1/jquery-ui.min.js" type="text/javascript"></script>
<script src="../js/plugins/bootstrap/bootstrap.min.js" type="text/javascript"></script>
<script src="../js/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>
<script src="../js/plugins/iCheck/icheck.min.js" type="text/javascript"></script>
<script src="../js/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="../js/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
<script src="../js/AdminLTE/app.js" type="text/javascript"></script>
<script src="../js/backoffice.js" type="text/javascript"></script>
<script src="../js/pop.js" type="text/javascript"></script>

<script type="text/javascript">
    var base_url = '<?php echo POP_URL; ?>';

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
