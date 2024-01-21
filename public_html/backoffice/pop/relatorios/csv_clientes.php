<?php
require_once __DIR__ . '/../../../autoloader.php';


$database = new Database();
$usuario = new AdmUsuario($database);
require_once __DIR__ . '/../../includes/is_logged.php';

$POPProjeto = new Projeto($database);
$POPElemento = new Elemento($database);
$BNCliente = new BNCliente($database);

if (isset($_GET["client_id"]) && $_GET["client_id"] != "") {
    $clientId = $_GET["client_id"];
    $clientData = $BNCliente->getDataWithId($clientId);
    if (isset($_GET["download"])) {
        $fileName = urlencode("relatorio_" . $clientData["nomeFantasia"] . "_" . time() . ".csv");
        header("Content-type: application/csv");
        header("Pragma: no-cache");
        header("Expires: 0");
        header("Content-Disposition: attachment; filename=" . $fileName);
    }

    $sqlPedidos = "SELECT
  ec.elemento,
  e.projeto as projeto,
  cli.cliente clientId,
  ec.valor cliente,
  ech.responsavel,
  ech.sistema,
  ech.conteudo,
  ech.data,
  ech.isUsed
FROM
  pop_ElementoChat ech,
  pop_Elemento e,
  pop_ElementoConteudo ec,
  back_Cliente cli
WHERE
  ec.elemento = ech.elemento
  AND e.elemento = ec.elemento
  AND ec.chave = \"Cliente\"
  AND ec.valor = cli.nomeFantasia
  AND ech.conteudo LIKE \"Tarefa -%tempo trabalhado%\"
  AND ech.isUsed = 1
  AND cli.cliente = ?
ORDER BY ech.data DESC
";

    $sqlParams = [$clientId];
    $chatTarefa = $database->customQueryPDO($sqlPedidos, $sqlParams);
    $listaItens = [];


    foreach ($chatTarefa as $item) {
        $tmp = [];
        $elemento = $POPElemento->getElementById($item["elemento"]);
        $responsavel = $usuario->getUserDataWithId($item["responsavel"]);
        $tmp["id"] = $item["elemento"];
        $tmp["data"] = $elemento["dataCriacao"];
        $tmp["titulo"] = str_replace(["Aprovar ", "Alterar "], "", $elemento["campos"]["Nome"]);
        $tmp["responsavel"] = $responsavel["nome"];
        $tmp["tempo"] = (int)$aux[0];
        $tmp["descricao"] = $item["projeto"];
        // Conta os minutos usados
        $aux = explode('tempo trabalhado ', $item['conteudo']);
        $aux = explode(' minutos', $aux[1]);


        $listaItens[] = $tmp;
    }
    if (isset($_GET["download"])) {
        $out = fopen('php://output', 'w');
        $fields = ["id", "data", "titulo", "responsavel", "tempo", "descricao"];
        fputcsv($out, $fields, ";", '"');
        foreach ($listaItens as $item) {
            $responsavel = $usuario->getUserDataWithId($item["responsavel"]);
            $item["responsavel"] = $responsavel["nome"];
            fputcsv($out, $item, ";");
        }
        fclose($out);
    }
    else { ?>
        <html>
        <body>

        <h2>Cliente : <?php echo $clientData["nomeFantasia"]; ?> <a
                    href="<?php echo $_SERVER["REQUEST_URI"]; ?>&download">CSV</a></h2>
        <table border="1" cellpadding="5">
            <thead>
            <tr>
                <td>Id</td>
                <td>Data</td>
                <td>Titulo</td>
                <td>Responsavel</td>
                <td>Tempo</td>
                <td>Descricao</td>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($listaItens as $item) { ?>
                <tr>
                    <td><?php echo $item["id"]; ?></td>
                    <td><?php echo $item['data']; ?></td>
                    <td><?php echo $item['titulo']; ?></td>
                    <td><?php echo $item['responsavel']; ?></td>
                    <td><?php echo $item['tempo']; ?></td>
                    <td><?php echo $item['descricao']; ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        </body>
        </html>
    <?php }
}
else {

    echo "<br><br><a href='index.php'>Voltar para selecao de usuarios/clientes</a>";
}
