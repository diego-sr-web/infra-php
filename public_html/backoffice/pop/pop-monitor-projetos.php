<?php
require_once __DIR__ . "/../../autoloader.php";

$database = new Database();
$usuario = new AdmUsuario($database);
$POPProjeto = new Projeto($database);
$POPElemento = new Elemento($database);
$BNCliente = new BNCliente($database);

require_once __DIR__ . '/../includes/is_logged.php';
require_once __DIR__ . '/include/le-notificacoes.php';

$listaPrioridade = $POPElemento->getPrioridadeList();
$listaClientes = $BNCliente->listAll("", "nomeFantasia");

$listaAreas = $usuario->listArea();
$listaUsuarios = $usuario->listAll("", "nome", FALSE);

$array_tipo_pedido = [59, 82, 105];

$listaStatus = [
    ['elementoStatus' => 1, 'nome' => 'Aguardando Responsável'],
    ['elementoStatus' => 2, 'nome' => 'Aguardando Início'],
    ['elementoStatus' => 3, 'nome' => 'Aguardando Cliente'],
    ['elementoStatus' => 4, 'nome' => 'Em Andamento'],
    ['elementoStatus' => 5, 'nome' => 'Pausado'],
    ['elementoStatus' => 7, 'nome' => 'Problema'],
    ['elementoStatus' => 8, 'nome' => 'Finalizado']
];

// Popula o array de filtros, soh vai funcionar um por vez ateh surgir a necessidade de adionar mais casos
$filtros = ["ptid" => '', "cid" => '', "aid" => '', "sid" => '', "pid" => '', "uid" => ''];
foreach ($filtros as $key => $value) {
    if (isset($_GET[$key])) {
        $filtros[$key] = $_GET[$key];
    }
}

/** @var array $cliente */

// Como eh um monitor ele observa todas as areas [] foi um geito de sinalizar todas
$todosElementos = $database->customQueryPDO("SELECT 
	`pop_Elemento`.`elemento` as elemento,
    `pop_Elemento`.`elementoTipo` as elementoTipo,
    `pop_Elemento`.`elementoStatus`as elementoStatus,
    `pop_Projeto`.`projeto` as projeto,
    `pop_Projeto`.`projetoTipo` as projetoTipo FROM `pop_Elemento`,`pop_Projeto` 
	WHERE `pop_Elemento`.`isUsed` = 1 AND `pop_Projeto`.`isUsed` = 1 AND `pop_Elemento`.`projeto`=`pop_Projeto`.`projeto` AND `pop_Projeto`.`projeto` != 13 AND `pop_Projeto`.`finalizado` = 0", []);

//var_dump($todosElementos);
//exit;
$listaTarefas = [];
/** @noinspection ForeachSourceInspection */
foreach ($todosElementos as $elemento) {
    $ept = $elemento["projetoTipo"];
    $ep = $elemento["projeto"];
    $et = $elemento["elementoTipo"];

    if (isset($listaTarefas[$ept][$ep][$et])) {
        if ($elemento["elementoStatus"] < $listaTarefas[$ept][$ep][$et]) {
            $listaTarefas[$ept][$ep][$et] = $elemento["elementoStatus"];
        }
    }
    else {
        $listaTarefas[$ept][$ep][$et] = $elemento["elementoStatus"];
    }
}

//var_dump($listaTarefas);
//exit;

$projetoTipoAux = $POPProjeto->getProjectTypeList();

$listaProjetosTipo = [];
foreach ($projetoTipoAux as $item) {
    $tid = $item["projetoTipo"];
    $listaProjetosTipo[$tid]["projetoTipo"] = $tid;
    $listaProjetosTipo[$tid]["nome"] = $item["nome"];
    $listaProjetosTipo[$tid]["cor"] = $item["cor"];
    $listaProjetosTipo[$tid]["icone"] = $item["icone"];
}

/*
if ($listaProjetosTipo !== []) {
	$listaProjetosTipo = Utils::sksort($listaProjetosTipo, "nome", TRUE);
}
*/

//var_dump($listaTarefas);
//exit;
$tarefasOrganizadas = $listaTarefas;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $titleNotif; ?>Monitor de Tarefas | POP Nerdweb</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="../css/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css"/>
    <link href="../css/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>
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
            <h1>Monitor de Tarefas</h1>

            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-tasks"></i> Tarefas</a></li>
                <li class="active">Monitor de Tarefas</li>
            </ol>
        </section>

        <section class="content">
            <?php require_once __DIR__ . "/include/sucesso_error.php"; ?>

            <?php foreach ($tarefasOrganizadas as $key => $listaProjetos) { ?>
                <?php if ($key == 2) { ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-solid box-info">
                                <div class="box-header">
                                    <h3 class="box-title"><i class="fa fa-tasks"></i>&nbsp; Projeto Facebook</h3>
                                </div>
                                <div class="box-body table-responsive no-padding" style="padding-top: 15px !important;">
                                    <table class="table table-striped table-hover tabela-em-espera">
                                        <thead>
                                        <tr>
                                            <th>Cliente</th>
                                            <th>Projeto</th>
                                            <th>Entrada</th>
                                            <th>Prazo</th>
                                            <th>Calendário</th>
                                            <th>Post</th>
                                            <th>Finalizar</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($listaProjetos as $chave => $valor) { ?>
                                            <tr>
                                                <?php
                                                $projetoId = $chave;

                                                $projetoData = $POPProjeto->getProjectById($projetoId);
                                                $etapas = ["p1" => "", "p2" => "", "p3" => ""];

                                                foreach ($valor as $k2 => $v2) {
                                                    switch ($k2) {
                                                        case 37:
                                                            $etapas["p1"] = $POPElemento->getElementStatusById($v2)["nome"];
                                                            break;
                                                        case 38:
                                                            $etapas["p2"] = $POPElemento->getElementStatusById($v2)["nome"];
                                                            break;
                                                        case 39:
                                                            $etapas["p3"] = $POPElemento->getElementStatusById($v2)["nome"];
                                                            break;
                                                    }
                                                }

                                                $nome = $BNCliente->getDataWithId($projetoData["cliente"]);
                                                $nome = $nome["nomeFantasia"];
                                                ?>
                                                <td><?php echo $nome; ?></td>
                                                <td><?php echo $nome . ' | ' . $projetoData["nome"]; ?></td>
                                                <td><?php echo date("d/m/y", strtotime($projetoData["dataEntrada"])); ?></td>
                                                <td><?php echo date("d/m/y", strtotime($projetoData["prazo"])); ?></td>
                                                <td><?php echo $etapas["p1"]; ?></td>
                                                <td><?php echo $etapas["p2"]; ?></td>
                                                <td><?php echo $etapas["p3"]; ?></td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table><!-- /row tarefas -->
                                </div>
                            </div><!-- /box -->
                        </div>
                    </div>
                <?php } elseif ($key == 11) { ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-solid box-info">
                                <div class="box-header">
                                    <h3 class="box-title"><i class="fa fa-tasks"></i>&nbsp; Projeto Campanha Display
                                    </h3>
                                </div>
                                <div class="box-body table-responsive no-padding" style="padding-top: 15px !important;">
                                    <table class="table table-striped table-hover tabela-em-espera">
                                        <thead>
                                        <tr>
                                            <th>Cliente</th>
                                            <th>Projeto</th>
                                            <th>Entrada</th>
                                            <th>Prazo</th>
                                            <th>Briefing</th>
                                            <th>Aprovar Conceito</th>
                                            <th>Desdobrar Conceito</th>
                                            <th>Publicar Campanha</th>
                                            <th>Revisar Publicação</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($listaProjetos as $chave => $valor) { ?>
                                            <tr>
                                                <?php
                                                $projetoId = $chave;

                                                $projetoData = $POPProjeto->getProjectById($projetoId);
                                                $etapas = ["p1" => "", "p2" => "", "p3" => "", "p4" => "", "p5" => ""];
                                                foreach ($valor as $k2 => $v2) {
                                                    switch ($k2) {
                                                        case 88:
                                                            $etapas["p1"] = $POPElemento->getElementStatusById($v2)["nome"];
                                                            break;
                                                        case 89:
                                                            $etapas["p2"] = $POPElemento->getElementStatusById($v2)["nome"];
                                                            break;
                                                        case 90:
                                                            $etapas["p3"] = $POPElemento->getElementStatusById($v2)["nome"];
                                                            break;
                                                        case 91:
                                                            $etapas["p4"] = $POPElemento->getElementStatusById($v2)["nome"];
                                                            break;
                                                        case 92:
                                                            $etapas["p5"] = $POPElemento->getElementStatusById($v2)["nome"];
                                                            break;

                                                    }
                                                }

                                                $nome = $BNCliente->getDataWithId($projetoData["cliente"]);
                                                $nome = $nome["nomeFantasia"];
                                                ?>
                                                <td><?php echo $nome; ?></td>
                                                <td><?php echo $projetoData["nome"]; ?></td>
                                                <td><?php echo date("d/m/y", strtotime($projetoData["dataEntrada"])); ?></td>
                                                <td><?php echo date("d/m/y", strtotime($projetoData["prazo"])); ?></td>
                                                <td><?php echo $etapas["p1"]; ?></td>
                                                <td><?php echo $etapas["p2"]; ?></td>
                                                <td><?php echo $etapas["p3"]; ?></td>
                                                <td><?php echo $etapas["p4"]; ?></td>
                                                <td><?php echo $etapas["p5"]; ?></td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table><!-- /row tarefas -->
                                </div>
                            </div><!-- /box -->
                        </div>
                    </div>
                <?php } elseif ($key == 12) { ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-solid box-info">
                                <div class="box-header">
                                    <h3 class="box-title"><i class="fa fa-tasks"></i>&nbsp; Projeto Email Marketing</h3>
                                </div>
                                <div class="box-body table-responsive no-padding" style="padding-top: 15px !important;">
                                    <table class="table table-striped table-hover tabela-em-espera">
                                        <thead>
                                        <tr>
                                            <th>Cliente</th>
                                            <th>Projeto</th>
                                            <th>Entrada</th>
                                            <th>Prazo</th>
                                            <th>Briefing</th>
                                            <th>Aprovar Template</th>
                                            <th>Montar Template</th>
                                            <th>Publicar</th>
                                            <th>Disparar</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($listaProjetos as $chave => $valor) { ?>
                                            <tr>
                                                <?php
                                                $projetoId = $chave;

                                                $projetoData = $POPProjeto->getProjectById($projetoId);
                                                $etapas = ["p1" => "", "p2" => "", "p3" => "", "p4" => "", "p5" => ""];
                                                foreach ($valor as $k2 => $v2) {
                                                    switch ($k2) {
                                                        case 94:
                                                            $etapas["p1"] = $POPElemento->getElementStatusById($v2)["nome"];
                                                            break;
                                                        case 95:
                                                            $etapas["p2"] = $POPElemento->getElementStatusById($v2)["nome"];
                                                            break;
                                                        case 96:
                                                            $etapas["p3"] = $POPElemento->getElementStatusById($v2)["nome"];
                                                            break;
                                                        case 97:
                                                            $etapas["p4"] = $POPElemento->getElementStatusById($v2)["nome"];
                                                            break;
                                                        case 98:
                                                            $etapas["p5"] = $POPElemento->getElementStatusById($v2)["nome"];
                                                            break;

                                                    }
                                                }

                                                $nome = $BNCliente->getDataWithId($projetoData["cliente"]);
                                                $nome = $nome["nomeFantasia"];
                                                ?>
                                                <td><?php echo $nome; ?></td>
                                                <td><?php echo $projetoData["nome"]; ?></td>
                                                <td><?php echo date("d/m/y", strtotime($projetoData["dataEntrada"])); ?></td>
                                                <td><?php echo date("d/m/y", strtotime($projetoData["prazo"])); ?></td>
                                                <td><?php echo $etapas["p1"]; ?></td>
                                                <td><?php echo $etapas["p2"]; ?></td>
                                                <td><?php echo $etapas["p3"]; ?></td>
                                                <td><?php echo $etapas["p4"]; ?></td>
                                                <td><?php echo $etapas["p5"]; ?></td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table><!-- /row tarefas -->
                                </div>
                            </div><!-- /box -->
                        </div>
                    </div>
                <?php } elseif ($key == 13) { ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-solid box-info">
                                <div class="box-header">
                                    <h3 class="box-title"><i class="fa fa-tasks"></i>&nbsp; Projeto Material Grafico
                                    </h3>
                                </div>
                                <div class="box-body table-responsive no-padding" style="padding-top: 15px !important;">
                                    <table class="table table-striped table-hover tabela-em-espera">
                                        <thead>
                                        <tr>
                                            <th>Cliente</th>
                                            <th>Projeto</th>
                                            <th>Entrada</th>
                                            <th>Prazo</th>
                                            <th>Briefing</th>
                                            <th>Aprovar Conceito</th>
                                            <th>Desdobrar Conceito</th>
                                            <th>Entregar Material</th>
                                            <th>Faturar Material</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($listaProjetos as $chave => $valor) { ?>
                                            <tr>
                                                <?php
                                                $projetoId = $chave;

                                                $projetoData = $POPProjeto->getProjectById($projetoId);
                                                $etapas = ["p1" => "", "p2" => "", "p3" => "", "p4" => "", "p5" => ""];
                                                foreach ($valor as $k2 => $v2) {
                                                    switch ($k2) {
                                                        case 99:
                                                            $etapas["p1"] = $POPElemento->getElementStatusById($v2)["nome"];
                                                            break;
                                                        case 100:
                                                            $etapas["p2"] = $POPElemento->getElementStatusById($v2)["nome"];
                                                            break;
                                                        case 101:
                                                            $etapas["p3"] = $POPElemento->getElementStatusById($v2)["nome"];
                                                            break;
                                                        case 102:
                                                            $etapas["p4"] = $POPElemento->getElementStatusById($v2)["nome"];
                                                            break;
                                                        case 104:
                                                            $etapas["p5"] = $POPElemento->getElementStatusById($v2)["nome"];
                                                            break;
                                                    }
                                                }

                                                $nome = $BNCliente->getDataWithId($projetoData["cliente"]);
                                                $nome = $nome["nomeFantasia"];
                                                ?>
                                                <td><?php echo $nome; ?></td>
                                                <td><?php echo $projetoData["nome"]; ?></td>
                                                <td><?php echo date("d/m/y", strtotime($projetoData["dataEntrada"])); ?></td>
                                                <td><?php echo date("d/m/y", strtotime($projetoData["prazo"])); ?></td>
                                                <td><?php echo $etapas["p1"]; ?></td>
                                                <td><?php echo $etapas["p2"]; ?></td>
                                                <td><?php echo $etapas["p3"]; ?></td>
                                                <td><?php echo $etapas["p4"]; ?></td>
                                                <td><?php echo $etapas["p5"]; ?></td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table><!-- /row tarefas -->
                                </div>
                            </div><!-- /box -->
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        </section>
    </aside>
</div>


<!-- MODAL TAREFA GERAL -->
<div class="modal fade" id="modal-tarefa" tabindex="-1" role="dialog" aria-hidden="true"></div>

<script type="text/javascript">
    var idUsuarioLogado = '<?php echo $dadosUsuario["usuarioNerdweb"]; ?>';
</script>
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
<script src="../js/plugins/trumbowyg/trumbowyg.min.js" type="text/javascript"></script>

<script type="text/javascript">
    var base_url = '<?php echo POP_URL; ?>';
    $(".tabela-em-espera").dataTable({
        "bPaginate": false,
//		"bLengthChange": false,
        "bFilter": true,
//		"aaSorting": [[4, "asc"]],
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
