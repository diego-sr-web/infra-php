<?php
/**
 * Codifica em JSON a variavel passada monta uma modal de aviso pra erro imprime a modal e termina a execucao do script
 *
 * @param string|array $var
 */
function printDebugAndExit(Template $Template, $var) {
    $dump = json_encode($var, JSON_PRETTY_PRINT);
    $html = $Template->insert("Modal/_header",
        ["titulo" => "Ocorreu um <b>ERRO</b> no carregamento da modal", "acao" => "none"]
    );
    $infoDebug = "
        Copie e Cole o conteudo dessa modal em um novo pedido, abra um pedido para a dev com as seguinte informacoes<br/>
        1) URL completa onde ocorreu o erro<br/>
        2) Qual Botao foi clicado<br/>
        3) Horario que ocorreu o erro<br/>
        4) Anexe ao pedido uma copia do texto abaixo<br/>
    ";
    $html .= $Template->insert("Modal/show-text", ["conteudo" => $infoDebug]);
    $html .= $Template->insert("Modal/show-text", ["conteudo" => $dump]);
    $html .= $Template->insert("Modal/_footer", ["confirmar" => "Ok", "confirmarIcone" => "fa-ok"]);
    echo json_encode(["tipo" => "html", "conteudo" => $html]);
    exit;
}
