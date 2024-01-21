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
if (isset($_POST['email'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $usuario = new AdmUsuario($database);
        $userData = $usuario->getUserDataWithEmail($email);
        if (isset($userData['nome'])) {
            $novaSenha = Utils::generatePassword(15);
            $usuario->setPassword($email, $novaSenha);
            $htmlEmail = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html><head>';
            $htmlEmail .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></head><body>';
            $htmlEmail .= 'Prezado ' . $userData['nome'] . '<br><br>';
            $htmlEmail .= 'Conforme sua solicitação, sua senha para acesso ao sistema de back-office da Nerdweb foi resetada. Seus dados de login são as seguintes:<br><br>';
            $htmlEmail .= 'Usuário: ' . $userData['email'] . '<br>';
            $htmlEmail .= 'Senha: ' . $novaSenha . '<br><br>';
            $htmlEmail .= 'Atenciosamente,<br>';
            $htmlEmail .= 'NerdWeb<br><a href="https://www.nerdweb.com.br">https://www.nerdweb.com.br</a><br>';
            $htmlEmail .= '</body>';
            $htmlEmail .= '</html>';
            $htmlEmail = Utils::htmlSpecialCodes($htmlEmail);
            $enviado = Utils::enviaEmail('Nerdweb', $email, 'Recuperacao de senha Backoffice Nerdweb', $htmlEmail, $userData['nome'], $userData['email']);
            if ($enviado) {
                $_SESSION["msg_sucesso"] = 'Sua nova senha foi enviada para seu email.';
            }
            else {
                $_SESSION["msg_erro"] = 'Houve um problema no envio da sua nova senha por email, contate um administrador.';
            }
        }
        else {
            $_SESSION["msg_erro"] = 'Email não cadastrado no Backoffice.';
        }
    }
    else {
        $_SESSION["msg_erro"] = 'Digite um email válido.';
    }
}
else {
    $_SESSION["msg_erro"] = 'Digite um email válido.';
}
?>
<!DOCTYPE html>
<html class="loading" lang="pt-br" data-textdirection="ltr">
<head>
    <?php $Template->insert("backoffice/materialize-head-2", ["pageTitle" => "Recuperar Senha | Backoffice Nerdweb"]); ?>
    <link rel="stylesheet" type="text/css" href="../assets/css/pages/forgot.css"">
</head>
<body class="vertical-layout page-header-light vertical-menu-collapsible vertical-gradient-menu preload-transitions 1-column bg-login" data-open="click" data-menu="vertical-gradient-menu" data-col="1-column">
    <div class="row">
        <div class="col s12">
            <div class="container">
                <div id="forgot-password" class="row">
                    <div class="col s12 m6 l4 z-depth-4 offset-m4 card-panel border-radius-6 forgot-card">
                        <form class="login-form" method="post" action="/backoffice/recuperar-senha.php">
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
                            <div class="row">
                                <div class="input-field col s12">
                                    <i class="material-icons prefix pt-2">person_outline</i>
                                    <input id="email" type="email" name="email" class="form-control" value="<?php echo $_POST['email'] ?? ''; ?>"/>
                                    <label for="email" class="center-align">Seu Email</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s12">
                                    <?php
                                    if ($_SESSION["msg_erro"]) { ?>
                                        <div class="card-alert card orange lighten-5">
                                            <div class="card-content orange-text">
                                                <p><?php echo $_SESSION["msg_erro"]; unset($_SESSION["msg_erro"]);?></p>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    if ($_SESSION["msg_sucesso"]) { ?>
                                        <div class="card-alert card green lighten-5">
                                            <div class="card-content green-text">
                                                <p><?php echo $_SESSION["msg_sucesso"]; ?></p>
                                            </div>
                                            <button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row">
                                <?php
                                if ($_SESSION["msg_sucesso"]) {
                                    unset($_SESSION["msg_sucesso"])
                                    ?>
                                    <a href="index.php" class="btn btn-block bg-olive">Clique aqui para entrar</a>
                                    <?php
                                } else {
                                ?>
                                <div class="input-field col s12">
                                    <button type="submit" class="btn waves-effect waves-light border-round blue darken-1 btn-block col s12">Enviar</button>
                                </div>
                                    <?php
                                }
                                ?>
                            </div>
                            <div class="footer">
                                <div class="row">
                                    <div class="input-field col s12">
                                        <p class="margin center-align pb-2"><a href="index.php">Voltar para Login</a></p>
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
    <!-- BEGIN: Footer-->
    <!-- SCRIPTS -->
    <?php $Template->insert("backoffice/materialize-scripts"); ?>
</body>
</html>
