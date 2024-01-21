<?php
header('Cache-Control: no-cache');
header('Content-Type: application/json');

require_once __DIR__ . '/../../../autoloader.php';

$database = new Database();
$usuario = new AdmUsuario($database);
$POPProjeto = new Projeto($database);
$POPElemento = new Elemento($database);
$POPChat = new Chat($database);
$POPBotao = new Botao($database);


$dadosUsuario = $usuario->getUserDataWithId($_SESSION['adm_usuario']);
$areasUsuario = $usuario->getAreasUsuario($_SESSION['adm_usuario']);

if ($usuario->checkSession() == FALSE) {
    $resposta = ['status' => '', 'tipo' => '', 'conteudo' => ''];
    echo json_encode($resposta);
    exit;
}


/////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////
// Funcoes que montam html, depois dar um geito de organizar e generalizar melhor elas//
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////

function monta_html_referencias($referencias) {
    $htmlReferencias = '';
    foreach ($referencias as $key => $campo) {
        if (strpos($key, 'referencia')) {
            $htmlReferencias .= '
<div class="col-sm-6 item-info">
	<div class="item-info-box">
		<h4>Referencia</h4><a class="open-lightbox" href="' . $campo . '" target="_blank"><img src="' . $campo . '" style="border: 1px solid #E0E8E8;"></a>
	</div>
</div>
';
        }
    }
    return $htmlReferencias;
}


function monta_html_material_extra($material_extra) {
    $htmlMaterial = "";
    foreach ($material_extra as $key => $campo) {
        if (strpos($key, 'extra')) {
            $htmlMaterial .= '
<div class="col-sm-6 item-info">
	<div class="item-info-box">
		<h4>Material</h4>
			<a class="open-lightbox" href="' . $campo . '" target="_blank"><img src="' . $campo . '" style="border: 1px solid #E0E8E8;"></a>
	</div>
</div>
';
        }
    }
    return $htmlMaterial;
}


// Precisa organizar melhor essas funcoes
/**
 * @param array $lista_prioridades
 * @param       $selecionado
 *
 * @return string
 */
function html_prioridade($lista_prioridades, $selecionado = NULL) {
    $prioridade = $selecionado['campos']['prioridade'] ?? $selecionado;
    $radioPrioridade = "";
    foreach ($lista_prioridades as $auxPrioridade) {
        $checked = '';
        if (($auxPrioridade['prioridade'] == $prioridade)) {
            $checked = 'checked';
        }
        $radioPrioridade .= '<div class="radio col-xs-3">
														<label style="color: ' . $auxPrioridade['cor'] . '; text-transform:uppercase;">
															<input type="radio" name="prioridade" class="" value="' . $auxPrioridade['prioridade'] . '" ' . $checked . ' />
															<b>' . $auxPrioridade['nome'] . '</b>
														</label>
													</div>';
    }
    return $radioPrioridade;
}

/**
 * @param array       $lista_usuario
 * @param null|string $selecionado
 *
 * @return string
 */
function html_usuario($lista_usuario, $selecionado = NULL) {
    $checked = "";
    if ($selecionado == NULL) {
        $checked = "checked='checked'";
    }
    $radioResponsavel = '<div class="radio col-xs-2">
													<label>
														<input type="radio" name="responsavel" class="" value="" ' . $checked . '/>
														<img class="avatar-responsavel" src="/backoffice/pop/uploads/projeto/default.jpg" title="Nenhum">
													</label>
												</div>';

    foreach ($lista_usuario as $auxUsuario) {
        $checked = ($auxUsuario['usuarioNerdweb'] == $selecionado) ? 'checked' : '';
        $radioResponsavel .= '<div class="radio col-xs-2">
															<label>
																<input type="radio" name="responsavel" class="" value="' . $auxUsuario['usuarioNerdweb'] . '" ' . $checked . ' />
																<img class="avatar-responsavel" src="' . $auxUsuario['imagem'] . '" title="' . $auxUsuario['nome'] . '">
															</label>
														</div>';
    }
    return $radioResponsavel;
}

/**
 * @param array    $chatElemento
 * @param Database $database
 *
 * @return string
 */
function html_historico_elemento($chatElemento, $database) {
    $htmlHistorico = '';

    foreach ($chatElemento as $itemChat) {
        $var = new responsavel('primeiro-msg', $itemChat['responsavel'], $database);
        $htmlHistorico .= '
										<div class="item">' . $var->retornaHtmlExibicao() . '<p class="message">
												<a href="#" class="name">
													<small title="' . date('d/m/Y - H:i:s', strtotime($itemChat['data'])) . '" class="text-muted pull-right">
														<i class="fa fa-clock-o"></i> ' . Utils::printDate(date('Y-m-d H:i:s', strtotime($itemChat['data']))) . '</small>' . $var->retornaNomeExibicao() . '</a>' . $itemChat['conteudo'] . '<br>
											</p>
										</div>';
    }
    if ($htmlHistorico == '') {
        $htmlHistorico .= '<p style="text-align: center;">Nenhum comentário</p>';
    }
    return $htmlHistorico;
}


/////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////
// Funcoes que montam html, depois dar um geito de organizar e generalizar melhor elas//
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////


/**
 * Codifica em JSON a variavel passada monta uma modal de aviso pra erro imprime a modal e termina a execucao do script
 *
 * @param string|array $var
 */
function printDebugAndExit($var) {
    $arquivo = "./v2/dump-debug.html";
    $dump = json_encode($var);
    $json = formata_retorno($arquivo, ["json" => $dump]);
    echo json_encode($json);
    exit;
}

/**
 * Carrega o conteudo de um arquivo html em uma string, ou da require em um arquivo php guardando o resultado em uma string
 * Retorna a string ou imprime uma modal de erro caso o arquivo nao exista ou seja um diretorio, a variavel args pode ser usada
 * para passar informacoes para o script php q vai ser incluido, como por exemplo $args["section"] = TRUE <- e fazer um teste sobre essa variavel no arquivo
 * php, assim vc pode ter varias modais no mesmo arquivo, se preocupando somente de passar os parametros certos para imprimir elas
 *
 * @param string $arquivo
 * @param array  $args
 *
 * @return string
 */
function template_get_contents($arquivo, $args = []) {
    $string = "";
    if (file_exists($arquivo) && !is_dir($arquivo)) {
        $ext = explode(".", $arquivo);
        $ext = $ext[count($ext) - 1];
        if ($ext == "html") {
            $string = file_get_contents($arquivo);
        }
        elseif ($ext == "php") {
            foreach ($args as $key => $value) {
                $options[$key] = $value;
            }
            ob_start();
            require_once $arquivo;
            $string = ob_get_clean();
        }
        else {
            // Termina a execucao pq nao faz sentido carregar alguma coisa que nao tem suporte no codigo
            printDebugAndExit("Extencao: <b>" . $ext . "<b> nao suportada");
        }
    }
    else {
        // Termina a execucao pq nao faz sentido carregar alguma coisa que nao tem suporte no codigo
        printDebugAndExit("Arquivo: <b>" . $arquivo . "<b> nao encontrado");
    }
    return $string;
}


/**
 * Carrega o html da modal e formata os dados de acordo com o que precisa ser exibido, a variavel $args pode
 * ser usada para passar informacoes extra nos arquivos php incluidos a variavel $campos_valores eh um array
 * na forma ["nome_do_campo" => valor_do_campo], e quebrado em 2 arrays um de campos e um de valores para fazerem
 * a substituicao de trechos ( definido na variavel campos) da string
 * resultante do arquivo carregado
 *
 * @param string $arquivo
 * @param array  $campos_valores
 * @param array  $args
 *
 * @return mixed
 */
function formata_retorno($arquivo, $campos_valores, $args = ["tipo" => "html"]) {
    $string = template_get_contents($arquivo, $args);
    $dados_modal["tipo"] = "html";
    if (isset($args["tipo"]) && $args["tipo"] != "") {
        $dados_modal["tipo"] = $args["tipo"];
    }
    $campos = [];
    $valores = [];
    // Com essa aproximacao agente pode simplificar a entrada e facilita a leitura
    // quando tem mtos campos pra se substituir na entrada
    foreach ($campos_valores as $key => $value) {
        $campos[] = $key;
        $valores[] = $value;
    }
    // alguns valores padroes pros botoes pra nao ser necessario passar em todas
    // as execucoes, tenta preencher alguns valores padroes sanos pro nome dos botoes
    // nao faz nada caso $campos ou $valores seja string.
    if (is_array($campos) && is_array($valores)) {
        if (!in_array("[BOTAO_OK_TEXT]", $campos)) {
            $campos[] = "[BOTAO_OK_TEXT]";
            $valores[] = "OK";
        }
        if (!in_array("[BOTAO_CANCELAR_TEXT]", $campos)) {
            $campos[] = "[BOTAO_CANCELAR_TEXT]";
            $valores[] = "Cancelar";
        }
        if (!in_array("[BOTAO_OK_ICON]", $campos)) {
            $campos[] = "[BOTAO_OK_ICON]";
            $valores[] = "fa-check";
        }
        if (!in_array("[BOTAO_CANCELAR_ICON]", $campos)) {
            $campos[] = "[BOTAO_CANCELAR_ICON]";
            $valores[] = "fa-times";
        }
    }

    $dados_modal["conteudo"] = str_ireplace($campos, $valores, $string);
    return $dados_modal;
}

function modal_botao($idElemento, $dadosElemento, $idBotao, $dadosBotao) {
    if ($dadosElemento !== []) {
        // Aqui comeca o processamento de verdade dos botoes sobre elementos
        $acao = $dadosBotao["acao"];
        $arquivo = __DIR__ . "/v2/";
        $dados_modal = [];
        switch ($acao) {
            ///////////////////
            //      1        //
            //     grupo     //
            //confirmacao.php//
            ///////////////////
            case "aprovar-elemento":
                $arquivo .= "confirmacao.php";
                $titulo = "Aprovar/Finalizar Item";
                $texto = "Tem certeza que deseja <b>FINALIZAR/APROVAR</b> o item?";
                $acao = "aprovar-elemento";
                $dados_modal = formata_retorno($arquivo, ["[TITULO-1]" => $titulo, "[TEXTO-1]" => $texto, "[INPUT-1]" => $acao, "[ELEMENTO_ID]" => $idElemento]);
                break;

            case "reprovar-elemento":
                $arquivo .= "confirmacao.php";
                $titulo = "Reprovar Item";
                $texto = "Tem certeza que deseja <b>REPROVAR</b> o item?";
                $acao = "reprovar-elemento";
                $extra = ["show_observacao" => TRUE];
                $dados_modal = formata_retorno($arquivo, ["[TITULO-1]" => $titulo, "[TEXTO-1]" => $texto, "[INPUT-1]" => $acao, "[ELEMENTO_ID]" => $idElemento], $extra);
                break;

            case "apagar-elemento";
                $arquivo .= "confirmacao.php";
                $titulo = "Apagar Item";
                $texto = "Tem certeza que deseja <b>APAGAR</b> o item?";
                $acao = "apagar-elemento";
                $dados_modal = formata_retorno($arquivo, ["[TITULO-1]" => $titulo, "[TEXTO-1]" => $texto, "[INPUT-1]" => $acao, "[ELEMENTO_ID]" => $idElemento]);
                break;



            //////////////////////////////
            //            2             //
            //           grupo          //
            //multiple-images-upload.php//
            //////////////////////////////
            case 'ver-referencias':
                $arquivo .= "multiple-images-upload.php";
                $titulo = "Lista de Referências";
                $label = "*Briefing";
                $name = "arquivos[]";
                $acao = "adicionar-referencias";
                $texto = "adicionar mais referências";
                $extra = ["show_conteudo" => TRUE, "show_image_preview" => FALSE];
                $htmlReferencias = '';
                if (isset($dadosElemento['campos']) && $dadosElemento['campos']) {
                    $htmlReferencias .= monta_html_referencias($dadosElemento["campos"]);
                }
                $dados_modal = formata_retorno($arquivo,
                    ['[TITULO-1]'    => $titulo, '[TEXTO-1]' => $texto, '[LABEL-2]' => $label, '[INPUT-1]' => $acao, '[INPUT-2]' => $name,
                     '[ELEMENTO_ID]' => $idElemento, '[ELEMENTO_CONTEUDO]' => $htmlReferencias], $extra);
                break;


            case 'adicionar-material-extra':
                $arquivo .= "multiple-images-upload.php";
                $titulo = "Material Extra";
                $label = "*Arquivos";
                $name = "arquivos[]";
                $acao = "adicionar-material-extra";
                $texto = "Adicionar material extra";
                $extra = ["show_conteudo" => TRUE, "show_image_preview" => FALSE];
                $htmlReferencias = '';
                if (isset($dadosElemento['campos']) && $dadosElemento['campos']) {
                    $htmlReferencias .= monta_html_referencias($dadosElemento["campos"]);
                }
                $dados_modal = formata_retorno($arquivo,
                    ['[TITULO-1]'    => $titulo, '[TEXTO-1]' => $texto, '[LABEL-2]' => $label, '[INPUT-1]' => $acao, '[INPUT-2]' => $name,
                     '[ELEMENTO_ID]' => $idElemento, '[ELEMENTO_CONTEUDO]' => $htmlReferencias], $extra);
                break;



            //////////////////////
            //        3.0       //
            //       grupo      //
            //adicionar-item.php//
            //    Tipo Editor   //
            //////////////////////
            case 'adicionar-briefing':
                $arquivo .= "adicionar-item.php";
                $titulo = "Adicionar Briefing";
                $label = "*Briefing";
                $name = "texto";
                $acao = "adicionar-briefing";
                $conteudo = "";
                $extra = ["show_editor" => TRUE];
                if (isset($dadosElemento["campos"]["briefing"])) {
                    $conteudo = $dadosElemento["campos"]["briefing"];
                }
                $dados_modal = formata_retorno($arquivo, ["[TITULO-1]"     => $titulo, "[LABEL-EDITOR]" => $label, "[INPUT-1]" => $name,
                                                          "[INPUT-EDITOR]" => $acao, "[ELEMENTO_ID]" => $idElemento, "[CONTEUDO_EDITOR]" => $conteudo], $extra);
                break;

            case 'adicionar-lista-de-conteudo':
                $arquivo .= "adicionar-item.php";
                $titulo = "Lista de Conteúdo";
                $label = "*Conteudo";
                $name = "texto";
                $acao = "adicionar-listagem-conteudo";
                $conteudo = "";
                $extra = ["show_editor" => TRUE];
                if (isset($dadosElemento["campos"]["conteudo"])) {
                    $conteudo = $dadosElemento["campos"]["conteudo"];
                }
                $dados_modal = formata_retorno($arquivo, ["[TITULO-1]" => $titulo, "[LABEL-EDITOR]" => $label, "[INPUT-1]" => $name, "[INPUT-EDITOR]" => $acao, "[ELEMENTO_ID]" => $idElemento, "[CONTEUDO_EDITOR]" => $conteudo], $extra);
                break;



            //////////////////////
            //        3.1       //
            //       grupo      //
            //adicionar-item.php//
            //  Tipo TextArea   //
            //////////////////////
            case 'adicionar-texto':
                $arquivo .= "adicionar-item.php";
                $titulo = ">Adicionar Texto";
                $label = "*Textos";
                $name = "texto";
                $acao = "adicionar-texto";
                $conteudo = "";
                $extra = ["show_textarea" => TRUE];
                if (isset($dadosElemento["campos"]["Texto"])) {
                    $conteudo = $dadosElemento["campos"]["Texto"];
                }
                $dados_modal = formata_retorno($arquivo, ["[TITULO-1]"     => $titulo, "[LABEL-TEXTAREA]" => $label, "[INPUT-1]" => $name,
                                                          "[INPUT-EDITOR]" => $acao, "[ELEMENTO_ID]" => $idElemento, "[CONTEUDO_TEXTAREA]" => $conteudo], $extra);
                break;

            case 'adicionar-dominios':
                $arquivo .= "adicionar-item.php";
                $titulo = "Adicionar Dominios";
                $label = "*Lista de Domínios";
                $name = "dominios";
                $acao = "adicionar-dominios";
                $conteudo = "";
                $extra = ["show_textarea" => TRUE];
                if (isset($dadosElemento["campos"]["dominio"])) {
                    $conteudo = $dadosElemento["campos"]["dominio"];
                }
                $dados_modal = formata_retorno($arquivo, ["[TITULO-1]"     => $titulo, "[LABEL-TEXTAREA]" => $label, "[INPUT-1]" => $name,
                                                          "[INPUT-EDITOR]" => $acao, "[ELEMENTO_ID]" => $idElemento, "[CONTEUDO_TEXTAREA]" => $conteudo], $extra);
                break;

            case 'adicionar-registro-dominio':
                $arquivo .= "adicionar-item.php";
                $titulo = "Registro de Domínio";
                $label = "*Registro de Domínio";
                $name = "registro";
                $acao = "adicionar-registro-dominio";
                $conteudo = "";
                $extra = ["show_textarea" => TRUE];
                if (isset($dadosElemento["campos"]["dominio"])) {
                    $conteudo = $dadosElemento["campos"]["dominio"];
                }
                $dados_modal = formata_retorno($arquivo, ["[TITULO-1]" => $titulo, "[LABEL-TEXTAREA]" => $label, "[INPUT-1]" => $name, "[INPUT-EDITOR]" => $acao, "[ELEMENTO_ID]" => $idElemento, "[CONTEUDO_TEXTAREA]" => $conteudo], $extra);
                break;

            case 'adicionar-listagem-emails':
                $arquivo .= "adicionar-item.php";
                $titulo = "Adicionar Listagem de Emails";
                $label = "*Lista de Emails";
                $name = "emails";
                $acao = "adicionar-listagem-emails";
                $conteudo = "";
                $extra = ["show_textarea" => TRUE];
                if (isset($dadosElemento["campos"]["emails"])) {
                    $conteudo = $dadosElemento["campos"]["emails"];
                }
                $dados_modal = formata_retorno($arquivo, ["[TITULO-1]" => $titulo, "[LABEL-TEXTAREA]" => $label, "[INPUT-1]" => $name, "[INPUT-EDITOR]" => $acao, "[ELEMENTO_ID]" => $idElemento, "[CONTEUDO_TEXTAREA]" => $conteudo], $extra);
                break;

            case 'adicionar-lista-modulos':
                $arquivo .= "adicionar-item.php";
                $titulo = "Adicionar Lista de Módulos";
                $label = "*Descrição dos Módulos";
                $name = "texto";
                $acao = "adicionar-lista-modulos";
                $conteudo = "";
                $extra = ["show_textarea" => TRUE];
                if (isset($dadosElemento["campos"]["lista"])) {
                    $conteudo = $dadosElemento["campos"]["lista"];
                }
                $dados_modal = formata_retorno($arquivo, ["[TITULO-1]" => $titulo, "[LABEL-TEXTAREA]" => $label, "[INPUT-1]" => $name, "[INPUT-EDITOR]" => $acao, "[ELEMENTO_ID]" => $idElemento, "[CONTEUDO_TEXTAREA]" => $conteudo], $extra);
                break;



            //////////////////////
            //        3.2        //
            //       grupo      //
            //adicionar-item.php//
            //    Tipo Imagem   //
            //////////////////////
            case 'adicionar-wireframe':
                $arquivo .= "adicionar-item.php";
                $titulo = "Adicionar wireframe";
                $label = "*Wireframe";
                $name = "imagem";
                $acao = "adicionar-wireframe";
                $conteudo = "";
                $extra = ["show_arquivo" => TRUE, "show_preview_arquivo" => FALSE];
                $dados_modal = formata_retorno($arquivo, ["[TITULO-1]"      => $titulo, "[LABEL-ARQUIVO]" => $label, "[INPUT-1]" => $name,
                                                          "[INPUT-ARQUIVO]" => $acao, "[ELEMENTO_ID]" => $idElemento, "[PATH_ARQUIVO]" => $conteudo], $extra);
                break;

            case 'adicionar-layout':
                $arquivo .= "adicionar-item.php";
                $titulo = "Adicionar Layout";
                $label = "*Layout";
                $name = "imagem";
                $acao = "adicionar-layout";
                $conteudo = "";
                $extra = ["show_arquivo" => TRUE, "show_preview_arquivo" => FALSE];
                $dados_modal = formata_retorno($arquivo, ["[TITULO-1]"      => $titulo, "[LABEL-ARQUIVO]" => $label, "[INPUT-1]" => $name,
                                                          "[INPUT-ARQUIVO]" => $acao, "[ELEMENTO_ID]" => $idElemento, "[PATH_ARQUIVO]" => $conteudo], $extra);
                break;

            case 'adicionar-arquivo':
                $arquivo .= "adicionar-item.php";
                $titulo = "Adicionar arquivo ao pedido";
                $label = "*Arquivo";
                $name = "arquivo";
                $acao = "adicionar-arquivo";
                $observacao = "observacao";
                $conteudo = "";
                $extra = ["show_arquivo" => TRUE, "show_preview_arquivo" => FALSE, "show_observacao" => TRUE];
                $dados_modal = formata_retorno($arquivo, ["[TITULO-1]"      => $titulo, "[LABEL-ARQUIVO]" => $label, "[INPUT-1]" => $name,
                                                          "[INPUT-ARQUIVO]" => $acao, "[ELEMENTO_ID]" => $idElemento, "[PATH_ARQUIVO]" => $conteudo, "[INPUT-OBSERVACAO]" => $observacao], $extra);
                break;

            case 'adicionar-imagem':
                $arquivo .= "adicionar-item.php";
                $titulo = "Adicionar Imagem";
                $label = "*Imagem";
                $name = "imagem";
                $acao = "adicionar-imagem";
                $conteudo = "";
                $extra = ["show_arquivo" => TRUE, "show_preview_arquivo" => FALSE];
                if (isset($dadosElemento["campos"]["briefing"])) {
                    $conteudo = $dadosElemento["campos"]["briefing"];
                }
                $dados_modal = formata_retorno($arquivo, ["[TITULO-1]"    => $titulo, "[LABEL-2]" => $label, "[INPUT-1]" => $name, "[INPUT-2]" => $acao,
                                                          "[ELEMENTO_ID]" => $idElemento, "[PATH_ARQUIVO]" => $conteudo], $extra);
                break;


            //////////////////////
            //        3.2       //
            //       grupo      //
            //adicionar-item.php//
            //    Tipo Text     //
            //////////////////////


            /////////////////////
            //Nao implementados//
            /////////////////////
            case 'adicionar-texto-post':
            case 'adicionar-diagramacao':
            case 'adicionar-estrutura-aws':
            case 'adicionar-estrutura-nerdpress':
            case 'adicionar-identidade-visual':
            case 'adicionar-arquivos-recortados':
            default:
                $dados_modal["tipo"] = "erro";
                $dados_modal["conteudo"] = "Tipo de modal ainda nao implementada {" . $acao . "}";
                break;
        }
    }
    else {
        // Nao encontrou o o elemento
        $dados_modal["tipo"] = "erro";
        $dados_modal["conteudo"] = "Elemento nao encontrado";
    }
    return $dados_modal;
}

function modal_projeto($idProjeto, $dadosProjeto, $idBotao, $dadosBotao) {
    if ($dadosProjeto !== []) {
        $dados_modal["tipo"] = "erro";
        $dados_modal["conteudo"] = "Tipo projeto ainda nao eh reconhecido";
    }
    else {
        $dados_modal["tipo"] = "erro";
        $dados_modal["conteudo"] = "Projeto nao encontrado";
    }
    return $dados_modal;
}

require_once __DIR__ . '/../../includes/is_logged.php';
/** @var array $dados_modal */
$dados_modal = [];
// Comeca a caminhar pelas possibilidades de chamadas
if (isset($_POST["botao"])) {
    $idBotao = $_POST["botao"];
    $idElemento = "";
    $idProjeto = "";
    $dadosBotao = $POPBotao->getButtonById($idBotao);
    if ($dadosBotao !== []) {
        if (isset($_POST["elemento"])) {
            $idElemento = $_POST["elemento"];
        }
        if (isset($_POST["projeto"])) {
            $idProjeto = $_POST["projeto"];
        }
        if ($idElemento !== "" && $idProjeto === "") {

            // Processamento de botao tipo ELEMENTO
            $dadosElemento = $POPElemento->getElementById($idElemento);
            $dados_modal = modal_botao($idElemento, $dadosElemento, $idBotao, $dadosBotao);
            // Fim processamento botao tipo ELEMENTO

        }
        elseif ($idElemento === "" && $idProjeto !== "") {

            // Processamento de botao tipo PROJETO
            $dadosProjeto = $POPProjeto->getProjectById($idProjeto);
            $dados_modal = modal_projeto($idProjeto, $dadosProjeto, $idBotao, $dadosBotao);
            // Fim processamento botao tipo PROJETO

        }
        /** @noinspection NotOptimalIfConditionsInspection */
        elseif ($idElemento !== "" && $idProjeto !== "") {
            // Avisa que um botao nao pode ter projeto e elemento ao mesmo tempo caso seja
            // necessario passar o projeto pro elemento os dados do elemento tem essa informacao
            $dados_modal["tipo"] = "erro";
            $dados_modal["conteudo"] = "Um botao NAO pode ser de elemento e projeto ao mesmo tempo";

        }
        else {
            // Provavelmente area de erro, nao faz sentido ter um botao tao generico
            $dados_modal["tipo"] = "erro";
            $dados_modal["conteudo"] = "Um botao DEVE ter um elemento ou projeto pelomenos";

        }
    }
    else {
        $dados_modal["tipo"] = "erro";
        $dados_modal["conteudo"] = "Id do botao nao reconhecida";
    }
}
else {
    $dados_modal["tipo"] = "erro";
    $dados_modal["conteudo"] = "Verifique o POST, variaveis nao enviadas corretamente";
}

if ($dados_modal === []) {
    // Se nao tiver encontrado a funcao do botao avisa isso
    $dados_modal["tipo"] = "erro";
    $dados_modal["conteudo"] = "Chamada de ajax nao reconhecida`";
}

// Echo o html da modal e encerra o script, caso o tipo de retorno seja HTML
if (isset($dados_modal["tipo"]) && $dados_modal["tipo"] == "html") {
    echo json_encode($dados_modal);
}
else {
    // Tenta Facilitar o debug criando uma tela com dump das informacoes passadas pro script
    printDebugAndExit(["POST" => $_POST, "CONTEUDO" => $dados_modal]);
}
