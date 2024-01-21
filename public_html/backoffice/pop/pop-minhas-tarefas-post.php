<?php
if (isset($_POST['form'])) {
    $dtAtual = date('Y-m-d H:i:s');

    $redirect = ['redireciona' => FALSE, 'url' => basename($_SERVER['SCRIPT_FILENAME']) . '?' . $_SERVER['QUERY_STRING'], 'queryString' => '',];

    /** @var array $redirect */
    switch ($_POST['form']) {
        case 'assumir-tarefa':
            if (isset($_POST['tarefa'])) {
                // encontra a tarefa e a atualiza com o novo responsável e o status correspondente
                $infoTarefa = $POPElemento->getElementById($_POST['tarefa']);

                // várias consultas para verificar se há mais tarefas agrupadas do mesmo tipo para atualizar
                $infoProjeto = $POPProjeto->getProjectById($infoTarefa['projeto']);
                $elementosProjeto = $POPElemento->getAllElements(['projeto'], [$infoProjeto['projeto']], TRUE);

                $tarefasProjeto = $POPElemento->filtraElementosPorBase($elementosProjeto, 1);

                foreach ($tarefasProjeto as $key => $value) {
                    // remove do array as tarefas com responsável, e as com status diferente de "aguardando responsável" e "aguardando cliente"
                    $arrayStatus = [1, 3];
                    if ($value['campos']['responsavel'] || !in_array($value['elementoStatus'], $arrayStatus)) {
                        unset($tarefasProjeto[$key]);
                    }
                }

                $tarefasProjeto = $POPElemento->agrupaTarefas($tarefasProjeto)['itensAgrupados'];

                // se houverem mais tarefas, atualiza num foreach
                if ($tarefasProjeto && isset($infoTarefa['campos']['Etapa'])) {
                    // foreach no array de tarefas de mesma etapa que a $infoTarefa
                    foreach ($tarefasProjeto[$infoProjeto['projeto']][$infoTarefa['campos']['Etapa']] as $item) {
                        // se não existir responsável, atualiza
                        if (!$item['campos']['responsavel']) {
                            $campos = ['responsavel'];
                            $valores = [$_SESSION['adm_usuario']];

                            // se a tarefa não estiver aguardando cliente, o status vai ser alterado
                            if ($item['elementoStatus'] != 3) {
                                $campos[] = 'elementoStatus';
                                $valores[] = 2;
                            }

                            $retorno = $POPElemento->updateElement($item['elemento'], $campos, $valores);
                        }
                        else {
                            $retorno = FALSE;
                        }
                    }

                    if (isset($retorno) && $retorno) {
                        $redirect['redireciona'] = TRUE;
                        $_SESSION["msg_sucesso"] = "Tarefa assumida com sucesso.";
                    }
                    else {
                        $redirect['redireciona'] = TRUE;
                        $_SESSION["msg_erro"] = "Houve algum problema ao assumir a tarefa.";
                    }

                    // se não, é tarefa única
                }
                else {
                    // se não existir responsável, atualiza
                    if (!$infoTarefa['campos']['responsavel']) {
                        $campos = ['responsavel'];
                        $valores = [$_SESSION['adm_usuario']];

                        // se a tarefa não estiver aguardando cliente, o status vai ser alterado
                        if ($infoTarefa['elementoStatus'] != 3) {
                            $campos[] = 'elementoStatus';
                            $valores[] = 2;
                        }

                        $retorno = $POPElemento->updateElement($infoTarefa['elemento'], $campos, $valores);

                        if ($retorno) {
                            $redirect['redireciona'] = TRUE;
                            $_SESSION["msg_sucesso"] = "Tarefa assumida com sucesso.";
                        }
                        else {
                            $redirect['redireciona'] = TRUE;
                            $_SESSION["msg_erro"] = "Houve algum problema ao assumir a tarefa.";
                        }
                    }
                    else {
                        $redirect['redireciona'] = TRUE;
                        $_SESSION["msg_erro"] = "Esta tarefa já possui responsável.";
                    }
                }

            }
            else {
                $redirect['redireciona'] = TRUE;
                $_SESSION["msg_erro"] = "Houve algum problema com a requisição.";
            }
            break;

        case 'iniciar-tarefa':
        case 'retomar-tarefa':
            if (isset($_POST['tarefa'])) {
                // encontra a tarefa e a atualiza com o status correspondente, e adiciona o histórico
                $infoTarefa = $POPElemento->getElementById($_POST['tarefa']);

                // várias consultas para verificar se há mais tarefas agrupadas do mesmo tipo para assumir
                $infoProjeto = $POPProjeto->getProjectById($infoTarefa['projeto']);
                $elementosProjeto = $POPElemento->getAllElements(['projeto'], [$infoProjeto['projeto']], TRUE);

                $tarefasProjeto = $POPElemento->filtraElementosPorBase($elementosProjeto, 1);

                foreach ($tarefasProjeto as $key => $value) {
                    // remove do array as tarefas com responsável diferente do atual, e as com status diferente de "aguardando início", "pausado" e "parado"
                    $arrayStatus = [2, 3, 5, 6];
                    if ((isset($value['campos']['responsavel']) && $value['campos']['responsavel'] != $_SESSION['adm_usuario']) || !in_array($value['elementoStatus'], $arrayStatus)) {
                        unset($tarefasProjeto[$key]);
                    }
                }

                $tarefasProjeto = $POPElemento->agrupaTarefas($tarefasProjeto)['itensAgrupados'];

                // se houverem mais tarefas, atualiza num foreach
                if ($tarefasProjeto && isset($infoTarefa['campos']['Etapa'])) {
                    // foreach no array de tarefas pertencentes à mesma etapa que a tarefa $infoTarefa
                    foreach ($tarefasProjeto[$infoProjeto['projeto']][$infoTarefa['campos']['Etapa']] as $item) {
                        $campos = $valores = [];

                        // se a tarefa não estiver aguardando cliente, o status vai ser alterado
                        if ($item['elementoStatus'] != 3) {
                            $campos[] = 'elementoStatus';
                            $valores[] = 4;
                        }

                        if (!$item['campos']['dtInicio']) {
                            $campos[] = 'dtInicio';
                            $valores[] = date('Y-m-d H:i:s');
                            $inicia = $POPElemento->marcaInicio($item['elemento']);
                        }
                        else {
                            $inicia = $POPElemento->marcaRetomada($item['elemento']);
                        }

                        $retorno = $POPElemento->updateElement($item['elemento'], $campos, $valores);
                    }

                    if (isset($retorno, $inicia) && $retorno && $inicia) {
                        $redirect['redireciona'] = TRUE;
                        $redirect['url'] = 'pop-projeto.php?projeto=' . $infoProjeto['projeto'] . '&tarefas=true';
                        $_SESSION["msg_sucesso"] = "Tarefa iniciada com sucesso.";
                    }
                    else {
                        $redirect['redireciona'] = TRUE;
                        $_SESSION["msg_erro"] = "Houve algum problema ao iniciar a tarefa.";
                    }
                    // se não, é tarefa única
                }
                else {
                    // se o responsável for o usuário atual, atualiza
                    if ($infoTarefa['campos']['responsavel'] == $_SESSION['adm_usuario']) {

                        $campos = $valores = [];

                        // se a tarefa não estiver aguardando cliente, o status vai ser alterado
                        if ($infoTarefa['elementoStatus'] != 3) {
                            $campos[] = 'elementoStatus';
                            $valores[] = 4;
                        }

                        if (!$infoTarefa['campos']['dtInicio']) {
                            $campos[] = 'dtInicio';
                            $valores[] = date('Y-m-d H:i:s');
                            $inicia = $POPElemento->marcaInicio($infoTarefa['elemento']);
                        }
                        else {
                            $inicia = $POPElemento->marcaRetomada($infoTarefa['elemento']);
                        }

                        $retorno = $POPElemento->updateElement($infoTarefa['elemento'], $campos, $valores);

                        if (isset($retorno, $inicia) && $retorno && $inicia) {
                            if (in_array($infoTarefa['elementoTipo'], $array_tipo_pedido)) {
                                $redirect['redireciona'] = TRUE;
                                $redirect['url'] = 'pop-pedido.php?eid=' . $infoTarefa['elemento'];
                                $_SESSION["msg_sucesso"] = "Tarefa iniciada com sucesso.";

                            }
                            else {
                                $redirect['redireciona'] = TRUE;
                                $redirect['url'] = 'pop-projeto.php?projeto=' . $infoProjeto['projeto'] . '&tarefas=true';
                                $_SESSION["msg_sucesso"] = "Tarefa iniciada com sucesso.";
                            }
                        }
                        else {
                            $redirect['redireciona'] = TRUE;
                            $_SESSION["msg_erro"] = "Houve algum problema ao iniciar a tarefa.";
                        }
                    }
                    else {
                        $redirect['redireciona'] = TRUE;
                        $_SESSION["msg_erro"] = "Esta tarefa não possui responsável ou já foi finalizada.";
                    }
                }
            }
            else {
                $redirect['redireciona'] = TRUE;
                $_SESSION["msg_erro"] = "Houve algum problema com a requisição.";
            }
            break;

        case 'pausar-tarefa':
            if (isset($_POST['tarefa'])) {
                // encontra a tarefa, atualiza com o status correspondente, e adiciona o histórico
                $infoTarefa = $POPElemento->getElementById($_POST['tarefa']);

                // várias consultas para verificar se há mais tarefas agrupadas do mesmo tipo para assumir
                $infoProjeto = $POPProjeto->getProjectById($infoTarefa['projeto']);
                $elementosProjeto = $POPElemento->getAllElements(['projeto'], [$infoProjeto['projeto']], TRUE);

                $tarefasProjeto = $POPElemento->filtraElementosPorBase($elementosProjeto, 1);

                foreach ($tarefasProjeto as $key => $value) {
                    // remove do array as tarefas com responsável diferente do atual, e as com status diferente de "em andamento"
                    $arrayStatus = [4];
                    if ((isset($value['campos']['responsavel']) && $value['campos']['responsavel'] != $_SESSION['adm_usuario']) || !in_array($value['elementoStatus'], $arrayStatus)) {
                        unset($tarefasProjeto[$key]);
                    }
                }

                $tarefasProjeto = $POPElemento->agrupaTarefas($tarefasProjeto)['itensAgrupados'];

                // se houverem mais tarefas, atualiza num foreach
                if ($tarefasProjeto && isset($infoTarefa['campos']['Etapa'])) {
                    foreach ($tarefasProjeto[$infoProjeto['projeto']][$infoTarefa['campos']['Etapa']] as $item) {
                        // se o responsável for o usuário atual, atualiza
                        if ($item['campos']['responsavel'] == $_SESSION['adm_usuario']) {
                            $retorno = $POPElemento->updateElement($item['elemento'], ['elementoStatus'], [5]);
                            $pausa = $POPElemento->marcaPausa($item['elemento']);
                        }
                    }

                    if (isset($retorno, $pausa) && $retorno && $pausa) {
                        $redirect['redireciona'] = TRUE;
                        $_SESSION["msg_sucesso"] = "Tarefa pausada com sucesso.";
                    }
                    else {
                        $redirect['redireciona'] = TRUE;
                        $_SESSION["msg_erro"] = "Houve algum problema ao pausar a tarefa.";
                    }

                    // se não, atualiza a tarefa única
                }
                else {
                    // se o responsável for o usuário atual, atualiza
                    if ($infoTarefa['campos']['responsavel']) {
                        $retorno = $POPElemento->updateElement($infoTarefa['elemento'], ['elementoStatus'], [5]);
                        $pausa = $POPElemento->marcaPausa($infoTarefa['elemento']);

                        if ($retorno && $pausa) {
                            $redirect['redireciona'] = TRUE;
                            $_SESSION["msg_sucesso"] = "Tarefa pausada com sucesso.";
                        }
                        else {
                            $redirect['redireciona'] = TRUE;
                            $_SESSION["msg_erro"] = "Houve algum problema ao pausar a tarefa.";
                        }
                    }
                    else {
                        $redirect['redireciona'] = TRUE;
                        $_SESSION["msg_erro"] = "Esta tarefa não possui responsável.";
                    }
                }
            }
            else {
                $redirect['redireciona'] = TRUE;
                $_SESSION["msg_erro"] = "Houve algum problema com a requisição.";
            }
            break;

        case 'finalizar-tarefa':
            if (isset($_POST['tarefa'])) {
                // encontra a tarefa, atualiza com o status correspondente e a data de finalização, e adiciona o histórico
                $infoTarefa = $POPElemento->getElementById($_POST['tarefa']);

                // várias consultas para verificar se há mais tarefas agrupadas do mesmo tipo para assumir
                $infoProjeto = $POPProjeto->getProjectById($infoTarefa['projeto']);
                $elementosProjeto = $POPElemento->getAllElements(['projeto'], [$infoProjeto['projeto']], TRUE);

                $tarefasProjeto = $POPElemento->filtraElementosPorBase($elementosProjeto, 1);

                foreach ($tarefasProjeto as $key => $value) {
                    // remove do array as tarefas com responsável diferente do atual e as com status diferente de "em andamento"
                    $arrayStatus = [4];
                    if ((isset($value['campos']['responsavel']) && $value['campos']['responsavel'] != $_SESSION['adm_usuario']) || !in_array($value['elementoStatus'], $arrayStatus)) {
                        unset($tarefasProjeto[$key]);
                    }
                }

                $tarefasProjeto = $POPElemento->agrupaTarefas($tarefasProjeto)['itensAgrupados'];

                // se houverem mais tarefas, atualiza num foreach
                if ($tarefasProjeto && isset($infoTarefa['campos']['Etapa'])) {
                    // foreach no array de tarefas pertencentes à mesma etapa que a tarefa $infoTarefa
                    foreach ($tarefasProjeto[$infoProjeto['projeto']][$infoTarefa['campos']['Etapa']] as $item) {
                        // se o responsável for o usuário atual, atualiza
                        if ($item['campos']['responsavel'] == $_SESSION['adm_usuario']) {
                            $retorno = $POPElemento->updateElement($item['elemento'], ['elementoStatus', 'dtFim'], [14, date('Y-m-d H:i:s')]);
                            $finaliza = $POPElemento->marcaFinalizada($item['elemento']);
                        }
                    }

                    if (isset($retorno, $finaliza) && $retorno && $finaliza) {
                        $redirect['redireciona'] = TRUE;
                        $_SESSION["msg_sucesso"] = "Tarefa finalizada com sucesso.";
                    }
                    else {
                        $redirect['redireciona'] = TRUE;
                        $_SESSION["msg_erro"] = "Houve algum problema ao finalizar a tarefa.";
                    }

                    // se não, atualiza a tarefa única
                }
                else {
                    // se o responsável for o usuário atual, atualiza
                    if ($infoTarefa['campos']['responsavel'] == $_SESSION['adm_usuario']) {
                        $retorno = $POPElemento->updateElement($infoTarefa['elemento'], ['elementoStatus', 'dtFim'], [8, date('Y-m-d H:i:s')]);
                        $finaliza = $POPElemento->marcaFinalizada($infoTarefa['elemento']);

                        if ($retorno && $finaliza) {
                            $redirect['redireciona'] = TRUE;
                            $_SESSION["msg_sucesso"] = "Tarefa finalizada com sucesso.";
                        }
                        else {
                            $redirect['redireciona'] = TRUE;
                            $_SESSION["msg_erro"] = "Houve algum problema ao finalizar a tarefa.";
                        }
                    }
                    else {
                        $redirect['redireciona'] = TRUE;
                        $_SESSION["msg_erro"] = "Você não é responsável por essa tarefa.";
                    }
                }

            }
            else {
                $redirect['redireciona'] = TRUE;
                $_SESSION["msg_erro"] = "Houve algum problema com a requisição.";
            }
            break;

        case 'editar-tarefa':
            if (isset($_POST['tarefa'])) {
                $infoTarefa = $POPElemento->getElementById($_POST['tarefa']);

                $_POST['prazo'] = $_POST['prazo'] ?? NULL;
                $_POST['responsavel'] = $_POST['responsavel'] ?? NULL;

                // várias consultas para verificar se há mais tarefas agrupadas do mesmo tipo para atualizar
                $infoProjeto = $POPProjeto->getProjectById($infoTarefa['projeto']);
                $elementosProjeto = $POPElemento->getAllElements(['projeto'], [$infoProjeto['projeto']], TRUE);

                $tarefasProjeto = $POPElemento->filtraElementosPorBase($elementosProjeto, 1);
                $tarefasProjeto = $POPElemento->agrupaTarefas($tarefasProjeto)['itensAgrupados'];

                // se houverem mais tarefas, atualiza num foreach
                if ($tarefasProjeto && isset($infoTarefa['campos']['Etapa'])) {
                    foreach ($tarefasProjeto[$infoProjeto['projeto']][$infoTarefa['campos']['Etapa']] as $item) {
                        $campos = ['prioridade', 'prazo', 'responsavel'];
                        $valores = [$_POST['prioridade'], $_POST['prazo'], $_POST['responsavel']];

                        // se a tarefa não estiver aguardando cliente, o status vai ser alterado
                        if ($item['elementoStatus'] != 3) {
                            $campos[] = 'elementoStatus';
                            $valores[] = ($_POST['responsavel']) ? 2 : 1;
                        }

                        $retorno = $POPElemento->updateElement($item['elemento'], $campos, $valores);
                    }

                    if ($retorno) {
                        $redirect['redireciona'] = TRUE;
                        $_SESSION["msg_sucesso"] = 'Tarefa atualizada com sucesso.';
                    }
                    else {
                        $redirect['redireciona'] = TRUE;
                        $_SESSION["msg_erro"] = 'Houve algum problema ao atualizar a tarefa.';
                    }

                    // se não, atualiza a tarefa única
                }
                else {
                    $campos = ['prioridade', 'prazo', 'responsavel'];
                    $valores = [$_POST['prioridade'], $_POST['prazo'], $_POST['responsavel']];

                    // se a tarefa não estiver aguardando cliente, o status vai ser alterado
                    if ($infoTarefa['elementoStatus'] != 3) {
                        $campos[] = 'elementoStatus';
                        $valores[] = ($_POST['responsavel']) ? 2 : 1;
                    }

                    $retorno = $POPElemento->updateElement($infoTarefa['elemento'], $campos, $valores);

                    if ($retorno) {
                        $redirect['redireciona'] = TRUE;
                        $_SESSION["msg_sucesso"] = 'Tarefa atualizada com sucesso.';
                    }
                    else {
                        $redirect['redireciona'] = TRUE;
                        $_SESSION["msg_erro"] = 'Houve algum problema ao atualizar a tarefa.';
                    }
                }

            }
            else {
                $_SESSION["msg_erro"] = 'Houve algum problema com a requisição.';
            }
            break;

        case 'remover-tarefa':
            if (isset($_POST['tarefa'])) {
                // encontra a tarefa e a atualiza com o novo responsável e o status correspondente
                $infoTarefa = $POPElemento->getElementById($_POST['tarefa']);

                // várias consultas para verificar se há mais tarefas agrupadas do mesmo tipo para assumir
                $infoProjeto = $POPProjeto->getProjectById($infoTarefa['projeto']);
                $elementosProjeto = $POPElemento->getAllElements(['projeto'], [$infoProjeto['projeto']], TRUE);

                $tarefasProjeto = $POPElemento->filtraElementosPorBase($elementosProjeto, 1);
                $tarefasProjeto = $POPElemento->agrupaTarefas($tarefasProjeto)['itensAgrupados'];

                // se houverem mais tarefas, atualiza num foreach
                if ($tarefasProjeto && isset($infoTarefa['campos']['Etapa'])) {
                    // foreach no array de tarefas pertencentes à mesma etapa que a tarefa $infoTarefa
                    foreach ($tarefasProjeto[$infoProjeto['projeto']][$infoTarefa['campos']['Etapa']] as $item) {
                        $campos = ['responsavel'];
                        $valores = [NULL];

                        // se a tarefa não estiver aguardando cliente, o status vai ser alterado
                        if ($item['elementoStatus'] != 3) {
                            $campos[] = 'elementoStatus';
                            $valores[] = 1;
                        }

                        $retorno = $POPElemento->updateElement($item['elemento'], $campos, $valores);
                    }

                    if (isset($retorno) && $retorno) {
                        $redirect['redireciona'] = TRUE;
                        $_SESSION["msg_sucesso"] = "Tarefa removida com sucesso.";
                    }
                    else {
                        $redirect['redireciona'] = TRUE;
                        $_SESSION["msg_erro"] = "Houve algum problema ao remover a tarefa.";
                    }

                    // se não, é tarefa única
                }
                else {
                    $campos = ['responsavel'];
                    $valores = [NULL];

                    // se a tarefa não estiver aguardando cliente, o status vai ser alterado
                    if ($infoTarefa['elementoStatus'] != 3) {
                        $campos[] = 'elementoStatus';
                        $valores[] = 1;
                    }

                    $retorno = $POPElemento->updateElement($infoTarefa['elemento'], $campos, $valores);

                    if ($retorno) {
                        $redirect['redireciona'] = TRUE;
                        $_SESSION["msg_sucesso"] = "Tarefa removida com sucesso.";
                    }
                    else {
                        $redirect['redireciona'] = TRUE;
                        $_SESSION["msg_erro"] = "Houve algum problema ao remover a tarefa.";
                    }
                }

            }
            else {
                $redirect['redireciona'] = TRUE;
                $_SESSION["msg_erro"] = "Houve algum problema com a requisição.";
            }
            break;


        /* ações em massa */
        case 'assumir-tarefas':
            if (isset($_POST['tarefas'])) {
                $listaIds = json_decode($_POST['tarefas']);

                foreach ($listaIds as $idTarefa) {
                    // encontra a tarefa e a atualiza com o novo responsável e o status correspondente
                    $infoTarefa = $POPElemento->getElementById($idTarefa);

                    // várias consultas para verificar se há mais tarefas agrupadas do mesmo tipo para atualizar
                    $infoProjeto = $POPProjeto->getProjectById($infoTarefa['projeto']);
                    $elementosProjeto = $POPElemento->getAllElements(['projeto'], [$infoProjeto['projeto']], TRUE);

                    $tarefasProjeto = $POPElemento->filtraElementosPorBase($elementosProjeto, 1);

                    foreach ($tarefasProjeto as $key => $value) {
                        // remove do array as tarefas com responsável, e as com status diferente de "aguardando responsável"
                        $arrayStatus = [1, 3];
                        if ($value['campos']['responsavel'] || !in_array($value['elementoStatus'], $arrayStatus)) {
                            unset($tarefasProjeto[$key]);
                        }
                    }

                    $tarefasProjeto = $POPElemento->agrupaTarefas($tarefasProjeto)['itensAgrupados'];

                    // se houverem mais tarefas, atualiza num foreach
                    if ($tarefasProjeto && isset($infoTarefa['campos']['Etapa'])) {
                        // foreach no array de tarefas de mesma etapa que a $infoTarefa
                        foreach ($tarefasProjeto[$infoProjeto['projeto']][$infoTarefa['campos']['Etapa']] as $item) {
                            // se não existir responsável, atualiza
                            if (!$item['campos']['responsavel']) {
                                $campos = ['responsavel'];
                                $valores = [$_SESSION['adm_usuario']];

                                // se a tarefa não estiver aguardando cliente, o status vai ser alterado
                                if ($item['elementoStatus'] != 3) {
                                    $campos[] = 'elementoStatus';
                                    $valores[] = 2;
                                }

                                $retorno = $POPElemento->updateElement($item['elemento'], $campos, $valores);
                            }
                            else {
                                $retorno = FALSE;
                            }
                        }

                        if (isset($retorno) && $retorno) {
                            $redirect['redireciona'] = TRUE;
                            $_SESSION['msg_sucesso'] = 'Tarefa assumida com sucesso.';
                        }
                        else {
                            $redirect['redireciona'] = TRUE;
                            $_SESSION['msg_erro'] = 'Houve algum problema ao assumir a tarefa.';
                        }

                        // se não, é tarefa única
                    }
                    else {
                        // se não existir responsável, atualiza
                        if (!$infoTarefa['campos']['responsavel']) {
                            $campos = ['responsavel'];
                            $valores = [$_SESSION['adm_usuario']];

                            // se a tarefa não estiver aguardando cliente, o status vai ser alterado
                            if ($infoTarefa['elementoStatus'] != 3) {
                                $campos[] = 'elementoStatus';
                                $valores[] = 2;
                            }

                            $retorno = $POPElemento->updateElement($infoTarefa['elemento'], $campos, $valores);

                            if ($retorno) {
                                $redirect['redireciona'] = TRUE;
                                $_SESSION['msg_sucesso'] = 'Tarefa assumida com sucesso.';
                            }
                            else {
                                $redirect['redireciona'] = TRUE;
                                $_SESSION['msg_erro'] = 'Houve algum problema ao assumir a tarefa.';
                            }
                        }
                        else {
                            $redirect['redireciona'] = TRUE;
                            $_SESSION['msg_erro'] = 'Esta tarefa já possui responsável.';
                        }
                    }
                }

            }
            else {
                $redirect['redireciona'] = TRUE;
                $_SESSION["msg_erro"] = "Houve algum problema com a requisição.";
            }
            break;

        case 'delegar-tarefas':
            if (isset($_POST['tarefas'])) {
                $listaIds = json_decode($_POST['tarefas']);

                foreach ($listaIds as $idTarefa) {
                    $infoTarefa = $POPElemento->getElementById($idTarefa);

                    $_POST['responsavel'] = $_POST['responsavel'] ?? NULL;

                    // várias consultas para verificar se há mais tarefas agrupadas do mesmo tipo para atualizar
                    $infoProjeto = $POPProjeto->getProjectById($infoTarefa['projeto']);
                    $elementosProjeto = $POPElemento->getAllElements(['projeto'], [$infoProjeto['projeto']], TRUE);

                    $tarefasProjeto = $POPElemento->filtraElementosPorBase($elementosProjeto, 1);
                    $tarefasProjeto = $POPElemento->agrupaTarefas($tarefasProjeto)['itensAgrupados'];

                    // se houverem mais tarefas, atualiza num foreach
                    if ($tarefasProjeto && isset($infoTarefa['campos']['Etapa'])) {
                        foreach ($tarefasProjeto[$infoProjeto['projeto']][$infoTarefa['campos']['Etapa']] as $item) {
                            $campos = ['prioridade', 'responsavel'];
                            $valores = [$_POST['prioridade'], $_POST['responsavel']];

                            // se a tarefa não estiver aguardando cliente, o status vai ser alterado
                            if ($item['elementoStatus'] != 3) {
                                $campos[] = 'elementoStatus';
                                $valores[] = ($_POST['responsavel']) ? 2 : 1;
                            }

                            $retorno = $POPElemento->updateElement($item['elemento'], $campos, $valores);
                        }

                        if ($retorno) {
                            $redirect['redireciona'] = TRUE;
                            $_SESSION['msg_sucesso'] = 'Tarefa atualizada com sucesso.';
                        }
                        else {
                            $redirect['redireciona'] = TRUE;
                            $_SESSION['msg_erro'] = 'Houve algum problema ao atualizar a tarefa.';
                        }

                        // se não, atualiza a tarefa única
                    }
                    else {
                        $campos = ['prioridade', 'responsavel'];
                        $valores = [$_POST['prioridade'], $_POST['responsavel']];

                        // se a tarefa não estiver aguardando cliente, o status vai ser alterado
                        if ($infoTarefa['elementoStatus'] != 3) {
                            $campos[] = 'elementoStatus';
                            $valores[] = ($_POST['responsavel']) ? 2 : 1;
                        }

                        $retorno = $POPElemento->updateElement($infoTarefa['elemento'], $campos, $valores);

                        if ($retorno) {
                            $redirect['redireciona'] = TRUE;
                            $_SESSION['msg_sucesso'] = 'Tarefa atualizada com sucesso.';
                        }
                        else {
                            $redirect['redireciona'] = TRUE;
                            $_SESSION['msg_erro'] = 'Houve algum problema ao atualizar a tarefa.';
                        }
                    }
                }

            }
            else {
                $redirect['redireciona'] = TRUE;
                $_SESSION["msg_erro"] = 'Houve algum problema com a requisição.';
            }
            break;

        case 'remover-tarefas':
            if (isset($_POST['tarefas'])) {
                $listaIds = json_decode($_POST['tarefas']);

                foreach ($listaIds as $idTarefa) {
                    // encontra a tarefa e a atualiza com o novo responsável e o status correspondente
                    $infoTarefa = $POPElemento->getElementById($idTarefa);

                    // várias consultas para verificar se há mais tarefas agrupadas do mesmo tipo para assumir
                    $infoProjeto = $POPProjeto->getProjectById($infoTarefa['projeto']);
                    $elementosProjeto = $POPElemento->getAllElements(['projeto'], [$infoProjeto['projeto']], TRUE);

                    $tarefasProjeto = $POPElemento->filtraElementosPorBase($elementosProjeto, 1);
                    $tarefasProjeto = $POPElemento->agrupaTarefas($tarefasProjeto)['itensAgrupados'];

                    // se houverem mais tarefas, atualiza num foreach
                    if ($tarefasProjeto && isset($infoTarefa['campos']['Etapa'])) {

                        // foreach no array de tarefas pertencentes à mesma etapa que a tarefa $infoTarefa
                        foreach ($tarefasProjeto[$infoProjeto['projeto']][$infoTarefa['campos']['Etapa']] as $item) {
                            // se o responsável for o usuário atual, atualiza
                            $campos = ['responsavel'];
                            $valores = [NULL];

                            // se a tarefa não estiver aguardando cliente, o status vai ser alterado
                            if ($item['elementoStatus'] != 3) {
                                $campos[] = 'elementoStatus';
                                $valores[] = 1;
                            }

                            $retorno = $POPElemento->updateElement($item['elemento'], $campos, $valores);
                        }

                        if (isset($retorno) && $retorno) {
                            $redirect['redireciona'] = TRUE;
                            $_SESSION["msg_sucesso"] = "Tarefa removida com sucesso.";
                        }
                        else {
                            $redirect['redireciona'] = TRUE;
                            $_SESSION["msg_erro"] = "Houve algum problema ao remover a tarefa.";
                        }

                        // se não, é tarefa única
                    }
                    else {
                        $campos = ['responsavel'];
                        $valores = [NULL];

                        // se a tarefa não estiver aguardando cliente, o status vai ser alterado
                        if ($infoTarefa['elementoStatus'] != 3) {
                            $campos[] = 'elementoStatus';
                            $valores[] = 1;
                        }

                        $retorno = $POPElemento->updateElement($infoTarefa['elemento'], $campos, $valores);

                        if ($retorno) {
                            $redirect['redireciona'] = TRUE;
                            $_SESSION["msg_sucesso"] = "Tarefa removida com sucesso.";
                        }
                        else {
                            $redirect['redireciona'] = TRUE;
                            $_SESSION["msg_erro"] = "Houve algum problema ao remover a tarefa.";
                        }
                    }
                }

            }
            else {
                $redirect['redireciona'] = TRUE;
                $_SESSION["msg_erro"] = "Houve algum problema com a requisição.";
            }
            break;

    }
    if ($redirect['redireciona']) {
        Utils::redirect($redirect["url"]);
    }
}
