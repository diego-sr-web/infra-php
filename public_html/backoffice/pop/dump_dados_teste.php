<?php
require_once __DIR__ . "/../../autoloader.php";

$database = new Database();
$usuario = new AdmUsuario($database);
$POPProjeto = new Projeto($database);
$POPElemento = new Elemento($database);
$BNCliente = new BNCliente($database);


$auxTiposProjeto2 = $POPProjeto->getProjectTypeList(['escondido'], [0], 'nome ASC');

$listaClientes = $BNCliente->listAll("", "nomeFantasia");
$areasId = [17, 23];
$todosElementos = $POPElemento->getAllElementsFiltered(TRUE, $areasId, $_SESSION["adm_usuario"]);

var_dump($auxTiposProjeto2[0]);

var_dump($listaClientes[50]);

var_dump($todosElementos[5]);

