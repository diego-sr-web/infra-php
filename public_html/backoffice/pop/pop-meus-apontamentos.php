<!--Inicialização da Pagina-->
<?php
require_once __DIR__ . "/../../autoloader.php";
require_once __DIR__ . "/../_init.inc.php";
?>
<!--Php da Pagina-->
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
<!--Php da Pagina-->

<!DOCTYPE html>
<html>
<head>
 <!--Datatables-->
 <link rel="stylesheet" type="text/css" href="/assets/css/pages/data-tables.css">
   <?php $Template->insert("backoffice/materialize-head", ["pageTitle" => "Blank Page | Backoffice Nerdweb"]); ?>
</head>

<body class="vertical-layout page-header-light vertical-menu-collapsible vertical-menu-nav-dark preload-transitions 2-columns" data-open="click" data-menu="vertical-gradient-menu" data-col="2-columns">
<header class="page-topbar" id="header">
        <?php $Template->insert("backoffice/materialize-header"); ?>
</header>
<div id="top-of-page" class="wrapper row-offcanvas row-offcanvas-left">
    <!-- Sidebar-->    
    <?php $Template->insert("backoffice/materialize-sidebar"); ?>
    <!-- Sidebar-->


    <div id="main">
        <div class="row">
            <div class="pt-2 pb-0" id="breadcrumbs-wrapper">
                <!-- header clientes -->
                <div class="container">
                    <div class="row">
                        <div class="col s12 m6 l6">
                            <h4 class="mt-0 mb-0"><span>Apontamentos</span></h4>
                        </div>
                        <div class="col s12 m6 l6 right-align-md">
                            <ol class="breadcrumbs mt-0 mb-0">
                                <li class="breadcrumb-item"><a href="main.php">Home</a>
                                </li>
                                <li class="breadcrumb-item active">Meus Apontamentos
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
                             Novo Apontamento
                </button>
         
                </div>          
            </div>
        </div>     
   </div>
    

<!--Main-->
<div id="main">
    <div class="row">
        <div class="col s12">
            <div class="container">
                <div class="section">
                    <div class="card">
                        <div class="card-content">
                        <!--Conteudo da Pagina-->                        
                        <?php $Template->insert("/blocos/tabelas/pop-apontamentos"); ?>             
                        <!--Conteudo da Pagina-->
                        </div>
                    </div>
                </div>
                <div class="content-overlay"></div>
            </div>
        </div>
    </div>
</div>
<!--End Main-->

<!-- MODAL PEDIDO GERAL -->
<div class="modal fade" id="modal-pedido" tabindex="-1" role="dialog" aria-hidden="true"></div>

<!--Footer-->
<?php $Template->insert("backoffice/materialize-footer"); ?>

<!-- BEGIN: SCRIPTS-->    
    <?php $Template->insert("backoffice/materialize-scripts"); ?>


<script type="text/javascript">
    var base_url = '<?php echo POP_URL; ?>';

//JS Tabela pop-apontamentos.tpl.php
    $(".tabela-apontamentos").dataTable({
        "responsive": true,
        "bLengthChange": false,
        "bFilter": true,
    });
</script>

</body>
</html>
