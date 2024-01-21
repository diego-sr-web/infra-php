<?php
require_once __DIR__ . "/../../autoloader.php";

$database = new Database();
$usuario = new AdmUsuario($database);
$POPProjeto = new Projeto($database);
$POPElemento = new Elemento($database);
$BNCliente = new BNCliente($database);

require_once __DIR__ . '/../includes/is_logged.php';
require_once __DIR__ . '/include/le-notificacoes.php';

if (isset($_POST['form'])) {
    $dtAtual = date('Y-m-d H:i:s');
    $redirect = ['redireciona' => FALSE, 'url' => 'pop-fluxos.php', 'queryString' => '',];

    switch ($_POST['form']) {
        case 'adicionar-tipoProjeto':
            if ($_POST['nome']) {
                $campos = $_POST['campo'] ?? [];
                $valores = $_POST['tipoCampo'] ?? [];
                $identificador = 'POP_PROJETO_' . strtoupper(Utils::stringToURL($_POST['nome']));
                $_POST['identifier'] = $identificador;

                unset($_POST['campo'], $_POST['tipoCampo'], $_POST['form']);
                $campos[] = 'diasPrazo';
                $valores[] = 1;

                foreach ($_POST as $key => $value) {
                    $campos[] = $key;
                    $valores[] = $value;
                }

                $retorno = $POPProjeto->insertProjectType($campos, $valores);

                if ($retorno) {
                    $idProjetoTipo = $retorno;
                    $redirect['redireciona'] = TRUE;
                    $redirect['url'] = 'pop-fluxo.php';
                    $redirect['queryString'] = "tipoProjeto=$idProjetoTipo&msg=1&txt=Tipo de projeto inserido com sucesso.";

                }
                else {
                    $redirect['redireciona'] = TRUE;
                    $redirect['queryString'] = "msg=2&txt=Houve um erro ao cadastrar o tipo de projeto.";
                }

            }
            else {
                $redirect['redireciona'] = TRUE;
                $redirect['queryString'] = "msg=2&txt=Houve um erro ao cadastrar o tipo de projeto.";
            }
            break;

        case 'editar-tipoProjeto':
            if ($_POST['nome']) {
                $idProjetoTipo = $_POST['projetoTipo'];
                $identificador = 'POP_PROJETO_' . strtoupper(Utils::stringToURL($_POST['nome']));
                //$_POST['identifier'] = $identificador;

                unset($_POST['form'], $_POST['projetoTipo']);

                foreach ($_POST as $key => $value) {
                    $campos[] = $key;
                    $valores[] = $value;
                }

                $retorno = $POPProjeto->updateProjectType($idProjetoTipo, $campos, $valores);

                if ($retorno) {
                    $redirect['redireciona'] = TRUE;
                    $redirect['queryString'] = "msg=1&txt=Tipo de projeto atualizado com sucesso.";
                }
                else {
                    $redirect['redireciona'] = TRUE;
                    $redirect['queryString'] = "msg=2&txt=Houve um erro ao atualizar o tipo de projeto.";
                }

            }
            else {
                $redirect['redireciona'] = TRUE;
                $redirect['queryString'] = "msg=2&txt=Houve um erro ao atualizar o tipo de projeto.";
            }
            break;

        default:
            break;
    }


    if ($redirect['redireciona']) {
        echo '<script language="javascript">window.location.href="' . $redirect['url'] . '?' . $redirect['queryString'] . '"</script>';
        exit();
    }

}

if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 1) {
        $msg_sucesso = $_GET['txt'];
    }
    elseif ($_GET['msg'] == 2) {
        $msg_erro = $_GET['txt'];
    }
}

$arrayQueryString = $_GET;
$queryString = http_build_query($arrayQueryString);

$ordens = [1 => ['nome' => 'ID', 'campo' => 'projetoTipo'], 2 => ['nome' => 'Nome Crescente', 'campo' => 'nome ASC'], 3 => ['nome' => 'Nome Decrescente', 'campo' => 'nome DESC'],];

$pagina = $_GET['pagina'] ?? 1;
$registros = 10;
$inicio = ($registros * $pagina) - $registros;
$order = $ordens[$_GET['ordem']]['campo'] ?? "";
$limit = "$inicio, $registros";

$numeroTiposProjeto = $POPProjeto->getProjectTypeCount();
$listaTiposProjeto = $POPProjeto->getProjectTypeList([], [], $order, $limit);

$totalPagina = ceil($numeroTiposProjeto / $registros);
$exibir = 3;
$anterior = (($pagina - 1) == 0) ? 1 : $pagina - 1;
$proxima = (($pagina + 1) >= $totalPagina) ? $totalPagina : $pagina + 1;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $titleNotif; ?>Construção de Fluxos | POP Nerdweb</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet"
          type="text/css"/>
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
            <h1>Construção de Fluxos</h1>

            <ol class="breadcrumb">
                <li class="active"><a href="pop-projetos.php"><i class="fa fa-code-fork"></i> Construção de Fluxos</a>
                </li>
            </ol>
        </section>

        <section class="content">
            <?php if (isset($msg_sucesso)) { ?>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="callout callout-success alert-dismissable" style="text-align: center;">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            <h4>Sucesso</h4>
                            <p><?php echo $msg_sucesso; ?></p>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php if (isset($msg_erro)) { ?>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="callout callout-danger alert-dismissable" style="text-align: center;">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            <h4>Alerta</h4>
                            <p><?php echo $msg_erro; ?></p>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-solid">
                        <div class="box-header">
                            <i class="fa fa-object-group"></i>
                            <h3 class="box-title">Tipos de Projeto</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-12 filtros">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="acao-bts">
                                                <button class="btn btn-primary btn-flat border-0"
                                                        data-target="#modal-geral" data-toggle="modal"
                                                        data-backdrop="static"><i class="fa fa-plus"></i> Adicionar novo
                                                    tipo de
                                                    projeto
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="box-filtros">
                                                <div class="busca-btns">
                                                    <form action="" method="get">
                                                        <div class="input-group">
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
                                                        <span class="filtro-label"><i class="fa fa-sort"></i> Ordenar por</span>
                                                        <button data-toggle="dropdown"
                                                                class="btn btn-flat dropdown-toggle btn-dropdown"
                                                                type="button">
                                                            <?php echo $ordens[$_GET['ordem']]['nome'] ?? 'Selecione...' ; ?>
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
                                <?php foreach ($listaTiposProjeto as $tipoProjeto) { ?>
                                    <div class="col-sm-12 tipo-projeto">
                                        <div class="tipo-projeto-box">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="tipo-projeto-id">
                                                        <span <?php if ($tipoProjeto['cor']) {
                                                            echo 'style="background-color: ' . $tipoProjeto['cor'] . ';';
                                                        } ?>"><i
                                                                class="fa <?php echo $tipoProjeto['icone']; ?>"></i></span>
                                                    </div>
                                                    <div class="tipo-projeto-info">
                                                        <h3><?php echo $tipoProjeto['nome']; ?></h3>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="tipo-projeto-data">
                                                        <p><?php echo $tipoProjeto['descricao']; ?></p>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="tipo-projeto-btns">
                                                        <a href="pop-fluxo.php?tipoProjeto=<?php echo $tipoProjeto['projetoTipo']; ?>"
                                                           class="btn btn-flat btn-block btn-primary border-0"><i
                                                                    class="fa fa-search pull-left"></i> Ver Tipo de
                                                            Projeto</a>
                                                        <button class="btn btn-flat btn-block btn-primary border-0 js-getTipoProjeto"
                                                                data-toggle="modal" data-backdrop="static"
                                                                data-target="#modal-tipoProjeto"
                                                                data-acao="editar-tipoProjeto"
                                                                data-tipoProjeto="<?php echo $tipoProjeto['projetoTipo']; ?>">
                                                            <i class="fa fa-pencil pull-left"></i>
                                                            Editar Tipo de Projeto
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- /tipo de projeto -->
                                <?php } ?>
                            </div>

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
        </section>
    </aside>
</div>


<!-- MODAL GERAL -->
<div class="modal fade" id="modal-geral" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Adicionar tipo de projeto</h4>
            </div>
            <form action="" method="post">
                <input type="hidden" name="form" value="adicionar-tipoProjeto"/>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nome do Tipo de Projeto</label>
                        <input title="Nome do Tipo de Projeto" type="text" name="nome" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label>Descrição</label>
                        <textarea title="Descrição" name="descricao" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Cor</label>
                        <div class="input-group demo2">
                            <input type="text" name="cor" value="" class="form-control"/>
                            <span class="input-group-addon"><i></i></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Ícone (formato "fa-icone" - <a target="_blank"
                                                              href="http://fortawesome.github.io/Font-Awesome/icons/">ver
                                aqui</a>)</label>
                        <input title="Ícone" type="text" name="icone" class="form-control"/>
                    </div>
                    <div class="box-etapas">
                        <div class="form-group box-etapa">
                            <label>Campos</label>
                            <div class="row lista-campos">
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <button class="btn btn-flat btn-primary pull-right border-0 js-adicionar-campo">
                                        Adicionar Campo
                                    </button>
                                </div>
                            </div>
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
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- MODAL TIPOPROJETO GERAL -->
<div class="modal fade" id="modal-tipoProjeto" tabindex="-1" role="dialog" aria-hidden="true">
</div>

<script type="text/javascript">
    var idUsuarioLogado = '<?php echo $dadosUsuario["usuarioNerdweb"]; ?>';
</script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="//code.jquery.com/ui/1.11.1/jquery-ui.min.js" type="text/javascript"></script>
<script src="../js/plugins/bootstrap/bootstrap.min.js" type="text/javascript"></script>
<script src="../js/plugins/iCheck/icheck.min.js" type="text/javascript"></script>
<script src="../js/plugins/colorpicker/bootstrap-colorpicker.min.js" type="text/javascript"></script>
<script src="../js/AdminLTE/app.js" type="text/javascript"></script>
<script src="../js/backoffice.js" type="text/javascript"></script>
<script src="../js/pop.js" type="text/javascript"></script>

<script type="text/javascript">
    var base_url = '<?php echo POP_URL; ?>';

    $(function () {
        $('.demo2').colorpicker();
    });

    var campo_campo = '<div class="campo-etapa">' +
        '<div class="col-xs-6">' +
        '<div class="form-group">' +
        '<input type="text" class="form-control" name="campo[]" placeholder="Nome do Campo">' +
        '</div>' +
        '</div>' +
        '<div class="col-xs-6">' +
        '<div class="form-group">' +
        '<select class="form-control" name="tipoCampo[]">' +
        '<option value="">-- Selecione o Tipo do Campo --</option>' +
        '<option value="text">text</option>' +
        '<option value="textarea">textarea</option>' +
        '<option value="editor">editor</option>' +
        '<option value="img">img</option>' +
        '<option value="file">file</option>' +
        '<option value="number">number</option>' +
        '<option value="date">date</option>' +
        '<option value="datetime">datetime</option>' +
        '<option value="boolean">boolean</option>' +
        '<option value="script">script</option>' +
        '</select>' +
        '</div>' +
        '</div>' +
        '</div>';

    $(document).on('click', '.js-adicionar-campo', function (event) {
        event.preventDefault();
        $(this).parent().parent().parent().find('.lista-campos').append(campo_campo);
    });

    $(document).on('click', '.js-getTipoProjeto', function (event) {
        event.preventDefault();

        var idModal = $(this).attr('data-target');
        var idTipoProjeto = $(this).attr('data-tipoProjeto');
        var acao = $(this).attr('data-acao');

        $(idModal).hide();

        $.ajax({
            url: 'ajax/ajax-pop.php',
            type: 'POST',
            data: {
                secao: 'fluxo',
                tipo: acao,
                tipoProjeto: idTipoProjeto
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
