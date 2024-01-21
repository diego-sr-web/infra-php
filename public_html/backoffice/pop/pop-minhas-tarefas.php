<?php
require_once __DIR__ . "/../../autoloader.php";

$database = new Database();
$usuario = new AdmUsuario($database);
$POPProjeto = new Projeto($database);
$POPElemento = new Elemento($database);
$BNCliente = new BNCliente($database);

$array_tipo_pedido = [59, 82, 105];

require_once __DIR__ . '/../includes/is_logged.php';
require_once __DIR__ . '/include/le-notificacoes.php';
/*
if (isset($_POST["form"]) && ($_POST["form"] == "novo-pedido" || $_POST["form"] == "novo-hora")) {
    require_once __DIR__ . "/pop-processa-modal.php";
}

else {
    require_once __DIR__ . "/pop-projetos-post.php";
    require_once __DIR__ . "/pop-minhas-tarefas-post.php";
}
*/
/** @var array $listaTarefas ['atuais'] */
/** @var array $listaTarefas ['disponiveis'] */
/** @var array $cliente */


$listaTiposProjeto = $listaTiposProjeto2 = [];
$auxTiposProjeto = $POPProjeto->getProjectTypeList(['escondido'], [0], 'nome ASC');
$auxTiposProjeto2 = $POPProjeto->getProjectTypeList(['escondido'], [0], 'nome ASC');

$listaClientes = $BNCliente->listAll("", "nomeFantasia");

foreach ($auxTiposProjeto as $tipo) {
    $listaTiposProjeto[$tipo['projetoTipo']] = $tipo;
}

foreach ($auxTiposProjeto2 as $tipo) {
    $listaTiposProjeto2[$tipo['projetoTipo']] = $tipo;
}

$ordens = [1 => ['nome' => 'ID', 'campo' => 'projeto', 'nivel' => 0], 2 => ['nome' => 'Nome', 'campo' => 'nome', 'nivel' => 0], 3 => ['nome' => 'Prazo', 'campo' => 'prazo', 'nivel' => 0], 4 => ['nome' => 'Prioridade', 'campo' => 'prioridade', 'nivel' => 0], 5 => ['nome' => 'Área', 'campo' => 'area', 'nivel' => 0],];

$filtros = [1 => ['nome' => 'Tipo de Projeto', 'campo' => 'projetoTipo', 'valores' => []], 2 => ['nome' => 'Cliente', 'campo' => 'cliente', 'valores' => []],];

foreach ($listaTiposProjeto as $item) {
    $filtros[1]['valores'][] = ['id' => $item['projetoTipo'], 'nome' => $item['nome'],];
}

$arrayQueryString = $_GET;
$queryString = http_build_query($arrayQueryString);

$todosElementos = $POPElemento->getAllElementsFiltered(TRUE, $areasId, $_SESSION["adm_usuario"]);
//$todosElementos = $POPElemento->getAllElements([],[],TRUE);

if (isset($_GET["busca"]) && $_GET["busca"] !== "") {
    $clientList = [];
    foreach ($listaClientes as $cliente) {
        $clientList[$cliente["cliente"]] = $cliente["nomeFantasia"];
    }
    $tmpBusca = [];
    //$i = 0;
    $buscaStr = $_GET["busca"];
    foreach ($todosElementos as $key => $value) {
        $projetoData = $POPProjeto->getProjectById($value["projeto"]);
        $projetoNome = NULL;
        if (isset($value["campos"]["Nome"]) && $value["campos"]["Nome"] != "") {
            $nome = $value["campos"]["Nome"];

        }
        elseif (isset($value["campos"]["Etapa"]) && $value["campos"]["Etapa"] != "") {
            $subEtapa = $POPElemento->get_SubEtapa_Info($value["elementoTipo"], $value["campos"]["Etapa"]);
            $nome = $subEtapa["nome"];
        }
        else {
            $tipo = $POPElemento->getElementTypeById($value["elementoTipo"]);
            $nome = $tipo["nome"];
        }
        if (isset($value["campos"]["Cliente"]) && $value["campos"]["Cliente"] != "") {
            $cliente = $value["campos"]["Cliente"];
        }
        else {
            if (isset($clientList[$projetoData["cliente"]])) {
                $cliente = $clientList[$projetoData["cliente"]];
            }
            else {
                $cliente = "";
            }
        }
        if (isset($projetoData["cliente"])) {
            $projetoNome = $projetoData["nome"];
        }

        if ((stripos($nome, $buscaStr) !== FALSE) || (stripos($cliente, $buscaStr) !== FALSE) || (stripos($projetoNome, $buscaStr) !== FALSE)) {
            $tmpBusca[] = $value;
        }
    }
}
if (isset($tmpBusca)) {
    $todosElementos = $tmpBusca;
}

$auxTarefas = $listaTarefas = ['atuais' => [], 'disponiveis' => []];

$todasTarefas = $POPElemento->filtraElementosPorBase($todosElementos, 1);

foreach ($todasTarefas as $aux) {
    $arrayStatusDescartado = [8, 9, 11, 14, 15, 16];
    if (!in_array($aux['elementoStatus'], $arrayStatusDescartado)) {
        if ($aux['campos']['responsavel'] == $_SESSION['adm_usuario']) {
            $listaTarefas['atuais'][] = $aux;
        }
        else {
            if (in_array($aux['campos']['area'], $areasId)) {
                if ($aux['campos']['responsavel'] == NULL) {
                    $listaTarefas['disponiveis'][] = $aux;
                }
            }
        }
    }
}

$auxTarefas['atuais'] = $POPElemento->agrupaTarefas($listaTarefas['atuais']);
$auxTarefas['disponiveis'] = $POPElemento->agrupaTarefas($listaTarefas['disponiveis']);
$listaTarefas['disponiveis'] = [];

foreach ($auxTarefas['disponiveis']['tarefas'] as $key => $value) {
    if (!isset($value['campos']['prazo'])) {
        $pid = $value['projeto'];
        $auxTarefas['disponiveis']['tarefas'][$key]['campos']['prazo'] = $POPProjeto->getProjectPrazo($pid);
        $auxTarefas['disponiveis']['tarefas'][$key]['prazo'] = strtotime($auxTarefas['disponiveis']['tarefas'][$key]['campos']['prazo']);
    }
    else {
        // Variavel usada pra organizar por prazo o projeto ( transforma a data de prazo em segundos pra ficar mais facil de fazer comparacao )
        $auxTarefas['disponiveis']['tarefas'][$key]['prazo'] = strtotime($auxTarefas['disponiveis']['tarefas'][$key]['campos']['prazo']);
    }

    $area = $auxTarefas['disponiveis']['tarefas'][$key]['campos']['area'];
    $listaTarefas['disponiveis'][0][] = $auxTarefas['disponiveis']['tarefas'][$key];
    $listaTarefas['disponiveis'][$area][] = $auxTarefas['disponiveis']['tarefas'][$key];
}


$listaTarefas['atuais'] = [];

foreach ($auxTarefas['atuais']['tarefas'] as $key => $value) {
    if (!isset($value['campos']['prazo'])) {
        $pid = $value['projeto'];
        $auxTarefas['atuais']['tarefas'][$key]['campos']['prazo'] = $POPProjeto->getProjectPrazo($pid);
        $auxTarefas['atuais']['tarefas'][$key]['prazo'] = strtotime($auxTarefas['atuais']['tarefas'][$key]['campos']['prazo']);
    }
    else {
        // Variavel usada pra organizar por prazo o projeto ( transforma a data de prazo em segundos pra ficar mais facil de fazer comparacao )
        $auxTarefas['atuais']['tarefas'][$key]['prazo'] = strtotime($auxTarefas['atuais']['tarefas'][$key]['campos']['prazo']);
    }

    $area = $auxTarefas['atuais']['tarefas'][$key]['campos']['area'];
    $listaTarefas['atuais'][0][] = $auxTarefas['atuais']['tarefas'][$key];
    $listaTarefas['atuais'][$area][] = $auxTarefas['atuais']['tarefas'][$key];
}
$fazerTarefa = FALSE;
if ((isset($_GET['tarefa']) && $_GET['tarefa'])) {
    $fazerTarefa = TRUE;
}
$countTodas = 0;
if (isset($listaTarefas['disponiveis'][0])) {
    $countTodas = count($listaTarefas['disponiveis'][0]);
}

$countTodasAtuais = 0;
if (isset($listaTarefas['atuais'][0]) ){
    $countTodasAtuais = count($listaTarefas['atuais'][0]);
}
$_SESSION["NUM_TAREFAS"] = $countTodas;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo "(" . $countTodas . ") "; ?> Minhas Tarefas | POP Nerdweb</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
          integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="../css/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css"/>
    <link href="../css/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>
    <link href="../css/ionicons.css" rel="stylesheet" type="text/css"/>
    <link href="../css/AdminLTE.css" rel="stylesheet" type="text/css"/>
    <link href="../css/pop.css?v=1.5" rel="stylesheet" type="text/css"/>
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
                <li><a href="#"><i class="fa fa-tasks"></i> Tarefas</a></li>
                <li class="active">Minhas Tarefas</li>
            </ol>

            <div class="top-btns">
                <div style="margin-right: 5px;" class="btn-group">

                    <button type="button" class="btn btn-primary btn-flat border-0 js-getTarefa"
                            data-target="#modal-pedido" data-acao="novo-pedido" data-toggle="modal"
                            data-backdrop="static">
                        <i class="fa fa-plus"></i>&nbsp; Novo Pedido
                    </button>
                    <button type="button" class="btn btn-primary btn-flat border-0 dropdown-toggle"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="js-getTarefa" data-target="#modal-pedido" data-acao="novo-pedido"
                               data-toggle="modal" data-backdrop="static" href="#">Novo Pedido</a></li>
                        <li><a class="js-getTarefa" data-target="#modal-pedido" data-acao="novo-pedido-cliente"
                               data-toggle="modal" data-backdrop="static" href="#">Novo Pedido de Cliente</a></li>
                    </ul>
                </div>
                <button class="btn btn-primary btn-flat border-0 js-getProjeto" data-target="#modal-projeto"
                        data-acao="novo-projeto" data-toggle="modal" data-backdrop="static">
                    <i class="fa fa-plus"></i>&nbsp; Novo Projeto
                </button>
            </div>
        </section>

        <section class="content">
            <?php require_once __DIR__ . "/include/sucesso_error.php"; ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom tabs-atuais box-primary">
                        <ul class="nav nav-tabs pull-right">
                            <li class="tab-li"><a class="dado-destaque js-aba-tarefas-atuais" href="#tab-area-atuais-0"
                                                  data-toggle="tab" data-tabnum="0">Todas
                                    (<?php echo $countTodasAtuais; ?>)</a></li>
                            <?php
                            foreach ($areasUsuario as $auxArea) {
                                $countArea = 0;
                                if (isset($listaTarefas['atuais'][$auxArea['area']])) {
                                    $countArea = count($listaTarefas['atuais'][$auxArea['area']]);
                                }
                                ?>
                                <li class="tab-li">
                                    <a class="dado-destaque js-aba-tarefas-atuais"
                                       href="#tab-area-atuais-<?php echo $auxArea['area']; ?>" data-toggle="tab"
                                       data-tabnum="<?php echo $auxArea['area']; ?>">
                                        <?php echo $auxArea['nome']; ?> (<?php echo $countArea; ?>)
                                    </a>
                                </li>
                            <?php } ?>
                            <li class="pull-left header"><i class="fa fa-tasks"></i> &nbsp;Minhas Tarefas</li>
                        </ul>
                        <div class="tab-content" style="padding: 15px 5px 5px !important; position: relative;">
                            <?php foreach (array_merge([0], $areasId) as $auxArea) { ?>
                                <div class="tab-pane active" id="tab-area-atuais-<?php echo $auxArea; ?>"
                                     data-tabnum="<?php echo $auxArea; ?>">
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
                                            <li><a href="#" class="js-acao-massa" data-acao="remover-tarefas"
                                                   data-target="#modal-tarefa">Remover</a></li>
                                        </ul>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover tabela-em-andamento">
                                            <thead>
                                            <tr>
                                                <th class="check" style="width: 18px;"><input type="checkbox"
                                                                                              name="check_all_minhas"
                                                                                              value=""/></th>
                                                <th style="width:50px;">Tipo</th>
                                                <th>Projeto</th>
                                                <th>Cliente</th>
                                                <th>Tarefa</th>
                                                <th>Prazo</th>
                                                <th>Prioridade</th>
                                                <th style="width: 180px;">Ações</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            if (!empty($listaTarefas['atuais'][$auxArea])) {
                                                foreach ($listaTarefas['atuais'][$auxArea] as $tarefa) {
                                                    $projeto = $POPProjeto->getProjectById($tarefa['projeto']);

                                                    if (isset($projeto["projeto"])) {
                                                        $tipoProjeto = $POPProjeto->getProjectTypeByProjectId($projeto['projeto']);
                                                        $tipoTarefa = $POPElemento->getElementTypeById($tarefa['elementoTipo']);

                                                        if ($tarefa["projeto"] == 13) {
                                                            $cliente = $tarefa["campos"]["Cliente"];
                                                        }
                                                        else {
                                                            $cliente = $BNCliente->getDataWithId($projeto['cliente']);
                                                            $cliente = $cliente['nomeFantasia'];
                                                        }
                                                        $areaResponsavel = new area('area', $tarefa['campos']['area'], $database);
                                                        $status = new status('elementoStatus', $tarefa['elementoStatus'], $database);
                                                        $dataCriacao = new date('dataCriacao', $tarefa['dataCriacao'], $database);
                                                        $prazo = new date('prazo', $tarefa['campos']['prazo'], $database);
                                                        $prioridade = new prioridade('prioridade', $tarefa['campos']['prioridade'], $database);

                                                        $infoSubetapa = [];
                                                        if (isset($tarefa['campos']['Etapa'])) {
                                                            $infoSubetapa = $POPElemento->get_SubEtapa_Info($tarefa['elementoTipo'], $tarefa['campos']['Etapa']);
                                                        }

                                                        $tempoTrabalhado = $POPElemento->calculaTempoTrabalhado($tarefa['elemento'], $_SESSION['adm_usuario']);
                                                        $tempoTrabalhado = Utils::secondsToTime($tempoTrabalhado);

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

                                                        $class_tr = '';
                                                        switch ($tarefa['elementoStatus']) {
                                                            case 3:
                                                                $class_tr = 'status-aguardando-cliente';
                                                                break;

                                                            case 4:
                                                                $class_tr = 'status-em-andamento';
                                                                break;
                                                        }
                                                        ?>
                                                        <tr class="tarefa-box <?php echo $class_tr; ?>">
                                                            <td class="check"><input type="checkbox" name="check_minhas"
                                                                                     value="<?php echo $tarefa['elemento']; ?>"/>
                                                            </td>
                                                            <td>
                                                                <span class="icone-tipo"
                                                                      style="background-color: <?php echo $tipoProjeto['cor']; ?>;"><i
                                                                            class="fa <?php echo $tipoProjeto['icone']; ?>"></i></span>
                                                                <span style="display: none;"><?php echo $tipoProjeto['nome']; ?></span>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                $getTarefa = '';
                                                                if ($tarefa['elementoStatus'] == 4) {
                                                                    $getTarefa = '&tarefas=true';
                                                                }

                                                                if (in_array($tarefa['elementoTipo'], $array_tipo_pedido)) { ?>
                                                                    <span><a href="pop-pedido.php?eid=<?php echo $tarefa['elemento'] . $getTarefa; ?>"><?php echo $projeto['nome']; ?></a></span>
                                                                <?php } else { ?>
                                                                    <span><a href="pop-projeto.php?projeto=<?php echo $projeto['projeto'] . $getTarefa; ?>"><?php echo $projeto['nome']; ?></a></span>
                                                                <?php } ?>
                                                            </td>

                                                            <td><?php echo $cliente; ?></td>
                                                            <td><?php echo $nomeTarefa; ?></td>
                                                            <td><?php echo $prazo->retornaHtmlExibicaoPrazo(); ?></td>
                                                            <td><?php echo $prioridade->retornaHtmlExibicao(); ?></td>
                                                            <td>
                                                                <div class="tarefa-btns">
                                                                    <?php if ($tarefa['elementoStatus'] == 2 || $tarefa['elementoStatus'] == 3 || $tarefa['elementoStatus'] == 5 || $tarefa["elementoStatus"] == 7) {
                                                                        $getTarefa = '&trf=' . $tarefa['elemento'];
                                                                        ?>
                                                                        <form action="" method="post">
                                                                            <input type="hidden" name="form"
                                                                                   value="iniciar-tarefa">
                                                                            <input type="hidden" name="tarefa"
                                                                                   value="<?php echo $tarefa['elemento']; ?>">
                                                                            <button type="submit"
                                                                                    class="btn btn-flat btn-primary border-0"
                                                                                    data-tarefa="<?php echo $tarefa['elemento']; ?>"
                                                                                    data-toggle="tooltip"
                                                                                    title="Iniciar Tarefa">
                                                                                <i class="fa fa-play"></i>
                                                                            </button>
                                                                        </form>
                                                                        <span data-toggle="tooltip"
                                                                              title="Remover das minhas tarefas">
																				<button class="btn btn-flat btn-primary border-0 js-getTarefa"
                                                                                        data-toggle="modal"
                                                                                        data-backdrop="static"
                                                                                        data-target="#modal-tarefa"
                                                                                        data-acao="remover-tarefa"
                                                                                        data-tarefa="<?php echo $tarefa['elemento']; ?>"
                                                                                        title="Remover das minhas tarefas">
																					<i class="fa fa-minus"></i>
																				</button>
																			</span>
                                                                        <?php
                                                                    }
                                                                    elseif ($tarefa['elementoStatus'] == 4) {
                                                                        $getTarefa = '&tarefas=true';
                                                                        ?>
                                                                        <form action="" method="post">
                                                                            <input type="hidden" name="form"
                                                                                   value="pausar-tarefa">
                                                                            <input type="hidden" name="tarefa"
                                                                                   value="<?php echo $tarefa['elemento']; ?>">
                                                                            <button type="submit"
                                                                                    class="btn btn-flat btn-primary border-0"
                                                                                    data-tarefa="<?php echo $tarefa['elemento']; ?>"
                                                                                    data-toggle="tooltip"
                                                                                    title="Pausar">
                                                                                <i class="fa fa-pause"></i>
                                                                            </button>
                                                                        </form>
                                                                        <?php
                                                                    }

                                                                    if (in_array($tarefa['elementoTipo'], $array_tipo_pedido)) { ?>
                                                                        <a href="pop-pedido.php?eid=<?php echo $tarefa['elemento'] . $getTarefa; ?>"
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
                                                                        <a href="pop-projeto.php?projeto=<?php echo $projeto['projeto'] . $getTarefa; ?>"
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
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom tabs-disponiveis box-warning">
                        <ul class="nav nav-tabs pull-right">
                            <li class="tab-li"><a class="dado-destaque js-aba-tarefas" href="#tab-area-0"
                                                  data-toggle="tab" data-tabnum="0">Todas (<?php echo $countTodas; ?>
                                    )</a></li>
                            <?php
                            foreach ($areasUsuario as $auxArea) {
                                $countArea = 0;
                                if (isset($listaTarefas['disponiveis'][$auxArea['area']])) {
                                    $countArea = count($listaTarefas['disponiveis'][$auxArea['area']]);
                                }
                                ?>
                                <li class="tab-li">
                                    <a class="dado-destaque js-aba-tarefas"
                                       href="#tab-area-<?php echo $auxArea['area']; ?>" data-toggle="tab"
                                       data-tabnum="<?php echo $auxArea['area']; ?>">
                                        <?php echo $auxArea['nome']; ?> (<?php echo $countArea; ?>)
                                    </a>
                                </li>
                            <?php } ?>
                            <li class="pull-left header"><i class="fa fa-clock-o"></i> Em Espera</li>
                        </ul>
                        <div class="tab-content" style="padding: 15px 5px 5px !important; position: relative;">
                            <?php foreach (array_merge([0], $areasId) as $auxArea) { ?>
                                <div class="tab-pane active" id="tab-area-<?php echo $auxArea; ?>"
                                     data-tabnum="<?php echo $auxArea; ?>">
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
                                                   data-target="#modal-tarefa">Assumir</a>
                                            </li>
                                            <li><a href="#" class="js-acao-massa" data-acao="delegar-tarefas"
                                                   data-target="#modal-tarefa">Delegar</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover tabela-em-espera">
                                            <thead>
                                            <tr>
                                                <th width="18" class="check"><input type="checkbox"
                                                                                    name="check_all_espera" value=""/>
                                                </th>
                                                <th width="50">Tipo</th>
                                                <th>Projeto</th>
                                                <th>Cliente</th>
                                                <th>Tarefa</th>
                                                <th>Prazo</th>
                                                <th>Prioridade</th>
                                                <th width="180">Ações</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            if (!empty($listaTarefas['disponiveis'][$auxArea])) {
                                                foreach ($listaTarefas['disponiveis'][$auxArea] as $tarefa) {
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

                                                        $class_tr = '';

                                                        if (isset($tarefa['campos']['prioridade']) && $tarefa['campos']['prioridade'] == '101') {
                                                            $class_tr = 'prioridade-alta';
                                                        }
                                                        elseif ($tarefa['elementoStatus'] == 3) {
                                                            $class_tr = 'status-aguardando-cliente';
                                                        }
                                                        ?>
                                                        <tr class="tarefa-box <?php echo $class_tr; ?>">
                                                            <td class="check"><input type="checkbox" name="check_espera"
                                                                                     value="<?php echo $tarefa['elemento']; ?>"/>
                                                            </td>
                                                            <td>
                                                                <span class="icone-tipo"
                                                                      style="background-color: <?php echo $tipoProjeto['cor']; ?>;"><i
                                                                            class="fa <?php echo $tipoProjeto['icone']; ?>"></i></span>
                                                                <span style="display: none;"><?php echo $tipoProjeto['nome']; ?></span>
                                                            </td>
                                                            <td>
                                                                <?php if (in_array($tarefa['elementoTipo'], $array_tipo_pedido)) { ?>
                                                                    <a style="font-weight: normal"
                                                                       href="pop-pedido.php?eid=<?php echo $tarefa['elemento']; ?>">Pedido</a>
                                                                <?php } else { ?>
                                                                    <a style="font-weight: normal"
                                                                       href="pop-projeto.php?projeto=<?php echo $projeto['projeto']; ?>"><?php echo $projeto['nome']; ?></a>
                                                                <?php } ?>
                                                            </td>

                                                            <td><?php echo $cliente; ?></td>
                                                            <td><?php echo $nomeTarefa; ?></td>
                                                            <td>
                                                                <span style="display: none;"><?php echo $prazo->getValor(); ?></span> <?php echo $prazo->retornaHtmlExibicaoPrazo(); ?>
                                                            </td>
                                                            <td><?php echo $prioridade->retornaHtmlExibicao(); ?></td>
                                                            <td>
                                                                <div class="tarefa-btns">
																		<span data-toggle="tooltip"
                                                                              title="Assumir Tarefa">
																			<button class="btn btn-flat btn-primary border-0 js-getTarefa"
                                                                                    data-toggle="modal"
                                                                                    data-backdrop="static"
                                                                                    data-target="#modal-tarefa"
                                                                                    data-acao="assumir-tarefa"
                                                                                    data-tarefa="<?php echo $tarefa['elemento']; ?>">
																				<i class="fa fa-plus"></i>
																			</button>
																		</span>
                                                                    <?php
                                                                    if (in_array($tarefa['elementoTipo'], $array_tipo_pedido)) { ?>
                                                                            <span data-toggle="tooltip"
                                                                                  title="Delegar Pedido">
																				<button class="btn btn-flat btn-primary border-0 js-getTarefa"
                                                                                        data-toggle="modal"
                                                                                        data-backdrop="static"
                                                                                        data-target="#modal-tarefa"
                                                                                        data-acao="editar-tarefa"
                                                                                        data-tarefa="<?php echo $tarefa['elemento']; ?>">
																					<i class="fa fa-user-plus"></i>
																				</button>
																			</span>
                                                                        <a href="pop-pedido.php?eid=<?php echo $tarefa['elemento']; ?>"
                                                                           class="btn btn-flat bg-navy border-0"
                                                                           data-toggle="tooltip" title="Ver Pedido">
                                                                            <i class="fa fa-search-plus"></i>
                                                                        </a>
                                                                    <?php } else { ?>
                                                                            <span data-toggle="tooltip"
                                                                                  title="Delegar Tarefa">
																					<button class="btn btn-flat btn-primary border-0 js-getTarefa"
                                                                                            data-toggle="modal"
                                                                                            data-backdrop="static"
                                                                                            data-target="#modal-tarefa"
                                                                                            data-acao="editar-tarefa"
                                                                                            data-tarefa="<?php echo $tarefa['elemento']; ?>">
																						<i class="fa fa-user-plus"></i>
																					</button>
																				</span>
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
                                            }
                                            ?>
                                            </tbody>
                                        </table><!-- /row tarefas -->
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div><!-- /box -->
                </div>
            </div>

        </section>
    </aside>
</div>

<!-- MODAL TAREFA GERAL -->
<div class="modal fade" id="modal-tarefa" tabindex="-1" role="dialog" aria-hidden="true"></div>

<!-- MODAL PEDIDO GERAL -->
<div class="modal fade" id="modal-pedido" tabindex="-1" role="dialog" aria-hidden="true"></div>

<!-- MODAL GERAL PROJETO -->
<div class="modal fade" id="modal-projeto" tabindex="-1" role="dialog" aria-hidden="true"></div>
<button class="button-floating js-getTarefa" data-target="#modal-pedido" data-acao="novo-hora" data-toggle="modal"
        data-backdrop="static">
    <i class="fas fa-user-clock"></i>
</button>

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
<script src="../js/pop.js?v=1" type="text/javascript"></script>
<script src="../js/plugins/trumbowyg/trumbowyg.min.js" type="text/javascript"></script>

<script type="text/javascript">
    var base_url = '<?php echo POP_URL; ?>';

    $(function () {
        // bugfix das tabs, em que a tabela não se ajusta direito se não tiver exibida
        // então, deixo exibida e oculto no document ready
        //
        var active_tab = getCookie('active_tab');
        if (active_tab == "") {
            active_tab = 0;
        }
        $('.js-aba-tarefas[data-tabnum=' + active_tab + ']').parent().addClass('active');
        $('.tabs-disponiveis .tab-pane').not('[data-tabnum="' + active_tab + '"]').removeClass('active');

        var active_tab_atuais = getCookie('active_tab_atuais');
        if (active_tab_atuais == "") {
            active_tab_atuais = 0;
        }
        $('.js-aba-tarefas-atuais[data-tabnum=' + active_tab_atuais + ']').parent().addClass('active');
        $('.tabs-atuais .tab-pane').not('[data-tabnum="' + active_tab_atuais + '"]').removeClass('active');
    });

    $(".tabela-em-andamento").dataTable({
        "bPaginate": false,
        //"bLengthChange": false,
        "bFilter": true,
        "aoColumnDefs": [
            {"bSortable": false, "aTargets": [0, 7]}
        ],
        "aaSorting": [[5, "asc"], [6, "asc"]]
    });

    $(".tabela-em-espera").dataTable({
        "bPaginate": false,
        //"bLengthChange": false,
        "bFilter": true,
        "aoColumnDefs": [
            {"bSortable": false, "aTargets": [0, 7]}
        ],
        "aaSorting": [[5, "asc"], [6, "asc"]]
    });

    $(document).on('ifChecked', 'input[name=check_all_minhas]', function (event) {
        $(this).parent().parent().parent().parent().parent().find('input[name=check_minhas]').iCheck('check');
    });

    $(document).on('ifUnchecked', 'input[name=check_all_minhas]', function (event) {
        $(this).parent().parent().parent().parent().parent().find('input[name=check_minhas]').iCheck('uncheck');
    });

    $(document).on('ifChecked', 'input[name=check_all_espera]', function (event) {
        $(this).parent().parent().parent().parent().parent().find('input[name=check_espera]').iCheck('check');
    });

    $(document).on('ifUnchecked', 'input[name=check_all_espera]', function (event) {
        $(this).parent().parent().parent().parent().parent().find('input[name=check_espera]').iCheck('uncheck');
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
        var input_name = '';
        var elementos = [];

        switch (acao) {
            case 'remover-tarefas':
                input_name = 'check_minhas';
                break;

            case 'assumir-tarefas':
            case 'delegar-tarefas':
                input_name = 'check_espera';
                break;

            default:
                break;
        }

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


    // salva as abas ativas em cookies, para manter a mesma ativa após o reload
    $(document).on('click', '.js-aba-tarefas', function () {
        var tab = $(this).attr('data-tabnum');
        setCookie('active_tab', tab, 1);
    });

    $(document).on('click', '.js-aba-tarefas-atuais', function () {
        var tab = $(this).attr('data-tabnum');
        setCookie('active_tab_atuais', tab, 1);
    });





</script>
</body>
</html>
