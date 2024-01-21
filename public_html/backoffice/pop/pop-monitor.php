<?php
require_once __DIR__ . "/../../autoloader.php";
require_once __DIR__ . "/../_init.inc.php";
$database = new Database();
$usuario = new AdmUsuario($database);
$POPProjeto = new Projeto($database);
$POPElemento = new Elemento($database);
$BNCliente = new BNCliente($database);

require_once __DIR__ . '/../includes/is_logged.php';
require_once __DIR__ . '/include/le-notificacoes.php';

if (isset($_POST["form"]) && $_POST["form"] == "novo-pedido") {
    require_once __DIR__ . "/pop-processa-modal.php";
}
require_once __DIR__ . "/pop-projetos-post.php";
require_once __DIR__ . "/pop-minhas-tarefas-post.php";

$listaPrioridade = $POPElemento->getPrioridadeList();
$listaClientes = $BNCliente->listAll("", "nomeFantasia");

$listaAreas = $usuario->listArea();
$listaUsuarios = $usuario->listAll("", "nome", FALSE);

$array_tipo_pedido = [59, 82, 105];

$listaStatus = [
    ['elementoStatus' => 1, 'nome' => 'Aguardando Responsável'],
    ['elementoStatus' => 2, 'nome' => 'Aguardando Início'],
    ['elementoStatus' => 3, 'nome' => 'Aguardando Cliente'],
    ['elementoStatus' => 16, 'nome' => 'Arquivado'],
    ['elementoStatus' => 4, 'nome' => 'Em Andamento'],
    ['elementoStatus' => 5, 'nome' => 'Pausado'],
    ['elementoStatus' => 7, 'nome' => 'Problema'],
    ['elementoStatus' => 8, 'nome' => 'Finalizado'],
];

$tipoProjetoGet = $_GET['tipo'] ?? 2;
$tipoProjetoSecundario = [];
if (isset($tipoProjetoGet))  {
    if($tipoProjetoGet == 16) {
        $tipoProjetoSecundario = [21,23,25,26];
    }elseif($tipoProjetoGet == 2) {
        $tipoProjetoSecundario = [15];
    }
}

// Como é um monitor, ele observa todas as areas, [] foi um jeito de sinalizar todas
$limpaFinalizado = TRUE;
if (isset($_GET['sid']) && $_GET['sid'] !== '') {
    $limpaFinalizado = FALSE;
}
$todosElementos = $POPElemento->getAllElementsFiltered($limpaFinalizado, [], $_SESSION['adm_usuario'], NULL, $tipoProjetoGet);

if (isset($tipoProjetoSecundario)) {
    foreach ($tipoProjetoSecundario as $item) {
        $elementoSecundarios = $POPElemento->getAllElementsFiltered2($limpaFinalizado, [], $_SESSION['adm_usuario'], NULL, $item);
        $todosElementos = array_merge($todosElementos, $elementoSecundarios);
    }
}

$listaTarefas = $POPElemento->filtraElementosPorBase($todosElementos, 1);
$listaTarefas = $POPElemento->agrupaTarefas($listaTarefas);
$listaTarefas = $listaTarefas['tarefas'];

// Forçando um prazo, caso a tarefa nao tenha marca o prazo do projeto
foreach ($listaTarefas as $key => $value) {
    if (!isset($value['campos']['prazo'])) {
        $pid = $value['projeto'];
        $listaTarefas[$key]['campos']['prazo'] = $POPProjeto->getProjectPrazo($pid);
        $listaTarefas[$key]['prazo'] = $listaTarefas[$key]['campos']['prazo'];
    }

    $listaTarefas[$key]['prazo'] = strtotime($listaTarefas[$key]['campos']['prazo']);
}

if (isset($_GET['cid']) && $_GET['cid'] !== '') {
    if ($tipoProjetoGet == 9) {
        $cli = $BNCliente->getDataWithId($_GET['cid']);

        foreach ($listaTarefas as $key => $value) {
            if ($value['campos']['Cliente'] != $cli['nomeFantasia']) {
                unset($listaTarefas[$key]);
            }
        }
    }
    else {
        foreach ($listaTarefas as $key => $value) {
            $projeto = $POPProjeto->getProjectById($value['projeto']);

            if ($projeto['cliente'] != $_GET['cid']) {
                unset($listaTarefas[$key]);
            }
        }
    }
}
if (isset($_GET['aid']) && $_GET['aid'] !== '') {
    foreach ($listaTarefas as $key => $value) {
        if ($value['campos']['area'] != $_GET['aid']) {
            unset($listaTarefas[$key]);
        }
    }
}
if (isset($_GET['pid']) && $_GET['pid'] !== '') {
    foreach ($listaTarefas as $key => $value) {
        if ($value['campos']['prioridade'] != $_GET['pid']) {
            unset($listaTarefas[$key]);
        }
    }
}
if (isset($_GET['sid']) && $_GET['sid'] !== '') {
    foreach ($listaTarefas as $key => $value) {
        if ($_GET['sid'] == 8) {
            if ($value['elementoStatus'] <= 8) {
                unset($listaTarefas[$key]);
            }
        }
        else {
            if ($value['elementoStatus'] != $_GET['sid']) {
                unset($listaTarefas[$key]);
            }
        }
    }
}
if (isset($_GET['uid']) && $_GET['uid'] !== '') {
    foreach ($listaTarefas as $key => $value) {
        if ($value['campos']['responsavel'] != $_GET['uid']) {
            unset($listaTarefas[$key]);
        }
    }
}

$tarefasProjeto = [];
// Separa tarefas por projeto em blocos ( caso seja lido mais de uma, nao eh usado no caso atual
foreach ($listaTarefas as $tarefa) {
    $pid = $tarefa['projeto'];
    //$tipo = $POPProjeto->getProjectTypeByProjectId($pid);
    //$tipo = $tipo['projetoTipo'];
    $tarefasProjeto[0][] = $tarefa;
}

$projeto = $POPProjeto->getProjectTypeById($tipoProjetoGet);
?>
<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->
<head>
    <?php $Template->insert("backoffice/materialize-head-3", ["pageTitle" => "Blank Page | POP Nerdweb"]); ?>
    <link rel="stylesheet" type="text/css" href="../../assets/vendors/flag-icon/css/flag-icon.min.css">
    <link rel="stylesheet" type="text/css" href="../../assets/vendors/data-tables/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="../../assets/vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="../../assets/css/pages/data-tables.css">

    </h
    <!-- END: Head-->
<body class="vertical-layout page-header-light vertical-menu-collapsible vertical-gradient-menu preload-transitions 2-columns" data-open="click" data-menu="vertical-gradient-menu" data-col="2-columns">
<!-- BEGIN: Header-->
<header class="page-topbar" id="header">
    <?php $Template->insert("backoffice/materialize-header"); ?>
</header>
<!-- END: Header-->
<!-- BEGIN: SideNav-->
<?php $Template->insert("backoffice/materialize-sidebar-pop"); ?>
<!-- END: SideNav-->
<!-- BEGIN: Page Main-->
<div id="main">
    <div class="row">
        <div class="pt-2 pb-1" id="breadcrumbs-wrapper">
            <!-- Search for small screen-->
            <div class="container">
                <div class="row">
                    <div class="col s12 m6 l6">
                        <h4 class="mt-0 mb-0"><span>Monitores</span></h4>
                    </div>
                    <div class="col s12 m6 l6 right-align-md">
                        <ol class="breadcrumbs mt-0 mb-0">
                            <li class="breadcrumb-item"><a href="main.php">Home</a></li>
                            <li class="breadcrumb-item active">Monitor de <?php echo $projeto["nome"]; ?></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="col s12">
            <div class="container">
                <div class="row">
                    <div class="col s12 m2 float-right mb-1">
                        <button class="waves-effect waves-light blue darken-3 btn js-getTarefa" style="width: 100%;" data-target="#modal-pedido" data-backdrop="static" data-acao="novo-pedido" data-toggle="modal"><i class="material-icons left" style="right: -30px;position: relative;">add</i> Novo Pedido</button>
                    </div>
                    <div class="col s12 m2 float-right mb-1">
                        <button class="waves-effect waves-light blue darken-3 btn js-getProjeto" style="width: 100%;" data-target="#modal-projeto" data-backdrop="static" data-acao="novo-projeto" data-toggle="modal"><i class="material-icons left" style="right: -30px;position: relative;">add</i> Novo Projeto</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col s12">
            <div class="container">
                <?php require_once __DIR__ . "/include/sucesso_error.php"; ?>
                <div class="section">
                    <div class="row">
                        <div class="col s12 m12">
                            <div class="section section-data-tables p-0">
                                <!-- lista de tarefas -->
                                <div class="row">
                                    <div class="col s12">
                                        <div class="card mt-0">
                                            <ul id="projects-collection" class="collection z-depth-1 grey darken-3 mt-0 mb-0">
                                                <li class="collection-item avatar mh-0 bg-t">
                                                    <i class="material-icons circle grey darken-4">dvr</i>
                                                    <h6 class="collection-header m-0 white-text">Filtros</h6>
                                                    <p class="white-text">Selecione Sua Opção</p>
                                                </li>
                                            </ul>
                                            <div class="card-content">
                                                <form method="get">
                                                    <input type="hidden" name="tipo" value="<?php echo $tipoProjetoGet; ?>">
                                                    <div class="row">
                                                        <div class="col s13 m10">
                                                            <div class="col m3 s12 mt-1 mb-1">
                                                                <div class="input-field mt-0 mb-0">
                                                                    <select class="select2-theme browser-default" id="select2-theme" name="cid">
                                                                        <option value="romboid">Clientes</option>
                                                                        <?php
                                                                        foreach ($listaClientes as $item) {
                                                                            $selected = '';
                                                                            if ((isset($_GET['cid']) && $item['cliente'] == $_GET['cid'])) {
                                                                                $selected = 'selected="selected"';
                                                                            }
                                                                            echo '<option ' . $selected . ' value="' . $item['cliente'] . '">' . $item['nomeFantasia'] . '</option>';
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col m3 s12 mt-1 mb-1">
                                                                <div class="input-field mt-0 mb-0">
                                                                    <select class="select2-theme browser-default" id="select2-theme" name="aid" >
                                                                        <option value="romboid">Áreas</option>
                                                                        <?php
                                                                        foreach ($listaAreas as $item) {
                                                                            $selected = '';
                                                                            if ((isset($_GET['aid']) && $item['area'] == $_GET['aid'])) {
                                                                                $selected = 'selected="selected"';
                                                                            }
                                                                            echo '<option ' . $selected . ' value="' . $item['area'] . '">' . $item['nome'] . '</option>';
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col m3 s12 mt-1 mb-1">
                                                                <div class="input-field mt-0 mb-0">
                                                                    <select class="select2-theme browser-default" id="select2-theme" name="uid">
                                                                        <option value="romboid">Funcionários</option>
                                                                        <?php
                                                                        foreach ($listaUsuarios as $item) {
                                                                            $selected = '';
                                                                            if ((isset($_GET['uid']) && $item['usuarioNerdweb'] == $_GET['uid'])) {
                                                                                $selected = 'selected="selected"';
                                                                            }
                                                                            echo '<option ' . $selected . ' value="' . $item['usuarioNerdweb'] . '">' . $item['nome'] . '</option>';
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col m3 s12 mt-1 mb-1">
                                                                <div class="input-field mt-0 mb-0">
                                                                    <select class="select2-theme browser-default" id="select2-theme" name="sid" >
                                                                        <option value="romboid">Status</option>
                                                                        <?php
                                                                        foreach ($listaStatus as $item) {
                                                                            $selected = '';
                                                                            if ((isset($_GET['sid']) && $item['elementoStatus'] == $_GET['sid'])) {
                                                                                $selected = 'selected="selected"';
                                                                            }
                                                                            echo '<option ' . $selected . ' value="' . $item['elementoStatus'] . '">' . $item['nome'] . '</option>';
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col s12 m2 mt-1 mb-1">
                                                            <button class="btn waves-effect waves-light right blue darken-3" type="submit" name="action" style="width: 100%">Filtrar</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col s12">
            <div class="container">
                <div class="section">
                    <?php foreach ($tarefasProjeto as $listaTarefas) { ?>
                    <div class="row">
                        <div class="col s12 m12">
                            <div class="section section-data-tables p-0">
                                <!-- lista de tarefas -->
                                <div class="row">
                                    <div class="col s12">
                                        <div class="card mt-0">
                                            <ul id="projects-collection" class="collection z-depth-1 mt-0 mb-0" style="background-color: <?php echo $projeto["cor"]; ?>">
                                                <li class="collection-item avatar mh-0 bg-t">
                                                    <i class="material-icons circle <?php echo $projeto["cor"]; ?>"><?php echo $projeto["icone"]; ?></i>
                                                    <h6 class="collection-header m-0 white-text">Monitor</h6>
                                                    <p class="white-text"> <?php echo $projeto["nome"]; ?></p>
                                                </li>
                                            </ul>
                                            <div class="card-content">
                                                <div class="row">
                                                    <div class="col s12">
                                                        <table id="page-length-option"  class="display dataTable dtr-inline" role="grid" aria-describedby="datatable-monitor" style=" width: 100%; white-space: nowrap;">
                                                            <thead>
                                                            <tr role="row">
                                                                <th>
                                                                    <label>
                                                                        <input type="checkbox" class="select-all" name="check_all_espera" value=""/>
                                                                        <span></span>
                                                                    </label>
                                                                </th>
                                                                <th>ID</th>
                                                                <th>Cliente</th>
                                                                <th>Projeto</th>
                                                                <th>Tarefa</th>
                                                                <th>Área</th>
                                                                <th>Quem</th>
                                                                <th>Status</th>
                                                                <th>Entrada</th>
                                                                <th>Prazo</th>
                                                                <th>Ação</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                            foreach ($listaTarefas as $tarefa) {
                                                                $projeto = $POPProjeto->getProjectById($tarefa['projeto']);
                                                                if (isset($projeto["projeto"])) {
                                                                    $tipoProjeto = $POPProjeto->getProjectTypeByProjectId($projeto['projeto']);
                                                                    $tipoTarefa = $POPElemento->getElementTypeById($tarefa['elementoTipo']);

                                                                    if ($tarefa["projeto"] == 13) {
                                                                        $cliente = $tarefa["campos"]["Cliente"];
                                                                    }
                                                                    else {
                                                                        $cliente = $BNCliente->getDataWithId($projeto['cliente']);
                                                                        /** @var array $cliente */
                                                                        $cliente = $cliente['nomeFantasia'];
                                                                    }

                                                                    $areaResponsavel = new area('area', $tarefa['campos']['area'], $database);
                                                                    $responsavel = new responsavel('responsavel', $tarefa["campos"]["responsavel"], $database);
                                                                    $status = new status('elementoStatus', $tarefa['elementoStatus'], $database);
                                                                    $dataCriacao = new date('dataCriacao', $tarefa['dataCriacao'], $database);
                                                                    $prazo = new date('prazo', $tarefa['campos']['prazo'], $database);
                                                                    $prioridade = new prioridade('prioridade', $tarefa['campos']['prioridade'], $database);
                                                                    $infoSubetapa = [];

                                                                    if (isset($tarefa['campos']['Etapa'])) {
                                                                        $infoSubetapa = $POPElemento->get_SubEtapa_Info($tarefa['elementoTipo'], $tarefa['campos']['Etapa']);
                                                                    }

                                                                    if ($infoSubetapa && isset($tarefa['campos']['contagem']['total'])) {
                                                                        if ($tarefa['campos']['contagem']['total'] > 1) {
                                                                            $nomeTarefa = $infoSubetapa['nome'] . ' (' . $tarefa['campos']['contagem']['parcial'] . ' de ' . $tarefa['campos']['contagem']['total'] . ')';
                                                                        }
                                                                        else {
                                                                            $nomeTarefa = $infoSubetapa['nome'];
                                                                        }
                                                                    }
                                                                    else {
                                                                        if (in_array($tarefa["elementoTipo"], $array_tipo_pedido)) {
                                                                            $nomeTarefa = $tarefa["campos"]["Nome"];
                                                                        }
                                                                        else {
                                                                            $nomeTarefa = $tipoTarefa['nome'];
                                                                        }
                                                                    }
                                                                    ?>
                                                                    <tr role="row" class="odd" <?php
                                                                    if ((isset($tarefa['campos']['prioridade']) && $tarefa['campos']['prioridade'] == '101')) {
                                                                        echo 'prioridade-alta';
                                                                    }
                                                                    ?>">
                                                                    <?php if ($isUserAdmin && (!isset($_GET['sid']) || (isset($_GET['sid']) && $_GET['sid'] != 8))) { ?>
                                                                        <td class="check">
                                                                            <label>
                                                                                <input type="checkbox" name="check_espera" name="check_espera"
                                                                                       value="<?php echo $tarefa['elemento']; ?>"/>
                                                                                <span></span>
                                                                            </label>
                                                                        </td>
                                                                    <?php } ?>
                                                                    <td class="max-length"><?php echo $tarefa['elemento']; ?></td>
                                                                    <td class="max-length"><?php echo $cliente; ?></td>
                                                                    <?php if ($tipoProjetoGet != 9) { ?>
                                                                        <td class="max-length"><a href="pop-projeto.php?projeto=<?php echo $projeto['projeto']; ?>"><?php echo $projeto['nome']; ?></td>
                                                                    <?php } ?>
                                                                    <td class="max-length"><?php echo $nomeTarefa; ?></td>
                                                                    <td><?php echo $areaResponsavel->retornaHtmlExibicao(); ?></td>
                                                                    <td>
                                                                    <span class="avatar-status avatar-online lh">
                                                                        <?php echo $responsavel->retornaHtmlExibicao(); ?>
                                                                    </span>
                                                                    </td>
                                                                    <td><?php echo $status->retornaHtmlExibicao(); ?></td>
                                                                    <td><span style="display: none;"><?php echo $dataCriacao->getValor() ?></span> <?php echo $dataCriacao->retornaHtmlExibicao(); ?></td>
                                                                    <td><?php echo $prazo->retornaHtmlExibicaoPrazo(); ?></td>
                                                                    <td>
                                                                        <?php if ($isUserAdmin && (!isset($_GET['sid']) || (isset($_GET['sid']) && $_GET['sid'] != 8))) { ?>
                                                                            <?php if ($responsavel->retornaHtmlExibicao() == '') { ?>
                                                                                <span data-toggle="tooltip" title="Assumir Tarefa">
																		<button class="btn-floating btn-small btn-flat waves-effect waves-light blue darken-3  white-text mr-1 js-getTarefa"
                                                                                data-toggle="modal"
                                                                                data-backdrop="static"
                                                                                data-target="#modal-tarefa"
                                                                                data-acao="assumir-tarefa"
                                                                                data-backdrop="static"
                                                                                data-tarefa="<?php echo $tarefa['elemento']; ?>">
																			<i class="material-icons">person_add</i>
																		</button>
																	</span>
                                                                            <?php } else { ?>
                                                                                <span data-toggle="tooltip" title="Remover Responsável">
																		<button class="btn-floating btn-small btn-flat waves-effect waves-light blue darken-3  white-text mr-1 js-getTarefa"
                                                                                data-toggle="modal"
                                                                                data-backdrop="static"
                                                                                data-target="#modal-tarefa"
                                                                                data-acao="remover-tarefa"
                                                                                data-backdrop="static"
                                                                                data-tarefa="<?php echo $tarefa['elemento']; ?>">
																			<i class="material-icons">remove</i>
																		</button>
																	</span>
                                                                            <?php } ?>
                                                                            <span data-toggle="tooltip" title="Delegar Tarefa">
																	<button class="btn-floating btn-small btn-flat waves-effect waves-light blue darken-3  white-text mr-1 js-getTarefa"
                                                                            data-toggle="modal" data-backdrop="static"
                                                                            data-target="#modal-tarefa"
                                                                            data-acao="editar-tarefa"
                                                                            data-backdrop="static"
                                                                            data-tarefa="<?php echo $tarefa['elemento']; ?>">
																		<i class="material-icons">person_add</i>
																	</button>
																</span>
                                                                        <?php } ?>

                                                                        <?php if (in_array($tarefa['elementoTipo'], $array_tipo_pedido)) { ?>
                                                                            <a href="pop-pedido.php?eid=<?php echo $tarefa['elemento']; ?>"
                                                                               class="btn-floating btn-small btn-flat waves-effect waves-light blue darken-3  white-text mr-1"
                                                                               data-toggle="tooltip" title="Ver Pedido">
                                                                                <i class="material-icons">zoom_in</i>
                                                                            </a>
                                                                        <?php } else { ?>
                                                                            <a href="pop-projeto.php?projeto=<?php echo $projeto['projeto']; ?>"
                                                                               class="btn-floating btn-small btn-flat waves-effect waves-light blue darken-3  white-text mr-1"
                                                                               data-toggle="tooltip" title="Ver Projeto">
                                                                                <i class="material-icons">folder</i>
                                                                            </a>
                                                                            <a href="pop-projeto.php?projeto=<?php echo $projeto['projeto']; ?>&trf=<?php echo $tarefa['elemento']; ?>"
                                                                               class="btn-floating btn-small btn-flat waves-effect waves-light blue darken-3  white-text mr-1"
                                                                               data-toggle="tooltip" title="Ver Tarefa">
                                                                                <i class="material-icons">zoom_in</i>
                                                                            </a>
                                                                        <?php } ?>
                                                                    </td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-overlay"></div>
        </div>
        <?php } ?>
    </div>
</div>
<!-- END: Page Main-->
<!-- BEGIN: Footer-->
<?php $Template->insert("backoffice/materialize-footer"); ?>
<!-- END: Footer-->
<!-- MODAL TAREFA GERAL -->
<div class="modal fade" id="modal-tarefa" tabindex="-1" role="dialog" aria-hidden="true"></div>

<!-- MODAL GERAL PROJETO -->
<div class="modal fade" id="modal-projeto" tabindex="-1" role="dialog" aria-hidden="true"></div>

<!-- MODAL PEDIDO GERAL -->
<div class="modal fade" id="modal-pedido" tabindex="-1" role="dialog" aria-hidden="true"></div>

<script type="text/javascript">
    var idUsuarioLogado = '<?php echo $dadosUsuario["usuarioNerdweb"]; ?>';
</script>
<script src="../../assets/js/vendors.min.js"></script>
<script src="../../assets/vendors/data-tables/js/jquery.dataTables.min.js"></script>
<script src="../../assets/vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js"></script>
<script src="../../assets/vendors/data-tables/js/dataTables.select.min.js"></script>
<script src="../../assets/js/plugins.js"></script>
<script src="../../assets/js/search.js"></script>
<script src="../../assets/js/scripts/data-tables.js"></script>
<script type="text/javascript">
    var base_url = '<?php echo POP_URL; ?>';
    var colunaPrazo, sorting;

    <?php if($tipoProjetoGet == 9) { ?>
    colunaPrazo = 6;
    <?php } else { ?>
    colunaPrazo = 7;
    <?php } ?>

    var unsortables = [colunaPrazo + 2];

    <?php if ($isUserAdmin && (!isset($_GET['sid']) || (isset($_GET['sid']) && $_GET['sid'] != 8))) { ?>
    colunaPrazo = colunaPrazo + 1;
    unsortables = [0, colunaPrazo + 2];
    <?php } ?>

    sorting = [[colunaPrazo, "asc"], [colunaPrazo + 1, "asc"]];

    $(".tabela-em-espera").dataTable({
        "bPaginate": true,
        "iDisplayLength": 50,
        //"bLengthChange": false,
        "bFilter": true,
        "aoColumnDefs": [
            {"bSortable": false, "aTargets": unsortables}
        ],
        "aaSorting": sorting,
        "fnDrawCallback": function (oSettings) {
            $("input[type='checkbox']:not(.simple), input[type='radio']:not(.simple)").iCheck({
                checkboxClass: "icheckbox_minimal", radioClass: "iradio_minimal"
            });
        }
    });

    $(document).on('ifChecked', '#modal-projeto input[name=projetoTipo]', function (event) {
        var idProjetoTipo = $(this).val();

        $.ajax({
            url: 'ajax/ajax-pop.php',
            type: 'POST',
            data: {
                secao: 'projeto',
                tipo: 'mostra-campos-extra',
                projetoTipo: idProjetoTipo
            }
        })
            .done(function (retorno) {
                $('#modal-projeto .campos-extra').html('');

                if (retorno.tipo == 'html') {
                    var htmlExtra = '<h4 style="margin-top: 30px;">Campos Adicionais do Tipo de Projeto</h4><hr style="margin: 7px 0">' + retorno.conteudo;
                    $('#modal-projeto .campos-extra').html(htmlExtra);
                }
            })
            .fail(function (retorno) {
                console.log(retorno);
            });
    });

    $(document).on('click', '.js-getProjeto', function (event) {
        event.preventDefault();

        var idModal = $(this).attr('data-target');
        var idProjeto = $(this).attr('data-projeto');
        var acao = $(this).attr('data-acao');

        //console.log(acao);
        var loadModal = true;

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

    $(document).on('click', '.js-acao-massa', function (event) {
        event.preventDefault();
        var $btn = $(this);

        var acao = $btn.attr('data-acao');
        var idModal = $btn.attr('data-target');
        var input_name = 'check_espera';
        var elementos = [];

        $('input[name=' + input_name + ']').each(function () {
            if (this.checked) {
                elementos.push(parseInt($(this).val()));
            }
        });

        if (elementos.length > 0) {
            $(idModal).hide();

            $.ajax({
                url: 'ajax/ajax-pop.php',
                type: 'POST',
                data: {
                    secao: 'tarefas',
                    tipo: acao,
                    tarefas: JSON.stringify(elementos)
                }
            })
                .done(function (retorno) {
                    if (retorno.tipo == 'html') {
                        $(idModal).html(retorno.conteudo);
                        $(idModal).modal('show');
                    } else {
                        console.log(retorno);
                    }
                })
                .fail(function (retorno) {
                    console.log(retorno);
                });
        } else {
            $(idModal).modal('hide');
        }
    });

    $(document).ready(function () {
        $('.custom-loading .overlay, .custom-loading .loading-img').remove();
    });
</script>
<!-- END: SCRIPTS-->
</body>
</html>
