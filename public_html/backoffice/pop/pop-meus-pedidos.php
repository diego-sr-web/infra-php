<?php
require_once __DIR__ . "/../../autoloader.php";
require_once __DIR__ . "/../_init.inc.php";

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
$_GET['projeto'] = 13;

$infoProjeto = $POPProjeto->getProjectById($_GET['projeto']);
$infoTipoProjeto = $POPProjeto->getProjectTypeById($infoProjeto['projetoTipo']);

$finalizados = FALSE;
if (isset($_GET['finalizados']) && $_GET['finalizados']) {
    $finalizados = TRUE;
}

$statusFinalizado = [8, 9, 14, 15, 16];

if ($finalizados) {
    $elementosProjeto = $POPElemento->getAllElements(['projeto'], [$_GET['projeto']]);
    $elementosProjeto = $POPElemento->filtraCampoValor($elementosProjeto, 'elementoStatus', $statusFinalizado, FALSE);
}
else {
    $elementosProjeto = $POPElemento->getAllElements(['projeto'], [$_GET['projeto']], TRUE);
}

$elementosProjeto = $POPElemento->filtraCampoValor($elementosProjeto, 'responsavel_de', $_SESSION['adm_usuario'], FALSE);

if ($elementosProjeto != []) {
    Utils::sksort($elementosProjeto, 'elemento', TRUE);
}
//exit;
?>

<!DOCTYPE html>
<html>

<head>
    <!--Datatables-->
    <link rel="stylesheet" type="text/css" href="/assets/css/pages/data-tables.css">
    <!--Datatables-->
    <?php 
    $Template->insert("backoffice/materialize-head", ["pageTitle" => "Blank Page | Backoffice Nerdweb"]);    
    ?>
</head>
<header class="page-topbar" id="header">
        <?php $Template->insert("backoffice/materialize-header"); ?>
    </header>

<div id="top-of-page" class="nav-wrapper row-offcanvas row-offcanvas-left">

    <!-- ################### Nav-Lateral ################## -->
         <!-- BEGIN: SideNav-->
         <?php  $Template->insert("backoffice/materialize-sidebar"); ?>
        <!-- END: SideNav-->
    <!-- ################### Nav-Lateral ################## -->

    <div id="main">
        <div class="row">
            <div class="pt-2 pb-0" id="breadcrumbs-wrapper">
                <!-- header clientes -->
                <div class="container">
                    <div class="row">
                        <div class="col s12 m6 l6">
                            <h4 class="mt-0 mb-0"><span>Pedidos</span></h4>
                        </div>
                        <div class="col s12 m6 l6 right-align-md">
                            <ol class="breadcrumbs mt-0 mb-0">
                                <li class="breadcrumb-item"><a href="main.php">Home</a>
                                </li>
                                <li class="breadcrumb-item active">Meus Pedidos
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col s12">
                <div class="container">
                   
               
                    <!-- adicionar Pedido Botão -->
                <button class="waves-effect waves-light btn gradient-45deg-indigo-light-blue mt-1 right w100 mt-md-1 modal-trigger js-modal"
                    data-toggle="modal" 
                    data-target="#modal-pedido" 
                    data-backdrop="static"     
                    data-acao="adicionar-cliente">
                    <i class="fa fa-plus-circle" aria-hidden="true"></i>
                    Adicionar novo pedido
                </button>
                <!-- 
                <button class="btn btn-primary btn-flat border-0 js-getTarefa"
                data-target="#modal-pedido" 
                data-acao="novo-pedido"
                data-toggle="modal" 
                data-backdrop="static">
                <i class="fa fa-plus"></i> Adicionar novo pedido
                </button>
                -->
                </div>          
            </div>

            <!--####################### Estrutura da tabela Base seguindo modelo Clientes.php ####################-->            
            <div class="col s12">
                <div class="container">
                    <div class="section section-data-tables">
                        <!-- Meus Pedidos -->
                        <div class="row">
                            <div class="col s12">
                                <div class="card">
                                    <div class="card-content" id="tabela">
               
                                    <!-- ################### Corpo da Pagina ################## -->
                                    <?php $Template->insert("blocos/tabelas/pedidos");?>
                                    <!-- ################### Corpo da Pagina ################## -->
                                     
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <div class="content-overlay"></div>
            <!-- END: Page Main ########################## Estrutura da Tebela ############################ -->
       
        </div>
    </div>
    
    <!-- END: Footer-->
    <?php $Template->insert("backoffice/materialize-footer"); ?>
    <!-- BEGIN: SCRIPTS-->    
    <?php $Template->insert("backoffice/materialize-scripts"); ?>

</div>
    
        

<!-- END: SCRIPTS-->
<script type="text/javascript">
    

    $(".tabela-meus-pedidos").dataTable({
        "responsive": true,
        "bPaginate": false,
        //"bLengthChange": false,
        "bFilter": true,
        "aaSorting": [[10, "asc"]]
    });
</script>
  <!-- SCRIPTS DA PAGINA -->


</body>
</html>
