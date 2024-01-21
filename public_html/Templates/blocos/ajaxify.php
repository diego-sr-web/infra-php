<?php
header('Cache-Control: no-cache');
header('Content-Type: application/json');
$retorno = [
    'tipo'     => 'text',
    'conteudo' => 'Houve algum problema com a requisição.'
];
if (isset($_GET["page"])) {
    $file = __DIR__ . "/".str_ireplace(["../","//"],"/", $_GET["page"] ).".php";
    ob_start();
    if (file_exists($file)) {
        /** @noinspection PhpIncludeInspection */
        require_once $file;
    }
    $conteudo = ob_get_clean();
    $tipo = "html";
    if (isset($_GET['tipo'])) {
        $tipo = $_GET['tipo'];
    }
    $retorno = [
        "tipo"     => $tipo,
        "conteudo" => $conteudo
    ];
}
try {
    echo json_encode($retorno, JSON_THROW_ON_ERROR);
}
catch (JsonException $e) {
    die("chamada invalida");
}
