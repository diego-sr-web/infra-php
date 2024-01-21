<?php
require_once __DIR__ . "/../../autoloader.php";
$database = new Database();
$usuario = new AdmUsuario($database);
$POPProjeto = new Projeto($database);
$POPElemento = new Elemento($database);
$BNCliente = new BNCliente($database);

require_once __DIR__ . '/../includes/is_logged.php';
require_once __DIR__ . '/include/le-notificacoes.php';

$listaAreas = $usuario->listArea();

if (isset($_POST['form'])) {
    $dtAtual = date('Y-m-d H:i:s');

    switch ($_POST['form']) {
        case 'editar-tipoProjeto':
            if ($_POST['nome']) {
                $idProjetoTipo = $_POST['projetoTipo'];
                $identificador = 'POP_PROJETO_' . strtoupper(Utils::stringToURL($_POST['nome']));
                $_POST['identifier'] = $identificador;

                unset($_POST['form'], $_POST['projetoTipo']);

                foreach ($_POST as $key => $value) {
                    $campos[] = $key;
                    $valores[] = $value;
                }

                $retorno = $POPProjeto->updateProjectType($idProjetoTipo, $campos, $valores);

                if ($retorno) {
                    $msg_sucesso = 'Tipo de projeto atualizado com sucesso.';
                }
                else {
                    $msg_erro = 'Houve um erro ao atualizar o tipo de projeto.';
                }

            }
            else {
                $msg_erro = 'Houve um erro ao atualizar o tipo de projeto.';
            }
            break;

        default:
            break;
    }

    if (isset($msg_sucesso)) {
        echo '<script language="javascript"> window.location.href="pop-fluxo.php?tipoProjeto=' . $_GET['tipoProjeto'] . '&msg=1&txt=' . $msg_sucesso . '</script>';
        exit;
    }
    elseif (isset($msg_erro)) {
        echo '<script language="javascript"> window.location.href="pop-fluxo.php?tipoProjeto=' . $_GET['tipoProjeto'] . '&msg=2&txt=' . $msg_erro . '</script>';
        exit;
    }

}

if (!isset($_GET['tipoProjeto'])) {
    echo '<script language="javascript"> window.location.href="pop-fluxos.php' . '</script>';
    exit;
}

$infoTipoProjeto = $POPProjeto->getProjectTypeById($_GET['tipoProjeto']);
$primeirasEtapas = $POPProjeto->getProjectPrimeiros($infoTipoProjeto['projetoTipo']);

$passoMontagem = 1;
$fluxoSessaoJson = $POPProjeto->restoreSession($_SESSION['adm_usuario'], $infoTipoProjeto['projetoTipo']);
$fluxoSessao = json_decode($fluxoSessaoJson, TRUE);

$etapasSessao = $primeirasSessao = $relacoesSessao = $dadosEtapasSessao = FALSE;

if ($fluxoSessao) {
    $passoMontagem = $fluxoSessao['passo'];
    $etapasSessao = $fluxoSessao['etapas'] ?? FALSE;
    $primeirasSessao = $fluxoSessao['primeiras'] ?? FALSE;
    $relacoesSessao = $fluxoSessao['relacoes'] ?? FALSE;
    $dadosEtapasSessao = $fluxoSessao['dadosEtapas'] ?? FALSE;
}

if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 1) {
        $msg_sucesso = $_GET['txt'];
    }
    elseif ($_GET['msg'] == 2) {
        $msg_erro = $_GET['txt'];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $titleNotif; ?>Fluxo de <?php echo $infoTipoProjeto['nome']; ?> | POP Nerdweb</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="../css/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css"/>
    <link href="../css/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>
    <link href="../css/colorpicker/bootstrap-colorpicker.min.css" rel="stylesheet" type="text/css"/>
    <link href="../css/ionicons.css" rel="stylesheet" type="text/css"/>
    <link href="../css/AdminLTE.css" rel="stylesheet" type="text/css"/>
    <link href="../css/pop.css" rel="stylesheet" type="text/css"/>
</head>
<body class="skin-black fixed">
<?php require_once __DIR__ . '/include/head.php'; ?>

<div id="top-of-page" class="wrapper row-offcanvas row-offcanvas-left">
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="left-side sidebar-offcanvas">
        <?php require_once __DIR__ . '/include/sidebar.php'; ?>
    </aside>

    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side">
        <section class="content-header">
            <h1>Fluxo de <?php echo $infoTipoProjeto['nome']; ?></h1>

            <ol class="breadcrumb">
                <li><a href="pop-fluxos.php"><i class="fa fa-code-fork"></i> Construção de Fluxos</a></li>
                <li class="active">Fluxo de <?php echo $infoTipoProjeto['nome']; ?></li>
            </ol>
        </section>

        <section class="content">
            <?php if (isset($msg_sucesso)) { ?>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="callout callout-success alert-dismissable" style="text-align: center;">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            <h4>Sucesso</h4>
                            <p><?php echo $msg_sucesso; ?></p>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php if (isset($msg_erro)) { ?>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="callout callout-danger alert-dismissable" style="text-align: center;">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            <h4>Alerta</h4>
                            <p><?php echo $msg_erro; ?></p>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-solid">
                        <div class="box-header">
                            <i class="fa fa-cubes"></i>
                            <h3 class="box-title">Dados do Tipo de Projeto</h3>
                            <a class="btn btn-primary btn-flat btn-addnew border-0" href="#" data-target="#"
                               data-toggle="modal" data-backdrop="static">
                                <i class="fa fa-archive pull-left font-16"></i> Arquivar Tipo de Projeto
                            </a>
                            <button class="btn btn-primary btn-flat btn-addnew border-0 js-getTipoProjeto"
                                    data-toggle="modal" data-backdrop="static" data-target="#modal-tipoProjeto"
                                    data-acao="editar-tipoProjeto"
                                    data-tipoProjeto="<?php echo $infoTipoProjeto['projetoTipo']; ?>">
                                <i class="fa fa-pencil pull-left"></i> Editar Tipo de Projeto
                            </button>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-5">
                                    <dl class="dl-horizontal">
                                        <dt>Descrição</dt>
                                        <dd><?php echo $infoTipoProjeto['descricao']; ?></dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /row dados e botões -->

            <div class="row">
                <div class="col-md-12">
                    <?php if ($primeirasEtapas) { ?>
                        <div class="box box-solid">
                            <div class="box-header">
                                <i class="fa fa-code-fork"></i>
                                <h3 class="box-title">Fluxo do Tipo de Projeto</h3>
                            </div>
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php foreach ($primeirasEtapas as $primEtapa) {
                                            $info = $POPElemento->getElementTypeById($primEtapa['elementoTipo']);
                                            var_dump($info);

                                            $prox = $POPProjeto->getProximaEtapaByProjectTypeId($info['elementoTipo'], $infoTipoProjeto['projetoTipo']);
                                            var_dump($prox);
                                            $temProxima = TRUE;

                                        } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="box box-solid">
                            <div class="box-header">
                                <i class="fa fa-code-fork"></i>
                                <h3 class="box-title">Montagem do Fluxo</h3>
                            </div>
                            <div class="box-body passo-fluxo">
                                <div class="row">
                                    <div class="col-md-12">
                                        <ul class="barra-progresso">
                                            <li class="passo <?php if ($passoMontagem == 1) {
                                                echo 'ativo';
                                            } ?>" data-passo="1" data-form=".adicionar-etapas"><span>1</span> Criar
                                                Etapas
                                            </li>
                                            <li class="passo <?php if ($passoMontagem == 2) {
                                                echo 'ativo';
                                            } ?>" data-passo="2" data-form=".identificar-etapas"><span>2</span>
                                                Identificar Etapas Iniciais
                                            </li>
                                            <li class="passo <?php if ($passoMontagem == 3) {
                                                echo 'ativo';
                                            } ?>" data-passo="3" data-form=".relacionar-etapas"><span>3</span>
                                                Relacionar Etapas
                                            </li>
                                            <li class="passo <?php if ($passoMontagem == 4) {
                                                echo 'ativo';
                                            } ?>" data-passo="4" data-form=".dados-etapas"><span>4</span> Adicionar
                                                Dados Etapas
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="formulario-content">
                                            <form class="form-etapa adicionar-etapas" method="post"
                                                  action="" <?php if ($passoMontagem != 1) {
                                                echo 'style="display:none"';
                                            } ?>>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <h4>Criação de Etapas</h4>
                                                        <p>Cadastre aqui as etapas do fluxo e insira os campos que a
                                                            etapa irá ter.</p>
                                                        <hr/>
                                                        <div class="box-etapas">
                                                            <?php if ($etapasSessao) { //se tiver algo na sessão, remonta o form
                                                                foreach ($etapasSessao as $aux) {
                                                                    ?>
                                                                    <div class="form-group box-etapa">
                                                                        <div class="js-remove-etapa bt-remove-etapa"><i
                                                                                    class="fa fa-remove"
                                                                                    title="Remover Etapa"></i></div>
                                                                        <label>Nome da Etapa</label>
                                                                        <input type="text" class="form-control"
                                                                               name="nomeEtapa[]"
                                                                               placeholder="Nome da Etapa"
                                                                               value="<?php echo $aux['nome']; ?>">
                                                                        <div class="lista-campos">
                                                                            <?php
                                                                            if (isset($aux['campos'])) {
                                                                                foreach ($aux['campos'] as $campo => $tipo) { ?>
                                                                                    <div class="row campo-etapa">
                                                                                        <div class="js-remove-campo bt-remove-campo">
                                                                                            <i class="fa fa-remove"
                                                                                               title="Remover Campo"></i>
                                                                                        </div>
                                                                                        <div class="col-xs-6 col-xs-offset-1">
                                                                                            <div class="form-group">
                                                                                                <input type="text"
                                                                                                       class="form-control"
                                                                                                       name="campoEtapa[]"
                                                                                                       placeholder="Nome do Campo"
                                                                                                       value="<?php echo $campo; ?>">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-xs-5">
                                                                                            <div class="form-group">
                                                                                                <select class="form-control"
                                                                                                        name="tipoCampo[]">
                                                                                                    <option value="">--
                                                                                                        Selecione o Tipo
                                                                                                        do Campo --
                                                                                                    </option>
                                                                                                    <option value="text" <?php echo ($tipo == 'text') ? 'selected' : ''; ?>>
                                                                                                        text
                                                                                                    </option>
                                                                                                    <option value="textarea" <?php echo ($tipo == 'textarea') ? 'selected' : ''; ?>>
                                                                                                        textarea
                                                                                                    </option>
                                                                                                    <option value="editor" <?php echo ($tipo == 'editor') ? 'selected' : ''; ?>>
                                                                                                        editor
                                                                                                    </option>
                                                                                                    <option value="img" <?php echo ($tipo == 'img') ? 'selected' : ''; ?>>
                                                                                                        img
                                                                                                    </option>
                                                                                                    <option value="file" <?php echo ($tipo == 'file') ? 'selected' : ''; ?>>
                                                                                                        file
                                                                                                    </option>
                                                                                                    <option value="number" <?php echo ($tipo == 'number') ? 'selected' : ''; ?>>
                                                                                                        number
                                                                                                    </option>
                                                                                                    <option value="date" <?php echo ($tipo == 'date') ? 'selected' : ''; ?>>
                                                                                                        date
                                                                                                    </option>
                                                                                                    <option value="datetime" <?php echo ($tipo == 'datetime') ? 'selected' : ''; ?>>
                                                                                                        datetime
                                                                                                    </option>
                                                                                                    <option value="boolean" <?php echo ($tipo == 'boolean') ? 'selected' : ''; ?>>
                                                                                                        boolean
                                                                                                    </option>
                                                                                                    <option value="script" <?php echo ($tipo == 'script') ? 'selected' : ''; ?>>
                                                                                                        script
                                                                                                    </option>
                                                                                                    <option value="etapa" <?php echo ($tipo == 'etapa') ? 'selected' : ''; ?>>
                                                                                                        SubEtapas
                                                                                                    </option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <?php
                                                                                }
                                                                            } ?>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-xs-12">
                                                                                <button class="btn btn-flat btn-primary pull-right border-0 js-adicionar-campo">
                                                                                    Adicionar Campo
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                }
                                                            }
                                                            else { ?>
                                                                <div class="form-group box-etapa">
                                                                    <div class="js-remove-etapa bt-remove-etapa"><i
                                                                                class="fa fa-remove"
                                                                                title="Remover Etapa"></i></div>
                                                                    <label>Nome da Etapa</label>
                                                                    <input type="text" class="form-control"
                                                                           name="nomeEtapa[]"
                                                                           placeholder="Nome da Etapa">
                                                                    <div class="lista-campos">
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-xs-12">
                                                                            <button class="btn btn-flat btn-primary pull-right border-0 js-adicionar-campo">
                                                                                Adicionar Campo
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                        <button class="btn btn-flat btn-primary pull-left border-0 js-adicionar-etapa">
                                                            Adicionar Etapa
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <input type="submit"
                                                                   class="btn btn-flat btn-block btn-success border-0 bt-continuar"
                                                                   value="Continuar">
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>

                                            <form class="form-etapa identificar-etapas" method="post"
                                                  action="" <?php if ($passoMontagem != 2) {
                                                echo 'style="display:none"';
                                            } ?>>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <h4>Identificação de Etapas Iniciais</h4>
                                                        <p>Marque aqui as etapas iniciais do fluxo, as etapas que são
                                                            geradas na inicialização do projeto.</p>
                                                        <hr/>
                                                        <div class="box-etapas">
                                                            <?php if ($primeirasSessao) {
                                                                $listaPrimeiras = [];
                                                                foreach ($primeirasSessao as $aux) {
                                                                    $listaPrimeiras[] = $aux['etapa'];
                                                                }

                                                                foreach ($etapasSessao as $aux) { ?>
                                                                    <div class="input-group etapa-primeira">
                                                                        <div class="form-control"><?php echo $aux['nome']; ?></div>
                                                                        <input type="hidden" name="etapa[]"
                                                                               value="<?php echo $aux['etapa']; ?>">
                                                                        <span class="input-group-addon">
																				<input type="checkbox" name="inicial[]"
                                                                                       value="1" <?php echo in_array($aux['etapa'], $listaPrimeiras) ? 'checked' : ''; ?>>
																			</span>
                                                                    </div>
                                                                    <?php
                                                                }

                                                            }
                                                            else {
                                                                if ($etapasSessao) {
                                                                    foreach ($etapasSessao as $aux) { ?>
                                                                        <div class="input-group etapa-primeira">
                                                                            <div class="form-control"><?php echo $aux['nome']; ?></div>
                                                                            <input type="hidden" name="etapa[]"
                                                                                   value="<?php echo $aux['etapa']; ?>">
                                                                            <span class="input-group-addon">
																					<input type="checkbox"
                                                                                           name="inicial[]" value="1">
																				</span>
                                                                        </div>
                                                                        <?php
                                                                    }
                                                                }
                                                            } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <input type="submit"
                                                                   class="btn btn-flat btn-block btn-success border-0 bt-continuar"
                                                                   value="Continuar">
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>

                                            <form class="form-etapa relacionar-etapas" method="post"
                                                  action="" <?php if ($passoMontagem != 3) {
                                                echo 'style="display:none"';
                                            } ?>>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <h4>Relacionamento de Etapas</h4>
                                                        <p>Relacione aqui as etapas, identificando quais serão as
                                                            próximas e anteriores.</p>
                                                        <hr/>
                                                        <div class="label-etapas">
                                                            <div class="row">
                                                                <div class="col-xs-4"><label>Etapa</label></div>
                                                                <div class="col-xs-4"><label>Próxima Etapa</label></div>
                                                                <div class="col-xs-4"><label>Etapa Anterior</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="box-etapas">
                                                            <?php if ($relacoesSessao) {
                                                                foreach ($relacoesSessao as $aux) { ?>
                                                                    <div class="row relacao-etapa">
                                                                        <div class="js-remove-relacao bt-remove-relacao">
                                                                            <i class="fa fa-remove"
                                                                               title="Remover Relação"></i></div>
                                                                        <div class="col-xs-4">
                                                                            <select class="form-control" name="etapa">
                                                                                <?php foreach ($etapasSessao as $aux2) { ?>
                                                                                    <option
                                                                                            value="<?php echo $aux2['etapa']; ?>" <?php echo ($aux['etapa'] == $aux2['etapa']) ? 'selected' : ''; ?>><?php echo $aux2['nome']; ?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-xs-4">
                                                                            <select class="form-control" name="proxima">
                                                                                <option value="">Nenhuma</option>
                                                                                <?php foreach ($etapasSessao as $aux2) { ?>
                                                                                    <option
                                                                                            value="<?php echo $aux2['etapa']; ?>" <?php echo ($aux['proxima'] == $aux2['etapa']) ? 'selected' : ''; ?>><?php echo $aux2['nome']; ?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-xs-4">
                                                                            <select class="form-control"
                                                                                    name="anterior">
                                                                                <option value="">Nenhuma</option>
                                                                                <?php foreach ($etapasSessao as $aux2) { ?>
                                                                                    <option
                                                                                            value="<?php echo $aux2['etapa']; ?>" <?php echo ($aux['anterior'] == $aux2['etapa']) ? 'selected' : ''; ?>><?php echo $aux2['nome']; ?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </div>
                                                        <button class="btn btn-flat btn-primary pull-right border-0 js-adicionar-relacao">
                                                            Adicionar Relação
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <input type="submit"
                                                                   class="btn btn-flat btn-block btn-success border-0 bt-continuar"
                                                                   value="Continuar">
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>

                                            <form class="form-etapa dados-etapas" method="post"
                                                  action="" <?php if ($passoMontagem != 4) {
                                                echo 'style="display:none"';
                                            } ?>>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <h4>Adicionar Dados das Etapas</h4>
                                                        <p>Adicione aqui as áreas responsáveis e o prazo em dias de cada
                                                            etapa.</p>
                                                        <hr/>
                                                        <div class="label-etapas">
                                                            <div class="row">
                                                                <div class="col-xs-7"><label>Etapa</label></div>
                                                                <div class="col-xs-3"><label>Área Responsável</label>
                                                                </div>
                                                                <div class="col-xs-2"><label>Prazo (dias)</label></div>
                                                            </div>
                                                        </div>
                                                        <div class="box-etapas">
                                                            <?php
                                                            if ($etapasSessao) {
                                                                $i = 0;

                                                                foreach ($etapasSessao as $aux) {
                                                                    $dadosEtapa = ['area' => '', 'prazo' => ''];
                                                                    if ($dadosEtapasSessao) {
                                                                        $dadosEtapa['area'] = $dadosEtapasSessao[$i]['area'];
                                                                        $dadosEtapa['prazo'] = $dadosEtapasSessao[$i]['prazo'];
                                                                    }
                                                                    ?>
                                                                    <div class="row etapa-dados">
                                                                        <input type="hidden" name="etapa"
                                                                               value="<?php echo $aux['etapa']; ?>">
                                                                        <div class="col-xs-7">
                                                                            <div class="form-control"><?php echo $aux['nome']; ?></div>
                                                                        </div>
                                                                        <div class="col-xs-3">
                                                                            <select class="form-control" name="area">
                                                                                <option value="">-- Selecione --
                                                                                </option>
                                                                                <?php
                                                                                foreach ($listaAreas as $area) {
                                                                                    $selected = ($area['area'] == $dadosEtapa['area']) ? 'selected="selected"' : '';
                                                                                    echo '<option value="' . $area['area'] . '" ' . $selected . '><span style="color: ' . $area['cor'] . '">' . $area['nome'] . '</span></option>';
                                                                                }
                                                                                ?>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-xs-2">
                                                                            <input class="form-control" type="text"
                                                                                   name="prazo" placeholder="Prazo"
                                                                                   value="<?php echo $dadosEtapa['prazo']; ?>">
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                    $i++;
                                                                }
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <input type="submit"
                                                                   class="btn btn-flat btn-block btn-success border-0 bt-continuar"
                                                                   value="Finalizar">
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>

                                            <div class="concluido" style="display: none;">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <div class="box-concluido">
                                                            <i class="fa fa-check-circle"></i>
                                                            <h3>Fluxo cadastrado com sucesso</h3>
                                                            <p>Muito bem! Você finalizou a criação do fluxo.<br>A página
                                                                será recarregada.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="erro" style="display: none;">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <div class="box-erro">
                                                            <i class="fa fa-times-circle"></i>
                                                            <h3>Houve um erro ao cadastrar o fluxo</h3>
                                                            <p>Ops! Houve algum erro ao cadastrar o fluxo.<br>
                                                                Verifique se você preencheu todos os campos necessários,
                                                                <br>
                                                                marcou as etapas iniciais e relacionou as etapas.
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /box -->
                    <?php } ?>
                </div><!-- /col fluxo-->
            </div><!-- /row fluxo-->
        </section><!-- /content -->
    </aside>
</div>

<!-- MODAL TIPOPROJETO GERAL -->
<div class="modal fade" id="modal-tipoProjeto" tabindex="-1" role="dialog" aria-hidden="true">
</div>

<script type="text/javascript">
    var idUsuarioLogado = '<?php echo $dadosUsuario["usuarioNerdweb"]; ?>';
</script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="//code.jquery.com/ui/1.11.1/jquery-ui.min.js" type="text/javascript"></script>
<script src="../js/plugins/bootstrap/bootstrap.min.js" type="text/javascript"></script>
<script src="../js/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>
<script src="../js/plugins/iCheck/icheck.min.js" type="text/javascript"></script>
<script src="../js/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="../js/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
<script src="../js/plugins/colorpicker/bootstrap-colorpicker.min.js" type="text/javascript"></script>
<script src="../js/AdminLTE/app.js" type="text/javascript"></script>
<script src="../js/backoffice.js" type="text/javascript"></script>
<script src="../js/pop.js" type="text/javascript"></script>

<script type="text/javascript">
    <?php
    $optionsArea = '';
    foreach ($listaAreas as $area) {
        $optionsArea .= '<option value="' . $area['area'] . '">' . $area['nome'] . '</option>';
    }
    ?>

    var base_url = '<?php echo POP_URL; ?>';

    var tipoProjeto = '<?php echo $infoTipoProjeto['projetoTipo']; ?>';

    var etapasJson = JSON.parse('<?php echo $etapasSessao ? json_encode($etapasSessao) : '{}'; ?>');
    var listaEtapas = etapasJson ? etapasJson : [];

    var primeirasJson = JSON.parse('<?php echo $primeirasSessao ? json_encode($primeirasSessao) : '{}'; ?>');
    var listaPrimeiras = primeirasJson ? primeirasJson : [];

    var relacoesJson = JSON.parse('<?php echo $relacoesSessao ? json_encode($relacoesSessao) : '{}'; ?>');
    var listaRelacoes = relacoesJson ? relacoesJson : [];

    var dadosEtapasJson = JSON.parse('<?php echo $dadosEtapasSessao ? json_encode($dadosEtapasSessao) : '{}'; ?>');
    var listaDadosEtapas = dadosEtapasJson ? dadosEtapasJson : [];

    var passoMontagem = '<?php echo $passoMontagem; ?>';
    var fluxo = {
        projetoTipo: tipoProjeto,
        passo: passoMontagem,
        etapas: listaEtapas,
        primeiras: listaPrimeiras,
        relacoes: listaRelacoes,
        dadosEtapas: listaDadosEtapas
    };

    var html_campo_etapa = '<div class="form-group box-etapa">' +
        '<div class="js-remove-etapa bt-remove-etapa"><i class="fa fa-remove" title="Remover Etapa"></i></div>' +
        '<label>Nome da Etapa</label>' +
        '<input type="text" class="form-control" name="nomeEtapa[]" placeholder="Nome da Etapa">' +
        '<div class="lista-campos"></div>' +
        '<div class="row">' +
        '<div class="col-xs-12">' +
        '<button class="btn btn-flat btn-primary pull-right border-0 js-adicionar-campo">Adicionar Campo</button>' +
        '</div>' +
        '</div>' +
        '</div>';

    var html_campo_campo = '<div class="row campo-etapa">' +
        '<div class="js-remove-campo bt-remove-campo"><i class="fa fa-remove" title="Remover Campo"></i></div>' +
        '<div class="col-xs-6 col-xs-offset-1">' +
        '<div class="form-group">' +
        '<input type="text" class="form-control" name="campoEtapa[]" placeholder="Nome do Campo">' +
        '</div>' +
        '</div>' +
        '<div class="col-xs-5">' +
        '<div class="form-group">' +
        '<select class="form-control" name="tipoCampo[]">' +
        '<option value="">-- Selecione o Tipo do Campo --</option>' +
        '<option value="text">text</option>' +
        '<option value="textarea">textarea</option>' +
        '<option value="editor">editor</option>' +
        '<option value="img">img</option>' +
        '<option value="file">file</option>' +
        '<option value="number">number</option>' +
        '<option value="date">date</option>' +
        '<option value="datetime">datetime</option>' +
        '<option value="boolean">boolean</option>' +
        '<option value="script">script</option>' +
        '<option value="etapa">SubEtapas</option>' +
        '</select>' +
        '</div>' +
        '</div>' +
        '</div>';

    // clique nos botões que trazem os dados do tipo do projeto na modal
    $(document).on('click', '.js-getTipoProjeto', function (event) {
        event.preventDefault();

        var idModal = $(this).attr('data-target');
        var idTipoProjeto = $(this).attr('data-tipoProjeto');
        var acao = $(this).attr('data-acao');

        $(idModal).hide();

        $.ajax({
            url: 'ajax/ajax-pop.php',
            type: 'POST',
            data: {
                secao: 'fluxo',
                tipo: acao,
                tipoProjeto: idTipoProjeto
            }
        })
            .done(function (retorno) {
                if (retorno.tipo == 'html') {
                    $(idModal).html(retorno.conteudo);
                    $(idModal).show();
                } else {
                    console.log(retorno);
                }
            })
            .fail(function (retorno) {
                console.log(retorno);
            });
    });

    // clique para mudar a tela de passo
    $(document).on('click', '.passo', function (event) {
        event.preventDefault();
        var passo = $(this).attr('data-passo');
        var form = $(this).attr('data-form');

        if (passo <= passoMontagem && passo != fluxo.passo) {
            $('.passo').removeClass('ativo');
            $(this).addClass('ativo');

            $('.form-etapa:visible').fadeOut(200, function () {
                $(form).fadeIn(200);
            });

            fluxo.passo = passo;
            quickSave();
        }
    });

    // adiciona uma linha de etapa no primero formulário
    $(document).on('click', '.js-adicionar-etapa', function (event) {
        event.preventDefault();
        quickSave();

        $('.adicionar-etapas .box-etapas').append(html_campo_etapa);
    });

    // adiciona uma linha de campos de etapa no primeiro formulário
    $(document).on('click', '.js-adicionar-campo', function (event) {
        event.preventDefault();
        quickSave();
        $(this).parent().parent().parent().find('.lista-campos').append(html_campo_campo);
    });

    // adiciona uma linha de relação entre etapas no terceiro formulário
    $(document).on('click', '.js-adicionar-relacao', function (event) {
        event.preventDefault();

        if (listaEtapas.length > 0) {
            quickSave();

            var options = '';
            for (var i = 0; i < listaEtapas.length; i++) {
                options = options + '<option value="' + listaEtapas[i].etapa + '">' + listaEtapas[i].nome + '</option>';
            }

            var htmlRelacao = '<div class="row relacao-etapa">' +
                '<div class="js-remove-relacao bt-remove-relacao"><i class="fa fa-remove" title="Remover Relação"></i></div>' +
                '<div class="col-xs-4">' +
                '<select class="form-control" name="etapa">' +
                '<option value="">-- Selecione --</option>' +
                options +
                '</select>' +
                '</div>' +
                '<div class="col-xs-4">' +
                '<select class="form-control" name="proxima">' +
                '<option value="">Nenhuma</option>' +
                options +
                '</select>' +
                '</div>' +
                '<div class="col-xs-4">' +
                '<select class="form-control" name="anterior">' +
                '<option value="">Nenhuma</option>' +
                options +
                '</select>' +
                '</div>' +
                '</div>';

            $(this).parent().find('.box-etapas').append(htmlRelacao);
        }
    });

    // regera options de relações conforme a etapa selecionada (remove a selecionada das próximas e anteriores)
    $(document).on('change', '.relacao-etapa select[name=etapa]', function (event) {
        event.preventDefault();
        var selected = $(this).val();
        var $selects = $('select[name=proxima], select[name=anterior]');
        var listaOptions = geraOptionsEtapa(selected);

        $(this).parent().parent().find($selects).html(listaOptions);
    });

    // remove etapas e campos no clique no X
    $(document).on('click', '.js-remove-etapa, .js-remove-campo', function (event) {
        event.preventDefault();
        $(this).parent().remove();

        quickSave();
    });

    // remove relação no clique no X
    $(document).on('click', '.js-remove-relacao', function (event) {
        event.preventDefault();
        $(this).parent().remove();

        quickSave();
    });


    /* ************************** SUBMITS FORMS ETAPAS ************************** */

    // submit do formulário de adição das etapas
    $(document).on('submit', '.adicionar-etapas', function (event) {
        event.preventDefault();
        quickSave();

        var htmlEtapa = '';
        $('.identificar-etapas').find('.box-etapas').html(htmlEtapa);

        for (var i = 0; i < listaEtapas.length; i++) {
            var checked = '';

            /* // tentando implementar para manter checado quando voltar o passo do fluxo, mas acho que não vai rolar porque ao adicionar uma
             // nova etapa no meio das outras, reorganizando, o negócio zoa, por eu usar ids por posição
             if(listaPrimeiras) {
             for(var j = 0; j < listaPrimeiras.length; j++) {
             if(listaPrimeiras[j].etapa == listaEtapas[i].etapa) {
             checked = 'checked';
             }
             }
             }
             */
            htmlEtapa = htmlEtapa +
                '<div class="input-group etapa-primeira">' +
                '<div class="form-control">' + listaEtapas[i].nome + '</div>' +
                '<input type="hidden" name="etapa[]" value="' + listaEtapas[i].etapa + '">' +
                '<span class="input-group-addon">' +
                '<input type="checkbox" name="inicial[]" value="1" ' + checked + '>' +
                '</span>' +
                '</div>';
        }

        $('.identificar-etapas').find('.box-etapas').append(htmlEtapa);
        $('input').iCheck({
            checkboxClass: 'icheckbox_minimal',
            radioClass: 'iradio_minimal'
        });

        $('.barra-progresso .passo[data-passo="1"]').removeClass('ativo');
        $('.barra-progresso .passo[data-passo="2"]').addClass('ativo');

        $(this).fadeOut(400, function () {
            $('.identificar-etapas').fadeIn(400);
        });

        fluxo.passo = 2;
        quickSave();
    });

    // submit do formulário de identificação das primeiras etapas
    $(document).on('submit', '.identificar-etapas', function (event) {
        event.preventDefault();
        quickSave();

        $('.barra-progresso .passo[data-passo="2"]').removeClass('ativo');
        $('.barra-progresso .passo[data-passo="3"]').addClass('ativo');

        $('.relacionar-etapas').find('.box-etapas').html('');

        $(this).fadeOut(400, function () {
            $('.relacionar-etapas').fadeIn(400);
        });

        fluxo.passo = 3;
        quickSave();
    });

    // submit do formulário de relacionamento entre as etapas
    $(document).on('submit', '.relacionar-etapas', function (event) {
        event.preventDefault();
        quickSave();

        var htmlEtapa = '';
        $('.dados-etapas').find('.box-etapas').html(htmlEtapa);

        for (var i = 0; i < listaEtapas.length; i++) {
            htmlEtapa = htmlEtapa +
                '<div class="row etapa-dados">' +
                '<input type="hidden" name="etapa" value="' + listaEtapas[i].etapa + '">' +
                '<div class="col-xs-7">' +
                '<div class="form-control">' + listaEtapas[i].nome + '</div>' +
                '</div>' +
                '<div class="col-xs-3">' +
                '<select class="form-control" name="area">' +
                '<option value="">-- Selecione --</option>' +
                '<?php echo $optionsArea; ?>' +
                '</select>' +
                '</div>' +
                '<div class="col-xs-2">' +
                '<input class="form-control" type="text" name="prazo" placeholder="Prazo">' +
                '</div>' +
                '</div>';
        }

        $('.dados-etapas').find('.box-etapas').append(htmlEtapa);

        $('.barra-progresso .passo[data-passo="3"]').removeClass('ativo');
        $('.barra-progresso .passo[data-passo="4"]').addClass('ativo');

        $(this).fadeOut(400, function () {
            $('.dados-etapas').fadeIn(400);
        });

        fluxo.passo = 4;
        quickSave();
    });

    // submit do formulário de relacionamento entre as etapas
    $(document).on('submit', '.dados-etapas', function (event) {
        event.preventDefault();
        var $form = $(this);

        quickSave();

        var dfd = $.Deferred();
        dfd.done(quickSave).done(function () {
            $.ajax({
                url: 'ajax/ajax-pop.php',
                type: 'POST',
                data: {
                    secao: 'fluxo',
                    form: 'adicionar-fluxo',
                    tipoProjeto: tipoProjeto,
                    etapas: listaEtapas,
                    primeiras: listaPrimeiras,
                    relacoes: listaRelacoes,
                    dadosEtapas: listaDadosEtapas
                }
            })
                .done(function (retorno) {
                    if (retorno.tipo == 'sucesso') {
                        $form.fadeOut(300, function () {
                            $('.barra-progresso .passo[data-passo="4"]').addClass('ativo');
                            $('.concluido').fadeIn(300);

                            setTimeout(function () {
                                location.reload();
                            }, 6000);
                        });
                    } else {
                        $form.fadeOut(300, function () {
                            $('.barra-progresso .passo[data-passo="4"]').addClass('ativo');
                            $('.erro').fadeIn(300, function () {
                                $('.erro').delay(5000).fadeOut(300, function () {
                                    $form.fadeIn(300);
                                });
                            });
                        });
                    }
                })
                .fail(function (retorno) {
                    console.log(retorno);
                });
        });

        dfd.resolve();
    });

    // clique em alguma das "primeiras etapas"
    $(document).on('ifChanged', '.etapa-primeira input[type=checkbox]', function (event) {
        fluxo.passo = 2;
        quickSave();
    });

    // mudança em alguma áreas no formulário de dados de etapas
    $(document).on('change', '.etapa-dados select[name=area]', function (event) {
        fluxo.passo = 4;
        quickSave();
    });

    // organiza e salva os dados do formulário, conforme o passo em que a criação do fluxo está
    function quickSave() {
        var passo = parseInt(fluxo.passo);

        switch (passo) {
            case 1:
                listaEtapas = [];
                var cont = 1;

                $('.adicionar-etapas').find('.box-etapa').each(function (index, el) {
                    var etapa = {etapa: cont, nome: null, campos: {}};

                    var tmp = $(el).find('select, textarea, input').serializeArray();
                    var nomeEtapa = $.grep(tmp, function (e) {
                        return e.name == "nomeEtapa[]";
                    });

                    if (nomeEtapa[0].value) {
                        etapa.nome = nomeEtapa[0].value;

                        var camposEtapa = $.grep(tmp, function (e) {
                            return e.name == "campoEtapa[]";
                        });
                        var tipoCampos = $.grep(tmp, function (e) {
                            return e.name == "tipoCampo[]";
                        });

                        if (camposEtapa.length == tipoCampos.length) {
                            for (var i = 0; i < camposEtapa.length; i++) {
                                etapa.campos[camposEtapa[i].value] = tipoCampos[i].value;
                            }

                            listaEtapas.push(etapa);
                        }
                    }

                    cont++;
                }).promise().done(function () {
                    fluxo.etapas = listaEtapas;
                    fluxo.passo = 1;
                });
                break;

            case 2:
                listaPrimeiras = [];

                $('.identificar-etapas').find('.etapa-primeira').each(function (index, el) {
                    var primeira = {etapa: null};
                    var tmp = $(el).find('select, textarea, input').serializeArray();

                    var etapaId = $.grep(tmp, function (e) {
                        return e.name == "etapa[]";
                    });
                    var isPrimeira = $.grep(tmp, function (e) {
                        return e.name == "inicial[]";
                    });

                    if (isPrimeira.length > 0 && isPrimeira[0].value == '1') {
                        primeira.etapa = etapaId[0].value;
                        listaPrimeiras.push(primeira);
                    }
                }).promise().done(function () {
                    fluxo.primeiras = listaPrimeiras;
                    fluxo.passo = 2;
                });
                break;

            case 3:
                listaRelacoes = [];

                $('.relacionar-etapas').find('.relacao-etapa').each(function (index, el) {
                    var relacao = {etapa: null, proxima: null, anterior: null};
                    var tmp = $(el).find('select, textarea, input').serializeArray();

                    var etapaId = $.grep(tmp, function (e) {
                        return e.name == "etapa";
                    });
                    var proximaId = $.grep(tmp, function (e) {
                        return e.name == "proxima";
                    });
                    var anteriorId = $.grep(tmp, function (e) {
                        return e.name == "anterior";
                    });

                    if (etapaId.length > 0) {
                        relacao.etapa = etapaId[0].value;
                        relacao.proxima = proximaId[0].value;
                        relacao.anterior = anteriorId[0].value;

                        listaRelacoes.push(relacao);
                    }
                }).promise().done(function () {
                    fluxo.relacoes = listaRelacoes;
                    fluxo.passo = 3;
                });
                break;

            case 4:
                listaDadosEtapas = [];

                $('.dados-etapas').find('.etapa-dados').each(function (index, el) {
                    var dado = {etapa: null, area: null, prazo: null};
                    var tmp = $(el).find('select, textarea, input').serializeArray();

                    var etapaId = $.grep(tmp, function (e) {
                        return e.name == "etapa";
                    });
                    var areaId = $.grep(tmp, function (e) {
                        return e.name == "area";
                    });
                    var prazo = $.grep(tmp, function (e) {
                        return e.name == "prazo";
                    });

                    if (etapaId.length > 0) {
                        dado.etapa = etapaId[0].value;
                        dado.area = areaId[0].value;
                        dado.prazo = prazo[0].value;

                        listaDadosEtapas.push(dado);
                    }

                }).promise().done(function () {
                    fluxo.dadosEtapas = listaDadosEtapas;
                    fluxo.passo = 4;
                });
                break;

            default:
                break;
        }

        console.log(fluxo);
        passoMontagem = fluxo.passo;
        salvaSessao();

    }

    // gera os <option> do select de relação entre etapas
    function geraOptionsEtapa(remove) {
        var options = '<option value="">Nenhuma</option>';
        var i;

        if (remove === undefined) {
            for (i = 0; i < listaEtapas.length; i++) {
                options = options + '<option value="' + listaEtapas[i].etapa + '">' + listaEtapas[i].nome + '</option>';
            }

        } else {
            for (i = 0; i < listaEtapas.length; i++) {
                if (listaEtapas[i].etapa != remove) {
                    options = options + '<option value="' + listaEtapas[i].etapa + '">' + listaEtapas[i].nome + '</option>';
                }
            }
        }

        return options;
    }

    function salvaSessao() {
        $.ajax({
            url: 'ajax/ajax-pop.php',
            type: 'POST',
            data: {
                secao: 'fluxo',
                tipo: 'adicionar-sessao',
                conteudo: fluxo,
                tipoProjeto: tipoProjeto
            }
        })
            .done(function (retorno) {
                //console.log(retorno);
            })
            .fail(function (retorno) {
                //console.log(retorno);
            });
    }
</script>
</body>
</html>
