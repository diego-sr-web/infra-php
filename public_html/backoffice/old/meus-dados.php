<?php
require_once __DIR__ . "/../autoloader.php";
require_once __DIR__ . "/_init.inc.php";

if (isset($_GET['acao'])) {
    switch ($_GET['acao']) {
        case 'edt':
            if ($_POST) {
                if ($_POST['u_senha'] && $_POST['u_senhanova'] && $_POST['u_senhanova2']) {
                    $valido = $usuario->checkUserPass($_SESSION['adm_email'], $_POST['u_senha']);

                    if ($valido) {
                        $userId = $_SESSION['adm_usuario'];
                        $retorno = $usuario->updateUserPassword($userId, $_POST['u_senha'], $_POST['u_senhanova']);

                        if ($retorno) {
                            $texto = emailResetSenhaBackoffice("Usuário", $_SESSION['adm_email'], $_POST['u_senhanova']);
                            $status = sendEmail("Nome", $_SESSION['adm_email'], "Alteracao de senha Nerdweb", $texto);

                            if ($status) {
                                $_SESSION["msg_sucesso"] = 'Senha alterada com sucesso. Sua nova senha foi enviada para o seu email.';
                            }
                            else {
                                $_SESSION["msg_sucesso"] = 'Senha alterada com sucesso, mas houve um erro ao enviá-la para seu email.';
                            }
                        }
                        else {
                            $_SESSION["msg_erro"] = 'Houve algum erro ao salvar os dados, tente novamente.';
                        }
                    }
                    else {
                        $_SESSION["msg_erro"] = 'A senha atual está incorreta.';
                    }
                }
                else {
                    $_SESSION["msg_erro"] = 'Preencha todos os campos obrigatórios.';
                }
            }
            break;

        default:
            break;
    }
} ?>
<!DOCTYPE html>
<html>
<head>
    <?php $Template->insert("backoffice/head", ["pageTitle" => "Meus Dados | Backoffice Nerdweb"]); ?>
</head>
<body class="skin-black fixed">
<?php $Template->insert("backoffice/header");?>
<div class="wrapper row-offcanvas row-offcanvas-left">
    <?php $Template->insert("backoffice/sidebar");?>
    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Meus Dados
                <small>Dados cadastrais</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="main.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                <li class="active">Meus dados</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <?php
                if (isset($_GET['acao'])) {
                    switch ($_GET['acao']) {
                        case 'edt':
                            $usuar = $usuario->getUserDataWithId($_SESSION['adm_usuario']);
                            if ($usuar) { ?>
                                <div class="col-md-6">
                                    <div class="box">
                                        <div class="box-header">
                                            <h3 class="box-title">Alterar Senha</h3>
                                        </div>
                                        <form action="" role="form" method="POST">
                                            <div class="box-body">
                                                <div class="form-group">
                                                    <label for="u_senha">Senha atual *</label>
                                                    <input name="u_senha" type="password" placeholder="" id="u_senha"
                                                           class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label for="u_senhanova">Nova senha *</label>
                                                    <input name="u_senhanova" type="password" placeholder=""
                                                           id="u_senhanova" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label for="u_senhanova2">Confirmação da nova senha *</label>
                                                    <input name="u_senhanova2" type="password" placeholder=""
                                                           id="u_senhanova2" class="form-control">
                                                </div>
                                                <?php require_once __DIR__ . "/pop/include/sucesso_error.php"; ?>
                                            </div>
                                            <div class="box-footer">
                                                <button class="btn btn-primary" type="submit">Salvar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <?php
                            }
                            break;

                        default:
                            break;
                    }
                }
                else {
                    $usuar = $usuario->getUserDataWithId($_SESSION['adm_usuario']);
                    if ($usuar) { ?>
                        <div class="col-md-6">
                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Meus Dados</h3>
                                </div>
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="u_nome">Nome</label>
                                        <input type="text" disabled="disabled" id="u_nome" class="form-control"
                                               value="<?php echo $usuar['nome']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="u_email">Email</label>
                                        <input type="text" disabled="disabled" id="u_email" class="form-control"
                                               value="<?php echo $usuar['email']; ?>">
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <a class="btn btn-primary btn-flat" href="?acao=edt">Alterar senha</a>
                                    <!--<a class="btn btn-primary btn-flat" href="?acao=edt">Editar</a>-->
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } ?>
            </div>
        </section><!-- /.content -->
    </aside><!-- /.right-side -->
</div><!-- ./wrapper -->

<?php $Template->insert("backoffice/footer");?>
<!-- SCRIPTS DA PAGINA -->
<script type="text/javascript">
    $(function () {
        $("#example1").dataTable({
            "bLengthChange": false,
            "bFilter": false,
        });
    });
</script>
</body>
</html>
