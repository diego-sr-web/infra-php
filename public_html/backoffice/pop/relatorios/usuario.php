<?php
require_once __DIR__ . '/../../../autoloader.php';

$database = new Database();
$usuario = new AdmUsuario($database);
require_once __DIR__ . '/../../includes/is_logged.php';

$POPProjeto = new Projeto($database);

$BNCliente = new BNCliente($database);


/**
 * @param Database   $database
 * @param            $tipoProjeto
 * @param int|string $userId
 *
 * @return array
 */
function generateMean($database, $tipoProjeto, $userId) {
    $tempoMedioProjetos = [];
    $tempoMedioTarefas = [];
    $chatParams = [$tipoProjeto, $userId];
    $sqlChat = "
            SELECT
              pch.*
            FROM pop_ProjetoChat pch INNER JOIN pop_Projeto p
              ON
                p.projeto = pch.projeto
              AND
                p.projetoTipo = ?
              AND p.isUsed = 1
            
            WHERE
              pch.responsavel = ?
            AND pch.isUsed = 1
            AND pch.conteudo LIKE '%tempo trabalhado%'
            AND pch.conteudo NOT LIKE 'Tarefa - Finalização da Campanha%'
            ORDER BY pch.data DESC
        ";
    $chat = $database->customQueryPDO($sqlChat, $chatParams);

    foreach ($chat as $chatItem) {
        $elementoTipo = NULL;

        if ($chatItem['elemento'] !== NULL) {
            $chatItemParams = [$chatItem["elemento"]];
            $aaa = $database->customQueryPDO("SELECT * FROM pop_Elemento WHERE elemento = ? LIMIT 1", $chatItemParams);
            $elementoTipo = $aaa[0]['elementoTipo'];
        }
        elseif (strpos($chatItem['conteudo'], 'Tarefa - ') !== FALSE) {
            $el = explode(' - ', $chatItem['conteudo']);
            $el = $el[1];
            $elAux = $database->ngSelectPrepared('pop_ElementoTipo', ['nome' => $el]);

            if ($elAux) {
                $elementoTipo = $elAux[0]['elementoTipo'];
            }
            else {
                $elAux2 = $database->ngSelectPrepared('pop_ElementoTipoSubEtapa', ['nome' => $el]);

                if ($elAux2) {
                    $elementoTipo = $elAux2[0]['elementoTipo'];
                }
            }
        }

        if (strpos($chatItem['conteudo'], 'tempo trabalhado ') !== FALSE) {
            $aux = explode('tempo trabalhado ', $chatItem['conteudo']);
            $aux = explode(' minutos', $aux[1]);
            $minutosProjeto = (int)$aux[0];

            $data = date('Y-m', strtotime($chatItem['data']));

            if (isset($tempoMedioProjetos[$data])) {
                $tempoMedioProjetos[$data]['quantidade'] += 1;
                $tempoMedioProjetos[$data]['minutos'] += $minutosProjeto;
            }
            else {
                $tempoMedioProjetos[$data]['quantidade'] = 1;
                $tempoMedioProjetos[$data]['minutos'] = $minutosProjeto;
            }


            if ($elementoTipo) {
                if (isset($tempoMedioTarefas[$elementoTipo][$data])) {
                    $tempoMedioTarefas[$elementoTipo][$data]['quantidade'] += 1;
                    $tempoMedioTarefas[$elementoTipo][$data]['minutos'] += $minutosProjeto;
                }
                else {
                    $tempoMedioTarefas[$elementoTipo][$data]['quantidade'] = 1;
                    $tempoMedioTarefas[$elementoTipo][$data]['minutos'] = $minutosProjeto;
                }
            }
        }
    }

    // Tempo no tipo de projeto
    foreach ($tempoMedioProjetos as $key => $tempo) {
        $media = $tempo['minutos'] / $tempo['quantidade'];
        $tempoMedioProjetos[$key]['media'] = $media;
    }

    // Tempo no tipo de tarefa
    foreach ($tempoMedioTarefas as $key => $tarefa) {
        foreach ($tarefa as $key2 => $tempo) {
            $media = $tempo['minutos'] / $tempo['quantidade'];
            $tempoMedioTarefas[$key][$key2]['media'] = $media;
        }
    }

    return [$tempoMedioProjetos, $tempoMedioTarefas];
}


/**
 * @param Database $database
 * @param array    $tempoMedioProjetos
 * @param array    $tempoMedioTarefas
 * @param          $nomeTipoProjeto
 */
function printMean($database, $tempoMedioProjetos, $tempoMedioTarefas, $nomeTipoProjeto) {
    $POPElemento = new Elemento($database);
    $html = "";
    $html .=
        '<h2>Projetos ' . $nomeTipoProjeto . '</h2>
    <table border="1" cellpadding="5">
        <thead>
        <tr>
            <td>Mês</td>
            <td>Quantidade</td>
            <td>Minutos</td>
            <td>Minutos/Tarefa</td>
        </tr>
        </thead>
        <tbody>
';
    foreach ($tempoMedioProjetos as $key => $tempoTarefa) {
        $html .= '
        <tr>
            <td>' . $key . '</td>
            <td>' . $tempoTarefa['quantidade'] . '</td>
            <td>' . $tempoTarefa['minutos'] . '</td>
            <td>' . $tempoTarefa['media'] . '</td>
        </tr>
';
    }
    $html .= '
        </tbody>
    </table>
<hr>
';

    $html .= '
<h2>Projetos ' . $nomeTipoProjeto . ' por Tarefa</h2>
';
    $html .= '
<table border="1" cellpadding="5">
    <thead>
    <tr>
        <td>Tarefa</td>
        <td>Mês</td>
        <td>Quantidade</td>
        <td>Minutos</td>
        <td>Minutos/Tarefa</td>
    </tr>
    </thead>
    <tbody>
';
    foreach ($tempoMedioTarefas as $key => $tarefa) {
        $tipoTarefa = $POPElemento->getElementTypeById($key);
        foreach ($tarefa as $key2 => $tempo) {
            $html .= '
    <tr>
        <td>' . $tipoTarefa['nome'] . '</td>
        <td>' . $key2 . '</td>
        <td>' . $tempo['quantidade'] . '</td>
        <td>' . $tempo['minutos'] . '</td>
        <td>' . $tempo['media'] . '</td>
    </tr>
';
        }
    }
    $html .= '
    </tbody>
</table>
<hr>
    ';
    echo $html;
}


$userId = (isset($_GET['user_id']) && $_GET['user_id']) ? $_GET['user_id'] : 0;

if ($userId != 0) {
    $user = $usuario->getUserDataWithId($userId);
    echo '<h1>' . $user['nome'] . '</h1>';

    $tempoTarefas = $tempoProjetos = [];

    $sqlPedidos = "
SELECT 
  * 
FROM 
  pop_ElementoChat ech
WHERE 
    ech.responsavel = ?
AND ech.isUsed = 1
ORDER BY ech.data DESC
";

    $sqlTarefas = "
SELECT
  pch.*
FROM pop_ProjetoChat pch INNER JOIN pop_Projeto p
  ON
    p.projeto = pch.projeto
AND p.isUsed = 1

WHERE
  pch.responsavel = ?
AND
  pch.isUsed = 1

ORDER BY pch.data DESC
";
    $sqlParams = [$userId];
    $chatTarefa = $database->customQueryPDO($sqlPedidos, $sqlParams);

    foreach ($chatTarefa as $chatItem) {
        if (strpos($chatItem['conteudo'], 'tempo trabalhado ')) {
            $aux = explode('tempo trabalhado ', $chatItem['conteudo']);
            $aux = explode(' minutos', $aux[1]);
            $minutosTarefa = (int)$aux[0];

            if (isset($tempoTarefas[date('Y-m', strtotime($chatItem['data']))])) {
                $tempoTarefas[date('Y-m', strtotime($chatItem['data']))]['quantidade'] += 1;
                $tempoTarefas[date('Y-m', strtotime($chatItem['data']))]['minutos'] += $minutosTarefa;
            }
            else {
                $tempoTarefas[date('Y-m', strtotime($chatItem['data']))]['quantidade'] = 1;
                $tempoTarefas[date('Y-m', strtotime($chatItem['data']))]['minutos'] = $minutosTarefa;
            }
        }
    }

    foreach ($tempoTarefas as $key => $tempo) {
        $media = $tempo['minutos'] / $tempo['quantidade'];
        $tempoTarefas[$key]['media'] = $media;
    }

    echo '<h2>Pedidos</h2>';
    ?>
    <table border="1" cellpadding="5">
        <thead>
        <tr>
            <td>Mês</td>
            <td>Quantidade</td>
            <td>Minutos</td>
            <td>Minutos/Tarefa</td>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($tempoTarefas as $key => $tempoTarefa) { ?>
            <tr>
                <td><?php echo $key; ?></td>
                <td><?php echo $tempoTarefa['quantidade']; ?></td>
                <td><?php echo $tempoTarefa['minutos']; ?></td>
                <td><?php echo $tempoTarefa['media']; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <hr>
    <?php

    $medias = generateMean($database, 2, $userId);
    if ($medias[0] != $medias[1]) {
        printMean($database, $medias[0], $medias[1], "Campanha Facebook");
    }
    $medias = generateMean($database, 10, $userId);
    if ($medias[0] != $medias[1]) {
        printMean($database, $medias[0], $medias[1], "WebSite");
    }
    $medias = generateMean($database, 11, $userId);
    if ($medias[0] != $medias[1]) {
        printMean($database, $medias[0], $medias[1], "Campanha Display");
    }
    $medias = generateMean($database, 12, $userId);
    if ($medias[0] != $medias[1]) {
        printMean($database, $medias[0], $medias[1], "Email Marketing");
    }
    $medias = generateMean($database, 13, $userId);
    if ($medias[0] != $medias[1]) {
        printMean($database, $medias[0], $medias[1], "Material Grafico");
    }
}
