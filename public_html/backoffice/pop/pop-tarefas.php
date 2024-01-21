<?php
require_once __DIR__ . "/../../autoloader.php";
$database = new Database();
$usuario = new AdmUsuario($database);
$POPProjeto = new Projeto($database);
$POPElemento = new Elemento($database);
$BNCliente = new BNCliente($database);

require_once __DIR__ . '/../includes/is_logged.php';
require_once __DIR__ . '/include/le-notificacoes.php';

if ($dadosUsuario['administrador'] != 1) {
    Utils::redirect("pop-minhas-tarefas.php");
}

require_once __DIR__ . "/pop-tarefas-post.php";

$arrayQueryString = $_GET;
$queryString = http_build_query($arrayQueryString);

$ordens = [1 => ['nome' => 'ID', 'campo' => 'projeto', 'nivel' => 0], 2 => ['nome' => 'Nome', 'campo' => 'nome', 'nivel' => 0], 3 => ['nome' => 'Prazo', 'campo' => 'prazo', 'nivel' => 0], 4 => ['nome' => 'Prioridade', 'campo' => 'prioridade', 'nivel' => 0], 5 => ['nome' => 'Área', 'campo' => 'area', 'nivel' => 0],];

// variáveis de paginação
$pagina = $_GET['pagina'] ?? 1;
$registros = 15;
$inicio = ($registros * $pagina) - $registros;
$limit = "$inicio, $registros";
$finalizados = 0;
if (isset($_GET['finalizado'])) {
    $finalizados = 1;
}
$todosElementos = $POPElemento->getAllElements();
$listaTarefas = [];

$todasTarefas = $POPElemento->filtraElementosPorBase($todosElementos, 1);

foreach ($todasTarefas as $aux) {
    if (in_array($aux['campos']['area'], $areasId)) {
        if ($finalizados && $aux['campos']['dtFim'] !== NULL) {
            $listaTarefas[] = $aux;

        }
        elseif (!$finalizados && $aux['elementoStatus'] != 9) {
            $listaTarefas[] = $aux;
        }
    }
}

$auxTarefas = $POPElemento->agrupaTarefas($listaTarefas);
$listaTarefas = $auxTarefas['tarefas'];

$order = FALSE;
if ((isset($_GET['ordem'], $ordens[$_GET['ordem']]))) {
    $order = $ordens[$_GET['ordem']]['campo'];
}

if ($order) {
    usort($listaTarefas, function ($a, $b) use ($order) {
        if (!isset($a['campos'][$order])) {
            return -1;
        }
        if (!isset($b['campos'][$order])) {
            return 1;
        }
        return $a['campos'][$order] > $b['campos'][$order];
    });
}

$numeroTarefas = count($listaTarefas);
$listaTarefas = array_slice($listaTarefas, $inicio, $registros); // paginar array
// controle dos botões de paginação
$totalPagina = ceil($numeroTarefas / $registros);
$exibir = 3;
$anterior = (($pagina - 1) == 0) ? 1 : $pagina - 1;
$proxima = (($pagina + 1) >= $totalPagina) ? $totalPagina : $pagina + 1;
$fazerTarefa = FALSE;
if ((isset($_GET['tarefa']) && $_GET['tarefa'])) {
    $fazerTarefa = TRUE;
}
foreach ($listaTarefas as $key => $value) {
    if (!isset($value["campos"]["prazo"])) {
        $pid = $value["projeto"];
        $listaTarefas[$key]["campos"]["prazo"] = $POPProjeto->getProjectPrazo($pid);
        $listaTarefas[$key]["prazo"] = $listaTarefas[$key]["campos"]["prazo"];
    }
    // Variavel usada pra organizar por prazo o projeto ( transforma a data de prazo em segundos pra ficar mais facil de fazer comparacao )
    $listaTarefas[$key]["prazo"] = strtotime($listaTarefas[$key]["campos"]["prazo"]);
}
if ($listaTarefas != []) {
    $listaTarefas = Utils::sksort($listaTarefas, "prazo", TRUE);
}
?><!DOCTYPE html>
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

        <!-- Main content -->
        <section class="content">
            <?php require_once __DIR__ . "/include/sucesso_error.php"; ?>

            <div class="row">
                <div class="col-xs-12">
                    <div class="nav-tabs-custom pop-custom-tabs monitor-tarefas">
                        <ul class="nav nav-tabs">
                            <li <?php echo (!$finalizados) ? 'class="active"' : ''; ?>><a href="pop-tarefas.php"><i
                                            class="fa fa-share"></i> &nbsp;Tarefas Ativas</a></li>
                            <li <?php echo $finalizados ? 'class="active"' : ''; ?>><a href="?finalizado=1"><i
                                            class="fa fa-check-square"></i> &nbsp;Tarefas Finalizadas</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="ativas">
                                <div class="monitor-box">
                                    <div class="row">
                                        <div class="col-sm-12 filtros">
                                            <div class="filtro-btns">
                                                <div class="btn-group">
                                                    <span class="filtro-label"><i class="fa fa-filter"></i> Filtrar por</span>
                                                    <button data-toggle="dropdown"
                                                            class="btn btn-flat dropdown-toggle btn-dropdown"
                                                            type="button">
                                                        Selecione... &nbsp;&nbsp;&nbsp; <span class="caret"></span>
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                    </button>
                                                    <ul role="menu" class="dropdown-menu with-submenu">
                                                        <li>
                                                            <a href="#">Filtro</a>
                                                            <ul class="dropdown-submenu">
                                                                <li><a href="#">Filtro 1</a></li>
                                                                <li><a href="#">Filtro 2</a></li>
                                                                <li><a href="#">Filtro 3</a></li>
                                                            </ul>
                                                        </li>
                                                        <li>
                                                            <a href="#">Filtro</a>
                                                            <ul class="dropdown-submenu">
                                                                <li><a href="#">Filtro 1</a></li>
                                                                <li><a href="#">Filtro 2</a></li>
                                                                <li><a href="#">Filtro 3</a></li>
                                                            </ul>
                                                        </li>
                                                        <li>
                                                            <a href="#">Filtro</a>
                                                            <ul class="dropdown-submenu">
                                                                <li><a href="#">Filtro 1</a></li>
                                                                <li><a href="#">Filtro 2</a></li>
                                                                <li><a href="#">Filtro 3</a></li>
                                                            </ul>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="btn-group">
                                                    <span class="filtro-label"><i
                                                                class="fa fa-sort"></i> Ordenar por</span>
                                                    <button data-toggle="dropdown"
                                                            class="btn btn-flat dropdown-toggle btn-dropdown"
                                                            type="button">
                                                        <?php if ((isset($_GET['ordem'], $ordens[$_GET['ordem']]))) {
                                                            echo $ordens[$_GET['ordem']]['nome'];
                                                        } else {
                                                            echo 'Selecione...';
                                                        } ?>
                                                        &nbsp;&nbsp;&nbsp;
                                                        <span class="caret"></span>
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
                                        <?php
                                        foreach ($listaTarefas as $tarefa) {
                                            $areaResponsavel = new area('area', $tarefa['campos']['area'], $database);
                                            $status = new status('elementoStatus', $tarefa['elementoStatus'], $database);
                                            $dataCriacao = new date('dataCriacao', $tarefa['dataCriacao'], $database);
                                            $prazo = new date('prazo', $tarefa['campos']['prazo'], $database);
                                            $responsavel = new responsavel('responsavel', $tarefa['campos']['responsavel'], $database);
                                            $prioridade = new prioridade('prioridade', $tarefa['campos']['prioridade'], $database);
                                            $tempoTrabalhado = $POPElemento->calculaTempoTrabalhado($tarefa['elemento']);
                                            $tempoTrabalhado = Utils::secondsToTime($tempoTrabalhado);

                                            $projeto = $POPProjeto->getProjectById($tarefa['projeto']);
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

                                            $infoSubetapa = FALSE;
                                            if (isset($tarefa['campos']['Etapa'])) {
                                                $infoSubetapa = $POPElemento->get_SubEtapa_Info($tarefa['elementoTipo'], $tarefa['campos']['Etapa']);
                                            }
                                            ?>
                                            <div class="col-sm-12 projeto">
                                                <div class="projeto-box">
                                                    <div class="row">
                                                        <div class="col-sm-7">
                                                            <div class="projeto-id">
                                                                <span><?php echo $tarefa['elemento']; ?></span>
                                                                <span style="background-color: <?php echo $tipoProjeto['cor']; ?>;"><i
                                                                            class="fa <?php echo $tipoProjeto['icone']; ?>"></i></span>
                                                            </div>
                                                            <div class="projeto-info">
                                                                <h3>
                                                                    <?php
                                                                    //echo $infoSubetapa ? $infoSubetapa['nome'].' ('.$tarefa['campos']['contagem']['parcial'].' de '.$tarefa['campos']['contagem']['total'].')' : $tipoTarefa['nome'];
                                                                    if ($infoSubetapa && isset($tarefa['campos']['contagem']['total'])) {
                                                                        if ($tarefa['campos']['contagem']['total'] > 1) {
                                                                            $nomeTarefa = $infoSubetapa['nome'] . ' (' . $tarefa['campos']['contagem']['parcial'] . ' de ' . $tarefa['campos']['contagem']['total'] . ')';
                                                                        }
                                                                        else {
                                                                            $nomeTarefa = $infoSubetapa['nome'];
                                                                        }
                                                                    }
                                                                    else {
                                                                        if ($tarefa["elementoTipo"] == 59) {
                                                                            $nomeTarefa = $tarefa["campos"]["Nome"];
                                                                        }
                                                                        else {
                                                                            $nomeTarefa = $tipoTarefa['nome'];
                                                                        }
                                                                    }

                                                                    echo $nomeTarefa;
                                                                    ?>
                                                                    <span><?php echo $cliente; ?></span>
                                                                </h3>
                                                                <ul>
                                                                    <li><p>Projeto: <span><a
                                                                                        href="pop-projeto.php?projeto=<?php echo $projeto['projeto']; ?>"><?php echo $projeto['nome']; ?></a></span>
                                                                        </p>
                                                                    </li>
                                                                    <li><p>Área
                                                                            Responsável: <?php echo $areaResponsavel->retornaHtmlExibicao(); ?></p>
                                                                    </li>
                                                                </ul>
                                                                <ul>
                                                                    <li><p>
                                                                            Prioridade: <?php echo $prioridade->retornaHtmlExibicao(); ?></p>
                                                                    </li>
                                                                    <li><p>
                                                                            Status: <?php echo $status->retornaHtmlExibicao(); ?></p>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <div class="projeto-data">
                                                                <p>Data
                                                                    Entrada: <?php echo $dataCriacao->retornaHtmlExibicao(); ?></p>
                                                            </div>
                                                            <div class="projeto-data">
                                                                <p>
                                                                    Prazo: <?php echo $prazo->retornaHtmlExibicao(); ?></p>
                                                            </div>
                                                            <div class="projeto-data">
                                                                <p>Tempo Trabalhado:
                                                                    <span><?php echo $tempoTrabalhado['h'] . ':' . $tempoTrabalhado['m'] . ':' . $tempoTrabalhado['s']; ?></span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-1">
                                                            <div class="tarefa-responsavel">
                                                                <p>Responsável:</p>
                                                                <?php echo $responsavel->retornaHtmlExibicao(); ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <div class="projeto-btns">
                                                                <button class="btn btn-flat btn-block btn-primary border-0 js-getTarefa"
                                                                        data-toggle="modal" data-backdrop="static"
                                                                        data-target="#modal-tarefa"
                                                                        data-acao="editar-tarefa"
                                                                        data-tarefa="<?php echo $tarefa['elemento']; ?>"
                                                                        title="Editar Tarefa">
                                                                    <i class="fa fa-pencil pull-left"></i> Editar Tarefa
                                                                </button>
                                                                <a href="pop-projeto.php?projeto=<?php echo $projeto['projeto']; ?>"
                                                                   class="btn btn-flat btn-block btn-primary border-0"
                                                                   title="Ver Projeto">
                                                                    <i class="fa fa-search pull-left"></i> Ver Projeto
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- /tarefa -->
                                        <?php } ?>
                                    </div><!-- /row tarefas -->

                                    <div class="row">
                                        <div class="col-sm-12" style="text-align: center;">
                                            <div class="btn-group paginacao">
                                                <?php
                                                $aux = $arrayQueryString;
                                                $aux['pagina'] = $anterior;
                                                echo '<a href="?' . http_build_query($aux) . '" class="btn btn-default btn-flat"><i class="fa fa-angle-double-left"></i></a>';

                                                for ($i = $pagina - $exibir; $i <= $pagina - 1; $i++) {
                                                    if ($i > 0) {
                                                        $aux = $arrayQueryString;
                                                        $aux['pagina'] = $i;
                                                        echo '<a class="btn btn-default btn-flat" href="?' . http_build_query($aux) . '"> ' . $i . ' </a>';
                                                    }
                                                }

                                                echo '<a class="btn btn-default btn-flat" href=""><strong>' . $pagina . '</strong></a>';

                                                for ($i = $pagina + 1; $i < $pagina + $exibir; $i++) {
                                                    if ($i <= $totalPagina) {
                                                        $aux = $arrayQueryString;
                                                        $aux['pagina'] = $i;
                                                        echo '<a class="btn btn-default btn-flat" href="?' . http_build_query($aux) . '"> ' . $i . ' </a>';
                                                    }
                                                }

                                                $aux = $arrayQueryString;
                                                $aux['pagina'] = $proxima;
                                                echo '<a href="?' . http_build_query($aux) . '" class="btn btn-default btn-flat"><i class="fa fa-angle-double-right"></i></a>';
                                                ?>
                                            </div>
                                        </div>
                                    </div><!-- /row paginação -->
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
