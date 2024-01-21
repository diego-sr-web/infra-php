<?php
require_once __DIR__ . "/../autoloader.php";

$database = new Database();
$usuario = new AdmUsuario($database);
$grupo = new AdmGrupo($database);
$permissao = new AdmPermissao($database);

require_once __DIR__ . '/includes/is_logged.php';
// Recupera o nome do arquivo atual, verifica se ele está dentro das permissões do usuário e,
// se não estiver, redireciona para a página inicial com uma mensagem de erro
$arquivo = explode('?', $_SERVER['REQUEST_URI']);
$arquivo = $arquivo[0];
$arquivo = explode('/', $arquivo);
$arquivo = $arquivo[count($arquivo) - 1];

$perm_files = $usuario->getUserFilePermissions($_SESSION['adm_usuario']);

if ($perm_files && !in_array($arquivo, $perm_files)) {
    //header("location: ".BACKOFFICE_URL."/main.php?perm=1");
}

$grupos = $grupo->listAll();
$permissoes = $permissao->listAll();

if (isset($_GET['acao'])) {
    switch ($_GET['acao']) {
        case 'add':
            if ($_POST) {
                if ($_POST['g_nome']) {
                    $dados = [$_POST['g_nome'], $_POST['g_desc']];
                    $retorno = $grupo->insertIntoTheDatabase($dados);
                    if ($retorno) {
                        $msg_sucesso = 'Grupo cadastrado com sucesso.';

                        $grupoId = $database->returnLastInsertedId();

                        if (isset($_POST['permissao'])) {
                            foreach ($_POST['permissao'] as $perm) {
                                $dados_permissao = [$perm, $grupoId];
                                $retorno = $grupo->insertPermissao($dados_permissao);
                            }
                            if ($retorno) {
                                $_SESSION["msg_sucesso"] = 'Grupo cadastrado com sucesso.';
                            }
                            else {
                                $msg_erro = 'Houve algum erro ao salvar os dados, tente novamente.';
                            }
                        }
                    }
                    else {
                        $msg_erro = 'Houve algum erro ao salvar os dados, tente novamente.';
                    }
                }
                else {
                    $msg_erro = 'Preencha todos os campos obrigatórios.';
                }
            }
            break;

        case 'edt':
            if (isset($_GET['gid'])) {
                if ($_POST['g_nome']) {
                    $grupoId = $_GET['gid'];
                    $dados = [$grupoId, $_POST['g_nome'], $_POST['g_desc']];
                    $retorno = $grupo->updateElementOnTheDatabase($dados);
                    if ($retorno) {
                        $_SESSION["msg_sucesso"] = 'Grupo atualizado com sucesso.';

                        $removido = $grupo->removePermissoesGrupo($grupoId);

                        if ($removido) {
                            if (isset($_POST['permissao'])) {
                                foreach ($_POST['permissao'] as $perm) {
                                    $dados_permissao = [$perm, $grupoId];
                                    $retorno = $grupo->insertPermissao($dados_permissao);
                                }
                                if ($retorno) {
                                    $_SESSION["msg_sucesso"] = 'Grupo atualizado com sucesso.';
                                }
                                else {
                                    $msg_erro = 'Houve algum erro ao salvar os dados, tente novamente.';
                                }
                            }
                        }
                    }
                    else {
                        $msg_erro = 'Houve algum erro ao salvar os dados, tente novamente.';
                    }
                }
                else {
                    $msg_erro = 'Preencha todos os campos obrigatórios.';
                }
            }
            break;

        case 'del':
            if (isset($_GET['gid'])) {
                $grupoId = $_GET['gid'];
                $removido = $grupo->removePermissoesGrupo($grupoId);

                if ($removido) {
                    $retorno = $grupo->removeElementFromTheDatabase($_GET['gid']);

                    if ($retorno) {
                        $_SESSION["msg_sucesso"] = 'Grupo apagado com sucesso.';
                    }
                    else {
                        $msg_erro = 'Houve algum erro ao apagar os dados, tente novamente.';
                    }
                }
                else {
                    $msg_erro = 'Houve algum erro ao apagar os dados, tente novamente.';
                }
            }
            break;

        default:
            break;
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <?php
    $pageTitle = $data["pageTitle"] ?? "PAGE TITLE UNDEFINED";
    ?>
    <meta charset="UTF-8">
    <title><?= $pageTitle ?></title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet"
          type="text/css"/>
    <!-- <link href="//use.fontawesome.com/releases/v5.7.2/css/all.css" rel="stylesheet" type="text/css"/> -->
    <link href="//code.ionicframework.com/ionicons/1.5.2/css/ionicons.min.css" rel="stylesheet" type="text/css"/>
    <link href="/backoffice/css/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css"/>
    <link href="/backoffice/css/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>
    <link href="/backoffice/css/cropit/cropit.css" rel="stylesheet" type="text/css"/>
    <link href="/backoffice/css/AdminLTE.css" rel="stylesheet" type="text/css"/>
    <link href="/backoffice/css/backoffice.css" rel="stylesheet" type="text/css"/>
</head>
<body class="skin-black fixed">
<?php
$nome = $data["nome"] ?? $_SESSION["adm_nome"] ?? "NAO_DEFINIDO";
$imagem = $data["imagem"] ?? $_SESSION["adm_imagem"] ?? "/backoffice/uploads/usuarios/default.png";
?>
<header class="header">
    <a href="main.php" class="logo">
        <!-- Add the class icon to your logo image or logo icon to add the margining -->
        <img src="/backoffice/img/logo-header.png" style="width: 90%;"/>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>
        <div class="navbar-right">
            <ul class="nav navbar-nav">
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="glyphicon glyphicon-user"></i>
                        <span><?= $nome ?><i class="caret"></i></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header bg-light-blue">
                            <img src="<?= $imagem ?>" class="img-circle" alt="User Image"/>
                            <p>
                                <?= $nome ?>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="/backoffice/alterar-dados.php" class="btn btn-default btn-flat">Meus Dados</a>
                            </div>
                            <div class="pull-right">
                                <a href="/backoffice/logout.php" class="btn btn-default btn-flat">Sair</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>

<div class="wrapper row-offcanvas row-offcanvas-left">
    <!-- Left side column. contains the logo and sidebar -->
    <?php
    $nome = $data["nome"] ?? $_SESSION["adm_nome"] ?? "NAO_DEFINIDO";
    $imagem = $data["imagem"] ?? $_SESSION["adm_imagem"] ?? "/backoffice/uploads/usuarios/default.png";
    ?>
    <aside class="left-side sidebar-offcanvas">
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel">
                <div class="image txt-center">
                    <img src="<?= $imagem ?>" class="img-circle" alt="User Image"/>
                </div>
                <div class="info txt-center" style="padding: 15px 0 0 0;">
                    <p>Olá, <?= $nome ?></p>
                </div>
            </div>
            <ul class="sidebar-menu">
                <li><a href="/backoffice/main.php"><i class="fa fa-dashboard"></i><span>Dashboard</span></a></li>
                <li><a href="/backoffice/pop/"><i class="fa fa-file-text-o"></i> <span>POP</span></a></li>
                <li><a href="/backoffice/clientes.php"><i class="fa fa-users"></i>Clientes</a></li>
                <li><a href="/backoffice/usuarios.php"><i class="fa fa-lock"></i> Usuários</a></li>
                <li><a href="/backoffice/areas.php"><i class="fa fa-lock"></i> Áreas</a></li>
                <li><a href="/backoffice/alterar-dados.php"><i class="fa fa-user"></i>Alterar Meus Dados</a></li>
            </ul>
        </section>
    </aside>

    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Grupos
                <small>Grupos de usuários do sistema</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="main.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                <li>Controle de Acesso</li>
                <li class="active">Grupos</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <?php require_once __DIR__ . "/pop/include/sucesso_error.php"; ?>

            <div class="row">
                <?php
                if (isset($_GET['acao'])) {
                    switch ($_GET['acao']) {
                        case 'add': ?>
                            <div class="col-md-6">
                                <div class="box">
                                    <div class="box-header">
                                        <h3 class="box-title">Cadastro de Grupo</h3>
                                    </div>
                                    <form action="" role="form" method="POST">
                                        <div class="box-body">
                                            <div class="form-group">
                                                <label for="g_nome">Nome *</label>
                                                <input name="g_nome" type="text" placeholder="" id="g_nome"
                                                       class="form-control"
                                                       value="<?php echo $_POST['g_nome'] ?? ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>Descrição</label>
                                                <textarea name="g_desc" class="form-control"
                                                          rows="3"><?php echo $_POST['g_desc'] ?? ''; ?></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Permissões do grupo</label>
                                                <?php
                                                foreach ($permissoes as $perm) { ?>
                                                    <div class="checkbox">
                                                        <label>
                                                            <div class="icheckbox_minimal" style="position: relative;"
                                                                 aria-checked="false" aria-disabled="false">
                                                                <input name="permissao[]" type="checkbox"
                                                                       class="inp-custom-check"
                                                                       value="<?php echo $perm['permissao']; ?>" <?php echo (isset($_POST['permissao']) && in_array($perm['permissao'], $_POST['permissao'])) ? 'checked' : ''; ?>>
                                                                <ins class="iCheck-helper ins-custom-check"></ins>
                                                            </div>
                                                            <?php echo $perm['nome']; ?>
                                                        </label>
                                                    </div>
                                                    <?php
                                                } ?>
                                            </div>
                                            <?php
                                            if ($msg_erro) { ?>
                                                <div class="alert alert-warning alert-dismissable">
                                                    <i class="fa fa-warning"></i>
                                                    <button aria-hidden="true" data-dismiss="alert" class="close"
                                                            type="button">×
                                                    </button>
                                                    <?php echo $msg_erro; ?>
                                                </div>
                                                <?php
                                            }
                                            if ($msg_sucesso) { ?>
                                                <div class="alert alert-success alert-dismissable">
                                                    <i class="fa fa-check"></i>
                                                    <button aria-hidden="true" data-dismiss="alert" class="close"
                                                            type="button">×
                                                    </button>
                                                    <?php echo $msg_sucesso; ?>
                                                </div>
                                                <?php
                                            } ?>
                                        </div>
                                        <div class="box-footer">
                                            <button class="btn btn-primary" type="submit">Salvar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <?php
                            break;

                        case 'edt':
                            $grup = $grupo->getDataWithId($_GET['gid']);
                            if ($grup) {
                                $permissoes_grupo = $grupo->getPermissoesGrupo($grup['grupo']);
                                $perm_grupo = [];
                                foreach ($permissoes_grupo as $aux) {
                                    $perm_grupo[] = (int)$aux['permissao'];
                                }
                                ?>
                                <div class="col-md-6">
                                    <div class="box">
                                        <div class="box-header">
                                            <h3 class="box-title">Edição de Grupo</h3>
                                        </div>
                                        <form action="" role="form" method="POST">
                                            <div class="box-body">
                                                <div class="form-group">
                                                    <label for="g_nome">Nome *</label>
                                                    <input name="g_nome" type="text" placeholder=""
                                                           value="<?php echo $grup['nome'] ?>" id="g_nome"
                                                           class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Descrição</label>
                                                    <textarea name="g_desc" class="form-control"
                                                              rows="3"><?php echo $grup['descricao'] ?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Permissões do grupo</label>
                                                    <?php
                                                    foreach ($permissoes as $perm) {
                                                        $checked = '';
                                                        if (in_array($perm['permissao'], $perm_grupo)) {
                                                            $checked = 'checked';
                                                        }
                                                        ?>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" name="permissao[]"
                                                                       value="<?php echo $perm['permissao']; ?>" <?php echo $checked; ?>/>
                                                                <?php echo $perm['nome']; ?>
                                                            </label>
                                                        </div>

                                                        <?php
                                                    } ?>
                                                </div>
                                                <?php
                                                if ($msg_erro) { ?>
                                                    <div class="alert alert-warning alert-dismissable">
                                                        <i class="fa fa-warning"></i>
                                                        <button aria-hidden="true" data-dismiss="alert" class="close"
                                                                type="button">×
                                                        </button>
                                                        <?php echo $msg_erro; ?>
                                                    </div>
                                                    <?php
                                                }
                                                if ($msg_sucesso) { ?>
                                                    <div class="alert alert-success alert-dismissable">
                                                        <i class="fa fa-check"></i>
                                                        <button aria-hidden="true" data-dismiss="alert" class="close"
                                                                type="button">×
                                                        </button>
                                                        <?php echo $msg_sucesso; ?>
                                                    </div>
                                                    <?php
                                                } ?>
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

                        case 'del': ?>
                            <div class="col-md-6">
                                <div class="box">
                                    <div class="box-header">
                                        <h3 class="box-title">Exclusão de Grupo</h3>
                                    </div>
                                    <div class="box-body">
                                        <?php
                                        if ($msg_erro) { ?>
                                            <div class="alert alert-warning alert-dismissable">
                                                <i class="fa fa-warning"></i>
                                                <button aria-hidden="true" data-dismiss="alert" class="close"
                                                        type="button">×
                                                </button>
                                                <?php echo $msg_erro; ?>
                                            </div>
                                            <?php
                                        }
                                        if ($msg_sucesso) { ?>
                                            <div class="alert alert-success alert-dismissable">
                                                <i class="fa fa-check"></i>
                                                <button aria-hidden="true" data-dismiss="alert" class="close"
                                                        type="button">×
                                                </button>
                                                <?php echo $msg_sucesso; ?>
                                            </div>
                                            <?php
                                        } ?>
                                    </div>
                                    <div class="box-footer">
                                        <a class="btn btn-primary btn-flat" href="grupos.php">Voltar</a>
                                        <!--<button class="btn btn-primary btn-flat" type="submit">Salvar</button>-->
                                    </div>
                                </div>
                            </div>
                            <?php
                            break;


                        case 'view':
                            $grup = $grupo->getDataWithId($_GET['gid']);
                            if ($grup) {
                                $permissoes_grupo = $grupo->getPermissoesGrupo($grup['grupo']);
                                $perm_grupo = [];
                                foreach ($permissoes_grupo as $aux) {
                                    $perm_grupo[] = (int)$aux['permissao'];
                                }
                                ?>
                                <div class="col-md-6">
                                    <div class="box">
                                        <div class="box-header">
                                            <h3 class="box-title">Visualização de Grupo</h3>
                                        </div>
                                        <form action="" role="form" method="POST">
                                            <div class="box-body">
                                                <div class="form-group">
                                                    <label for="g_nome">Nome</label>
                                                    <input name="g_nome" type="text" disabled="disabled" placeholder=""
                                                           value="<?php echo $grup['nome'] ?>" id="g_nome"
                                                           class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Descrição</label>
                                                    <textarea name="g_desc" disabled="disabled" class="form-control"
                                                              rows="3"><?php echo $grup['descricao'] ?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Permissões do grupo</label>
                                                    <?php
                                                    foreach ($permissoes as $perm) {
                                                        $checked = '';
                                                        if (in_array($perm['permissao'], $perm_grupo)) {
                                                            $checked = 'checked';
                                                        }
                                                        ?>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" disabled="disabled"
                                                                       name="permissao[]"
                                                                       value="<?php echo $perm['permissao']; ?>" <?php echo $checked; ?>/>
                                                                <?php echo $perm['nome']; ?>
                                                            </label>
                                                        </div>
                                                        <?php
                                                    } ?>
                                                </div>
                                            </div>
                                            <div class="box-footer">
                                                <a class="btn btn-primary btn-flat" href="grupos.php">Voltar</a>
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
                else { ?>
                    <div class="col-md-12">
                        <div class="box box-solid">
                            <div class="box-header">
                                <h3 class="box-title">Lista de grupos</h3>
                                <a class="btn btn-primary btn-flat btn-addnew" href="?acao=add">Adicionar novo grupo</a>
                            </div><!-- /.box-header -->
                            <div class="box-body table-responsive">
                                <table id="example1" class="table table-hover table-striped">
                                    <thead>
                                    <tr>
                                        <th width="40">ID</th>
                                        <th>Nome</th>
                                        <th width="150">Ações</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($grupos as $registro) { ?>
                                        <tr>
                                            <td><?php echo $registro['grupo']; ?></td>
                                            <td><?php echo $registro['nome']; ?></td>
                                            <td>
                                                <ul class="acoes_lista">
                                                    <li><a href="?acao=view&gid=<?php echo $registro['grupo']; ?>"
                                                           data-toggle="tooltip" data-original-title="Visualizar"><i
                                                                    class="fa fa-eye"></i></a></li>
                                                    <li><a href="?acao=edt&gid=<?php echo $registro['grupo']; ?>"
                                                           data-toggle="tooltip" data-original-title="Editar"><i
                                                                    class="fa fa-edit"></i></a></li>
                                                    <li><a href="?acao=del&gid=<?php echo $registro['grupo']; ?>"
                                                           onclick="return confirm('Deseja apagar este registro?');"
                                                           data-toggle="tooltip"
                                                           data-original-title="Apagar"><i class="fa fa-times"></i></a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nome</th>
                                        <th>Ações</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div><!-- /.box-body -->
                        </div><!-- /.box -->
                    </div>
                    <?php
                } ?>
            </div>
        </section><!-- /.content -->
    </aside><!-- /.right-side -->
</div><!-- ./wrapper -->

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js" type="text/javascript"></script>
<script src="js/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="js/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
<script src="js/AdminLTE/app.js" type="text/javascript"></script>

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
