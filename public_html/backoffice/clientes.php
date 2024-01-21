<?php
require_once __DIR__ . "/../autoloader.php";
require_once __DIR__ . "/_init.inc.php";
if ($_SESSION["adm_is_admin"] != 1) {
    Utils::redirect("main.php");
}
?>
<!DOCTYPE html>
<html class="loading" lang="pt-br" data-textdirection="ltr">
<head>
    <!-- CSS Geral-->
    <?php $Template->insert("backoffice/materialize-head", ["pageTitle" => "Clientes | Backoffice Nerdweb"]); ?>
    <!-- CSS da página-->
    <link rel="stylesheet" type="text/css" href="../assets/css/pages/data-tables.css">
</head>
<body class="vertical-layout page-header-light vertical-menu-collapsible vertical-gradient-menu preload-transitions 2-columns" data-open="click" data-menu="vertical-gradient-menu" data-col="2-columns">
    <!-- Header -->
    <header class="page-topbar" id="header">
        <?php $Template->insert("backoffice/materialize-header"); ?>
    </header>
    <!-- SideNav -->
    <?php $Template->insert("backoffice/materialize-sidebar"); ?>
    <!-- Page Main -->
    <div id="main">
        <div class="row">
            <div class="pt-2 pb-0" id="breadcrumbs-wrapper">
                <!-- header clientes -->
                <div class="container">
                    <div class="row">
                        <div class="col s12 m6 l6">
                            <h4 class="mt-0 mb-0"><span>Clientes</span></h4>
                        </div>
                        <div class="col s12 m6 l6 right-align-md">
                            <ol class="breadcrumbs mt-0 mb-0">
                                <li class="breadcrumb-item"><a href="main.php">Home</a>
                                </li>
                                <li class="breadcrumb-item active">Clientes
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col s12">
                <div class="container">
                    <!-- adicionar clientes -->
                    <button class="waves-effect waves-light btn gradient-45deg-indigo-light-blue mt-1 float-right w100 mt-md-1 modal-trigger js-modal"
                            data-toggle="modal" data-target="#modal-backoffice" data-secao="cliente" data-acao="adicionar-cliente"> <i class="fa fa-plus-circle" aria-hidden="true"></i> Adicionar Clientes
                    </button>
                </div>
            </div>
            <div class="col s12">
                <div class="container">
                    <div class="section section-data-tables">
                        <!-- lista de clientes -->
                        <div class="row">
                            <div class="col s12">
                                <div class="card">
                                    <div class="card-content" id="tabela">

                                        <?php $Template->insert("/blocos/tabelas/clientes"); ?>
                                    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-overlay"></div>
            </div>
        </div>
    </div>
    <!-- END: Page Main -->
    <?php $Template->insert("backoffice/materialize-footer");?>
    <!-- SCRIPTS -->
    <?php $Template->insert("backoffice/materialize-scripts");?>
  
  
    <!-- SCRIPTS DA PAGINA -->
    <script type="text/javascript">
        $(document).ready( function () {
            getListaCliente('todos');
        } );

        $(document).on('submit', '.form-crop', function () {
            var $crop = $(this).find('.crop-logo');
            var imageData = $crop.cropit('export', {
                type: 'image/png'
            });
            $crop.find('.cropit-image-data').val(imageData);

            return true;
        });
    </script>
</body>
</html>
