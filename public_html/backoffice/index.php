<?php
require_once __DIR__ . "/../autoloader.php";
$database = new Database();
$usuario = new AdmUsuario($database);
$Template = new Template();
if ($usuario->checkSession() === TRUE) {
    $redirectURL = "main.php";
    if (isset($_SESSION["redirect_url"])) {
        $redirectURL = $_SESSION["redirect_url"];
        unset($_SESSION["redirect_url"]);
    }
    echo '<script type="text/javascript">window.location="' . $redirectURL . '";</script>';
    exit;
}
$msg = NULL;
if ($_POST) {
    $user = $password = '';
    if (isset($_POST['email'])) {
        $user = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    }
    if (isset($_POST['senha'])) {
        $password = $_POST['senha'];
    }
    if ($user != '' && $password != '') {
        $database = new Database();
        $usuario = new AdmUsuario($database);
        $valid = $usuario->login($user, $password);
        if ($valid) {
            $redirectURL = "main.php";
            if (isset($_SESSION["redirect_url"])) {
                $redirectURL = $_SESSION["redirect_url"];
                unset($_SESSION["redirect_url"]);
            }
            echo '<script type="text/javascript">window.location="' . $redirectURL . '";</script>';
            exit;
        }
        else {
            $msg = 'Os campos email ou senha estão incorretos.';
        }
    }
    else {
        $msg = 'Preencha todos os campos.';
    }
}
?>
<!DOCTYPE html>
<html class="loading" lang="pt-br" data-textdirection="ltr">
<head>
    <?php $Template->insert("backoffice/materialize-head-2", ["pageTitle" => "Entrar | Backoffice Nerdweb"]); ?>
    <link rel="stylesheet" type="text/css" href="../assets/css/pages/login.css">
</head>
<body class="vertical-layout page-header-light vertical-menu-collapsible vertical-gradient-menu preload-transitions 1-column bg-login" data-open="click" data-menu="vertical-gradient-menu" data-col="1-column">
<div class="row">
    <div class="col s12">
        <div class="container">
            <div id="login-page" class="row">
                <div class="col s12 m6 l4 z-depth-4 card-panel border-radius-6 login-card">
                    <form class="login-form" action="" method="post">
                        <div class="row">
                            <div class="input-field col s12">
                                <div class="header blue darken-1">
                                    <div class="logo-wrapper logo-wrapper-login">
                                        <img class="hide-on-med-and-down " src="/backoffice/img/logo-header.png" alt=""/>
                                        <img class="show-on-medium-and-down hide-on-med-and-up" src="/backoffice/img/logo-header.png" alt="materialize logo"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row margin">
                            <div class="input-field col s12">
                                <i class="material-icons prefix pt-2">person_outline</i>
                                <input id="username" type="text" name="email" class="form-control" value="<?php echo $_POST['email'] ?? ''; ?>"/>
                                <label for="username" class="center-align">Seu email</label>
                            </div>
                        </div>
                        <div class="row margin">
                            <div class="input-field col s12">
                                <i class="material-icons prefix pt-2">lock_outline</i>
                                <input id="password" type="password" name="senha" class="form-control"/>
                                <label for="password">Sua senha</label>
                            </div>
                        </div>
                        <?php if ($msg) { ?>
                            <div class="callout callout-warning">
                                <p><?php echo $msg; ?></p>
                            </div>
                        <?php } ?>
                        <div class="footer">
                            <div class="row">
                                <div class="input-field col s12">
                                    <button type="submit" class="btn waves-effect waves-light border-round blue darken-1 btn-block col s12">Entrar</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s12">
                                    <p class="margin center-align pb-2"><a href="recuperar-senha.php">Esqueci minha senha</a></p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="content-overlay"></div>
    </div>
</div>
<!-- BEGIN VENDOR JS-->
<script src="../assets/js/vendors.min.js"></script>
<script src="../assets/js/plugins.js"></script>
<script src="../assets/js/search.js"></script>
<script src="../assets/js/custom/custom-script.js"></script>
</body>
</html>
