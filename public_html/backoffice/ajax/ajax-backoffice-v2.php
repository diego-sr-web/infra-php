<?php
header('Cache-Control: no-cache');
header('Content-Type: application/json');

require_once __DIR__ . "/../../autoloader.php";

$database = new Database();
$usuario = new AdmUsuario($database);
$POPProjeto = new Projeto($database);
$POPElemento = new Elemento($database);
$BNCliente = new BNCliente($database);
$POPChat = new Chat($database);
$POPBotao = new Botao($database);

$dadosUsuario = $usuario->getUserDataWithId($_SESSION['adm_usuario']);
$areasUsuario = $usuario->getAreasUsuario($_SESSION['adm_usuario']);

$todasAreas = $usuario->listArea();
$todasAreasHidden = $usuario->listArea("",TRUE);
$todasAreas = Utils::sksort(array_merge($todasAreas,$todasAreasHidden), "nome", TRUE);

if ($usuario->checkSession() == FALSE) {
    $resposta = ['status' => '', 'tipo' => '', 'conteudo' => ''];
    echo json_encode($resposta);
    exit;
}

require_once __DIR__ . "/ajax-backoffice-v2-funcoes.inc.php";
require_once __DIR__ . "/ajax-backoffice-v2-funcoes-area.inc.php";
require_once __DIR__ . '/../includes/is_logged.php';

$dados_modal = [];
// Comeca a caminhar pelas possibilidades de chamadas

if (isset($_POST['secao'])) {
    $secao = $_POST['secao'] ?? NULL;
    $acao = $_POST['tipo'] ?? NULL;
    $form = $_POST['form'] ?? NULL;
    $Template = new Template(["buffer" => TRUE]);
    switch ($secao) {

        case 'grupo':
            break;

        case 'area':
            if (isset($_POST['id'])) {
                $infoArea = $usuario->getAreaWithId($_POST['id']);
            }

            switch ($acao) {
                case "adicionar-area":
                    $dados_modal = modal_adicionar_area($Template);
                    break;
                case 'apagar-area':
                    if (isset($infoArea["area"], $infoArea["nome"])) {
                        $dados_modal = modal_apaga_area($Template, $infoArea);
                    }
                    break;
                case 'editar-area':
                    if (isset($infoArea['area'], $infoArea['nome'], $infoArea['cor'])) {
                        $dados_modal = modal_editar_area($Template, $infoArea);
                    }
                    break;
            }
            break;

        case "usuario":
            if (isset($_POST["id"])) {
                $infoUsuario = $usuario->getUserDataWithId($_POST['id']);
            }
            require_once __DIR__ . "/ajax-backoffice-v2-funcoes-usuario.inc.php";
            switch ($acao) {
                case 'adicionar-usuario':
                    $dados_modal = modal_adicionar_usuario($Template, $todasAreas);
                    break;
                case 'editar-usuario':
                    if (isset($infoUsuario["usuarioNerdweb"], $infoUsuario["nome"])) {
                        $dados_modal = modal_editar_usuario($Template, $infoUsuario, $todasAreas,$usuario);
                    }
                    break;
                case 'ver-usuario':
                    if (isset($infoUsuario["usuarioNerdweb"])) {
                        $dados_modal = modal_ver_usuario($Template, $infoUsuario);
                    }
                    break;
            }
            break;

        case "cliente":
            if (isset($_POST["id"])) {
                $infoCliente = $BNCliente->getDataWithId($_POST['id']);
            }
            require_once __DIR__ . "/ajax-backoffice-v2-funcoes-cliente.inc.php";
            switch ($acao) {
                case 'adicionar-cliente':
                        $dados_modal = modal_adicionar_cliente($Template);
                    break;
                case 'desativar-cliente':
                    if (isset($infoCliente["cliente"], $infoCliente["nomeFantasia"])) {
                        $dados_modal = modal_desativa_cliente($Template, $infoCliente);
                    }
                    break;
                case 'ver-cliente':
                    if (isset($infoCliente["cliente"], $infoCliente["nomeFantasia"])) {
                        $dados_modal = modal_exibe_cliente($Template, $infoCliente);
                    }
                    break;
                case 'editar-cliente':
                    if (isset($infoCliente["cliente"], $infoCliente["nomeFantasia"])) {
                        $dados_modal = modal_edita_cliente($Template, $infoCliente);
                    }
                    break;
            }
    }
}

if ($dados_modal === []) {
    // Se nao tiver encontrado a funcao do botao avisa isso
    $dados_modal["tipo"] = "erro";
    $dados_modal["conteudo"] = "Chamada de ajax nao reconhecida`";
}

// Echo o html da modal e encerra o script, caso o tipo de retorno seja HTML
if (isset($dados_modal["tipo"]) && $dados_modal["tipo"] == "html") {
    echo json_encode($dados_modal);
} else {
    // Tenta Facilitar o debug criando uma tela com dump das informacoes passadas pro script
    printDebugAndExit($Template, ["POST" => $_POST, "CONTEUDO" => $dados_modal]);
}
