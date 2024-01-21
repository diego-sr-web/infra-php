<?php
require_once __DIR__ . '/../../../autoloader.php';

$database = new Database();
$usuario = new AdmUsuario($database);
require_once __DIR__ . '/../../includes/is_logged.php';

$POPProjeto = new Projeto($database);
$POPElemento = new Elemento($database);
$BNCliente = new BNCliente($database);

$sqlUsuario = "
SELECT 
 usuarioNerdweb,
 email,
 nome
FROM 
`back_AdmUsuario` 
WHERE 
1
";

$listaUsuarios = [];
foreach ($database->customQueryPDO($sqlUsuario, []) as $item) {
    $listaUsuarios[$item["usuarioNerdweb"]] = $item["nome"];
}

$sqlStatus = "
SELECT 
* 
FROM 
`pop_ElementoStatus` 
WHERE
1
";
$listaStatus = [];
foreach ($database->customQueryPDO($sqlStatus) as $item) {
    $listaStatus[$item["elementoStatus"]] = $item["nome"];
}

$sqlPedidosDev = "
SELECT 
    tarefas.`elemento` as id,
    tarefas.`dataCriacao` as criacao,
    tarefas.`elementoStatus` as status
FROM 
    `pop_Elemento` as tarefas, 
    `pop_ElementoConteudo` as conteudo 
WHERE 
    `tarefas`.`elemento` = `conteudo`.`elemento`
AND `conteudo`.`chave` = \"Para_Area\"
AND (`conteudo`.`valor` = 2 OR `conteudo`.`valor` = 7)
AND `tarefas`.`projeto`= 13
AND `tarefas`.`dataCriacao` >= ( NOW( ) - INTERVAL 2 MONTH ) ";

$listaPedidos = $database->customQueryPDO($sqlPedidosDev, []);
$listaPedidos = Utils::sksort($listaPedidos, "criacao");
$pedidos = [];
$styleO = "<b>";
$styleC = "</b>";
$cssli = "list-group-item-primary";
$num = 1;


?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Lista de itens Equipe Dev | POP Nerdweb</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">


</head>
<body>

<ol class="breadcrumb">
    <li class="active">Itens na fila</li>
</ol>

<?php

$tipo = isset($_GET['tipo']);

echo $tipo . "<br/>";


echo "idPedido;DataCriacao;cliente;usuario;pedido;produto;status" . "<br>" . PHP_EOL;


echo "<ul class='list-group'>";
foreach ($listaPedidos as $pedido) {
    $tmp = $POPElemento->getElementById($pedido["id"]);
    $quemPediu = $listaUsuarios[$tmp["campos"]["responsavel_de"]];
    $status = $listaStatus[$tmp["elementoStatus"]];


    if ($status != "Finalizado" && $status != "Pedido Arquivado") {

        $linha = '"' . str_ireplace('"', "'", $tmp["elemento"]) . '";'
            . '"' . str_ireplace('"', "'", $tmp["dataCriacao"]) . '";'
            . '"' . str_ireplace('"', "'", $tmp["campos"]["Cliente"]) . '";'
            . '"' . str_ireplace('"', "'", $quemPediu) . '";'
            . '"' . str_ireplace('"', "'", $tmp["campos"]["Nome"]) . '";'
            . '"' . str_ireplace('"', "'", $tmp["campos"]["Produto"]) . '";'
            . '"' . str_ireplace('"', "'", $tmp["campos"]["Produto"]) . '";'
            . '"' . str_ireplace('"', "'", $status) . '";';

        if ($num % 2 == 0) {
            $cssli = "list-group-item-primary";
        }
        else {
            $cssli = "list-group-item-secondary";
        }

        if ($status == "Aguardando Início") {
            $cssli = "list-group-item-success";
        }
        if ($status == "Aguardando Responsável") {
            $cssli = "";
        }

        echo "<li class='list-group-item " . $cssli . "'>" . $linha . "</li>";
        $num++;
    }
}

?>
</ul>

</body>
</html>
