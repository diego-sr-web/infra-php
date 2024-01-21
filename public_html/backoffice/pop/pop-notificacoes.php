<?php
require_once __DIR__ . "/../../autoloader.php";

$database = new Database();
$usuario = new AdmUsuario($database);
$POPProjeto = new Projeto($database);
$POPElemento = new Elemento($database);
$BNCliente = new BNCliente($database);

require_once __DIR__ . '/../includes/is_logged.php';
require_once __DIR__ . '/include/le-notificacoes.php';

$listaNotificacoes = $POPElemento->Notificacao->getNotificacoesUsuario($dadosUsuario['usuarioNerdweb'], 150);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $titleNotif; ?>Notificações | POP Nerdweb</title>
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
        <section class="content-header" style="margin-bottom: 15px;">
            <ol class="breadcrumb">
                <li><a href="pop-notificacoes.php"><i class="fa fa-bell"></i> Notificações</a></li>
                <li class="active">Minhas Notificações</li>
            </ol>
        </section>

        <section class="content">
            <?php require_once __DIR__ . "/include/sucesso_error.php"; ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-solid">
                        <div class="box-header">
                            <h3 class="box-title"><i class="fa fa-bell"></i> &nbsp;Minhas Notificações</h3>
                        </div>
                        <div class="box-body">
                            <div class="row" style="display:flex; justify-content: center;">
                                <div class="col-sm-6">
                                    <div class="notifications-header">
                                        <a class="js-marcar-lido"
                                           data-usuario="<?php echo $dadosUsuario['usuarioNerdweb']; ?>"
                                           data-target=".notifications-list" href="#">Marcar todas como lidas</a>
                                    </div>
                                    <ul class="notifications-list">
                                        <?php
                                        if ($listaNotificacoes) {
                                            foreach ($listaNotificacoes as $notificacao) {
                                                $nova = ($notificacao['dataLeitura'] == NULL) ? 'class="nova"' : '';

                                                if ($notificacao['cliente']) {
                                                    $cli = $BNCliente->getDataWithId($notificacao['cliente']);
                                                    $imgCliente = '<span data-toggle="tooltip" data-placement="left" title="' . $cli['nomeFantasia'] . '">
															<img src="' . $BNCliente->path . $cli['logo'] . '" alt="' . $cli['nomeFantasia'] . '">
														   </span>';
                                                }
                                                else {
                                                    $imgCliente = '<span data-toggle="tooltip" data-placement="left" title="Logo do Cliente"><img src="http://placehold.it/65x65" alt="Logo do Cliente"></span>';
                                                }
                                                ?>
                                                <li <?php echo $nova; ?>>
                                                    <a class="js-notificacao"
                                                       data-notificacao="<?php echo $notificacao['notificacao']; ?>"
                                                       href="<?php echo $notificacao['url']; ?>">
                                                        <div class="icon">
                                                            <?php echo $imgCliente; ?>
                                                        </div>
                                                        <div class="txt">
                                                            <p><?php echo $notificacao['texto']; ?></p>
                                                            <p class="tempo">
                                                                <i style="color: <?php echo $notificacao['cor']; ?>;"
                                                                   class="fa <?php echo $notificacao['icone']; ?>"></i>
                                                                há <?php echo Utils::printDate($notificacao['dataCriacao']); ?>
                                                            </p>
                                                        </div>
                                                        <?php if ($notificacao['prazo']) { ?>
                                                            <small class="prazo"><i
                                                                        class="fa fa-calendar"></i><br/><?php echo date('d/m/Y', strtotime($notificacao['prazo'])); ?>
                                                            </small>
                                                        <?php } ?>
                                                    </a>
                                                </li>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
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
</script>
</body>
</html>
