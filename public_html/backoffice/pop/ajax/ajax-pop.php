<?php
header('Cache-Control: no-cache');
header('Content-Type: application/json');

require_once __DIR__ . '/../../../autoloader.php';

$database = new Database();
$usuario = new AdmUsuario($database);
$POPProjeto = new Projeto($database);
$POPElemento = new Elemento($database);
$POPChat = new Chat($database);

$BNCliente = new BNCliente($database);

if ($usuario->checkSession() == FALSE) {
    $resposta = ['status' => '', 'tipo' => '', 'conteudo' => ''];
    echo json_encode($resposta);
    exit;
}

function printDebug($var) {
    echo json_encode($var);
    exit;
}

/**
 * @param string $arquivo
 * @param array  $campos
 * @param array  $valores
 * @param string $tipo
 *
 * @return array
 */
function formata_retorno($arquivo, $campos, $valores, $tipo = "html") {
    $string = file_get_contents($arquivo);
    $retorno["tipo"] = $tipo;
    $retorno["conteudo"] = str_ireplace($campos, $valores, $string);

    return $retorno;
}


/**
 * @param array $lista_prioridades
 * @param       $selecionado
 *
 * @return string
 */
function html_prioridade($lista_prioridades, $selecionado = NULL) {
    $prioridade = $selecionado;
    if (isset($selecionado['campos']['prioridade'])) {
        $prioridade = $selecionado['campos']['prioridade'];
    }
    $radioPrioridade = "";
    foreach ($lista_prioridades as $auxPrioridade) {
        $checked = ($auxPrioridade['prioridade'] == $prioridade) ? 'checked' : '';
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
 * @param string      $nomeCampo
 *
 * @return string
 */
function html_usuario($lista_usuario, $selecionado = NULL, $nomeCampo = "responsavel") {
    $checked = "";
    if ($selecionado == NULL) {
        $checked = "checked='checked'";
    }
    $radioResponsavel = '<div class="radio col-xs-2">
													<label>
														<input type="radio" name="' . $nomeCampo . '" class="" value="" ' . $checked . '/>
														<img class="avatar-responsavel" src="/backoffice/pop/uploads/projeto/default.jpg" title="Nenhum">
													</label>
												</div>';

    foreach ($lista_usuario as $auxUsuario) {
        $checked = ($auxUsuario['usuarioNerdweb'] == $selecionado) ? 'checked' : '';
        $radioResponsavel .= '<div class="radio col-xs-2">
															<label>
																<input type="radio" name="' . $nomeCampo . '" class="" value="' . $auxUsuario['usuarioNerdweb'] . '" ' . $checked . ' />
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
        $conteudo = Utils::insert_href(nl2br(strip_tags($itemChat['conteudo'])));
        $htmlHistorico .= '
                                    <div class="item">' . $var->retornaHtmlExibicao() . '<p class="message">
                                            <a href="#" class="name">
                                                <small title="' . date('d/m/Y - H:i:s', strtotime($itemChat['data'])) . '" class="text-muted pull-right">
                                                    <i class="fa fa-clock-o"></i> ' . Utils::printDate(date('Y-m-d H:i:s', strtotime($itemChat['data']))) . '</small>' . $var->retornaNomeExibicao() . '</a>' . $conteudo . '<br>
                                        </p>
                                    </div>';
    }
    if ($htmlHistorico == '') {
        $htmlHistorico .= '<p style="text-align: center;">Nenhum comentário</p>';
    }
    return $htmlHistorico;
}


/**
 * @param array  $array
 * @param string $campo
 *
 * @return string
 */
function getIfExists($array, $campo) {
    if (isset($array[$campo])) {
        return $array[$campo];
    }
    return "";
}


require_once __DIR__ . '/../../includes/is_logged.php';

if (isset($_POST['secao'])) {
    $dtAtual = date('Y-m-d H:i:s');

    $secao = $_POST['secao'] ?? NULL;
    $acao = $_POST['tipo'] ?? NULL;
    $form = $_POST['form'] ?? NULL;

    $resposta = ['status' => '', 'tipo' => '', 'conteudo' => ''];

    switch ($secao) {
        case 'tarefa':
            if ($acao) {
                // Continuando o codigo normal
                $arquivo = "modal/$secao/$acao.html";

                if (isset($_POST['tarefa'])) {
                    $infoTarefa = $POPElemento->getElementById($_POST['tarefa']);
                    $tipoTarefa = $POPElemento->getElementTypeById($infoTarefa['elementoTipo']);

                    switch ($acao) {
                        case 'assumir-tarefa':
                        case 'finalizar-tarefa':
                        case 'remover-tarefa':
                        case 'arquivar-pedido':
                            $infoProjeto = $POPProjeto->getProjectById($infoTarefa['projeto']);
                            $tipoProjeto = $POPProjeto->getProjectTypeByProjectId($infoProjeto['projeto']);

                            if ($infoTarefa['projeto'] == 13) {
                                $nome = $infoTarefa["campos"]['Nome'];
                            }
                            else {
                                $nome = $tipoTarefa['nome'];
                            }

                            $resposta = formata_retorno($arquivo,
                                ['[ID_TAREFA]', '[NOME_TAREFA]', '[NOME_PROJETO]'],
                                [$infoTarefa['elemento'], $nome, $infoProjeto['nome']]
                            );
                            break;

                        case 'editar-tarefa':
                            $listaPrioridade = $POPElemento->getPrioridadeList();
                            $listaUsuarios = $usuario->getUsuarios();
                            $radioPrioridade = html_prioridade($listaPrioridade, $infoTarefa['campos']['prioridade']);
                            $radioResponsavel = html_usuario($listaUsuarios, $infoTarefa['campos']['responsavel']);

                            if ($infoTarefa['projeto'] == 13) {
                                $nome = $infoTarefa["campos"]['Nome'];
                            }
                            else {
                                $nome = $tipoTarefa['nome'];
                            }


                            $resposta = formata_retorno($arquivo,
                                ['[ID_TAREFA]', '[NOME_TAREFA]', '[PRIORIDADE_TAREFA]', '[PRAZO_TAREFA]', '[RESPONSAVEL_TAREFA]'],
                                [$infoTarefa['elemento'], $nome, $radioPrioridade, $infoTarefa['campos']['prazo'], $radioResponsavel]
                            );
                            break;

                        case 'editar-pedido':
                            $listaClientes = $BNCliente->listAll('', 'nomeFantasia');
                            $tmp = [];
                            foreach ($listaClientes as $item) {
                                if ($item["ativo"] == 1) {
                                    $tmp[] = $item;
                                }
                            }
                            $listaClientes = $tmp;
                            $selectClientes = '';
                            if ($listaClientes) {
                                foreach ($listaClientes as $cliente) {
                                    $selectClientes .= '<option value="' . $cliente['nomeFantasia'] . '">' . $cliente['nomeFantasia'] . '</option>';
                                }
                            }

                            $listaPrioridade = $POPElemento->getPrioridadeList();
                            $radioPrioridade = html_prioridade($listaPrioridade, $infoTarefa['campos']['prioridade']);

                            $listaUsuarios = $usuario->getUsuariosArea($infoTarefa['campos']['area']);
                            $radioResponsavel = html_usuario($listaUsuarios, $infoTarefa['campos']['responsavel'], "pessoaPara");

                            $resposta = formata_retorno($arquivo,
                                ['[ID_PEDIDO]', '[PEDIDO_NOME]', '[PEDIDO_PRODUTO]', '[PEDIDO_CLIENTE]', '[PEDIDO_PRAZO]', '[PEDIDO_PRIORIDADE]', '[PEDIDO_RESPONSAVEL]', '[PEDIDO_DESCRICAO]'],
                                [$infoTarefa['elemento'], $infoTarefa['campos']['Nome'], $infoTarefa['campos']['Produto'], $selectClientes, $infoTarefa['campos']['prazo'], $radioPrioridade, $radioResponsavel, $infoTarefa['campos']['Descricao']]
                            );
                            break;

                        default:
                            $resposta['tipo'] = 'text';
                            $resposta['conteudo'] = 'Acao nao reconhecida.';
                            break;
                    }
                }
                elseif ($acao == 'novo-pedido' || $acao == "novo-pedido-cliente") {
                    // Simulando como se fossem 2 tipos diferentes de pedido, pq pode vir a ser
                    // HACK feio, precisa melhorar esse trecho de codigo pra ficar mais organizado
                    if ($acao == "novo-pedido") {
                        $override = "false";
                    }
                    else {
                        $override = "true";
                    }

                    $listaClientes = $BNCliente->listAll('', 'nomeFantasia');
                    $tmp = [];
                    foreach ($listaClientes as $item) {
                        if ($item["ativo"] == 1) {
                            $tmp[] = $item;
                        }
                    }
                    $listaClientes = $tmp;
                    $selectClientes = '';
                    if ($listaClientes) {
                        foreach ($listaClientes as $cliente) {
                            $selectClientes .= '<option value="' . $cliente['nomeFantasia'] . '">' . $cliente['nomeFantasia'] . '</option>';
                        }
                    }

                    $listaPrioridade = $POPElemento->getPrioridadeList();
                    $radioPrioridade = html_prioridade($listaPrioridade);

                    $radioAreasUsuario = '';
                    if ($areasUsuario) {
                        foreach ($areasUsuario as $aux) {
                            if ($aux['area'] != 1) {
                                $tmp = '<div class="radio col-xs-4">
																<label style="color: ' . $aux['cor'] . '; text-transform: uppercase;">
																	<input type="radio" name="areaDe" class="" value="' . $aux['area'] . '" required />
																	<b>' . $aux['nome'] . '</b>
																</label>
															</div>';
                                if (isset($aux["primaria"]) && $aux["primaria"] === TRUE) {
                                    $tmp = str_ireplace("required />", "required checked='checked' />", $tmp);
                                }
                                $radioAreasUsuario .= $tmp;
                            }
                        }
                    }

                    $listaAreas = $usuario->listArea("", FALSE);
                    $radioAreas = '';
                    if ($listaAreas) {
                        foreach ($listaAreas as $aux) {
                            if ($aux['area'] != 1) {
                                $radioAreas .= '<div class="radio col-xs-4">
														<label style="color: ' . $aux['cor'] . '; text-transform:uppercase;">
															<input type="radio" name="areaPara" class="" value="' . $aux['area'] . '" required />
															<b>' . $aux['nome'] . '</b>
														</label>
													</div>';
                            }
                        }
                    }

                    $resposta = formata_retorno($arquivo, ['[CLIENTE_PEDIDO]', '[PRIORIDADE_PEDIDO]', '[AREA_USUARIO]', '[AREA_PEDIDO]', "[OVERRIDE_AREA]"], [$selectClientes, $radioPrioridade, $radioAreasUsuario, $radioAreas, $override]);
                }
                elseif ($acao == "novo-hora") {
                    $listaClientes = $BNCliente->listAll('', 'nomeFantasia');
                    $selectClientes = '';
                    if ($listaClientes) {
                        foreach ($listaClientes as $cliente) {
                            $selectClientes .= '<option value="' . $cliente['nomeFantasia'] . '">' . $cliente['nomeFantasia'] . '</option>';
                        }
                    }

                    $listaPrioridade = $POPElemento->getPrioridadeList();
                    $radioAreasUsuario = '';
                    if ($areasUsuario) {
                        foreach ($areasUsuario as $aux) {
                            if ($aux['area'] != 1) {
                                $tmp = '<div class="radio col-xs-4">
																<label style="color: ' . $aux['cor'] . '; text-transform: uppercase;">
																	<input type="radio" name="areaDe" class="" value="' . $aux['area'] . '" required />
																	<b>' . $aux['nome'] . '</b>
																</label>
															</div>';
                                if (isset($aux["primaria"]) && $aux["primaria"] === TRUE) {
                                    $tmp = str_ireplace("required />", "required checked='checked' />", $tmp);
                                }
                                $radioAreasUsuario .= $tmp;
                            }
                        }
                    }

                    $resposta = formata_retorno($arquivo, ['[CLIENTE_PEDIDO]', '[AREA_USUARIO]'], [$selectClientes, $radioAreasUsuario]);

                }
                else {
                    $resposta['tipo'] = 'text';
                    $resposta['conteudo'] = 'Houve algum problema com a requisição.';
                }
            }

            if ($form) {
                switch ($form) {
                    case '':
                        break;

                    default:
                        break;
                }
            }
            break;

        case 'tarefas':
            if ($acao) {
                $arquivo = "modal/$secao/$acao.html";

                if (isset($_POST['tarefas'])) {
                    switch ($acao) {
                        case 'assumir-tarefas':
                        case 'remover-tarefas':
                            $jsonIds = $_POST['tarefas'];
                            $listaIds = json_decode($jsonIds);
                            $txtTarefas = '';

                            foreach ($listaIds as $id) {
                                $infoTarefa = $POPElemento->getElementById($id);
                                $tipoTarefa = $POPElemento->getElementTypeById($infoTarefa['elementoTipo']);
                                $infoProjeto = $POPProjeto->getProjectById($infoTarefa['projeto']);
                                if ($infoTarefa['projeto'] == 13) {
                                    $txtTarefas .= $infoTarefa["campos"]['Nome'] . ' - ' . $infoProjeto['nome'] . '<br>';
                                }
                                else {
                                    $txtTarefas .= $tipoTarefa['nome'] . ' - ' . $infoProjeto['nome'] . '<br>';
                                }
                            }

                            $resposta = formata_retorno($arquivo,
                                ['[LISTA_ID_TAREFAS]', '[TXT_TAREFAS]'],
                                [$jsonIds, $txtTarefas]
                            );
                            break;

                        case 'delegar-tarefas':
                            $jsonIds = $_POST['tarefas'];
                            $listaIds = json_decode($jsonIds);
                            $txtTarefas = $radioResponsavel = '';
                            $listaUsuarios = [];

                            $listaPrioridade = $POPElemento->getPrioridadeList();
                            $radioPrioridade = html_prioridade($listaPrioridade, 102);

                            foreach ($listaIds as $id) {
                                $infoTarefa = $POPElemento->getElementById($id);
                                $tipoTarefa = $POPElemento->getElementTypeById($infoTarefa['elementoTipo']);
                                $infoProjeto = $POPProjeto->getProjectById($infoTarefa['projeto']);
                                if ($infoTarefa['projeto'] == 13) {
                                    $txtTarefas .= $infoTarefa["campos"]['Nome'] . ' - ' . $infoProjeto['nome'] . '<br>';
                                }
                                else {
                                    $txtTarefas .= $tipoTarefa['nome'] . ' - ' . $infoProjeto['nome'] . '<br>';
                                }
                            }
                            $usuariosArea = $usuario->getUsuarios();
                            foreach ($usuariosArea as $us) {
                                $listaUsuarios[] = $us;

                            }
                            $radioResponsavel .= html_usuario($listaUsuarios, NULL);

                            $resposta = formata_retorno($arquivo,
                                ['[LISTA_ID_TAREFAS]', '[TXT_TAREFAS]', '[PRIORIDADE_TAREFA]', '[RESPONSAVEL_TAREFA]'],
                                [$jsonIds, $txtTarefas, $radioPrioridade, $radioResponsavel]
                            );
                            break;

                        default:
                            $resposta['tipo'] = 'text';
                            $resposta['conteudo'] = 'Acao nao reconhecida.';
                            break;
                    }
                }
            }
            break;

        case 'fluxo':
            if ($acao) {
                switch ($acao) {
                    case 'adicionar-sessao':
                        if (isset($_POST['conteudo'], $_POST['tipoProjeto'])) {
                            $_POST['conteudo'] = json_encode($_POST['conteudo']);
                            $POPProjeto->saveSession($_SESSION['adm_usuario'], $_POST['tipoProjeto'], $_POST['conteudo']);
                        }
                        else {
                            $resposta['tipo'] = 'text';
                            $resposta['conteudo'] = 'Houve algum problema com a requisição.';
                        }
                        break;

                    case 'editar-tipoProjeto':
                        if (isset($_POST['tipoProjeto'])) {
                            $infoTipoProjeto = $POPProjeto->getProjectTypeById($_POST['tipoProjeto']);
                            $arquivo = "modal/$secao/$acao.html";
                            $resposta = formata_retorno($arquivo, ['[ID_PROJETOTIPO]', '[NOME_PROJETOTIPO]', '[DESCRICAO_PROJETOTIPO]', '[COR_PROJETOTIPO]', '[ICONE_PROJETOTIPO]'],
                                [$infoTipoProjeto['projetoTipo'], $infoTipoProjeto['nome'], $infoTipoProjeto['descricao'], $infoTipoProjeto['cor'], $infoTipoProjeto['icone']]);
                        }
                        else {
                            $resposta['tipo'] = 'text';
                            $resposta['conteudo'] = 'Houve algum problema com a requisição.';
                        }
                        break;
                    default:
                        break;
                }
            }

            if ($form) {
                switch ($form) {
                    case 'adicionar-fluxo':
                        if (isset($_POST['etapas'], $_POST['primeiras'], $_POST['relacoes'], $_POST['tipoProjeto'])) {

                            $POPProjetoT = new Projeto($database);
                            $POPElementoT = new Elemento($database);

                            // INICIA TRANSAÇÃO PARA FAZER AS CONSULTAS E INSERÇÕES
                            //$database->connect();

                            $listaEtapasOk = [];
                            $listaEtapasId = [];

                            // insere as etapas
                            foreach ($_POST['etapas'] as $etapa) {
                                if (isset($etapa['campos'])) {
                                    // destrincha os campos, adiciona no array principal e unseta o array de campos
                                    $camposEtapa = $etapa['campos'];
                                    foreach ($camposEtapa as $key => $aux) {
                                        $etapa[$key] = $aux;
                                    }
                                    unset($etapa['campos']);
                                }

                                // pega o id temporário sequencial (gerado no javascript) e guarda numa variável para uso posterior, e unseta o
                                $etapaIdTmp = $etapa['etapa'];
                                unset($etapa['etapa']);

                                $campos = $valores = [];
                                foreach ($etapa as $key => $value) {
                                    $campos[] = $key;
                                    $valores[] = $value;
                                }

                                $etapaId = $POPElementoT->insertElementType($campos, $valores);
                                $listaEtapasOk[] = $etapa;

                                // insere tarefa como elemento base da etapa
                                $retorno = $POPElementoT->insertElementBase($etapaId, 1);

                                if (!$retorno) {
                                    $resposta['tipo'] = 'erro';
                                    $resposta['conteudo'] = 'Houve algum problema ao salvar os dados 1 (bases).';
                                    break 2;
                                }

                                $etapa['etapa'] = $etapaId;
                                // grava no array $listaEtapasId, com indice do ID temporário, o registro da etapa
                                $listaEtapasId[$etapaIdTmp] = $etapa;
                            }

                            $prazoProjeto = 0;

                            // insere os dados das etapas
                            foreach ($_POST['dadosEtapas'] as $dadosEtapa) {
                                if (!$dadosEtapa['area']) {
                                    $resposta['tipo'] = 'erro';
                                    $resposta['conteudo'] = 'Houve algum problema ao salvar os dados 2 (areas são obrigatórias).';
                                    break 2;
                                }

                                $etapaDado = $listaEtapasId[$dadosEtapa['etapa']]; // pega o registro inserido em banco usando o id temporário

                                if (isset($dadosEtapa['prazo'])) {
                                    $prazoOk = $POPElementoT->updateElementType($etapaDado['etapa'], ['prazo'], [$dadosEtapa['prazo']]);
                                    $prazoProjeto += (int)$dadosEtapa['prazo'];
                                }

                                $retorno = $POPElementoT->insertElementoTipoArea($etapaDado['etapa'], $dadosEtapa['area']);

                                if (!$retorno) {
                                    $resposta['tipo'] = 'erro';
                                    $resposta['conteudo'] = 'Houve algum problema ao salvar os dados 2 (areas).';
                                    break 2;
                                }
                            }

                            // insere as as primeiras etapas
                            foreach ($_POST['primeiras'] as $primeira) {
                                $etapaPrimeira = $listaEtapasId[$primeira['etapa']]; // pega o registro inserido em banco usando o id temporário
                                $retorno = $POPProjetoT->insertElementFirst($_POST['tipoProjeto'], $etapaPrimeira['etapa']);

                                if (!$retorno) {
                                    $resposta['tipo'] = 'erro';
                                    $resposta['conteudo'] = 'Houve algum problema ao salvar os dados 3 (primeiras).';
                                    break 2;
                                }
                            }

                            // insere as relações
                            foreach ($_POST['relacoes'] as $relacao) {
                                $etapaRelacao = $listaEtapasId[$relacao['etapa']]; // pega o registro inserido em banco usando o id temporário

                                if ($relacao['proxima']) {
                                    $relacaoProxima = $listaEtapasId[$relacao['proxima']];
                                    $retorno = $POPProjetoT->insertElementNext($_POST['tipoProjeto'], $etapaRelacao['etapa'], $relacaoProxima['etapa']);

                                    if (!$retorno) {
                                        $resposta['tipo'] = 'erro';
                                        $resposta['conteudo'] = 'Houve algum problema ao salvar os dados 4 (proximas).';
                                        break 2;
                                    }
                                }

                                if ($relacao['anterior']) {
                                    $relacaoAnterior = $listaEtapasId[$relacao['anterior']];
                                    $retorno = $POPProjetoT->insertElementPrev($_POST['tipoProjeto'], $etapaRelacao['etapa'], $relacaoAnterior['etapa']);

                                    if (!$retorno) {
                                        $resposta['tipo'] = 'erro';
                                        $resposta['conteudo'] = 'Houve algum problema ao salvar os dados 4 (anteriores).';
                                        break 2;
                                    }
                                }
                            }

                            $retorno = $POPProjetoT->updateProjectType($_POST['tipoProjeto'], ['diasPrazo'], [$prazoProjeto]);
                            if (!$retorno) {
                                $resposta['tipo'] = 'erro';
                                $resposta['conteudo'] = 'Houve algum problema ao salvar o prazo do projeto.';
                                break;
                            }

                            // FECHA A TRANSAÇÃO
                            //$database->disconnect();

                            $POPProjeto->cleanSession($_SESSION['adm_usuario'], $_POST['tipoProjeto']);

                            $resposta['tipo'] = 'sucesso';
                            $resposta['conteudo'] = 'Dados gravados com sucesso.';
                        }
                        else {
                            $resposta['tipo'] = 'erro';
                            $resposta['conteudo'] = 'Houve algum problema com a requisição.';
                        }
                        break;
                    default:
                        break;
                }
            }
            break;

        case 'elemento':
            if ($acao) {
                switch ($acao) {
                    case 'adicionar-imagem':
                    case 'adicionar-texto':
                    case 'adicionar-diagramacao':
                    case 'adicionar-wireframe':
                    case 'adicionar-layout':
                    case 'adicionar-briefing':
                    case 'adicionar-estrutura-aws':
                    case 'adicionar-estrutura-nerdpress':
                    case 'adicionar-arquivo':
                    case 'adicionar-arquivos':
                    case 'adicionar-dominios':
                    case 'adicionar-identidade-visual':
                    case 'adicionar-registro-dominio':
                    case 'adicionar-listagem-emails':
                    case 'adicionar-lista-de-conteudo':
                    case 'adicionar-lista-modulos':
                    case 'adicionar-arquivos-recortados':
                    case 'aguardar-cliente':
                    case 'aprovar-elemento':
                    case 'adicionar-url-teste':
                    case 'reprovar-elemento':
                    case 'apagar-elemento':
                        if (isset($_POST['elemento'])) {
                            $elemento = $POPElemento->getElementById($_POST['elemento']);

                            $textoPost = getIfExists($elemento["campos"], "Texto");
                            $textoImagem = getIfExists($elemento["campos"], "textoImagem");
                            $briefing = getIfExists($elemento["campos"], "briefing");
                            $dominios = getIfExists($elemento["campos"], "dominio");
                            $diagramacao = getIfExists($elemento["campos"], "link");
                            $link_google = getIfExists($elemento["campos"], "arquivos");
                            $link_google_2 = getIfExists($elemento["campos"], "link");
                            $listagem_email = getIfExists($elemento["campos"], "emails");
                            $listagem_conteudo = getIfExists($elemento["campos"], "conteudo");
                            $listagem_modulos = getIfExists($elemento["campos"], "lista");
                            $url_teste = getIfExists($elemento["campos"], "link_2");

                            $arquivo = "modal/$secao/$acao.html";
                            $resposta = formata_retorno($arquivo, ['[ELEMENTO_ID]', '[ELEMENTO_TEXTO]', '[ELEMENTO_TEXTOIMAGEM]', '[ELEMENTO_BRIEFING]', '[ELEMENTO_DOMINIOS]', '[ELEMENTO_DIAGRAMACAO]', '[LINK_GOOGLE_DRIVE]', '[LINK_GOOGLE_DRIVE_2]', '[ELEMENTO_TEXTO_EMAIL]', '[ELEMENTO_LISTAGEM_DE_CONTEUDO]', '[ELEMENTO_LISTAGEM_DE_MODULOS]', '[LINK_URL_TESTE]'],
                                [$elemento['elemento'], $textoPost, $textoImagem, $briefing, $diagramacao, $link_google, $link_google_2, $listagem_email, $listagem_conteudo, $listagem_modulos, $url_teste]);
                        }
                        else {
                            $resposta['tipo'] = 'text';
                            $resposta['conteudo'] = 'Houve algum problema com a requisição.';
                        }
                        break;

                    case 'adicionar-briefing-mail-mkt':
                        if (isset($_POST['elemento'])) {
                            $elemento = $POPElemento->getElementById($_POST['elemento']);
                            $briefing = getIfExists($elemento["campos"], "briefing");
                            $dataPublicacao = getIfExists($elemento["campos"], "dataPublicacao");

                            $geraHtml = getIfExists($elemento["campos"], "geraHtml");
                            if ($geraHtml == "on") {
                                $geraHtml = "checked";
                            }
                            else {
                                $geraHtml = "";
                            }
                            $enviarInbound = getIfExists($elemento["campos"], "enviarInbound");
                            if ($enviarInbound == "on") {
                                $enviarInbound = "checked";
                            }
                            else {
                                $enviarInbound = "";
                            }

                            $arquivo = "modal/$secao/$acao.html";
                            $resposta = formata_retorno($arquivo,
                                [
                                    '[ELEMENTO_ID]',
                                    '[ELEMENTO_BRIEFING]',
                                    '[ELEMENTO_HTML]',
                                    '[ELEMENTO_INBOUND]',
                                    '[ELEMENTO_DATA]',

                                ],
                                [
                                    $elemento['elemento'],
                                    $briefing,
                                    $geraHtml,
                                    $enviarInbound,
                                    $dataPublicacao,
                                ]
                            );
                        }
                        else {
                            $resposta['tipo'] = 'text';
                            $resposta['conteudo'] = 'Houve algum problema com a requisição.';
                        }
                        break;

                    case 'adicionar-briefing-pecas-media':
                        if (isset($_POST['elemento'])) {
                            $elemento = $POPElemento->getElementById($_POST['elemento']);
                            $briefing = getIfExists($elemento["campos"], "briefing");
                            $dataPublicacao = getIfExists($elemento["campos"], "dataPublicacao");
                            $url = getIfExists($elemento["campos"], "url");

                            $desdobramento = getIfExists($elemento["campos"], "desdobrarPeca");
                            if ($desdobramento == "on") {
                                $desdobramento = "checked";
                            }
                            else {
                                $desdobramento = "";
                            }

                            $arquivo = "modal/$secao/$acao.html";
                            $resposta = formata_retorno($arquivo,
                                [
                                    '[ELEMENTO_ID]',
                                    '[ELEMENTO_BRIEFING]',
                                    '[ELEMENTO_URL]',
                                    '[ELEMENTO_DATA]',
                                    '[ELEMENTO_DESDOBRAMENTO]',

                                ],
                                [
                                    $elemento['elemento'],
                                    $briefing,
                                    $url,
                                    $dataPublicacao,
                                    $desdobramento
                                ]
                            );
                        }
                        else {
                            $resposta['tipo'] = 'text';
                            $resposta['conteudo'] = 'Houve algum problema com a requisição.';
                        }
                        break;
                    case 'adicionar-briefing-material-grafico':
                        if (isset($_POST['elemento'])) {
                            $elemento = $POPElemento->getElementById($_POST['elemento']);
                            $briefing = getIfExists($elemento["campos"], "briefing");
                            $fechaImpressao = getIfExists($elemento["campos"], "fechaImpressao");
                            if ($fechaImpressao == "on") {
                                $fechaImpressao = "checked";
                            }
                            else {
                                $fechaImpressao = "";
                            }

                            $arquivo = "modal/$secao/$acao.html";
                            $resposta = formata_retorno($arquivo,
                                [
                                    '[ELEMENTO_ID]',
                                    '[ELEMENTO_BRIEFING]',
                                    '[ELEMENTO_DESDOBRAMENTO]',

                                ],
                                [
                                    $elemento['elemento'],
                                    $briefing,
                                    $fechaImpressao
                                ]
                            );
                        }
                        else {
                            $resposta['tipo'] = 'text';
                            $resposta['conteudo'] = 'Houve algum problema com a requisição.';
                        }
                        break;
                    case 'adicionar-dados-pecas-media':
                        if (isset($_POST['elemento'])) {
                            $elemento = $POPElemento->getElementById($_POST['elemento']);
                            $conteudo = getIfExists($elemento["campos"], "conteudo");
                            $referencia = getIfExists($elemento["campos"], "referencia");
                            $arquivo = "modal/$secao/$acao.html";
                            $resposta = formata_retorno($arquivo,
                                [
                                    '[ELEMENTO_ID]',
                                    '[ELEMENTO_CONTEUDO]',
                                    '[ELEMENTO_REFERENCIAS]'
                                ],
                                [
                                    $elemento['elemento'],
                                    $conteudo,
                                    $referencia
                                ]
                            );
                        }
                        else {
                            $resposta['tipo'] = 'text';
                            $resposta['conteudo'] = 'Houve algum problema com a requisição.';
                        }
                        break;

                    case 'adicionar-drive-pecas-media':
                        if (isset($_POST['elemento'])) {
                            $elemento = $POPElemento->getElementById($_POST['elemento']);
                            $link_google = getIfExists($elemento["campos"], "gdrive");

                            $arquivo = "modal/$secao/$acao.html";
                            $resposta = formata_retorno($arquivo,
                                [
                                    '[ELEMENTO_ID]',
                                    '[ELEMENTO_DRIVE]'
                                ],
                                [
                                    $elemento['elemento'],
                                    $link_google
                                ]
                            );
                        }
                        else {
                            $resposta['tipo'] = 'text';
                            $resposta['conteudo'] = 'Houve algum problema com a requisição.';
                        }
                        break;

                    case 'adicionar-drive-2-pecas-media':
                        if (isset($_POST['elemento'])) {
                            $elemento = $POPElemento->getElementById($_POST['elemento']);
                            $link_google = getIfExists($elemento["campos"], "midia");

                            $arquivo = "modal/$secao/$acao.html";
                            $resposta = formata_retorno($arquivo,
                                [
                                    '[ELEMENTO_ID]',
                                    '[ELEMENTO_MIDIA]'
                                ],
                                [
                                    $elemento['elemento'],
                                    $link_google
                                ]
                            );
                        }
                        else {
                            $resposta['tipo'] = 'text';
                            $resposta['conteudo'] = 'Houve algum problema com a requisição.';
                        }
                        break;


                    case 'reprovar-elemento-condicional':
                        if (isset($_POST['elemento'])) {
                            $elemento = $POPElemento->getElementById($_POST['elemento']);
                            $arquivo = "modal/$secao/reprovar-elemento-condicional.html";
                            $radio = "<div><h1> ERRO NA CRIACAO DA MODAL </h1></div>";
                            $listaEtapaReprovacao = [];
                            if ($elemento["elementoTipo"] == 125) {
                                // Email Marketing
                                $etapaReprovacao = "360";
                                if (isset($elemento["campos"]["AreaTipo"])) {
                                    $etapaReprovacao = "367";
                                }
                                $listaEtapaReprovacao =
                                    [
                                        353              => "Alterar Conteúdo do E-mail Marketing",
                                        $etapaReprovacao => "Alterar Arte do Email Marketing"
                                    ];
                            }
                            elseif ($elemento["elementoTipo"] == 126) {
                                // Peca de Midia
                                $listaEtapaReprovacao =
                                    [
                                        305 => "Alterar Conteúdo das Peças de Mídia",
                                        310 => "Alterar Peça Conceito"
                                    ];
                            }elseif ($elemento["elementoTipo"] == 109) {
                                // Peca de Midia
                                $listaEtapaReprovacao =
                                    [
                                        182 => "Alterar Conteúdo do Post",
                                        186 => "Alterar Peça do Post"
                                    ];
                            }elseif ($elemento["elementoTipo"] == 130) {
                                // Material Grafico
                                $listaEtapaReprovacao =
                                    [
                                        401 =>  "Alterar Conteúdo do E-mail",
                                        403 =>  "Alterar Arte do E-mail",
                                    ];
                            }elseif ($elemento["elementoTipo"] == 131) {
                                // Material Grafico
                                $listaEtapaReprovacao =
                                    [
                                        421 =>  "Alterar Conteúdo do E-mail",
                                        423 =>  "Alterar Arte do E-mail",
                                    ];
                            }elseif ($elemento["elementoTipo"] == 132) {
                                // Material Grafico
                                $listaEtapaReprovacao =
                                    [
                                        452 =>  "Alterar Conteúdo do Material Gráfico",
                                        456 =>  "Alterar Arte do Material Gráfico",
                                    ];
                            }
                            if ($listaEtapaReprovacao != []) {
                                $radio = "";
                            }
                            foreach ($listaEtapaReprovacao as $id => $nome) {
                                $radio .= '
                                            <div class="radio col-xs-3">
                                                <label style="text-transform:uppercase;">
                                                    <input type="radio" name="etapaReprovacao" class="" required value="' . $id . '" />
                                                    <b>' . $nome . '</b>
                                                </label>
                                            </div>
                                            ';
                            }
                            $resposta = formata_retorno($arquivo,
                                [
                                    "[ELEMENTO_ID]",
                                    "[RADIO_REPROVACAO]",
                                ],
                                [
                                    $elemento['elemento'],
                                    $radio,
                                ]);
                        }
                        else {
                            $resposta['tipo'] = 'text';
                            $resposta['conteudo'] = 'Houve algum problema com a requisição.';
                        }


                        break;
                    case 'adicionar-url-drive':
                        $titulo = "Adicionar Link do Google Drive";
                        $tipoFormulario = "adicionar-url-gdrive";
                        $tituloLabel = "URL do Google Drive";
                        $nomeDoCampo = "url-google";
                        $placeHolder = "http://drive.google.com/";
                        $campo = "gdrive";
                    case 'adicionar-url-html':
                        if (!isset($tipoFormulario)) {
                            $titulo = "Adicionar Link do HTML";
                            $tipoFormulario = "adicionar-url-html";
                            $tituloLabel = "URL do HTML";
                            $nomeDoCampo = "url";
                            $placeHolder = "http://cache.studionerdweb.com.br/";
                            $campo = "url";
                        }
                        if (isset($_POST['elemento'])) {
                            $elemento = $POPElemento->getElementById($_POST['elemento']);
                            $conteudo = $link_google = getIfExists($elemento["campos"], $campo);
                            $arquivo = "modal/$secao/adicionar-url.html";
                            $resposta = formata_retorno($arquivo,
                                ['[MODAL_TITULO]',
                                 '[FORM_FUNCAO]',
                                 '[ELEMENTO_ID]',
                                 '[MODAL_LABEL]',
                                 '[FORM_NOME_CAMPO]',
                                 '[FORM_PLACEHOLDER]',
                                 '[ELEMENTO_CONTEUDO]'
                                ],
                                [$titulo,
                                 $tipoFormulario,
                                 $elemento["elemento"],
                                 $tituloLabel,
                                 $nomeDoCampo,
                                 $placeHolder,
                                 $conteudo
                                ]);
                        }
                        else {
                            $resposta['tipo'] = 'text';
                            $resposta['conteudo'] = 'Houve algum problema com a requisição.';
                        }
                        break;

                    case 'adicionar-texto-post':
                        if (isset($_POST['elemento'])) {
                            $elemento = $POPElemento->getElementById($_POST['elemento']);

                            $textoPost = getIfExists($elemento["campos"], "Texto");
                            $textoImagem = getIfExists($elemento["campos"], "textoImagem");
                            $observacao = getIfExists($elemento["campos"], "Observacoes");
                            $arquivo = "modal/$secao/$acao.html";
                            $resposta = formata_retorno($arquivo,
                                ['[ELEMENTO_ID]',
                                 '[ELEMENTO_TEXTO]',
                                 '[ELEMENTO_TEXTOIMAGEM]',
                                 '[ELEMENTO_OBSERVACAO]',
                                ],
                                [
                                    $elemento['elemento'],
                                    $textoPost,
                                    $textoImagem,
                                    $observacao,
                                ]);
                        }
                        else {
                            $resposta['tipo'] = 'text';
                            $resposta['conteudo'] = 'Houve algum problema com a requisição.';
                        }
                        break;

                    case 'adicionar-info-post-blog':
                        if (isset($_POST['elemento'])) {
                            $elemento = $POPElemento->getElementById($_POST['elemento']);


                            $tituloPost = getIfExists($elemento["campos"], "tituloPost");
                            $slugPost = getIfExists($elemento["campos"], "slugPost");
                            $imagemPost = getIfExists($elemento["campos"], "imagemPost");
                            $dataPublicacao = getIfExists($elemento["campos"], "dataPublicacao");
                            $temFacebookPost = getIfExists($elemento["campos"], "temFacebookPost"); // checkbox tem facebook
                            if ($temFacebookPost == "on") {
                                $temFacebookPost = "checked";
                            }
                            else {
                                $temFacebookPost = "";
                            }
                            $impulsionamentoPost = getIfExists($elemento["campos"], "impulsionarPost");
                            if ($impulsionamentoPost == "on") {
                                $impulsionamentoPost = "checked";
                            }
                            else {
                                $impulsionamentoPost = "";
                            }
                            $conteudoPost = getIfExists($elemento["campos"], "conteudoPost");
                            $referencia = getIfExists($elemento["campos"], "referencias");

                            $arquivo = "modal/$secao/$acao.html";
                            $resposta = formata_retorno($arquivo,
                                [
                                    "[ELEMENTO_ID]",
                                    "[ELEMENTO_TITULO]",
                                    "[ELEMENTO_SLUG]",
                                    "[ELEMENTO_IMAGEM]",
                                    "[ELEMENTO_DATA]",
                                    "[ELEMENTO_FACEBOOK]",
                                    "[ELEMENTO_IMPULSIONAMENTO]",
                                    "[ELEMENTO_CONTEUDO]",
                                    "[ELEMENTO_REFERENCIA]",
                                ],
                                [
                                    $elemento['elemento'],
                                    $tituloPost,
                                    $slugPost,
                                    $imagemPost,
                                    $dataPublicacao,
                                    $temFacebookPost,
                                    $impulsionamentoPost,
                                    $conteudoPost,
                                    $referencia,
                                ]);
                        }
                        else {
                            $resposta['tipo'] = 'text';
                            $resposta['conteudo'] = 'Houve algum problema com a requisição.';
                        }
                        break;

                    case "adicionar-info-post-social";
                        $elemento = $POPElemento->getElementById($_POST['elemento']);
                        $dataPublicacao = getIfExists($elemento["campos"], "dataPublicacao");
                        $referencia = getIfExists($elemento["campos"], "referencia");
                        $legenda = getIfExists($elemento["campos"], "legenda");
                        $mecanica = getIfExists($elemento["campos"], "mecanica");

                        $impulsionamentoPost = getIfExists($elemento["campos"], "impulsionarPost");
                        if ($impulsionamentoPost == "on") {
                            $impulsionamentoPost = "checked";
                        }
                        else {
                            $impulsionamentoPost = "";
                        }


                        $arquivo = "modal/$secao/$acao.html";
                        $resposta = formata_retorno($arquivo,
                            [
                                "[ELEMENTO_ID]",
                                "[ELEMENTO_DATA]",
                                "[ELEMENTO_IMPULSIONAMENTO]",
                                "[ELEMENTO_REFERENCIAS]",
                                "[ELEMENTO_LEGENDA]",
                                "[ELEMENTO_MECANICA]"
                            ],
                            [
                                $elemento['elemento'],
                                $dataPublicacao,
                                $impulsionamentoPost,
                                $referencia,
                                $legenda,
                                $mecanica
                            ]);
                        break;

                    case "adicionar-info-mail-mkt";
                        $elemento = $POPElemento->getElementById($_POST['elemento']);
                        $conteudo = getIfExists($elemento["campos"], "conteudo");
                        $observacoes = getIfExists($elemento["campos"], "Observacoes");
                        $assunto = getIfExists($elemento["campos"], "assunto");
                        $arquivo = "modal/$secao/$acao.html";
                        $resposta = formata_retorno($arquivo,
                            [
                                "[ELEMENTO_ID]",
                                "[ELEMENTO_ASSUNTO]",
                                "[ELEMENTO_CONTEUDO]",
                            ],
                            [
                                $elemento['elemento'],
                                $assunto,
                                $conteudo,
                            ]);
                        break;

                    case 'adicionar-post':
                    case 'aprovar-todos':
                        $resposta['tipo'] = 'html';
                        $resposta['conteudo'] = file_get_contents("modal/$secao/$acao.html");
                        break;

                    case 'editar-elemento-facebook':
                        if (isset($_POST['elemento'])) {
                            $elemento = $POPElemento->getElementById($_POST['elemento']);
                            $listaCategorias = $POPElemento->getCategoryList();
                            $htmlCategorias = '';
                            foreach ($listaCategorias as $cat) {
                                $htmlCategorias .= '<option value="' . $cat['categoria'] . '">' . $cat['nome'] . '</option>';
                            }
                            $arquivo = "modal/$secao/$acao.html";
                            $resposta = formata_retorno($arquivo, ['[ELEMENTO_ID]', '[DATAPUBLICACAO_POST]', '[SELECT_CATEGORIAS]'],
                                [$elemento['elemento'], $elemento['campos']['Dia'], $htmlCategorias]);
                        }
                        else {
                            $resposta['tipo'] = 'text';
                            $resposta['conteudo'] = 'Houve algum problema com a requisição.';
                        }

                        break;

                    case 'ver-historico':
                        if (isset($_POST['elemento'])) {
                            $elemento = $POPElemento->getElementById($_POST['elemento']);
                            $chatElemento = $POPChat->getElementChat($elemento['elemento']);
                            $htmlHistorico = html_historico_elemento($chatElemento, $database);
                            $arquivo = "modal/$secao/$acao.html";
                            $resposta = formata_retorno($arquivo, ['[ELEMENTO_ID]', '[ELEMENTO_CHAT]'], [$elemento['elemento'], $htmlHistorico]);
                        }
                        else {
                            $resposta['tipo'] = 'text';
                            $resposta['conteudo'] = 'Houve algum problema com a requisição.';
                        }
                        break;

                    case 'ver-historico-observacao':
                        if (isset($_POST['elemento'])) {
                            $elemento = $POPElemento->getElementById($_POST['elemento']);
                            $htmlHistorico = "";
                            foreach ($elemento["campos"] as $chave => $valor) {
                                if (stripos($chave, "Observacao") !== FALSE) {
                                    $htmlHistorico .= "<div>" . $chave . "<br><span>" . Utils::insert_href(nl2br($valor)) . "</span><br></div><br><br>";
                                }
                            }
                            $arquivo = "modal/$secao/$acao.html";
                            $resposta = formata_retorno($arquivo, ['[ELEMENTO_ID]', '[ELEMENTO_HISTORICO_OBSERVACAO]'], [$elemento['elemento'], $htmlHistorico]);
                        }
                        else {
                            $resposta['tipo'] = 'text';
                            $resposta['conteudo'] = 'Houve algum problema com a requisição.';
                        }
                        break;

                    case 'ver-historico-projeto':
                        if (isset($_POST['elemento'])) {
                            $elemento = $POPElemento->getElementById($_POST['elemento']);
                            $chatProjeto = $POPChat->getProjetoChat($elemento['projeto'], $elemento['elemento']);
                            $htmlHistorico = html_historico_elemento($chatProjeto, $database);
                            $arquivo = "modal/$secao/$acao.html";
                            $resposta = formata_retorno($arquivo, ['[PROJETO_ID]', '[ELEMENTO_ID]', '[PROJETO_CHAT]'], [$elemento['projeto'], $elemento['elemento'], $htmlHistorico]);
                        }
                        else {
                            $resposta['tipo'] = 'text';
                            $resposta['conteudo'] = 'Houve algum problema com a requisição.';
                        }
                        break;

                    case 'ver-historico-elemento-completo':
                        if (isset($_POST['elemento'])) {
                            $elemento = $POPElemento->getElementById($_POST['elemento']);
                            $chatProjeto = $POPChat->getProjetoChat($elemento['projeto'], $elemento['elemento']);
                            $chatProjetoSistema = $POPChat->getProjetoChat($elemento['projeto'], $elemento['elemento'], "ASC", 1);
                            $chatProjetoMerged = Utils::sksort(array_merge($chatProjeto, $chatProjetoSistema), "data", TRUE);
                            $htmlHistorico = html_historico_elemento($chatProjetoMerged, $database);
                            $arquivo = "modal/$secao/$acao.html";
                            $resposta = formata_retorno($arquivo, ['[PROJETO_ID]', '[ELEMENTO_ID]', '[PROJETO_CHAT]'], [$elemento['projeto'], $elemento['elemento'], $htmlHistorico]);
                        }
                        else {
                            $resposta['tipo'] = 'text';
                            $resposta['conteudo'] = 'Houve algum problema com a requisição.!!!!';
                        }
                        break;

                    case 'ver-referencias':
                    case 'ver-referencias-imagem':
                        if (isset($_POST['elemento'])) {
                            $elemento = $POPElemento->getElementById($_POST['elemento']);
                            $projeto = $POPProjeto->getProjectById($elemento["projeto"]);
                            $htmlReferencias = '';
                            if (isset($elemento['campos']) && $elemento['campos']) {
                                foreach ($elemento['campos'] as $key => $campo) {
                                    if (strpos($key, 'referencia')) {

                                        $htmlReferencias .= '<div class="col-sm-6 item-info">
												<div class="item-info-box">
													<h4>Referencia</h4>
														<a class="open-lightbox" href="' . $campo . '" target="_blank">
															<img src="' . $campo . '" style="border: 1px solid #E0E8E8;">
														</a>
												</div>
											</div>';
                                    }
                                }
                            }
                            $arquivo = "modal/$secao/$acao.html";
                            $resposta = formata_retorno($arquivo, ['[ELEMENTO_ID]', '[ELEMENTO_REFERENCIAS]'], [$elemento['elemento'], $htmlReferencias]);
                        }
                        else {
                            $resposta['tipo'] = 'text';
                            $resposta['conteudo'] = 'Houve algum problema com a requisição.';
                        }
                        break;

                    case 'adicionar-material-extra':
                        if (isset($_POST['elemento'])) {
                            $elemento = $POPElemento->getElementById($_POST['elemento']);
                            $htmlMaterial = '';

                            if (isset($elemento['campos']) && $elemento['campos']) {
                                foreach ($elemento['campos'] as $key => $campo) {
                                    if (strpos($key, 'extra')) {
                                        $htmlMaterial .= '<div class="col-sm-6 item-info">
												<div class="item-info-box">
													<h4>Material</h4>
														<a class="open-lightbox" href="' . $campo . '" target="_blank">
															<img src="' . $campo . '" style="border: 1px solid #E0E8E8;">
														</a>
												</div>
											</div>';
                                    }
                                }
                            }
                            $arquivo = "modal/$secao/$acao.html";
                            $resposta = formata_retorno($arquivo, ['[ELEMENTO_ID]', '[ELEMENTO_MATERIALEXTRA]'], [$elemento['elemento'], $htmlMaterial]);
                        }
                        else {
                            $resposta['tipo'] = 'text';
                            $resposta['conteudo'] = 'Houve algum problema com a requisição.';
                        }
                        break;

                    default:
                        $resposta['tipo'] = 'text';
                        $resposta['conteudo'] = 'Acao 1233 nao reconhecida.';
                        break;
                }
            }

            if ($form) {
                switch ($form) {
                    case 'salva-comentario':
                        if ($_POST['elemento'] && $_POST['comentario']) {
                            $retorno = $POPChat->addElementChat($_POST['elemento'], $_SESSION['adm_usuario'], $_POST['comentario']);
                            if ($retorno) {
                                // Resgata informações do comentário para mostrar o chat
                                $chatElemento = $POPChat->getElementChat($_POST['elemento']);
                                $htmlHistorico = html_historico_elemento($chatElemento, $database);
                                $resposta['tipo'] = 'html';
                                $resposta['conteudo'] = $htmlHistorico;
                            }
                            else {
                                $resposta['tipo'] = 'msg';
                                $resposta['conteudo'] = 'Houve algum erro ao salvar o registro.';
                            }
                        }
                        else {
                            $resposta['tipo'] = 'msg';
                            $resposta['conteudo'] = 'Preencha todos os campos obrigatórios.';
                        }
                        break;
                    default:
                        break;
                }
            }
            break;

        case 'projeto':
            if ($acao) {
                switch ($acao) {
                    case 'editar-projeto':
                    case 'arquivar-projeto':
                    case 'nova-pagina-website':
                    case "novo-post-blog":
                    case "novo-post-social":
                    case "novo-e-mail-mkt":
                        if (isset($_POST['projeto'])) {
                            $projeto = $POPProjeto->getProjectById($_POST['projeto']);

                            $arquivo = "modal/$secao/$acao.html";
                            $resposta = formata_retorno($arquivo,
                                ['[PROJETO_ID]', '[PROJETO_NOME]', '[PROJETO_DATAENTRADA]', '[PROJETO_PRAZO]'],
                                [$projeto['projeto'], $projeto['nome'], $projeto['dataEntrada'], $projeto['prazo']]
                            );
                        }
                        else {
                            $resposta['tipo'] = 'text';
                            $resposta['conteudo'] = 'Houve algum problema com a requisição.';
                        }
                        break;

                    case "novo-pecas-midia":
                        if (isset($_POST["projeto"])) {
                            $projeto = $POPProjeto->getProjectById($_POST['projeto']);
                            $arquivo = "modal/$secao/$acao.html";
                            $resposta = formata_retorno($arquivo, ['[PROJETO_ID]'] , [$projeto['projeto']] );
                        }else {
                            $resposta['tipo'] = 'text';
                            $resposta['conteudo'] = 'Houve algum problema com a requisição.';
                        }
                        break;

                    case 'novo-post-facebook':
                        if (isset($_POST['projeto'])) {
                            $projeto = $POPProjeto->getProjectById($_POST['projeto']);

                            $mesCampanha = $datepicker['ini'] = $datepicker['fim'] = '';

                            if (isset($projeto['campos']['mes']) && $projeto['campos']['mes']) {
                                $mesCampanha = str_replace('/', '-', $projeto['campos']['mes']);
                                $mesCampanha = '01-' . $mesCampanha;

                                $mes = date('m', strtotime($mesCampanha));
                                $ano = date('Y', strtotime($mesCampanha));
                                $datepicker['ini'] = date('Y-m-d', strtotime($mesCampanha));
                                $datepicker['fim'] = $ano . '-' . $mes . '-' . cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
                            }

                            $listaCategorias = $POPElemento->getCategoryList();
                            $categoriasProgramacao = [];

                            if (isset($projeto['campos']['planejamento']) && $projeto['campos']['planejamento']) {

                                $programacaoCampanha = json_decode($projeto['campos']['planejamento'], TRUE);

                                if ($programacaoCampanha !== '[]') {
                                    foreach ($programacaoCampanha as $progCamp) {
                                        if ($progCamp) {
                                            $infoCategoria = $POPElemento->getCategoryById($progCamp['categoria']);

                                            //categorias usadas na campanha, usar no select de criação de novos posts
                                            if (!in_array($infoCategoria, $categoriasProgramacao)) {
                                                $categoriasProgramacao[] = $infoCategoria;
                                            }
                                        }
                                    }
                                }
                            }

                            $selectCategorias = '';

                            $categoriasCampanha = $categoriasProgramacao ?? $listaCategorias;
                            foreach ($categoriasCampanha as $categoria) {
                                $selectCategorias .= '<option value="' . $categoria["categoria"] . '">' . $categoria["nome"] . '</option>';
                            }

                            $arquivo = "modal/$secao/$acao.html";
                            $resposta = formata_retorno($arquivo,
                                ['[PROJETO_ID]', '[PROJETO_MESINICIO]', '[PROJETO_MESFIM]', 'PROJETO_CATEGORIA'],
                                [$projeto['projeto'], $datepicker['ini'], $datepicker['fim'], $selectCategorias]
                            );
                        }
                        else {
                            $resposta['tipo'] = 'text';
                            $resposta['conteudo'] = 'Houve algum problema com a requisição.';
                        }
                        break;

                    case 'novo-programacao-facebook':
                        if (isset($_POST['projeto'])) {
                            $projeto = $POPProjeto->getProjectById($_POST['projeto']);
                            $listaCategorias = $POPElemento->getCategoryList();

                            $selectCategorias = '';

                            foreach ($listaCategorias as $categoria) {
                                $selectCategorias .= '<option value="' . $categoria["categoria"] . '">' . $categoria["nome"] . '</option>';
                            }

                            $arquivo = "modal/$secao/$acao.html";
                            $resposta = formata_retorno($arquivo,
                                ['[PROJETO_ID]', '[PROJETO_CATEGORIAS]'],
                                [$projeto['projeto'], $selectCategorias]
                            );
                        }
                        else {
                            $resposta['tipo'] = 'text';
                            $resposta['conteudo'] = 'Houve algum problema com a requisição.';
                        }
                        break;

                    case 'novo-projeto':
                        $listaTiposProjeto = $POPProjeto->getProjectTypeList(['escondido'], [0], 'nome ASC');
                        $radioTipoProjeto = '';
                        foreach ($listaTiposProjeto as $aux) {
                            $radioTipoProjeto .= '<div class="radio col-xs-6">
														<label style="color: ' . $aux['cor'] . '; text-transform:uppercase;">
															<input type="radio" name="projetoTipo" class="" required value="' . $aux['projetoTipo'] . '" />
															<b>' . $aux['nome'] . '</b>
														</label>
													</div>';
                        }

                        $listaClientes = $BNCliente->listAll('', 'nomeFantasia');
                        $selectClientes = '';
                        if ($listaClientes) {
                            foreach ($listaClientes as $cliente) {
                                $selectClientes .= '<option value="' . $cliente['cliente'] . '">' . $cliente['nomeFantasia'] . '</option>';
                            }
                        }

                        $arquivo = "modal/$secao/$acao.html";
                        $resposta = formata_retorno($arquivo,
                            ['[TIPO_PROJETO]', '[CLIENTE_PROJETO]', '[DATA_ATUAL]'],
                            [$radioTipoProjeto, $selectClientes, date('d/m/Y')]
                        );
                        break;

                    case 'mostra-campos-extra':
                        if ($_POST['projetoTipo']) {
                            $infoProjetoTipo = $POPProjeto->getProjectTypeById($_POST['projetoTipo']);
                            $camposExtra = $infoProjetoTipo['campos'];
                            $nomesCampos = $infoProjetoTipo['nomesCampos'];
                            if ($camposExtra) {
                                $htmlCampos = '';
                                foreach ($camposExtra as $key => $value) {
                                    if (class_exists($value)) {
                                        /** @var nomeTipo $var */
                                        $var = new $value($value, $key, $database);
                                        if (isset($nomesCampos[$key])) {
                                            $var->setExibicao($nomesCampos[$key]);
                                        }
                                        $htmlCampos .= $var->retornaHtmlInsercao();

                                    }
                                }
                                $resposta['tipo'] = 'html';
                                $resposta['conteudo'] = $htmlCampos;
                            }
                        }
                        break;

                    case 'salva-programacao':
                        if ($_POST['projeto'] && $_POST['programacao']) {
                            $programacaoJSON = json_encode($_POST['programacao']);
                            $retorno = $POPProjeto->updateExtraField($_POST['projeto'], 'planejamento', $programacaoJSON);
                            if ($retorno) {
                                $resposta['tipo'] = 'msg';
                                $resposta['conteudo'] = 'Salvo com sucesso';
                            }
                            else {
                                $resposta['tipo'] = 'msg';
                                $resposta['conteudo'] = 'Houve algum erro ao salvar o registro.';
                            }
                        }
                        break;

                    case 'adicionar-briefing-projeto':
                        if (isset($_POST['projeto'])) {
                            $projeto = $POPProjeto->getProjectById($_POST['projeto']);
                            $briefing = $projeto["campos"]["briefing"] ?? "";
                            $arquivo = "modal/$secao/$acao.html";
                            $resposta = formata_retorno($arquivo,
                                ['[PROJETO_ID]', '[PROJETO_BRIEFING]'],
                                [$projeto['projeto'], $briefing]
                            );
                        }
                        else {
                            $resposta['tipo'] = 'text';
                            $resposta['conteudo'] = 'Houve algum problema com a requisição.';
                        }
                        break;

                    case 'ver-historico-projeto':
                        if (isset($_POST['projeto'])) {
                            $chatProjeto = $POPChat->getProjetoChat($_POST['projeto']);
                            $htmlHistorico = html_historico_elemento($chatProjeto, $database);
                            $arquivo = "modal/$secao/$acao.html";
                            $resposta = formata_retorno($arquivo, ['[PROJETO_ID]', '[PROJETO_CHAT]'], [$_POST['projeto'], $htmlHistorico]);
                        }
                        else {
                            $resposta['tipo'] = 'text';
                            $resposta['conteudo'] = 'Houve algum problema com a requisição.';
                        }
                        break;

                    default:
                        break;
                }
            }

            if ($form) {
                switch ($form) {
                    case 'salva-comentario':
                        if ($_POST['projeto'] && $_POST['comentario']) {
                            $idElemento = $_POST['elemento'] ?? NULL;

                            $retorno = $POPChat->addProjectChat($_POST['projeto'], $_SESSION['adm_usuario'], $_POST['comentario'], $idElemento);

                            if ($retorno) {
                                // Resgata informações do comentário para mostrar o chat
                                $chatProjeto = $POPChat->getProjetoChat($_POST['projeto'], $idElemento);
                                $htmlHistorico = html_historico_elemento($chatProjeto, $database);
                                $resposta['tipo'] = 'html';
                                $resposta['conteudo'] = $htmlHistorico;

                            }
                            else {
                                $resposta['tipo'] = 'msg';
                                $resposta['conteudo'] = 'Houve algum erro ao salvar o registro.';
                            }
                        }
                        else {
                            $resposta['tipo'] = 'msg';
                            $resposta['conteudo'] = 'Preencha todos os campos obrigatórios.';
                        }
                        break;
                    default:
                        break;
                }
            }
            break;

        case 'categoria':
            if ($acao) {
                switch ($acao) {
                    case 'ver-categoria':
                        if (isset($_POST['categoria'])) {
                            $infoCategoria = $POPElemento->getCategoryById($_POST['categoria']);
                            $resposta['tipo'] = 'json';
                            $resposta['conteudo'] = json_encode($infoCategoria);
                        }
                        else {
                            $resposta['tipo'] = 'text';
                            $resposta['conteudo'] = 'Houve algum problema com a requisição.';
                        }
                        break;

                    case 'editar-categoria':
                    case 'apagar-categoria':
                        if (isset($_POST['categoria'])) {
                            $infoCategoria = $POPElemento->getCategoryById($_POST['categoria']);
                            $arquivo = "modal/$secao/$acao.html";
                            $resposta = formata_retorno($arquivo, ['[CATEGORIA_ID]', '[CATEGORIA_NOME]', '[CATEGORIA_DESCRICAO]', '[CATEGORIA_COR]'], [$infoCategoria['categoria'], $infoCategoria['nome'], $infoCategoria['descricao'], $infoCategoria['cor']]);
                        }
                        else {
                            $resposta['tipo'] = 'text';
                            $resposta['conteudo'] = 'Houve algum problema com a requisição.';
                        }
                        break;
                    default:
                        break;
                }
            }

            if ($form) {
                switch ($form) {
                    case 'salva-comentario':
                        if ($_POST['elemento'] && $_POST['comentario']) {
                            $retorno = $POPChat->addElementChat($_POST['elemento'], $_SESSION['adm_usuario'], $_POST['comentario']);
                            if ($retorno) {
                                // Resgata informações do comentário para mostrar o chat
                                $chatElemento = $POPChat->getElementChat($_POST['elemento']);
                                $htmlHistorico = html_historico_elemento($chatElemento, $database);
                                $resposta['tipo'] = 'html';
                                $resposta['conteudo'] = $htmlHistorico;
                            }
                            else {
                                $resposta['tipo'] = 'msg';
                                $resposta['conteudo'] = 'Houve algum erro ao salvar o registro.';
                            }
                        }
                        else {
                            $resposta['tipo'] = 'msg';
                            $resposta['conteudo'] = 'Preencha todos os campos obrigatórios.';
                        }
                        break;

                    default:
                        break;
                }
            }
            break;

        case 'usuario':
            if ($acao) {
                switch ($acao) {
                    case 'pessoas-area':
                        if ($_POST['area']) {
                            $listaUsuarios = $usuario->getUsuariosArea($_POST['area']);
                            if ($listaUsuarios) {
                                $htmlCampos = html_usuario($listaUsuarios);
                                $resposta['tipo'] = 'html';
                                $resposta['conteudo'] = $htmlCampos;
                            }
                        }
                        break;
                    default:
                        break;
                }
            }

            if ($form) {
                switch ($form) {
                    case '':
                        break;

                    default:
                        break;
                }
            }
            break;
        default:
            break;
    }
    echo json_encode($resposta);
}
