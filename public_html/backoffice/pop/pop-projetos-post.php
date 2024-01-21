<?php
require_once __DIR__ . "/../../autoloader.php";

$nova_database = new Database();
$POPProjeto = new Projeto($nova_database);

if (isset($_POST['form'])) {
    $dtAtual = date('Y-m-d H:i:s');
    $redirect = ['redireciona' => FALSE, 'url' => 'pop-projetos.php', 'queryString' => '',];

    if ($_POST['form'] == 'novo-projeto') {
        if ($_POST['nome'] && $_POST['projetoTipo']) {
            // reformata as datas para usar '-' (o php se perde se usar '/')
            $_POST['dataEntrada'] = str_replace('/', '-', $_POST['dataEntrada']);
            $_POST['prazo'] = str_replace('/', '-', $_POST['prazo']);

            $_POST['dataEntrada'] = ($_POST['dataEntrada']) ? date('Y-m-d', strtotime($_POST['dataEntrada'])) : date('Y-m-d');
            $_POST['prazo'] = ($_POST['prazo']) ? date('Y-m-d', strtotime($_POST['prazo'])) : NULL;

            $infoTipoProjeto = $POPProjeto->getProjectTypeById($_POST['projetoTipo']);
            $diasPrazo = ($infoTipoProjeto['diasPrazo']) ? $infoTipoProjeto['diasPrazo'] : 15;
            $prazoEstimado = date('Y-m-d', strtotime('+' . $diasPrazo . ' days', strtotime($_POST['dataEntrada'])));

            // copia o $_POST para o $camposExtra, e descarta todos os campos obrigatórios, para sobrar somente os campos extra para inserção
            $camposExtra = $_POST;
            unset($camposExtra['form'], $camposExtra['nome'], $camposExtra['dataEntrada'], $camposExtra['prazo'], $camposExtra['projetoTipo'], $camposExtra['cliente']);
            $extraArgs = [];
            $extraArgs['campos'] = $camposExtra;
            if (isset($_POST["mes"])) {
                $data = explode("/", $_POST["mes"]);
                $nome_projeto = $_POST['nome'] . " - " . getNomeMes($data[0]) . " - " . $data[1];
            }
            else {
                $nome_projeto = $_POST['nome'];
            }
            $projetoTipo = $_POST['projetoTipo'];
            // Xunxo pra fazer funcionar E-mail marketing via Interface
            if ($projetoTipo == 23) {
                $projetoTipo = 21;
            }
            // EOX

            $retorno = $POPProjeto->createProject($nome_projeto, $projetoTipo, $_POST['dataEntrada'], $_POST['prazo'], $prazoEstimado, $_POST['cliente'], $extraArgs);


            if ($retorno) {
                // Xunxo
                if ($_POST['projetoTipo'] == 23) {
                    $elementosProjetoTMP = $POPElemento->getAllElements(['projeto'], [$retorno]);
                    if (isset($elementosProjetoTMP[0])) {
                        $POPElemento->updateExtraField($elementosProjetoTMP[0]["elemento"], "AreaTipo", "3");
                    }
                    unset($elementosProjetoTMP);
                }
                // EOX
                $idProjeto = $retorno;
                $redirect['redireciona'] = TRUE;
                $redirect['url'] = 'pop-minhas-tarefas.php';
                //$_SESSION["msg_sucesso"] = "Projeto Criado com sucesso.";
                $retorno = 1;
                echo json_encode($retorno);
            }
            else {
                $retorno = 2;
                //$_SESSION["msg_sucesso"] = "Houve algum erro ao inserir o projeto.";
                echo json_encode($retorno);
            }
        }
        else {
            //$_SESSION["msg_sucesso"] = "Preencha todos os campos obrigatórios.";
            $retorno = 3;
            echo json_encode($retorno);
        }
    }
    /*
    if ($redirect['redireciona']) {
        Utils::redirect($redirect['url']);
    }*/
}
