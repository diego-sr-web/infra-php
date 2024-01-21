<?php
require_once __DIR__ . "/../autoloader.php";
require_once __DIR__ . "/_init.inc.php";
if (isset($_POST['form'])) {
    switch ($_POST['form']) {
        case 'alterar-senha':
            if ($_POST['senha'] && $_POST['senha_confirmacao']) {
                if ($_POST['senha'] !== $_POST['senha_confirmacao']) {
                    $_SESSION["msg_erro"] = "Senha e confirmacao de senha devem ser iguais";
                }
                elseif (strlen($_POST['senha']) < 8) {
                    $_SESSION["msg_erro"] = "Senha muito curta, a senha deve conter pelo menos 8 caracteres";
                }
                else {
                    $senha = $_POST["senha"];
                    $retorno = $usuario->setPassword($dadosUsuario['email'], $senha);
                    if ($retorno) {
                        $_SESSION["msg_sucesso"] = 'Usuário atualizado com sucesso.';
                    }
                    else {
                        $_SESSION["msg_erro"] = 'Houve algum erro ao salvar o registro.';
                    }
                }
            }
            else {
                $_SESSION["msg_erro"] = 'Preencha todos os campos obrigatórios.';
            }
            break;
        case 'alterar-avatar':
            if ($_POST['image-data']) {
                $avatar = uploadImagemBase64($_POST['image-data'], Utils::stringToURL($_SESSION['adm_nome']), $_SERVER['DOCUMENT_ROOT'] . '/backoffice/uploads/usuarios/');
                $retorno = $usuario->setAvatar($_SESSION['adm_usuario'], $avatar['arquivo']);

                if ($retorno) {
                    $_SESSION["msg_sucesso"] = 'Avatar atualizado com sucesso.';
                }
                else {
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
    <?php $Template->insert("backoffice/materialize-head", ["pageTitle" => "Usuários | Backoffice Nerdweb"]); ?>
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
                        <h5 class="breadcrumbs-title mt-0 mb-0">
                            <span>Dados do usuario</span>
                        </h5>
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
                                            <img src="../../backoffice/uploads/usuarios/jean-romano.jpg" alt="users avatar" class="z-depth-4 circle" height="64" width="64">
                                        </a>
                                        <div class="media-body">
                                            <h5 class="media-heading mt-0">Avatar</h5>
                                            <div class="user-edit-btns display-flex file-field input-field">
                                                <div class="btn blue darken-4 file-custom">
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
                                                        <input id="name" name="name" type="text" class="validate" value="" data-error=".errorTxt2">
                                                        <label for="name">Nome</label>
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
                                                                <div class="col s3">
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox" />
                                                                            <span>básico</span>
                                                                        </label>
                                                                    </p>
                                                                </div>
                                                                <div class="col s3">
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox" />
                                                                            <span>intermediário</span>
                                                                        </label>
                                                                    </p>
                                                                </div>
                                                                <div class="col s3">
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox" />
                                                                            <span>avançado</span>
                                                                        </label>
                                                                    </p>
                                                                </div>
                                                                <div class="col s3">
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
                                                                <div class="col s3">
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox" />
                                                                            <span>Atendimento</span>
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
                                                                            <span>Atendimento</span>
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
                                                                            <span>Atendimento</span>
                                                                        </label>
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox" />
                                                                            <span>Atendimento</span>
                                                                        </label>
                                                                    </p>
                                                                </div>
                                                                <div class="col s3">
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox" />
                                                                            <span>Atendimento</span>
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
                                                                            <span>Atendimento</span>
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
                                                                            <span>Atendimento</span>
                                                                        </label>
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox" />
                                                                            <span>Atendimento</span>
                                                                        </label>
                                                                    </p>
                                                                </div>
                                                                <div class="col s3">
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox" />
                                                                            <span>Atendimento</span>
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
                                                                            <span>Atendimento</span>
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
                                                                            <span>Atendimento</span>
                                                                        </label>
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox" />
                                                                            <span>Atendimento</span>
                                                                        </label>
                                                                    </p>
                                                                </div>
                                                                <div class="col s3">
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox" />
                                                                            <span>Atendimento</span>
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
                                                                            <span>Atendimento</span>
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
                                                                            <span>Atendimento</span>
                                                                        </label>
                                                                    </p>
                                                                    <p class="mb-1">
                                                                        <label>
                                                                            <input type="checkbox" />
                                                                            <span>Atendimento</span>
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
<?php $Template->insert("backoffice/materialize-scripts");?>
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
