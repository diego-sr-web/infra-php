<?php
require_once __DIR__ . '/../../../autoloader.php';

$database = new Database();
$usuario = new AdmUsuario($database);
$POPProjeto = new Projeto($database);
$POPElemento = new Elemento($database);
$POPChat = new Chat($database);
$BNCliente = new BNCliente($database);

// usar a execução desse arquivo quando for inserido um campo novo, e os elementos não tiverem esse campo
$in_tipo = 94;
$in_nome = 'arquivos';
$in_valor = NULL;

/*
$elementos = $database->ngSelectPrepared('pop_Elemento', ['elementoTipo' => $in_tipo]);

if($elementos) {
	foreach ($elementos as $el) {
		$conteudo = $database->ngSelectPrepared('pop_ElementoConteudo', ['elemento' => $el['elemento'], 'chave' => $in_nome]);

		if(empty($conteudo)) {
			$ok = $database->ngInsertPrepared('pop_ElementoConteudo', ['elemento' => $el['elemento'], 'chave' => $in_nome, 'valor' => $in_valor]);
		}
	}
}
*/
