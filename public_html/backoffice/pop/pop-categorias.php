<?php
require_once __DIR__ . "/../../autoloader.php";

$database = new Database();
$usuario = new AdmUsuario($database);
$POPProjeto = new Projeto($database);
$POPElemento = new Elemento($database);
$BNCliente = new BNCliente($database);

require_once __DIR__ . '/../includes/is_logged.php';
require_once __DIR__ . '/include/le-notificacoes.php';

require_once __DIR__ . "/pop-categorias-post.php";

$arrayQueryString = $_GET;
$queryString = http_build_query($arrayQueryString);

$ordens = [1 => ['nome' => 'ID', 'campo' => 'categoria'], 2 => ['nome' => 'Nome', 'campo' => 'nome'],];

$pagina = 1;
if ((isset($_GET['pagina']) && $_GET['pagina'] != "")) {
    $pagina = $_GET['pagina'];
}
$registros = 10;
$inicio = ($registros * $pagina) - $registros;
$order = (isset($_GET['ordem'], $ordens[$_GET['ordem']])) ? $ordens[$_GET['ordem']]['campo'] : '';
$limit = "$inicio, $registros";

$numeroCategorias = $POPElemento->getCategoryCount();
$listaCategorias = $POPElemento->getCategoryList(NULL, NULL, $order, $limit);

$totalPagina = ceil($numeroCategorias / $registros);
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
    <title><?php echo $titleNotif; ?>Categorias | POP Nerdweb</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet"
          type="text/css"/>

    <link href="../css/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css"/>
    <link href="../css/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>
    <link href="../css/colorpicker/bootstrap-colorpicker.min.css" rel="stylesheet" type="text/css"/>
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
            <h1>Categorias</h1>

            <ol class="breadcrumb">
                <li><a href="pop-projetos.php"><i class="fa fa-folder-open"></i> Projetos</a></li>
                <li class="active">Categorias de Post</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <?php require_once __DIR__ . "/include/sucesso_error.php"; ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-solid">
                        <div class="box-header">
                            <h3 class="box-title">Lista de Categorias</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-12 filtros">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="acao-bts">
                                                <button class="btn btn-primary btn-flat border-0"
                                                        data-target="#modal-nova-categoria" data-toggle="modal"
                                                        data-backdrop="static"><i class="fa fa-plus"></i> Adicionar nova
                                                    categoria
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="filtro-btns">
                                                <div class="btn-group">
                                                    <span class="filtro-label"><i
                                                                class="fa fa-sort"></i> Ordenar por</span>
                                                    <button data-toggle="dropdown"
                                                            class="btn btn-flat dropdown-toggle btn-dropdown"
                                                            type="button">
                                                        <?php echo $ordens[$_GET['ordem']]['nome'] ?? 'Selecione...'; ?>
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
                                </div><!-- /filtros -->
                            </div><!-- /row filtros -->
                            <div class="row">
                                <?php foreach ($listaCategorias as $categoria) { ?>
                                    <div class="col-sm-12 projeto">
                                        <div class="projeto-box">
                                            <div class="row">
                                                <div class="col-sm-8">
                                                    <div class="projeto-id">
                                                        <span style="background-color: <?php echo $categoria["cor"]; ?>;"><?php echo $categoria['categoria']; ?></span>
                                                    </div>
                                                    <div class="projeto-info">
                                                        <h3><?php echo $categoria['nome']; ?></h3>
                                                        <p><?php echo $categoria['descricao']; ?></p>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="projeto-btns">
                                                        <button class="btn btn-flat btn-block btn-primary border-0 js-getCategoria"
                                                                data-toggle="modal" data-backdrop="static"
                                                                data-target="#modal-categoria"
                                                                data-acao="editar-categoria"
                                                                data-categoria="<?php echo $categoria['categoria']; ?> ">
                                                            <i class="fa fa-pencil pull-left"></i> Editar
                                                            Categoria
                                                        </button>
                                                        <button class="btn btn-flat btn-block btn-danger border-0 js-getCategoria"
                                                                data-toggle="modal" data-backdrop="static"
                                                                data-target="#modal-categoria"
                                                                data-acao="apagar-categoria"
                                                                data-categoria="<?php echo $categoria['categoria']; ?> ">
                                                            <i class="fa fa-remove pull-left"></i> Apagar
                                                            Categoria
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- /CATEGORIA -->
                                <?php } ?>
                            </div><!-- /row categorias -->

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


<!-- MODAL NOVA CATEGORIA - FORM nova-categoria -->
<div class="modal fade" id="modal-nova-categoria" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Cadastrar nova categoria</h4>
            </div>
            <form action="" method="post">
                <input type="hidden" name="form" value="nova-categoria"/>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nome da Categoria*</label>
                        <input type="text" name="nome" class="form-control" placeholder="Nome da Categoria"/>
                    </div>
                    <div class="form-group">
                        <label>Descrição da Categoria*</label>
                        <textarea name="descricao" class="form-control" placeholder="Nome da Categoria"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Cor</label>
                        <div class="input-group demo2">
                            <input type="text" name="cor" value="" class="form-control"/>
                            <span class="input-group-addon"><i></i></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer clearfix">
                    <button type="button" class="btn btn-danger btn-flat border-0 pull-left" data-dismiss="modal"><i
                                class="fa fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary btn-flat border-0"><i class="fa fa-save"></i> Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL GERAL CATEGORIA -->
<div class="modal fade" id="modal-categoria" tabindex="-1" role="dialog" aria-hidden="true"></div>

<script type="text/javascript">
    var idUsuarioLogado = '<?php echo $dadosUsuario["usuarioNerdweb"]; ?>';
</script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="//code.jquery.com/ui/1.11.1/jquery-ui.min.js" type="text/javascript"></script>
<script src="../js/plugins/bootstrap/bootstrap.min.js" type="text/javascript"></script>
<script src="../js/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>
<script src="../js/plugins/iCheck/icheck.min.js" type="text/javascript"></script>
<script src="../js/plugins/colorpicker/bootstrap-colorpicker.min.js" type="text/javascript"></script>
<script src="../js/AdminLTE/app.js" type="text/javascript"></script>
<script src="../js/backoffice.js" type="text/javascript"></script>
<script src="../js/pop.js" type="text/javascript"></script>

<script type="text/javascript">
    /* ********************* CLICKS ********************* */
    $(document).on('click', '.js-getCategoria', function (event) {
        event.preventDefault();

        var idModal = $(this).attr('data-target');
        var idCategoria = $(this).attr('data-categoria');
        var acao = $(this).attr('data-acao');

        $(idModal).hide();

        $.ajax({
            url: 'ajax/ajax-pop.php',
            type: 'POST',
            data: {
                secao: 'categoria',
                tipo: acao,
                categoria: idCategoria
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

    /* ********************* READY ********************* */
    $(document).ready(function () {
        $('.textarea').wysihtml5();
    });

    $(function () {
        $('.demo2').colorpicker();
    });
</script>
</body>
</html>
