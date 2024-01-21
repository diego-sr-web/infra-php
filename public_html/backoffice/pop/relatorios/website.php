<?php
header("Content-type: application/csv");
header("Content-Disposition: attachment; filename=projeto-website.csv");
header("Pragma: no-cache");
header("Expires: 0");

require_once __DIR__ . '/../../../autoloader.php';

$database = new Database();
$usuario = new AdmUsuario($database);
$POPProjeto = new Projeto($database);
$POPElemento = new Elemento($database);

$BNCliente = new BNCliente($database);

$sqlWebsites = "SELECT * FROM `pop_Projeto` WHERE `projetoTipo` = 10 AND `finalizado` = 0 ";
$listaWebsites = $database->customQueryPDO($sqlWebsites, []);


echo "idWebsite;dataCriacao;prazoEstimado;website,cliente" . "<br>" . PHP_EOL;
foreach ($listaWebsites as $website) {
    $cliente = $BNCliente->getDataWithId($website["cliente"]);
    $cliente = $cliente["nomeFantasia"];
    $linha = '"' . str_ireplace('"', "'", $website["projeto"]) . '";'
        . '"' . str_ireplace('"', "'", $website["dataEntrada"]) . '";'
        . '"' . str_ireplace('"', "'", $website["prazo"]) . '";'
        . '"' . str_ireplace('"', "'", $website["nome"]) . '";'
        . '"' . str_ireplace('"', "'", $cliente) . '";'
        . "<br>" . PHP_EOL;
    echo $linha;
}
