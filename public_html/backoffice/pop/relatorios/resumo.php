<?php
require_once __DIR__ . '/../../../autoloader.php';

function countAnos($arrayDados, $campo) {
    $anos = [];
    foreach ($arrayDados as $data) {
        $tmp = explode("-", $data[$campo]);
        if (isset($anos[$tmp[0]])) {
            $anos[$tmp[0]]++;
        }
        else {
            $anos[$tmp[0]] = 1;
        }
    }
    return $anos;
}

$database = new Database();

$listaUsuarios = $database->ngSelectPrepared('back_AdmUsuario', ["ativo" => 1], "", "nome");
$listaClientes = $database->ngSelectPrepared("back_Cliente", ["ativo" => 1], "", "nomeFantasia");

//
$sqlFacebook = "SELECT * FROM `pop_Projeto` WHERE `projetoTipo`=2";
$sqlPostsFacebook = "SELECT * FROM `pop_Elemento` WHERE `elementoTipo` = 38";

$sqlWebsites = "SELECT * FROM `pop_Projeto` WHERE `projetoTipo`=10";
$sqlPaginasWebsite = "SELECT * FROM `pop_Elemento` WHERE `elementoTipo` = 67 || `elementoTipo` = 72";

$sqlDisplay = "SELECT * FROM `pop_Projeto` WHERE `projetoTipo`=11";

$sqlMarketing = "SELECT * FROM `pop_Projeto` WHERE `projetoTipo`=12";

$sqlGrafico = "SELECT * FROM `pop_Projeto` WHERE `projetoTipo`=13";

$sqlBlog = "SELECT * FROM `pop_Projeto` WHERE `projetoTipo`=14";
$sqlPostsBlog = "SELECT * FROM `pop_Elemento` WHERE `elementoTipo` = 107";

$sqlPedidos = "SELECT * FROM `pop_Elemento` WHERE `elementoTipo` = 59 || `elementoTipo` = 82 || `elementoTipo` = 105";

$listaFacebook = $database->customQueryPDO($sqlFacebook, []);
$listaPostsFacebook = $database->customQueryPDO($sqlPostsFacebook, []);

$listaWebsites = $database->customQueryPDO($sqlWebsites, []);
$listaPaginasWebsites = $database->customQueryPDO($sqlPaginasWebsite, []);

$listaCampanhaDisplay = $database->customQueryPDO($sqlDisplay, []);
$listaMailMarketing = $database->customQueryPDO($sqlMarketing, []);
$listaMaterialGrafico = $database->customQueryPDO($sqlGrafico, []);

$listaBlog = $database->customQueryPDO($sqlBlog, []);
$listaPostBlog = $database->customQueryPDO($sqlPostsBlog, []);

$listaPedidos = $database->customQueryPDO($sqlPedidos, []);


echo "<hr>";
echo "<h2>Dados Gerais</h2>";

echo "Total de campanhas do facebook: " . count($listaFacebook) . "<br>";
echo "Total de posts do facebook: " . count($listaPostsFacebook) . "<br>";

echo "Total de websites: " . count($listaWebsites) . "<br>";
echo "Total de paginas: " . count($listaPaginasWebsites) . "<br>";

echo "Total de Mail Marketing: " . count($listaMailMarketing) . "<br>";
echo "Total de Projetos Graficos: " . count($listaMaterialGrafico) . "<br>";

echo "Total de Campanhas de blog: " . count($listaBlog) . "<br>";
echo "Total de Posts de blog: " . count($listaPostBlog) . "<br>";

echo "Total de Pedidos" . count($listaPedidos) . "<br>";

echo "Funcionarios Ativos: " . count($listaUsuarios) . "<br>";
echo "Clientes Ativos: " . count($listaClientes) . "<br>";

echo "<br><br><hr>";
echo "<h2>Pedidos</h2>";
echo "Numero de Pedidos por Ano:<br>";
$totais = countAnos($listaPedidos, "dataCriacao");
foreach ($totais as $key => $valor) {
    echo $key . ": " . $valor . "<br>";
}


echo "<br><br><hr>";
echo "<h2>Facebook</h2>";
echo "Total de campanhas por Ano:<br>";
$totais = countAnos($listaFacebook, "dataEntrada");
foreach ($totais as $key => $valor) {
    echo $key . ": " . $valor . "<br>";
}
echo "<br>Numero de posts por Ano:<br>";
$totais = countAnos($listaPostsFacebook, "dataCriacao");
foreach ($totais as $key => $valor) {
    echo $key . ": " . $valor . "<br>";
}


echo "<br><br><hr>";
echo "<h2>Websites</h2>";
echo "Total desenvolvido por ano:<br>";
$totais = countAnos($listaWebsites, "dataEntrada");
foreach ($totais as $key => $valor) {
    echo $key . ": " . $valor . "<br>";
}
echo "<br>Numero de paginas:<br>";
$totais = countAnos($listaPaginasWebsites, "dataCriacao");
foreach ($totais as $key => $valor) {
    echo $key . ": " . $valor . "<br>";
}


echo "<br><br><hr>";
echo "<h2>Mail Marketing</h2>";
echo "Total desenvolvido por ano:<br>";
$totais = countAnos($listaMailMarketing, "dataEntrada");
foreach ($totais as $key => $valor) {
    echo $key . ": " . $valor . "<br>";
}


echo "<br><br><hr>";
echo "<h2>Projetos Graficos</h2>";
echo "Total desenvolvido por ano:<br>";
$totais = countAnos($listaMaterialGrafico, "dataEntrada");
foreach ($totais as $key => $valor) {
    echo $key . ": " . $valor . "<br>";
}


echo "<br><br><hr>";
echo "<h2>Blogs</h2>";
echo "Total desenvolvido por ano:<br>";
$totais = countAnos($listaBlog, "dataEntrada");
foreach ($totais as $key => $valor) {
    echo $key . ": " . $valor . "<br>";
}
echo "<br>Numero de Posts:<br>";
$totais = countAnos($listaPostBlog, "dataCriacao");
foreach ($totais as $key => $valor) {
    echo $key . ": " . $valor . "<br>";
}
