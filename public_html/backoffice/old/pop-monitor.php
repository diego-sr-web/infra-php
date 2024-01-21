<?php
require_once __DIR__ . "/../../autoloader.php";

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
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $titleNotif; ?>Monitor de <?php echo $projeto['nome']; ?> | POP Nerdweb</title>
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
        <section class="content-header" style="margin-bottom: 15px;">
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-bar-chart"></i> Monitores</a></li>
                <li class="active">Monitor de <?php echo $projeto["nome"]; ?></li>
            </ol>

            <div class="top-btns">
                <button style="margin-right: 5px;" class="btn btn-primary btn-flat border-0 js-getTarefa"
                        data-target="#modal-pedido" data-backdrop="static" data-acao="novo-pedido" data-toggle="modal">
                    <i class="fa fa-plus"></i> Adicionar Novo Pedido
                </button>
                <button class="btn btn-primary btn-flat border-0 js-getProjeto"
                        data-target="#modal-projeto" data-backdrop="static" data-acao="novo-projeto" data-toggle="modal">
                    <i class="fa fa-plus"></i> Adicionar Novo Projeto
                </button>
            </div>
        </section>

        <section class="content">
            <?php require_once __DIR__ . "/include/sucesso_error.php"; ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-solid">
                        <div class="box-header" style="background-color: #333333;">
                            <h3 class="box-title" style="color: #ffffff"><i class="fa fa-tasks"></i>&nbsp;Filtros</h3>
                        </div>
                        <div class="box-body">
                            <form method="get">
                                <input type="hidden" name="tipo" value="<?php echo $tipoProjetoGet; ?>">

                                <div class="row">
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>Cliente</label>
                                            <select name="cid" class="form-control">
                                                <option value="">Todos os clientes</option>
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
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>Área</label>
                                            <select name="aid" class="form-control">
                                                <option value="">Todas as áreas</option>
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
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>Responsável</label>
                                            <select name="uid" class="form-control">
                                                <option value="">Todos os funcionários</option>
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
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select name="sid" class="form-control">
                                                <option value="">Todos os status</option>
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
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>Prioridade</label>
                                            <select name="pid" class="form-control">
                                                <option value="">Todas as prioridades</option>
                                                <?php
                                                foreach ($listaPrioridade as $item) {
                                                    $selected = '';
                                                    if ((isset($_GET['pid']) && $item['prioridade'] == $_GET['pid'])) {
                                                        $selected = 'selected="selected"';
                                                    }
                                                    echo '<option ' . $selected . ' value="' . $item['prioridade'] . '">' . $item['nome'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <label></label>
                                        <button style="margin-top: 4px;" type="submit" name="action" class="btn btn-flat btn-primary btn-block">Filtrar
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <?php foreach ($tarefasProjeto as $listaTarefas) { ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-solid box-info custom-loading">
                            <div class="overlay"></div>
                            <div class="loading-img"></div>
                            <div class="box-header" style="background-color: <?php echo $projeto["cor"]; ?>">
                                <h3 class="box-title"><i class="fa <?php echo $projeto["icone"]; ?>"></i>&nbsp; Monitor
                                    de <?php echo $projeto["nome"]; ?></h3>
                            </div>
                            <div class="box-body table-responsive <?php
                            if (($isUserAdmin && (!isset($_GET['sid']) || (isset($_GET['sid']) && $_GET['sid'] != 8)))) {
                                echo 'table-space';
                            }?>"
                                 style="padding: 15px 5px 5px !important; position: relative;">
                                <?php if ($isUserAdmin && (!isset($_GET['sid']) || (isset($_GET['sid']) && $_GET['sid'] != 8))) { ?>
                                    <div class="btn-group btn-table">
                                        <button type="button" class="btn btn-flat btn-default dropdown-toggle"
                                                data-toggle="dropdown">Ações em Massa
                                        </button>
                                        <button type="button" class="btn btn-flat btn-default dropdown-toggle"
                                                data-toggle="dropdown">
                                            <span class="caret"></span>
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="#" class="js-acao-massa" data-acao="assumir-tarefas"
                                                   data-backdrop="static" data-target="#modal-tarefa">Assumir</a></li>
                                            <li><a href="#" class="js-acao-massa" data-acao="delegar-tarefas"
                                                   data-backdrop="static" data-target="#modal-tarefa">Delegar</a></li>
                                            <li><a href="#" class="js-acao-massa" data-acao="remover-tarefas"
                                                   data-backdrop="static" data-target="#modal-tarefa">Remover
                                                    responsável</a></li>
                                        </ul>
                                    </div>
                                <?php } ?>
                                <table class="table table-striped table-hover tabela-em-espera">
                                    <thead>
                                    <tr>
                                        <?php if ($isUserAdmin && (!isset($_GET['sid']) || (isset($_GET['sid']) && $_GET['sid'] != 8))) { ?>
                                            <th class="check" style="width: 18px;"><input type="checkbox"
                                                                                          name="check_all_espera"
                                                                                          value=""/></th>
                                        <?php } ?>
                                        <th>
                                            <div data-toggle="tooltip"
                                                 data-title="Shift + Clique para ordenar por mais de uma coluna">Id
                                            </div>
                                        </th>
                                        <th>
                                            <div data-toggle="tooltip"
                                                 data-title="Shift + Clique para ordenar por mais de uma coluna">Cliente
                                            </div>
                                        </th>
                                        <?php if ($tipoProjetoGet != 9) { ?>
                                            <th>
                                                <div data-toggle="tooltip"
                                                     data-title="Shift + Clique para ordenar por mais de uma coluna">
                                                    Projeto
                                                </div>
                                            </th>
                                        <?php } ?>
                                        <th>
                                            <div data-toggle="tooltip"
                                                 data-title="Shift + Clique para ordenar por mais de uma coluna">Tarefa
                                            </div>
                                        </th>
                                        <th>
                                            <div data-toggle="tooltip"
                                                 data-title="Shift + Clique para ordenar por mais de uma coluna">Área
                                            </div>
                                        </th>
                                        <th>
                                            <div data-toggle="tooltip"
                                                 data-title="Shift + Clique para ordenar por mais de uma coluna">Quem
                                            </div>
                                        </th>
                                        <th>
                                            <div data-toggle="tooltip"
                                                 data-title="Shift + Clique para ordenar por mais de uma coluna">Status
                                            </div>
                                        </th>
                                        <th>
                                            <div data-toggle="tooltip"
                                                 data-title="Shift + Clique para ordenar por mais de uma coluna">Entrada
                                            </div>
                                        </th>
                                        <th style="width: 120px;">
                                            <div data-toggle="tooltip"
                                                 data-title="Shift + Clique para ordenar por mais de uma coluna">Prazo
                                            </div>
                                        </th>
                                        <th>
                                            <div data-toggle="tooltip"
                                                 data-title="Shift + Clique para ordenar por mais de uma coluna">
                                                Prioridade
                                            </div>
                                        </th>
                                        <th style="min-width: <?php
                                        if (($isUserAdmin && (!isset($_GET['sid']) || (isset($_GET['sid']) && $_GET['sid'] != 8)))) {
                                            echo '195px';
                                        } else {
                                            echo '90px';
                                        } ?>;">
                                            Ações
                                        </th>
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
                                            <tr class="tarefa-box <?php
                                            if ((isset($tarefa['campos']['prioridade']) && $tarefa['campos']['prioridade'] == '101')) {
                                                echo 'prioridade-alta';
                                            }
                                            ?>">
                                                <?php if ($isUserAdmin && (!isset($_GET['sid']) || (isset($_GET['sid']) && $_GET['sid'] != 8))) { ?>
                                                    <td class="check"><input type="checkbox" name="check_espera"
                                                                             value="<?php echo $tarefa['elemento']; ?>"/>
                                                    </td>
                                                <?php } ?>
                                                <td><?php echo $tarefa['elemento']; ?></td>
                                                <td><?php echo $cliente; ?></td>
                                                <?php if ($tipoProjetoGet != 9) { ?>
                                                    <td><a style="font-weight: normal"
                                                           href="pop-projeto.php?projeto=<?php echo $projeto['projeto']; ?>"><?php echo $projeto['nome']; ?></a>
                                                    </td>
                                                <?php } ?>
                                                <td><?php echo $nomeTarefa; ?></td>
                                                <td><?php echo $areaResponsavel->retornaHtmlExibicao(); ?></td>
                                                <td>
                                                    <span style="display: none;"><?php echo $responsavel->retornaNomeExibicao(); ?></span> <?php echo $responsavel->retornaHtmlExibicao(); ?>
                                                </td>
                                                <td><?php echo $status->retornaHtmlExibicao(); ?></td>
                                                <td>
                                                    <span style="display: none;"><?php echo $dataCriacao->getValor() ?></span> <?php echo $dataCriacao->retornaHtmlExibicao(); ?>
                                                </td>
                                                <td><?php echo $prazo->retornaHtmlExibicaoPrazo(); ?></td>
                                                <td><?php echo $prioridade->retornaHtmlExibicao(); ?></td>
                                                <td>
                                                    <div class="tarefa-btns">
                                                        <?php if ($isUserAdmin && (!isset($_GET['sid']) || (isset($_GET['sid']) && $_GET['sid'] != 8))) { ?>
                                                            <?php if ($responsavel->retornaHtmlExibicao() == '') { ?>
                                                                <span data-toggle="tooltip" title="Assumir Tarefa">
																		<button class="btn btn-flat btn-primary border-0 js-getTarefa"
                                                                                data-toggle="modal"
                                                                                data-backdrop="static"
                                                                                data-target="#modal-tarefa"
                                                                                data-acao="assumir-tarefa"
                                                                                data-backdrop="static"
                                                                                data-tarefa="<?php echo $tarefa['elemento']; ?>">
																			<i class="fa fa-plus"></i>
																		</button>
																	</span>
                                                            <?php } else { ?>
                                                                <span data-toggle="tooltip" title="Remover Responsável">
																		<button class="btn btn-flat btn-primary border-0 js-getTarefa"
                                                                                data-toggle="modal"
                                                                                data-backdrop="static"
                                                                                data-target="#modal-tarefa"
                                                                                data-acao="remover-tarefa"
                                                                                data-backdrop="static"
                                                                                data-tarefa="<?php echo $tarefa['elemento']; ?>">
																			<i class="fa fa-minus"></i>
																		</button>
																	</span>
                                                            <?php } ?>
                                                            <span data-toggle="tooltip" title="Delegar Tarefa">
																	<button class="btn btn-flat btn-primary border-0 js-getTarefa"
                                                                            data-toggle="modal" data-backdrop="static"
                                                                            data-target="#modal-tarefa"
                                                                            data-acao="editar-tarefa"
                                                                            data-backdrop="static"
                                                                            data-tarefa="<?php echo $tarefa['elemento']; ?>">
																		<i class="fa fa-user-plus"></i>
																	</button>
																</span>
                                                        <?php } ?>

                                                        <?php if (in_array($tarefa['elementoTipo'], $array_tipo_pedido)) { ?>
                                                            <a href="pop-pedido.php?eid=<?php echo $tarefa['elemento']; ?>"
                                                               class="btn btn-flat bg-navy border-0"
                                                               data-toggle="tooltip" title="Ver Pedido">
                                                                <i class="fa fa-search-plus"></i>
                                                            </a>
                                                        <?php } else { ?>
                                                            <a href="pop-projeto.php?projeto=<?php echo $projeto['projeto']; ?>"
                                                               class="btn btn-flat bg-navy border-0"
                                                               data-toggle="tooltip" title="Ver Projeto">
                                                                <i class="fa fa-folder-open"></i>
                                                            </a>
                                                            <a href="pop-projeto.php?projeto=<?php echo $projeto['projeto']; ?>&trf=<?php echo $tarefa['elemento']; ?>"
                                                               class="btn btn-flat bg-navy border-0"
                                                               data-toggle="tooltip" title="Ver Tarefa">
                                                                <i class="fa fa-search-plus"></i>
                                                            </a>
                                                        <?php } ?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                    </tbody>
                                </table><!-- /row tarefas -->
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </section>
    </aside>
</div>

<!-- MODAL TAREFA GERAL -->
<div class="modal fade" id="modal-tarefa" tabindex="-1" role="dialog" aria-hidden="true"></div>

<!-- MODAL GERAL PROJETO -->
<div class="modal fade" id="modal-projeto" tabindex="-1" role="dialog" aria-hidden="true"></div>

<!-- MODAL PEDIDO GERAL -->
<div class="modal fade" id="modal-pedido" tabindex="-1" role="dialog" aria-hidden="true"></div>

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
<script src="../js/plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="../js/plugins/datepicker/locales/bootstrap-datepicker.pt-BR.js" type="text/javascript"></script>
<script src="../js/AdminLTE/app.js" type="text/javascript"></script>
<script src="../js/backoffice.js" type="text/javascript"></script>
<script src="../js/pop.js" type="text/javascript"></script>
<script src="../js/plugins/trumbowyg/trumbowyg.min.js" type="text/javascript"></script>

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
</body>
</html>
