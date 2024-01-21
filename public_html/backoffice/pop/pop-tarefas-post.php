<?php
if (isset($_POST['form'])) {
    $dtAtual = date('Y-m-d H:i:s');

    switch ($_POST['form']) {
        case 'editar-tarefa':
            if (isset($_POST['tarefa'])) {
                $infoTarefa = $POPElemento->getElementById($_POST['tarefa']);

                $_POST['prazo'] = $_POST['prazo'] ? $_POST['prazo'] : NULL;
                $_POST['responsavel'] = ($_POST['responsavel'] && $_POST['responsavel'] != '') ? $_POST['responsavel'] : NULL;

                // várias consultas para verificar se há mais tarefas agrupadas do mesmo tipo para atualizar
                $infoProjeto = $POPProjeto->getProjectById($infoTarefa['projeto']);
                $elementosProjeto = $POPElemento->getAllElements(['projeto'], [$infoProjeto['projeto']]);

                $tarefasProjeto = $POPElemento->filtraElementosPorBase($elementosProjeto, 1);
                $tarefasProjeto = $POPElemento->agrupaTarefas($tarefasProjeto)['itensAgrupados'];

                // se houverem mais tarefas, atualiza num foreach
                if ($tarefasProjeto && isset($infoTarefa['campos']['Etapa'])) {
                    foreach ($tarefasProjeto[$infoProjeto['projeto']][$infoTarefa['campos']['Etapa']] as $item) {
                        if ($_POST['responsavel'] && !$item['campos']['responsavel']) {
                            $retorno = $POPElemento->updateElement($item['elemento'], ['prioridade', 'prazo', 'responsavel', 'elementoStatus'], [$_POST['prioridade'], $_POST['prazo'], $_POST['responsavel'], 2]);
                        }
                        else {
                            $retorno = $POPElemento->updateElement($item['elemento'], ['prioridade', 'prazo', 'responsavel'], [$_POST['prioridade'], $_POST['prazo'], $_POST['responsavel']]);
                        }
                    }

                    if ($retorno) {
                        $_SESSION["msg_sucesso"] = 'Tarefa atualizada com sucesso.';
                    }
                    else {
                        $_SESSION["msg_erro"] = 'Houve algum problema ao atualizar a tarefa.';
                    }

                    // se não, atualiza a tarefa única
                }
                else {
                    if ($_POST['responsavel'] && !$infoTarefa['campos']['responsavel']) {
                        $retorno = $POPElemento->updateElement($infoTarefa['elemento'], ['prioridade', 'prazo', 'responsavel', 'elementoStatus'], [$_POST['prioridade'], $_POST['prazo'], $_POST['responsavel'], 2]);
                    }
                    else {
                        $retorno = $POPElemento->updateElement($infoTarefa['elemento'], ['prioridade', 'prazo', 'responsavel'], [$_POST['prioridade'], $_POST['prazo'], $_POST['responsavel']]);
                    }

                    if ($retorno) {
                        $_SESSION["msg_sucesso"] = 'Tarefa atualizada com sucesso.';
                    }
                    else {
                        $_SESSION["msg_erro"] = 'Houve algum problema ao atualizar a tarefa.';
                    }
                }

            }
            else {
                $_SESSION["msg_erro"] = 'Houve algum problema com a requisição.';
            }
            break;

        default:
            break;
    }
}
