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
    <?php $Template->insert("backoffice/materialize-head-2", ["pageTitle" => "Usuários | Backoffice Nerdweb"]); ?>
    <link rel="stylesheet" type="text/css" href="../assets/css/pages/page-users.css">
    <link rel="stylesheet" href="../assets/vendors/select2/select2-materialize.css" type="text/css">
</head>
<body class="vertical-layout page-header-light vertical-menu-collapsible vertical-menu-nav-dark preload-transitions 2-columns" data-open="click" data-menu="vertical-gradient-menu" data-col="2-columns">
<!-- BEGIN: Header-->
<header class="page-topbar" id="header">
    <?php $Template->insert("backoffice/materialize-header"); ?>
</header>
<!-- BEGIN: SideNav-->
<?php $Template->insert("backoffice/materialize-sidebar"); ?>
<!-- BEGIN: Page Main-->
<div id="main">
    <?php require_once __DIR__ . "/pop/include/sucesso_error.php"; ?>
    <div class="row">
        <div class="pt-2 pb-0" id="breadcrumbs-wrapper">
            <!-- Search for small screen-->
            <div class="container">
                <div class="row">
                    <div class="col s12 m6 l6">
                        <h4 class=" mt-0 mb-0">
                            <span>Dados dos Clientes</span>
                        </h4>
                    </div>
                    <div class="col s12 m6 l6 right-align-md">
                        <ol class="breadcrumbs mt-0 mb-0">
                            <li class="breadcrumb-item">
                                <a href="main.php">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="clientes.php">Clientes</a>
                            </li>
                            <li class="breadcrumb-item active">
                                Dados dos Clientes
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="col s12">
            <div class="container">
                <!-- users edit start -->
                <div class="section users-edit">
                    <div class="card">
                        <div class="card-content">
                            <div class="row">
                                <div class="col s12" id="account">
                                    <!-- Cadastro dados clientes -->
                                    <form id="accountForm">
                                        <div class="row">
                                            <div class="col s12">
                                                <div class="row">
                                                    <div class="col s12 input-field">
                                                        <i class="material-icons prefix">business</i>
                                                        <input id="name_fantasy" name="name_fantasy" type="text" class="validate" value="" data-error=".errorTxt2">
                                                        <label for="name_fantasy">Nome Fantasia</label>
                                                        <small class="errorTxt2"></small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col s12 m6">
                                                <div class="row">
                                                    <div class="col s12 input-field">
                                                        <i class="material-icons prefix">group</i>
                                                        <input id="responsavel" name="responsavel" type="text" class="validate" value="" data-error=".errorTxt2">
                                                        <label for="responsavel">Nome Responsável</label>
                                                        <small class="errorTxt2"></small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col s12 m6">
                                                <div class="row">
                                                    <div class="col s12 input-field">
                                                        <i class="material-icons prefix">email</i>
                                                        <input id="mail" name="mail" type="email" class="validate" value="" data-error=".errorTxt2">
                                                        <label for="mail">Email</label>
                                                        <small class="errorTxt2"></small>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Configuração Produto -->
                                            <div class="col s12">
                                                <div id="checkboxes" class="card card-tabs">
                                                    <div class="card-content">
                                                        <div class="card-title">
                                                            <div class="row">
                                                                <div class="col s12 m6 l10">
                                                                    <h4 class="card-title">Categoria Serviços</h4>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="view-checkboxes">
                                                            <div class="row">
                                                                <div class="col s12 m3">
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox" />
                                                                            <span>website</span>
                                                                        </label>
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox" />
                                                                            <span>redes socias</span>
                                                                        </label>
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox" />
                                                                            <span>landing page</span>
                                                                        </label>
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox" />
                                                                            <span>email marketing</span>
                                                                        </label>
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox" />
                                                                            <span>website</span>
                                                                        </label>
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox" />
                                                                            <span>website</span>
                                                                        </label>
                                                                    </p>
                                                                </div>
                                                                <div class="col s12 m3">
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox" />
                                                                            <span>website</span>
                                                                        </label>
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox" />
                                                                            <span>website</span>
                                                                        </label>
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox" />
                                                                            <span>website</span>
                                                                        </label>
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox" />
                                                                            <span>website</span>
                                                                        </label>
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox" />
                                                                            <span>website</span>
                                                                        </label>
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox" />
                                                                            <span>website</span>
                                                                        </label>
                                                                    </p>
                                                                </div>
                                                                <div class="col s12 m3">
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox" />
                                                                            <span>website</span>
                                                                        </label>
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox" />
                                                                            <span>website</span>
                                                                        </label>
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox" />
                                                                            <span>website</span>
                                                                        </label>
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox" />
                                                                            <span>website</span>
                                                                        </label>
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox" />
                                                                            <span>website</span>
                                                                        </label>
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox" />
                                                                            <span>website</span>
                                                                        </label>
                                                                    </p>
                                                                </div>
                                                                <div class="col s12 m3">
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox" />
                                                                            <span>website</span>
                                                                        </label>
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox" />
                                                                            <span>website</span>
                                                                        </label>
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox" />
                                                                            <span>website</span>
                                                                        </label>
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox" />
                                                                            <span>website</span>
                                                                        </label>
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox" />
                                                                            <span>website</span>
                                                                        </label>
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox" />
                                                                            <span>website</span>
                                                                        </label>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col s12 display-flex justify-content-end mt-3">
                                                <button type="submit" class="btn blue darken-4">Salvar</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $Template->insert("backoffice/materialize-footer");?>
<script src="../assets/js/vendors.min.js"></script>
<script src="../assets/vendors/select2/select2.full.min.js"></script>
<script src="../assets/vendors/jquery-validation/jquery.validate.min.js"> </script>
<script src="../assets/js/plugins.js"></script>
<script src="../assets/js/search.js"></script>
<script src="../assets/js/custom/custom-script.js"></script>
<script src="../assets/js/scripts/page-users.js"></script>
<!--
<script type="text/javascript">
    $(function () {
        $('.crop-avatar').cropit({
            width: 200,
            height: 200,
            minZoom: 'fit',
            freeMove: true,
            smallImage: 'allow'
        });
        $('.crop-avatar').cropit('imageSrc', '<?php echo $infoUsuario["imagem"]; ?>');
    });
    $(document).on('submit', '.form-crop', function () {
        var $crop = $(this).find('.crop-avatar');
        var imageData = $crop.cropit('export', {
            type: 'image/png'
        });
        $crop.find('.cropit-image-data').val(imageData);

        return true;
    });
</script>
-->
</body>
</html>
