<?php
require_once __DIR__ . "/../autoloader.php";
require_once __DIR__ . "/_init.inc.php";
?>
<!DOCTYPE html>
<html class="loading" lang="pt" data-textdirection="ltr">
<!-- BEGIN: Head-->
<head>
    <?php $Template->insert("backoffice/materialize-head", ["pageTitle" => "Usuarios | Backoffice Nerdweb"]); ?>
</head>
<!-- END: Head-->

<body class="vertical-layout page-header-light vertical-menu-collapsible preload-transitions 2-columns" data-open="click" data-menu="vertical-gradient-menu"
      data-col="2-columns">

<!-- BEGIN: Header-->
<header class="page-topbar" id="header">
    <?php $Template->insert("backoffice/materialize-header"); ?>
</header>
<!-- END: Header-->

<!-- BEGIN: SideNav-->
<?php $Template->insert("backoffice/materialize-sidebar"); ?>
<!-- END: SideNav-->

<!-- BEGIN: Page Main-->
<div id="main">
    <div class="row">

        <div class="col s12">
            <div class="container">
                <div class="section">
                    <div class="card">
                        <div class="row">
                            <div class="col s12 m8 l4">
                                <div class="card-content">
                                    <h4 class="card-title">Listagem Usuarios</h4>
                                    <a class="waves-effect waves-light btn-large purple" onclick="getListaUsuario('todos')">
                                        <i class="material-icons left">cloud</i>Todos
                                    </a>
                                    <a class="waves-effect waves-light btn-large green" onclick="getListaUsuario('ativo')">
                                        <i class="material-icons left">cloud</i>Ativos
                                    </a>
                                    <a class="waves-effect waves-light btn-large red" onclick="getListaUsuario('inativo')">
                                        <i class="material-icons left">cloud</i>Inativos
                                    </a>
                                </div>
                            </div>
                            <div class="col s12 m8 l4">
                                <div class="card-content">
                                    <h4 class="card-title">Listagem Clientes</h4>
                                    <a class="waves-effect waves-light btn-large purple" onclick="getListaCliente('todos')">
                                        <i class="material-icons left">cloud</i>Todos
                                    </a>
                                    <a class="waves-effect waves-light btn-large green" onclick="getListaCliente('ativo')">
                                        <i class="material-icons left">cloud</i>Ativos
                                    </a>
                                    <a class="waves-effect waves-light btn-large red" onclick="getListaCliente('inativo')">
                                        <i class="material-icons left">cloud</i>Inativos
                                    </a>
                                </div>
                            </div>
                            <div class="col s12 m8 l4">
                                <div class="card-content">
                                    <h4 class="card-title">Listagem Areas</h4>
                                    <a class="waves-effect waves-light btn-large purple" onclick="getListaArea('todos')">
                                        <i class="material-icons left">cloud</i>Todos
                                    </a>
                                    <a class="waves-effect waves-light btn-large green" onclick="getListaArea('ativo')">
                                        <i class="material-icons left">cloud</i>Ativos
                                    </a>
                                    <a class="waves-effect waves-light btn-large red" onclick="getListaArea('inativo')">
                                        <i class="material-icons left">cloud</i>Inativos
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="section">
                    <div class="card">
                        <div class="card-content" id="tabela"></div>
                    </div>
                </div>
                <?php $Template->insert("backoffice/materialize-sidebar-right"); ?>
            </div>
            <div class="content-overlay"></div>
        </div>
    </div>
</div>
<!-- END: Page Main-->

<!-- BEGIN: Footer-->
<?php $Template->insert("backoffice/materialize-footer"); ?>
<!-- END: Footer-->
<!-- BEGIN: SCRIPTS-->
<?php $Template->insert("backoffice/materialize-scripts"); ?>
<!-- END: SCRIPTS-->
<script>
    $(document).ready(function () {  });
</script>
</body>
</html>
