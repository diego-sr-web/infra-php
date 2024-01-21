<?php
require_once __DIR__ . "/../../autoloader.php";


$database = new Database();
$POPProjeto = new Projeto($database);
$POPElemento = new Elemento($database);
$BNCliente = new BNCliente($database);
$usuario = new AdmUsuario($database);
$listaCategorias = $POPElemento->getCategoryList();

require_once __DIR__ . '/../includes/is_logged.php';

$infoProjeto = $POPProjeto->getProjectById($_GET['projeto']);
$infoTipoProjeto = $POPProjeto->getProjectTypeById($infoProjeto['projetoTipo']);
$cliente = $BNCliente->getDataWithId($infoProjeto['cliente']);

$mesCampanha = '';

if (isset($infoProjeto['campos']['mes']) && $infoProjeto['campos']['mes']) {
    $mesCampanha = str_replace('/', '-', $infoProjeto['campos']['mes']);
    $mesCampanha = '01-' . $mesCampanha;
}
$nomeCampoImagem = "image";
$nomeCampoTexto = "Texto";
$nomeCampoData = "Dia";
if ($infoProjeto["projetoTipo"] != 2) {
    $nomeCampoImagem = "arquivos";
    $nomeCampoTexto = "legenda";
    $nomeCampoData = "dataPublicacao";
}

$jsProgramacaoCampanha = $categoriasProgramacao = [];

if (isset($infoProjeto['campos']['planejamento']) && $infoProjeto['campos']['planejamento']) {
    $programacaoCampanha = json_decode($infoProjeto['campos']['planejamento'], TRUE);
    if ($programacaoCampanha !== '[]') {
        foreach ($programacaoCampanha as $progCamp) {
            if ($progCamp) {
                $infoCategoria = $POPElemento->getCategoryById($progCamp['categoria']);

                //categorias usadas na campanha, usar no select de criação de novos posts
                if (!in_array($infoCategoria, $categoriasProgramacao)) {
                    $categoriasProgramacao[] = $infoCategoria;
                }

                // programação da campanha para o calendário
                /** @var string $dataProg */
                $dataProg = date('m-d-Y', strtotime($progCamp['data']));
                $diaProg = date('d', strtotime($progCamp['data']));
                // html que estará no dia do calendário
                $jsProgramacaoCampanha[$dataProg] = '<div style=\"background-color:' . $infoCategoria['cor'] . ';\"></div><div data-index=\"' . (int)$diaProg . '\" class=\"day-actions\"><a class=\"js-remove-programacao\" title=\"Apagar\"><i class=\"fa fa-close\"></i></a></div>';
            }
        }
    }
}
else {
    $programacaoCampanha = [];
}

$jsProgramacaoCampanha = json_encode($jsProgramacaoCampanha);

$elementosProjeto = $POPElemento->getAllElements(['projeto'], [$infoProjeto['projeto']]);
$itensProjeto = $tarefasProjeto = [];

$auxTarefas = $POPElemento->filtraElementosPorBase($elementosProjeto, 1);

foreach ($auxTarefas as $tmp) {
    if (!isset($tmp['campos']['dtFim'])) {
        $tmp['campos']['dtFim'] = NULL;
    }
    if ($tmp['campos']['dtFim'] == NULL) {
        $tarefasProjeto[] = $tmp;
    }
}

$tarefasProjeto = $POPElemento->agrupaTarefas($tarefasProjeto);
$tarefasProjeto = $tarefasProjeto['tarefas'];

foreach ($elementosProjeto as $key => $value) {
    if ($value["elementoTipo"] != 38 && $value["elementoTipo"] != 109) {
        unset($elementosProjeto[$key]);
    }
    //if (!isset($_GET["completo"]) && $value["campos"]["Etapa"] != 12) {
    //    unset($elementosProjeto[$key]);
    //}
}

foreach ($elementosProjeto as $key => $aux) {
    $elementosProjeto[$key]['dataPublicacao'] = $aux['campos'][$nomeCampoData];
}

$elementosProjeto = Utils::sksort($elementosProjeto, "dataPublicacao", TRUE);
$incompleto = FALSE;
foreach ($elementosProjeto as $elemento) {
    $finalizados = [6, 12];
    if (($elemento["elementoStatus"] != 9) && !in_array($elemento["campos"]["Etapa"], $finalizados)) {
        $incompleto = TRUE;
    }
}


