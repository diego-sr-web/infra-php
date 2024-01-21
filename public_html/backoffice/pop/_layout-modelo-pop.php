<!--Inicialização da Pagina-->
<?php
require_once __DIR__ . "/../../autoloader.php";
require_once __DIR__ . "/../_init.inc.php";
?>
<!--Inicialização da Pagina-->








<!--################################# Php da Pagiana ##########################################-->
<?php
$database = new Database();
$usuario = new AdmUsuario($database);
$POPProjeto = new Projeto($database);
$POPElemento = new Elemento($database);

$BNCliente = new BNCliente($database);
$listaClientes = $BNCliente->listAll("", "nomeFantasia");

require_once __DIR__ . '/../includes/is_logged.php';
require_once __DIR__ . '/include/le-notificacoes.php';
$listaPrioridade = $POPElemento->getPrioridadeList();
$listaAreas = $usuario->listArea();
$listaUsuarios = $usuario->listAll("", "nome", FALSE);
if (isset($_POST["form"])) {
    require_once __DIR__ . "/pop-processa-modal.php";
}

$_GET['projeto'] = 3112;
$infoProjeto = $POPProjeto->getProjectById($_GET['projeto']);
$infoTipoProjeto = $POPProjeto->getProjectTypeById($infoProjeto['projetoTipo']);
$elementosProjeto = $POPElemento->getAllElements(['projeto'], [$_GET['projeto']]);
$elementosProjeto = $POPElemento->filtraCampoValor($elementosProjeto, 'responsavel', $_SESSION['adm_usuario'], FALSE);
if ($elementosProjeto != []) {
    Utils::sksort($elementosProjeto, 'elemento', TRUE);
}
?>
<!--################################# Php da Pagiana ##########################################-->




<!DOCTYPE html>
<html>


<!--################################# Cabeçalho ##########################################-->
<head>
 <!--Datatables-->
 <link rel="stylesheet" type="text/css" href="../../assets/css/pages/data-tables.css">
<!--Datatables-->
    <?php $Template->insert("backoffice/materialize-head", ["pageTitle" => "Blank Page | Backoffice Nerdweb"]); ?>
</head>
<!--################################# Php da Pagian ##########################################-->


<body class="vertical-layout page-header-light vertical-menu-collapsible vertical-menu-nav-dark preload-transitions 2-columns" data-open="click" data-menu="vertical-gradient-menu" data-col="2-columns">

<div id="top-of-page" class="wrapper row-offcanvas row-offcanvas-left">
    <!--############################## Sidebar ###########################-->
    <!-- Left side column. contains the logo and sidebar -->
    <?php $Template->insert("backoffice/materialize-sidebar"); ?>
    <!-- Right side column. Contains the navbar and content of the page -->
    <!--############################## Sidebar ###########################-->


    <!--############################## Corpo d Pagina ###########################-->
    <!-- BEGIN: Page Main-->
<div id="main">
    <div class="row">
        <div class="col s12">
            <div class="container">
                <div class="section">
                    <div class="card">
                        <div class="card-content">
                        <!--Conteudo da Pagina-->

                        <?php /*$Template->insert("backoffice/materialize-sidebar"); */ echo 'Aqui vaia a pagina'; ?>


                        <!--Conteudo da Pagina-->
                        </div>
                    </div>
                </div>
            <div class="content-overlay"></div>
        </div>
    </div>
</div>
<!-- END: Page Main-->
<!--############################## Corpo d Pagina ###########################-->




</div>

<!-- MODAL PEDIDO GERAL -->
<div class="modal fade" id="modal-pedido" tabindex="-1" role="dialog" aria-hidden="true"></div>

<!-- END: Footer-->

<?php $Template->insert("backoffice/materialize-footer"); ?>
    <!-- BEGIN: SCRIPTS-->    

<?php $Template->insert("backoffice/materialize-scripts"); ?>


<script type="text/javascript">
    var base_url = '<?php echo POP_URL; ?>';

    $(".tabela-meus-pedidos").dataTable({
        "bPaginate": false,
        //"bLengthChange": false,
        "bFilter": true,
        "aaSorting": [[10, "asc"]]
    });
</script>
</body>
</html>
