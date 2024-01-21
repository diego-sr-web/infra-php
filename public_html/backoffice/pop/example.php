<?php
require_once __DIR__ . "/../../autoloader.php";
require_once __DIR__ . "/../_init.inc.php";
?>
<!DOCTYPE html>
<html class="loading" lang="pt" data-textdirection="ltr">
<!-- BEGIN: Head-->
<head>
    <?php $Template->insert("pop/materialize-head", ["pageTitle" => "Blank Page | POP Nerdweb"]); ?>
</head>
<!-- END: Head-->

<body class="vertical-layout page-header-light vertical-menu-collapsible preload-transitions 2-columns" data-open="click" data-menu="vertical-gradient-menu" data-col="2-columns">

<!-- BEGIN: Header-->
<header class="page-topbar" id="header">
    <?php $Template->insert("pop/materialize-header"); ?>
</header>
<!-- END: Header-->

<!-- BEGIN: SideNav-->
<?php $Template->insert("pop/materialize-sidebar"); ?>
<!-- END: SideNav-->

<!-- BEGIN: Page Main-->
<div id="main">
    <div class="row">

        <div class="col s12">
            <div class="container">
                <div class="section">
                    <div class="card">
                        <div class="card-content">
                            <p class="caption mb-0">
                                Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta) desconocido usó una galería de textos y los mezcló de tal manera que logró hacer un libro de textos especimen. No sólo sobrevivió 500 años, sino que tambien ingresó como texto de relleno en documentos electrónicos, quedando esencialmente igual al original. Fue popularizado en los 60s con la creación de las hojas "Letraset", las cuales contenian pasajes de Lorem Ipsum, y más recientemente con software de autoedición, como por ejemplo Aldus PageMaker, el cual incluye versiones de Lorem Ipsum.
                            </p>
                        </div>
                    </div>
                </div>
                <?php $Template->insert("pop/materialize-sidebar-right"); ?>
            </div>
            <div class="content-overlay"></div>
        </div>
    </div>
</div>
<!-- END: Page Main-->

<!-- BEGIN: Footer-->
<?php $Template->insert("pop/materialize-footer"); ?>
<!-- END: Footer-->
<!-- BEGIN: SCRIPTS-->
<?php $Template->insert("pop/materialize-scripts"); ?>
<!-- END: SCRIPTS-->
</body>

</html>
