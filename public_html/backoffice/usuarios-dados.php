<?php
require_once __DIR__ . "/../autoloader.php";
require_once __DIR__ . "/_init.inc.php";

if (isset($_POST['form'])) {
    switch ($_POST['form']) {
        case 'alterar-senha':
            if ($_POST['senha'] && $_POST['senha_confirmacao']) {
                if ($_POST['senha'] !== $_POST['senha_confirmacao']) {
                    $_SESSION["msg_erro"] = "Senha e confirmacao de senha devem ser iguais";
                } elseif (strlen($_POST['senha']) < 8) {
                    $_SESSION["msg_erro"] = "Senha muito curta, a senha deve conter pelo menos 8 caracteres";
                } else {
                    $senha = $_POST["senha"];
                    $retorno = $usuario->setPassword($dadosUsuario['email'], $senha);
                    if ($retorno) {
                        $_SESSION["msg_sucesso"] = 'Usuário atualizado com sucesso.';
                    } else {
                        $_SESSION["msg_erro"] = 'Houve algum erro ao salvar o registro.';
                    }
                }
            } else {
                $_SESSION["msg_erro"] = 'Preencha todos os campos obrigatórios.';
            }
            break;
        case 'alterar-avatar':
            if ($_POST['image-data']) {
                $avatar = uploadImagemBase64($_POST['image-data'], Utils::stringToURL($_SESSION['adm_nome']), $_SERVER['DOCUMENT_ROOT'] . '/backoffice/uploads/usuarios/');
                $retorno = $usuario->setAvatar($_SESSION['adm_usuario'], $avatar['arquivo']);

                if ($retorno) {
                    $_SESSION["msg_sucesso"] = 'Avatar atualizado com sucesso.';
                } else {
                    $_SESSION["msg_erro"] = 'Houve algum erro ao salvar o registro.';
                }
            }
            break;
    }
}
$listaUsuarios = $usuario->listAll();
$listaArea = $usuario->listArea();
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
<body class="vertical-layout page-header-light vertical-menu-collapsible vertical-menu-nav-dark preload-transitions 2-columns"
      data-open="click" data-menu="vertical-gradient-menu" data-col="2-columns">
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
                        <h4 class="mt-0 mb-0">
                            <span>Meus dados</span>
                        </h4>
                    </div>
                    <div class="col s12 m6 l6 right-align-md">
                        <ol class="breadcrumbs mt-0 mb-0">
                            <li class="breadcrumb-item">
                                <a href="main.php">Home</a>
                            </li>
                            <li class="breadcrumb-item active">
                                Dados do usuario
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
                                    <!-- Edição dados usuários Avatar -->
                                    <div class="media display-flex align-items-center mb-2">
                                        <a class="mr-2" href="#">
                                            <img src="<?php echo $_SESSION['adm_imagem']; ?>"
                                                 alt="users avatar" class="z-depth-4 circle" height="64" width="64">
                                        </a>
                                        <div class="media-body">
                                            <h5 class="media-heading mt-0">Avatar</h5>
                                            <small>Allowed JPG, GIF or PNG. Max size of 800kB</small>
                                            <div class="user-edit-btns display-flex file-field input-field">
                                                <div class="btn blue darken-3 file-custom">
                                                    <span>Enviar</span>
                                                    <input type="file">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Edição dados usuários Dados -->
                                    <form id="accountForm">
                                        <div class="row">
                                            <div class="col s12">
                                                <div class="row">
                                                    <div class="col s12 input-field">
                                                        <i class="material-icons prefix">account_circle</i>
                                                        <input id="name" name="name" type="text" class="validate"
                                                               value="<?php echo $_SESSION['adm_nome'] ?>" data-error=".errorTxt2">
                                                        <label for="name">Nome</label>
                                                        <small class="errorTxt2"></small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col s12 m6">
                                                <div class="row">
                                                    <div class="col s12 input-field">
                                                        <i class="material-icons prefix">lock_outline</i>
                                                        <input id="senha" name="senha" type="password" class="validate"
                                                               value="" data-error=".errorTxt1">
                                                        <label for="senha">Senha*</label>
                                                        <small class="errorTxt1"></small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col s12 m6">
                                                <div class="row">
                                                    <div class="col s12 input-field">
                                                        <i class="material-icons prefix">lock_outline</i>
                                                        <input id="senha_confirmacao" name="senha_confirmacao"
                                                               type="password" class="validate" value=""
                                                               data-error=".errorTxt1">
                                                        <label for="senha_confirmacao">Confirmação de Senha*</label>
                                                        <small class="errorTxt1"></small>
                                                    </div>
                                                </div>
                                            </div>

                                            <?php if ($_SESSION['adm_is_admin'] == 1) { ?>
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
                                                                            <input type="checkbox"/>
                                                                            <span>básico</span>
                                                                        </label>
                                                                    </p>
                                                                </div>
                                                                <div class="col s12 m3">
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox"/>
                                                                            <span>intermediário</span>
                                                                        </label>
                                                                    </p>
                                                                </div>
                                                                <div class="col s12 m3">
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox"/>
                                                                            <span>avançado</span>
                                                                        </label>
                                                                    </p>
                                                                </div>
                                                                <div class="col s12 m3">
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox"/>
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
                                                                            <input type="checkbox"/>
                                                                            <span>Atendimento</span>
                                                                        </label>
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox"/>
                                                                            <span>Atendimento</span>
                                                                        </label>
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox"/>
                                                                            <span>Atendimento</span>
                                                                        </label>
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox"/>
                                                                            <span>Atendimento</span>
                                                                        </label>
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox"/>
                                                                            <span>Atendimento</span>
                                                                        </label>
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox"/>
                                                                            <span>Atendimento</span>
                                                                        </label>
                                                                    </p>
                                                                </div>
                                                                <div class="col s12 m3">
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox"/>
                                                                            <span>Atendimento</span>
                                                                        </label>
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox"/>
                                                                            <span>Atendimento</span>
                                                                        </label>
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox"/>
                                                                            <span>Atendimento</span>
                                                                        </label>
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox"/>
                                                                            <span>Atendimento</span>
                                                                        </label>
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox"/>
                                                                            <span>Atendimento</span>
                                                                        </label>
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox"/>
                                                                            <span>Atendimento</span>
                                                                        </label>
                                                                    </p>
                                                                </div>
                                                                <div class="col s12 m3">
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox"/>
                                                                            <span>Atendimento</span>
                                                                        </label>
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox"/>
                                                                            <span>Atendimento</span>
                                                                        </label>
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox"/>
                                                                            <span>Atendimento</span>
                                                                        </label>
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox"/>
                                                                            <span>Atendimento</span>
                                                                        </label>
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox"/>
                                                                            <span>Atendimento</span>
                                                                        </label>
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox"/>
                                                                            <span>Atendimento</span>
                                                                        </label>
                                                                    </p>
                                                                </div>
                                                                <div class="col s12 m3">
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox"/>
                                                                            <span>Atendimento</span>
                                                                        </label>
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox"/>
                                                                            <span>Atendimento</span>
                                                                        </label>
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox"/>
                                                                            <span>Atendimento</span>
                                                                        </label>
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox"/>
                                                                            <span>Atendimento</span>
                                                                        </label>
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox"/>
                                                                            <span>Atendimento</span>
                                                                        </label>
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox"/>
                                                                            <span>Atendimento</span>
                                                                        </label>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php } ?>


                                            <div class="col s12 mt-3">
                                                <button type="submit" class="btn blue darken-4 float-right w100">
                                                    Salvar
                                                </button>
                                                <button type="submit" class="btn red darken-3 float-right w100 mt-md-1">
                                                    Cancelar
                                                </button>
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
<?php $Template->insert("backoffice/materialize-footer"); ?>
<!-- SCRIPTS -->
<?php $Template->insert("backoffice/materialize-scripts"); ?>
<!-- SCRIPTS DA PAGINA -->
<script src="../assets/vendors/select2/select2.full.min.js"></script>
<script src="../assets/vendors/jquery-validation/jquery.validate.min.js"></script>
<script src="../assets/js/scripts/page-users.js"></script>
</body>
</html>