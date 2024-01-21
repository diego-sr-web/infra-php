<?php
require_once __DIR__ . '/../../../autoloader.php';

if (Utils::is_cli()) {
    $_SERVER['SERVER_ADDR'] = '172.26.1.114'; // alterar quando for mudar de lugar
    $_SERVER['SERVER_NAME'] = 'nerdweb.popflow.com.br'; // alterar quando for mudar de lugar (url de upload de arquivos)
    $finalDeLinha = PHP_EOL;
}
else {
    $finalDeLinha = "<br>";
}


$database = new Database();
$POPProjeto = new Projeto($database);
$POPElemento = new Elemento($database);
$array_tipo_pedido = [59, 82, 105];

$listaProjetos = $POPProjeto->getProjectList(["finalizado"], [0]);

$dtAtual = date('Y-m-d H:i:s');
$msg_erro_etapa = [];
$msg_erro_elemento = [];
$msg_erro_projeto = [];
$msg_erro_pedido = [];

$msg_sucesso_etapa = [];
$msg_sucesso_elemento = [];
$msg_sucesso_projeto = [];
$msg_sucesso_pedido = [];


if ($listaProjetos) {
    foreach ($listaProjetos as $projeto) {
        if ($projeto["finalizado"] != 1) {
            $elementosProjeto = $POPElemento->getAllElementsCron(['projeto'], [$projeto['projeto']]);
            // Caminha por todos os elementos do projeto
            foreach ($elementosProjeto as $elemento) {
                // Sessao da parte de automacao e decisoes do sistema
                if (isset($elemento['campos']["area"]) && $elemento["campos"]["area"] == 22 && $elemento["elementoStatus"] != 9) {
                    if (isset($elemento["campos"]["Etapa"])) {
                        switch ($elemento["campos"]["Etapa"]) {
                            // 1 Ponto de decisao Post de blog
                            case 171:
                                if ($elemento["campos"]["temFacebookPost"] === "on") {
                                    // Tem facebook
                                    $POPElemento->trocaSubEtapa($elemento["elemento"], 172);
                                }
                                elseif ($elemento["campos"]["impulsionarPost"] === "on") {
                                    // Nao tem facebook + tem impulsionamento
                                    $POPElemento->trocaSubEtapa($elemento["elemento"], 174);
                                }
                                else {
                                    // Nao tem facebook nem impulsionamento
                                    $POPElemento->setStatus($elemento["elemento"], 8);
                                }
                                break;
                            // 2 Ponto de decisao Post de blog
                            case 173:
                                // X Ponto de decisao - "Impulsionar Post ?"
                                $POPElemento->etapaDecisaoSistema($elemento, "impulsionarPost");
                                break;
                            case 192:
                                // X Ponto de decisao - "Impulsionar Post ?"
                                $POPElemento->etapaDecisaoSistema($elemento, "impulsionarPost");
                                break;
                            case 231:
                                // 1 Ponto de decisao Material Grafico V3 - "Fecha pra impressao ?"
                                $POPElemento->etapaDecisaoSistema($elemento, "fechaImpressao");
                                break;
                            case 206:
                                // Primeiro ponto de decisao, como nao tem suporte ainda
                                // Soh troca a subetapa pra andar pelo caminho A
                                $POPElemento->trocaSubEtapa($elemento["elemento"], 207);
                                break;
                            case 217:
                                // 2 Ponto de decisao Email Marketing - "Gera HTML ?"
                                if ($elemento["campos"]["geraHtml"] === "on") {
                                    $POPElemento->trocaSubEtapa($elemento["elemento"], 218);
                                }
                                elseif ($elemento["campos"]["enviarInbound"] === "on") {
                                    // Nao gera html, + manda pra inbound
                                    $POPElemento->trocaSubEtapa($elemento["elemento"], 223);
                                }
                                else {
                                    // Nao gera html nem inbound, finaliza o projeto aqui
                                    $POPElemento->setStatus($elemento["elemento"], 8);
                                }
                                break;
                            case 222:
                                // 3 Ponto de decisao Email Marketing - "Enviar pra inbound ?"
                                $POPElemento->etapaDecisaoSistema($elemento, "enviarInbound");
                                break;
                            case 251:
                                // 1 Ponto de decisao Pecas de Midia - "Desdobrar ?"
                                $POPElemento->etapaDecisaoSistema($elemento, "desdobrarPeca");
                                break;
                            case 257:
                                // 2 Ponto de decisao Pecas de Midia - "Enviar pra midia?"
                                $POPElemento->etapaDecisaoSistema($elemento, "enviarMidia");
                                break;
                            case 316:
                                // 2 Ponto de decisao Pecas de Midia -  "Desdobrar?"
                                if ($elemento["campos"]["desdobrarPeca"] === "on") {
                                    // Interface
                                    $POPElemento->trocaSubEtapa($elemento["elemento"], 317);
                                }
                                else {
                                    $POPElemento->trocaSubEtapa($elemento["elemento"], 323);
                                }
                                break;

                            case 356:
                                // Aqui le do projeto se eh Criacao ou Interface
                                if (isset($elemento["campos"]["AreaTipo"]) && $elemento["campos"]["AreaTipo"] == 3) {
                                    // Interface
                                    $POPElemento->trocaSubEtapa($elemento["elemento"], 364);
                                }
                                else {
                                    // Criacao
                                    $POPElemento->trocaSubEtapa($elemento["elemento"], 357);
                                }
                                break;
                            case 371:
                                // 2 Ponto de decisao Email Marketing - "Gera HTML ?"
                                if ($elemento["campos"]["geraHtml"] === "on") {
                                    $POPElemento->trocaSubEtapa($elemento["elemento"], 372);
                                }
                                elseif ($elemento["campos"]["enviarInbound"] === "on") {
                                    // Nao gera html, + manda pra inbound
                                    $POPElemento->trocaSubEtapa($elemento["elemento"], 377);
                                }
                                else {
                                    // Nao gera html nem inbound, finaliza o projeto aqui
                                    $POPElemento->setStatus($elemento["elemento"], 8);
                                }
                                break;
                            case 376:
                                // 3 Ponto de decisao Email Marketing - "Enviar pra inbound ?"
                                $POPElemento->etapaDecisaoSistema($elemento, "enviarInbound");
                                break;
                            case 190:
                            case 379:
                            case 315:
                            case 406:
                            case 426:
                            case 458:
                                // Reprovacao condicional, potencialmente tratar todos os casos aqui, talvez nao seja a melhor ideia
                                if (isset($elemento["campos"]["EtapaReprovacao"]) && $elemento['campos']["EtapaReprovacao"] !== NULL) {
                                    $POPElemento->trocaSubEtapa($elemento["elemento"], $elemento["campos"]["EtapaReprovacao"]);
                                    $POPElemento->updateElement($elemento["elemento"], ["EtapaReprovacao"], [NULL]);
                                }
                                else {
                                    $POPElemento->setStatus($elemento["elemento"], 7);
                                }
                                break;

                            case 380:
                                // Reprovacao da subetapa 372, pode ir tanto para 360 qto 367
                                $POPElemento->trocaSubEtapa($elemento["elemento"], 360);
                                break;
                            case 407:
                                if ($elemento["campos"]["geraHtml"] === "on") {
                                    $POPElemento->trocaSubEtapa($elemento["elemento"], 408);
                                } else {
                                    $POPElemento->trocaSubEtapa($elemento["elemento"], 409);
                                }
                                break;
                            case 409:
                                if ($elemento["campos"]["enviarInbound"] === "on") {
                                    $POPElemento->trocaSubEtapa($elemento["elemento"], 410);
                                }else {
                                    $POPElemento->trocaSubEtapa($elemento["elemento"], 411);
                                }
                                break;
                            case 427:
                                if ($elemento["campos"]["geraHtml"] === "on") {
                                    $POPElemento->trocaSubEtapa($elemento["elemento"], 428);
                                }else {
                                    $POPElemento->trocaSubEtapa($elemento["elemento"], 429);
                                }
                                break;
                            case 429:
                                if ($elemento["campos"]["enviarInbound"] === "on") {
                                    $POPElemento->trocaSubEtapa($elemento["elemento"], 430);
                                }else {
                                    $POPElemento->trocaSubEtapa($elemento["elemento"], 431);
                                }
                                break;
                            case 460:
                                if ($elemento["campos"]["fechaImpressao"] === "on") {
                                    $POPElemento->trocaSubEtapa($elemento["elemento"], 461);
                                }else {
                                    $POPElemento->setStatus($elemento["elemento"], 8);
                                }
                            break;
                        }
                    }
                }

                // Fim da sessao dedicada a sitema e automacao


                // Segue Fluxo normal aqui
                if (isset($elemento["campos"]["Etapa"])) {
                    //var_dump($elemento);

                }
                // Caminha pelas SubEtapas
                if (!in_array($elemento["elementoTipo"], $array_tipo_pedido)) {
                    if ((($elemento['elementoStatus'] == 14) || ($elemento['elementoStatus'] == 15))) {
                        //var_dump($elemento["elemento"], "entrei no caminho de sub-etapas");
                        //exit;
                        $etapa_atual = $elemento["campos"]["Etapa"];
                        // Avanca/volta etapa se necessario (* no caso de etapa com status finalizado *)
                        $modo = "";
                        $processou = FALSE;
                        if ($POPProjeto->avancaSubEtapa($elemento['elemento'])) {
                            $modo = "avancou";
                            $processou = TRUE;
                        }
                        elseif ($POPProjeto->voltaSubEtapa($elemento['elemento'])) {
                            $modo = "voltou";
                            $processou = TRUE;
                        }

                        if ($processou === TRUE) {
                            $msg_sucesso_etapa[] = 'Elemento:' . $elemento['elemento'] . ' SubEtapa:' . $etapa_atual . ' ' . $modo . ' de SubEtapa.';
                        }
                        else {
                            // nao imprime nada pra nao ficar poluindo log, + em caso de debug eh sempre bom saber oq ta acontecendo
                            //$msg_erro_etapa[] = 'Elemento:' . $elemento['elemento'] . ' Etapa:'. $etapa_atual.' etapa atual nao esta concluida.';
                        }

                    }
                    elseif ($elemento['elementoStatus'] == 10) {
                        // Caminha pelos elementos que nao possuem SubEtapas
                        // var_dump("Achei um elemento Finalizado");
                        // Tenta Avancar a Etapa do elemento (* no caso de etapa com status finalizado *)
                        // caso seja possivel ele vai criar uma etapa NOVA em um ELEMENTO NOVO
                        $retorno = $POPProjeto->voltaEtapa($elemento['elemento']);
                        if (isset($retorno) && $retorno === TRUE) {
                            $msg_sucesso_elemento[] = 'Elemento:' . $elemento['elemento'] . ' voltou de etapa.';
                        }
                        else {
                            // nao imprime nada pra nao ficar poluindo log, + em caso de debug eh sempre bom saber oq ta acontecendo
                            //$msg_erro_elemento[] = 'Elemento:' . $elemento['elemento'] . ' não está finalizado para avançar de etapa.';
                        }

                    }
                    elseif ($elemento['elementoStatus'] == 8) {
                        // Caminha pelos elementos que nao possuem SubEtapas
                        // var_dump("Achei um elemento Finalizado");
                        // Tenta Avancar a Etapa do elemento (* no caso de etapa com status finalizado *)
                        // caso seja possivel ele vai criar uma etapa NOVA em um ELEMENTO NOVO
                        $retorno = $POPProjeto->avancaEtapa($elemento['elemento']);
                        if (isset($retorno) && $retorno === TRUE) {
                            $msg_sucesso_elemento[] = 'Elemento:' . $elemento['elemento'] . ' avançou de etapa.';
                        }
                        else {
                            // nao imprime nada pra nao ficar poluindo log, + em caso de debug eh sempre bom saber oq ta acontecendo
                            //$msg_erro_elemento[] = 'Elemento:' . $elemento['elemento'] . ' não está finalizado para avançar de etapa.';
                        }
                    }
                }

                if (in_array($elemento["elementoTipo"], $array_tipo_pedido)) {
                    // Aqui faz processamento dos pedidos

                    // Processamento das SubEtapas de Pedidos
                    if ((($elemento['elementoStatus'] == 14) || ($elemento['elementoStatus'] == 15))) {
                        //var_dump($elemento["elemento"], "entrei no caminho de sub-etapas");
                        //exit;
                        $etapa_atual = $elemento["campos"]["Etapa"];
                        // Avanca/volta etapa se necessario (* no caso de etapa com status finalizado *)
                        $modo = "";
                        $processou = FALSE;
                        if ($POPProjeto->avancaSubEtapa($elemento['elemento'])) {
                            $modo = "avancou";
                            $processou = TRUE;
                        }
                        elseif ($POPProjeto->voltaSubEtapa($elemento['elemento'])) {
                            $modo = "voltou";
                            $processou = TRUE;
                        }

                        if (isset($retorno) && $retorno === TRUE) {
                            $msg_sucesso_pedido[] = 'Pedido:' . $elemento['elemento'] . ' ' . $modo . ' de Etapa.';
                        }
                        else {
                            // nao imprime nada pra nao ficar poluindo o log, + em caso de debu eh sempre bom saber oq ta acontecendo
                            //$msg_erro_pedido[] = 'Pedido:' . $elemento['elemento'] . ' não está finalizado para avançar de SubEtapa.';
                        }
                    }

                    if ($elemento["elementoStatus"] == 8) {
                        $retorno = $POPElemento->updateElement($elemento['elemento'], ['elementoStatus'], [9]);

                        if (isset($retorno) && $retorno === TRUE) {
                            $msg_sucesso_pedido[] = 'Pedido:' . $elemento['elemento'] . ' Finalizado com sucesso.';
                        }
                        else {
                            // nao imprime nada pra nao ficar poluindo o log, + em caso de debu eh sempre bom saber oq ta acontecendo
                            //$msg_erro_pedido[] = 'Pedido:' . $elemento['elemento'] . ' não está finalizado para avançar de SubEtapa.';
                        }
                    }
                }
                //exit;


                // Limpa o responsavel de elementos que tao com status aguardando tarefa
                if ($elemento["campos"]["responsavel"] != NULL && $elemento["elementoStatus"] == 1) {
                    $POPElemento->updateElement($elemento["elemento"], ["responsavel"], [NULL]);
                }

            }
        }

        // Caso todos os elemenentos do Projeto estejam com o Status FINALIZADO_PROCESSADO
        // Marca o Projeto Como Finalizado
        $finaliza = $POPProjeto->finalizaProjeto($projeto['projeto']);
        if ($finaliza) {
            $msg_sucesso_projeto[] = 'Projeto:' . $projeto['projeto'] . ' foi marcado como finalizado.';
        }
        else {
            // nao imprime nada pra nao ficar poluindo log, + em caso de debug eh sempre bom saber oq ta acontecendo
            //$msg_erro_projeto[] = 'Projeto:' . $projeto['projeto'] . ' não está pronto para ser finalizado.';
        }
    }
}


echo "DATA:" . $dtAtual;
echo $finalDeLinha . 'Sucessos:' . $finalDeLinha;

echo "Etapas:" . $finalDeLinha;
foreach ($msg_sucesso_etapa as $etapa) {
    echo $etapa . $finalDeLinha;
}
echo $finalDeLinha;
echo "Elemento:" . $finalDeLinha;
foreach ($msg_sucesso_elemento as $elemento) {
    echo $elemento . $finalDeLinha;
}
echo $finalDeLinha;
echo "Projeto:" . $finalDeLinha;
foreach ($msg_sucesso_projeto as $projeto) {
    echo $projeto . $finalDeLinha;
}
echo $finalDeLinha;
echo "Pedido:" . $finalDeLinha;
foreach ($msg_sucesso_pedido as $pedido) {
    echo $pedido . $finalDeLinha;
}
echo $finalDeLinha;
echo $finalDeLinha . 'Erros:' . $finalDeLinha;

echo "Etapas:" . $finalDeLinha;
foreach ($msg_erro_etapa as $etapa) {
    echo $etapa . $finalDeLinha;
}
echo $finalDeLinha;
echo "Elemento:" . $finalDeLinha;
foreach ($msg_erro_elemento as $elemento) {
    echo $elemento . $finalDeLinha;
}
echo $finalDeLinha;
echo "Projeto:" . $finalDeLinha;
foreach ($msg_erro_projeto as $projeto) {
    echo $projeto . $finalDeLinha;
}
echo "Pedido:" . $finalDeLinha;
foreach ($msg_erro_pedido as $pedido) {
    echo $pedido . $finalDeLinha;
}
echo $finalDeLinha;
