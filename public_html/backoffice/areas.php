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
    <?php $Template->insert("backoffice/materialize-head", ["pageTitle" => "Áreas | Backoffice Nerdweb"]); ?>
    <!-- CSS da página-->
    <link rel="stylesheet" type="text/css" href="../assets/css/pages/data-tables.css">
</head>
<body class="vertical-layout page-header-light vertical-menu-collapsible vertical-gradient-menu preload-transitions 2-columns"
      data-open="click" data-menu="vertical-gradient-menu" data-col="2-columns">
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
            <!-- header áreas -->
            <div class="container">
                <div class="row">
                    <div class="col s12 m6 l6">
                        <h4 class="mt-0 mb-0"><span>Áreas</span></h4>
                    </div>
                    <div class="col s12 m6 l6 right-align-md">
                        <ol class="breadcrumbs mt-0 mb-0">
                            <li class="breadcrumb-item"><a href="main.php">Home</a>
                            </li>
                            <li class="breadcrumb-item active">Áreas
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="col s12">
            <div class="container">
                <div style="display: flex; margin-top: 20px; justify-content: flex-end;">
                    <!-- adicionar áreas -->
                    <?php
                    $Template->insert("backoffice/botao-simples",
                        [
                            "acao" => "adicionar-area",
                            "secao" => "area",
                            "cor" => "btn-primary",
                            "icone" => "material-icons",
                            "textoBotao" => "Adicionar Área"
                        ]
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="col s12">
            <div class="container">
                <div class="section section-data-tables">
                    <!-- lista de Áreas -->
                    <div class="row">
                        <div class="col s12">
                            <div class="card">
                                <div class="card-content" id="tabela">
                                    <?php //$Template->insert("/blocos/tabelas/areas"); ?>
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
<?php $Template->insert("backoffice/materialize-footer"); ?>
<!-- SCRIPTS -->
<?php $Template->insert("backoffice/materialize-scripts"); ?>
<!-- SCRIPTS DA PAGINA -->
<script type="text/javascript">
    $(document).ready(function () {
        getListaArea('todos');
    });
</script>

</body>
</html>
