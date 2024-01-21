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
    <?php $Template->insert("backoffice/materialize-head", ["pageTitle" => "Usuários | Backoffice Nerdweb"]); ?>
    <!-- CSS da página-->
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
                                <span>Cadastro Usuário</span>
                            </h4>
                        </div>
                        <div class="col s12 m6 l6 right-align-md">
                            <ol class="breadcrumbs mt-0 mb-0">
                                <li class="breadcrumb-item">
                                    <a href="main.php">Home</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="usuarios.php">Usuários</a>
                                </li>
                                <li class="breadcrumb-item active">
                                    Cadastro Usuário
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
                                        <!-- Cadastro usuários Dados -->
                                        <form id="accountForm">
                                            <div class="row">
                                                <div class="col s12 m6">
                                                    <div class="row">
                                                        <div class="col s12 input-field">
                                                            <i class="material-icons prefix">account_circle</i>
                                                            <input id="name" name="name" type="text" class="validate" value="" data-error=".errorTxt2">
                                                            <label for="name">Nome</label>
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
                                                <div class="col s12 m6">
                                                    <div class="row">
                                                        <div class="col s12 input-field">
                                                            <i class="material-icons prefix">lock_outline</i>
                                                            <input id="senha" name="senha" type="password" class="validate" value="" data-error=".errorTxt1">
                                                            <label for="senha">Senha*</label>
                                                            <small class="errorTxt1"></small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col s12 m6">
                                                    <div class="row">
                                                        <div class="col s12 input-field">
                                                            <i class="material-icons prefix">lock_outline</i>
                                                            <input id="senha_confirmacao" name="senha_confirmacao" type="password" class="validate" value="" data-error=".errorTxt1">
                                                            <label for="senha_confirmacao">Confirmação de Senha*</label>
                                                            <small class="errorTxt1"></small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Configuração nivel acesso -->
                                                <div class="col s12">
                                                    <div id="checkboxes" class="card card-tabs">
                                                        <div class="card-content">
                                                            <div class="card-title">
                                                                <div class="row">
                                                                    <div class="col s12 m6 l10">
                                                                        <h4 class="card-title">Permissões</h4>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div id="view-checkboxes">
                                                                <div class="row">
                                                                    <div class="col s12 m3">
                                                                        <p class="mb-1">
                                                                            <label>
                                                                                <input type="checkbox" />
                                                                                <span>admin</span>
                                                                            </label>
                                                                        </p>
                                                                    </div>
                                                                    <div class="col s12 m3">
                                                                        <p class="mb-1">
                                                                            <label>
                                                                                <input type="checkbox" />
                                                                                <span>intermediário</span>
                                                                            </label>
                                                                        </p>
                                                                    </div>
                                                                    <div class="col s12 m3">
                                                                        <p class="mb-1">
                                                                            <label>
                                                                                <input type="checkbox" />
                                                                                <span>avançado</span>
                                                                            </label>
                                                                        </p>
                                                                    </div>
                                                                    <div class="col s12 m3">
                                                                        <p class="mb-1">
                                                                            <label>
                                                                                <input type="checkbox" />
                                                                                <span>master</span>
                                                                            </label>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Configuração área -->
                                                <div class="col s12">
                                                    <div id="checkboxes" class="card card-tabs">
                                                        <div class="card-content">
                                                            <div class="card-title">
                                                                <div class="row">
                                                                    <div class="col s12 m6 l10">
                                                                        <h4 class="card-title">Áreas</h4>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div id="view-checkboxes">
                                                                <div class="row">
                                                                    <div class="col s12 m3">
                                                                        <p class="mb-1">
                                                                            <label>
                                                                                <input type="checkbox" />
                                                                                <span>Admin</span>
                                                                            </label>
                                                                        </p>
                                                                        <p class="mb-1">
                                                                            <label>
                                                                                <input type="checkbox" />
                                                                                <span>Administrativo</span>
                                                                            </label>
                                                                        </p>
                                                                        <p class="mb-1">
                                                                            <label>
                                                                                <input type="checkbox" />
                                                                                <span>Atendimento</span>
                                                                            </label>
                                                                        </p>
                                                                        <p class="mb-1">
                                                                            <label>
                                                                                <input type="checkbox" />
                                                                                <span>BI</span>
                                                                            </label>
                                                                        </p>
                                                                        <p class="mb-1">
                                                                            <label>
                                                                                <input type="checkbox" />
                                                                                <span>BUGs & Ajustes</span>
                                                                            </label>
                                                                        </p>
                                                                        <p class="mb-1">
                                                                            <label>
                                                                                <input type="checkbox" />
                                                                                <span>Conteúdo</span>
                                                                            </label>
                                                                        </p>
                                                                    </div>
                                                                    <div class="col s12 m3">
                                                                        <p class="mb-1">
                                                                            <label>
                                                                                <input type="checkbox" />
                                                                                <span>Conteúdo Revisar</span>
                                                                            </label>
                                                                        </p>
                                                                        <p class="mb-1">
                                                                            <label>
                                                                                <input type="checkbox" />
                                                                                <span>Criação</span>
                                                                            </label>
                                                                        </p>
                                                                        <p class="mb-1">
                                                                            <label>
                                                                                <input type="checkbox" />
                                                                                <span>Criação Revisar</span>
                                                                            </label>
                                                                        </p>
                                                                        <p class="mb-1">
                                                                            <label>
                                                                                <input type="checkbox" />
                                                                                <span>Desenvolvimento</span>
                                                                            </label>
                                                                        </p>
                                                                        <p class="mb-1">
                                                                            <label>
                                                                                <input type="checkbox" />
                                                                                <span>Front-End</span>
                                                                            </label>
                                                                        </p>
                                                                        <p class="mb-1">
                                                                            <label>
                                                                                <input type="checkbox" />
                                                                                <span>Inbound</span>
                                                                            </label>
                                                                        </p>
                                                                    </div>
                                                                    <div class="col s12 m3">
                                                                        <p class="mb-1">
                                                                            <label>
                                                                                <input type="checkbox" />
                                                                                <span>Infra</span>
                                                                            </label>
                                                                        </p>
                                                                        <p class="mb-1">
                                                                            <label>
                                                                                <input type="checkbox" />
                                                                                <span>Interface</span>
                                                                            </label>
                                                                        </p>
                                                                        <p class="mb-1">
                                                                            <label>
                                                                                <input type="checkbox" />
                                                                                <span>Interface Revisar</span>
                                                                            </label>
                                                                        </p>
                                                                        <p class="mb-1">
                                                                            <label>
                                                                                <input type="checkbox" />
                                                                                <span>Marketing</span>
                                                                            </label>
                                                                        </p>
                                                                        <p class="mb-1">
                                                                            <label>
                                                                                <input type="checkbox" />
                                                                                <span>Monitoramento</span>
                                                                            </label>
                                                                        </p>
                                                                        <p class="mb-1">
                                                                            <label>
                                                                                <input type="checkbox" />
                                                                                <span>Mídia</span>
                                                                            </label>
                                                                        </p>
                                                                    </div>
                                                                    <div class="col s12 m3">
                                                                        <p class="mb-1">
                                                                            <label>
                                                                                <input type="checkbox" />
                                                                                <span>Mídia Revisar</span>
                                                                            </label>
                                                                        </p>
                                                                        <p class="mb-1">
                                                                            <label>
                                                                                <input type="checkbox" />
                                                                                <span>Planejamento</span>
                                                                            </label>
                                                                        </p>
                                                                        <p class="mb-1">
                                                                            <label>
                                                                                <input type="checkbox" />
                                                                                <span>Qualidade</span>
                                                                            </label>
                                                                        </p>
                                                                        <p class="mb-1">
                                                                            <label>
                                                                                <input type="checkbox" />
                                                                                <span>SEO</span>
                                                                            </label>
                                                                        </p>
                                                                        <p class="mb-1">
                                                                            <label>
                                                                                <input type="checkbox" />
                                                                                <span>Sistema e Automacao</span>
                                                                            </label>
                                                                        </p>
                                                                        <p class="mb-1">
                                                                            <label>
                                                                                <input type="checkbox" />
                                                                                <span>Sites</span>
                                                                            </label>
                                                                        </p>
                                                                        <p class="mb-1">
                                                                            <label>
                                                                                <input type="checkbox" />
                                                                                <span>Vendas</span>
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
    <!-- BEGIN: Footer-->
    <?php $Template->insert("backoffice/materialize-footer");?>
    <!-- SCRIPTS -->
    <?php $Template->insert("backoffice/materialize-scripts"); ?>
    <!-- SCRIPTS DA PAGINA -->
    <script src="../assets/vendors/select2/select2.full.min.js"></script>
    <script src="../assets/vendors/jquery-validation/jquery.validate.min.js"> </script>
    <script src="../assets/js/scripts/page-users.js"></script>
</body>
</html>
