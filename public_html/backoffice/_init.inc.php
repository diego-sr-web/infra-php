<?php
$database = new Database();
$Template = new Template();
$usuario = new AdmUsuario($database);
$BNCliente = new BNCliente($database);
$POPProjeto = new Projeto($database);
$POPElemento = new Elemento($database);
$dadosUsuario = $usuario->getUserDataWithId($_SESSION['adm_usuario']);
require_once __DIR__ . '/includes/is_logged.php';

// Recupera o nome do arquivo atual, verifica se ele está dentro das permissões do usuário e,
// se não estiver, redireciona para a página inicial com uma mensagem de erro
$arquivo = explode('?', $_SERVER['REQUEST_URI']);
$arquivo = $arquivo[0];
$arquivo = explode('/', $arquivo);
$arquivo = $arquivo[count($arquivo) - 1];

