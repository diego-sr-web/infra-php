<?php
require_once __DIR__ . "/../../autoloader.php";

$database = new Database();
$usuario = new AdmUsuario($database);
$POPProjeto = new Projeto($database);
$POPElemento = new Elemento($database);
$BNCliente = new BNCliente($database);

require_once __DIR__ . '/../includes/is_logged.php';
require_once __DIR__ . '/include/le-notificacoes.php';

require_once __DIR__ . "/pop-projetos-post.php";

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

$ordens = [1 => ['nome' => 'ID', 'campo' => 'projeto'], 2 => ['nome' => 'Nome Crescente', 'campo' => 'nome ASC'], 3 => ['nome' => 'Nome Decrescente', 'campo' => 'nome DESC'], 4 => ['nome' => 'Prazo Crescente', 'campo' => 'prazo ASC'], 5 => ['nome' => 'Prazo Decrescente', 'campo' => 'prazo DESC'],];

$filtros = [1 => ['nome' => 'Tipo de Projeto', 'campo' => 'projetoTipo', 'valores' => []], 2 => ['nome' => 'Cliente', 'campo' => 'cliente', 'valores' => []],];

$numRegistros = [1 => 10, 2 => 20, 3 => 50, 4 => 100,];

foreach ($listaTiposProjeto as $item) {
    $filtros[1]['valores'][] = ['id' => $item['projetoTipo'], 'nome' => $item['nome'],];
}

$arrayQueryString = $_GET;
$queryString = http_build_query($arrayQueryString);

$pagina = $_GET['pagina'] ?? 1;
$registros = $_GET['registros'] ?? 10;
$inicio = ($registros * $pagina) - $registros;
$order = $ordens[$_GET['ordem']]['campo'] ?? "";
$limit = "$inicio, $registros";
$finalizados = 0;
if (isset($_GET['finalizado'])) {
    $finalizados = 1;
}
$condicoes = ['finalizado'];
$valores = [$finalizados];

if (isset($_GET['filtro'], $_GET['filtroValor'])) {
    $condicoes[] = $filtros[$_GET['filtro']]['campo'];
    $valores[] = $filtros[$_GET['filtro']]['valores'][$_GET['filtroValor']]['id'];
}

$numeroProjetos = $POPProjeto->getProjectCount($condicoes, $valores);
if (isset($_GET["busca"])) {
    $listaProjetos = $POPProjeto->getProjectsByName($_GET["busca"]);
}
else {
    $listaProjetos = $POPProjeto->getProjectList($condicoes, $valores, $order, $limit);
}

foreach ($listaProjetos as $key => $value) {
    if (isset($value["projeto"]) && $value["projeto"] == 13) {
        unset($listaProjetos[$key]);
    }
}


$totalPagina = ceil($numeroProjetos / $registros);
$exibir = 3;
$anterior = (($pagina - 1) == 0) ? 1 : $pagina - 1;
$proxima = (($pagina + 1) >= $totalPagina) ? $totalPagina : $pagina + 1;
$fazerTarefa = FALSE;
if ((isset($_GET['tarefa']) && $_GET['tarefa'])) {
    $fazerTarefa = TRUE;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $titleNotif; ?>Projetos | POP Nerdweb</title>
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
            <h1>Projetos</h1>

            <ol class="breadcrumb">
                <li class="active"><a href="pop-projetos.php"><i class="fa fa-folder-open"></i> Projetos</a></li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <?php require_once __DIR__ . "/include/sucesso_error.php"; ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-solid">
                        <div class="box-header">
                            <?php if ($finalizados) { ?>
                                <h3 class="box-title">Lista de Projetos Finalizados</h3>
                            <?php } else { ?>
                                <h3 class="box-title">Lista de Projetos Ativos</h3>
                            <?php } ?>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-12 filtros">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <?php if (!$finalizados) { ?>
                                                <div class="acao-bts">
                                                    <button class="btn btn-primary btn-flat border-0 js-getProjeto"
                                                            data-target="#modal-projeto" data-acao="novo-projeto"
                                                            data-toggle="modal" data-backdrop="static"><i
                                                                class="fa fa-plus"></i> Adicionar novo
                                                        projeto
                                                    </button>
                                                </div>
                                            <?php } ?>
                                            <div class="box-filtros">
                                                <div class="filtro-btns left">
                                                    <div class="btn-group">
                                                        <span class="filtro-label"><i class="fa fa-filter"></i> Nº registros</span>
                                                        <button data-toggle="dropdown"
                                                                class="btn btn-flat dropdown-toggle btn-dropdown"
                                                                type="button">
                                                            <?php echo $_GET['registros'] ?? 'Selecione...'; ?>
                                                            &nbsp;&nbsp;&nbsp; <span class="caret"></span>
                                                            <span class="sr-only">Toggle Dropdown</span>
                                                        </button>
                                                        <ul role="menu" class="dropdown-menu with-submenu">
                                                            <?php
                                                            foreach ($numRegistros as $key => $numReg) {
                                                                $aux = $arrayQueryString;
                                                                $aux['registros'] = $numReg;
                                                                echo '<li><a href="?' . http_build_query($aux) . '">' . $numReg . '</a></li>';
                                                            }
                                                            ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="box-filtros">
                                                <div class="busca-btns">
                                                    <form action="" method="get">
                                                        <div class="input-group">
                                                            <?php if (isset($_GET["finalizados"]) && $_GET["finalizados"] == 1) { ?>
                                                                <input type="hidden" name="finalizados" value="1">
                                                            <?php } ?>
                                                            <input class="form-control" type="text" name="busca"
                                                                   placeholder="Buscar por projeto..."
                                                                   value="<?php echo $_GET['busca'] ?? ''; ?>">
                                                            <span class="input-group-btn">
																	<button class="btn btn-primary btn-flat"
                                                                            type="submit"><i
                                                                                class="fa fa-search"></i></button>
																</span>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="box-filtros">
                                                <div class="filtro-btns">
                                                    <div class="btn-group">
                                                        <span class="filtro-label"><i class="fa fa-filter"></i> Filtrar por</span>
                                                        <button data-toggle="dropdown"
                                                                class="btn btn-flat dropdown-toggle btn-dropdown"
                                                                type="button">
                                                            <?php
                                                            if ((isset($_GET['filtro'], $filtros[$_GET['filtro']]))) {
                                                                echo $filtros[$_GET['filtro']]['nome'] . ' > ' . $filtros[$_GET['filtro']]['valores'][$_GET['filtroValor']]['nome'];
                                                            } else {
                                                                echo 'Selecione...';
                                                            } ?>
                                                            &nbsp;&nbsp;&nbsp; <span class="caret"></span>
                                                            <span class="sr-only">Toggle Dropdown</span>
                                                        </button>
                                                        <ul role="menu" class="dropdown-menu with-submenu">
                                                            <?php
                                                            foreach ($filtros as $key => $filtro) {
                                                                $html = '';
                                                                $aux = $arrayQueryString;
                                                                $aux['filtro'] = $key;
                                                                $html .= '<li>';
                                                                $html .= '	<a href="#">' . $filtro['nome'] . '</a>';

                                                                if ($filtro['valores']) {
                                                                    $html .= '	<ul class="dropdown-submenu">';
                                                                    foreach ($filtro['valores'] as $key2 => $item) {
                                                                        $aux['filtroValor'] = $key2;
                                                                        $html .= '	<li><a href="?' . http_build_query($aux) . '">' . $item['nome'] . '</a></li>';
                                                                    }
                                                                    $html .= '	</ul>';
                                                                }

                                                                $html .= '</li>';

                                                                echo $html;
                                                            }
                                                            ?>
                                                        </ul>
                                                    </div>
                                                    <div class="btn-group">
                                                        <span class="filtro-label"><i class="fa fa-sort"></i> Ordenar por</span>
                                                        <button data-toggle="dropdown"
                                                                class="btn btn-flat dropdown-toggle btn-dropdown"
                                                                type="button">
                                                            <?php
                                                            if ((isset($_GET['ordem'], $ordens[$_GET['ordem']]))) {
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
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /filtros -->
                            </div><!-- /row filtros -->
                            <div class="row">
                                <?php
                                foreach ($listaProjetos as $projeto) {
                                    //var_dump($projeto);
                                    $cliente = $BNCliente->getDataWithId($projeto['cliente']);
                                    $prazo = new date('prazo', $projeto['prazo'], $database);

                                    $listaTarefas = [];
                                    $tmpTarefas = $POPElemento->getAllElements(['projeto'], [$projeto['projeto']]);
                                    //var_dump($tmpTarefas);
                                    $tmpTarefas = $POPElemento->filtraElementosPorBase($tmpTarefas, 1);
                                    //var_dump($tmpTarefas);
                                    foreach ($tmpTarefas as $tmpTarefa) {
                                        //if($tmpTarefa['campos']['dtFim'] == NULL) {
                                        if ($tmpTarefa['elementoStatus'] != 9) {
                                            $listaTarefas[] = $tmpTarefa;
                                        }
                                    }

                                    $tmpTarefas = $POPElemento->agrupaTarefas($listaTarefas);
                                    $listaTarefas = $tmpTarefas['tarefas'];

                                    ?>
                                    <div class="col-sm-12 projeto">
                                        <div class="projeto-box">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="projeto-id">
                                                        <span><?php echo $projeto['projeto']; ?></span>
                                                        <span style="background-color: <?php echo $listaTiposProjeto[$projeto['projetoTipo']]['cor']; ?>;"><i
                                                                    class="fa <?php echo $listaTiposProjeto[$projeto['projetoTipo']]['icone']; ?>"></i></span>
                                                    </div>
                                                    <div class="projeto-info">
                                                        <h3><?php echo $projeto['nome']; ?>
                                                            <span><?php echo ($cliente) ? $cliente['nomeFantasia'] : 'Cliente não cadastrado'; ?></span>
                                                        </h3>
                                                        <ul class="info-etapas">
                                                            <li><p>Etapas:</p></li>
                                                            <li><p>Áreas:</p></li>
                                                        </ul>
                                                        <?php
                                                        foreach ($listaTarefas as $tarefa) {
                                                            $tipoTarefa = $POPElemento->getElementTypeById($tarefa['elementoTipo']);
                                                            $areaResponsavel = new area('area', $tarefa['campos']['area'], $database);

                                                            $infoSubetapa = FALSE;
                                                            if (isset($tarefa['campos']['Etapa'])) {
                                                                $infoSubetapa = $POPElemento->get_SubEtapa_Info($tarefa['elementoTipo'], $tarefa['campos']['Etapa']);
                                                            }
                                                            ?>
                                                            <ul class="info-etapas">
                                                                <li><p>
                                                                        <span><?php echo $infoSubetapa ? $infoSubetapa['nome'] : $tipoTarefa['nome']; ?></span>
                                                                    </p></li>
                                                                <li>
                                                                    <p><?php echo $areaResponsavel->retornaHtmlExibicao(); ?></p>
                                                                </li>
                                                            </ul>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="projeto-data">
                                                        <p>Prazo Final: <?php echo $prazo->retornaHtmlExibicao(); ?></p>
                                                    </div>
                                                    <div class="projeto-responsavel">
                                                        <p>Trabalhando:</p>
                                                        <?php foreach ($listaTarefas as $tarefa) {
                                                            $responsavel = new responsavel('responsavel', $tarefa['campos']['responsavel'], $database);
                                                            echo $responsavel->retornaHtmlExibicao();
                                                        } ?>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="projeto-btns">
                                                        <a href="pop-projeto.php?projeto=<?php echo $projeto['projeto']; ?>"
                                                           class="btn btn-flat btn-block btn-primary border-0"><i
                                                                    class="fa fa-search pull-left"></i> Ver Projeto</a>
                                                        <!-- <button class="btn btn-flat btn-block btn-primary border-0"><i class="fa fa-pencil pull-left"></i> Editar Projeto</button> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- /projeto -->
                                <?php } ?>
                            </div><!-- /row projetos -->

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
                    </div><!-- /.box -->
                </div>
            </div>
        </section><!-- /.content -->
    </aside><!-- /.right-side -->
</div><!-- ./wrapper -->

<!-- MODAL GERAL PROJETO -->
<div class="modal fade" id="modal-projeto" tabindex="-1" role="dialog" aria-hidden="true"></div>

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

<script type="text/javascript">
    /* ********************* CLICKS ********************* */
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


    /* ********************* READY ********************* */
    $(document).ready(function () {
        $('.textarea').wysihtml5();
    });
</script>
</body>
</html>
