<?php
require_once __DIR__ . '/../../../autoloader.php';
$database = new Database();
$usuario = new AdmUsuario($database);
require_once __DIR__ . '/../../includes/is_logged.php';

$POPProjeto = new Projeto($database);
$BNCliente = new BNCliente($database);

$listaUsuario = $usuario->getUsuarios();
unset($listaUsuario[0]);
$listaAno = ["2016", "2017", "2018", "2019"];
$listaMes = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
?>
    <h2>Lista de tarefas por mes</h2>
    <form action="mensal.php" method="get">
        <select name="ano">
            <?php foreach ($listaAno as $ano) { ?>
                <option value="<?php echo $ano; ?>" <?php if (isset($_GET["ano"]) && $_GET["ano"] == $ano) echo "selected='selected'" ?>><?php echo $ano; ?></option>
            <?php } ?>
        </select>
        <select name="mes">
            <option value="0">Selecione um MES</option>
            <?php foreach ($listaMes as $mes) { ?>
                <option value="<?php echo $mes; ?>" <?php if (isset($_GET["mes"]) && $_GET["mes"] == $mes) echo "selected='selected'" ?>><?php echo $mes; ?></option>
            <?php } ?>
        </select>
        <button type="submit">Ver Tarefas</button>
    </form>
<?php
if (isset($_GET["ano"], $_GET["mes"])) {
    $dia = $_GET["ano"] . "-" . $_GET["mes"];
    $sqlPedidos = "
SELECT 
* 
FROM 
pop_ElementoChat ech
WHERE 
 ech.isUsed = 1
AND YEAR(ech.data) = '" . $_GET["ano"] . "'
AND MONTH(ech.data) = '" . $_GET["mes"] . "'
AND ech.conteudo LIKE '%Aprovada%'
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
 pch.isUsed = 1
AND YEAR(pch.data) = '" . $_GET["ano"] . "'
AND MONTH(pch.data) = '" . $_GET["mes"] . "'
AND pch.conteudo LIKE '%Aprovada%'
ORDER BY pch.data DESC
";

    $chatPedido = $database->customQueryPDO($sqlPedidos);

    $listaTarefas = [];
    foreach ($chatPedido as $chatItem) {
        if (strpos($chatItem['conteudo'], 'tempo trabalhado ')) {
            $aux = explode('tempo trabalhado ', $chatItem['conteudo']);
            $aux = explode(' minutos', $aux[1]);
            $chatItem["minutos"] = (int)$aux[0];
            unset($chatItem["sistema"], $chatItem["conteudo"]);
            $listaTarefas[] = $chatItem;
        }
    }
    unset($chatPedido);
    $i = 0;
    $chatTarefa = $database->customQueryPDO($sqlTarefas);
    foreach ($chatTarefa as $chatItem) {
        $aux = explode('tempo trabalhado ', $chatItem['conteudo']);
        if (isset($aux[1])) {
            $aux = explode(' minutos', $aux[1]);
            $chatItem["minutos"] = (int)$aux[0];
            unset($chatItem["projeto"], $chatItem["sistema"], $chatItem["conteudo"]);
            $listaTarefas[] = $chatItem;
        }
    }
    unset($chatTarefa);

    foreach ($listaUsuario as $chave => $usuario) {
        $listaUsuario[$chave]["tempoTrabalhado"] = 0;
        $listaUsuario[$chave]["numeroTarefas"] = 0;
        $listaUsuario[$chave]["tarefas"] = [];
        foreach ($listaTarefas as $tarefa) {
            if ($usuario["usuarioNerdweb"] == $tarefa["responsavel"]) {
                $listaUsuario[$chave]["tempoTrabalhado"] += $tarefa["minutos"];
                $listaUsuario[$chave]["numeroTarefas"] += 1;
                $listaUsuario[$chave]["tarefas"][] = $tarefa["elemento"] . " ";
                $i++;
            }
        }
    }

    ?>
    <h2>Tarefas do ano <?php echo $_GET["ano"]; ?> e mes <?php echo $_GET["mes"]; ?></h2>
    <table border="1" cellpadding="5">
        <thead>
        <tr>
            <td>Id</td>
            <td>Nome</td>
            <td>Tarefas Concluidas</td>
            <td>Horas Trabalhadas</td>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($listaUsuario as $usuario) { ?>
            <tr>
                <td><?php echo $usuario["usuarioNerdweb"]; ?></td>
                <td><?php echo $usuario['nome']; ?></td>
                <td><?php echo $usuario['numeroTarefas']; ?></td>
                <td><?php echo sprintf("%02d:%02d", floor($usuario['tempoTrabalhado'] / 60), ($usuario['tempoTrabalhado'] % 60)); ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <hr>
    <?php
}

echo "<br><br><a href='index.php'>Voltar para selecao de usuarios/clientes</a>";

