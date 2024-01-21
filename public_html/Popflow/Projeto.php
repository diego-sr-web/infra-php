<?php
/**
 * Popflow - Sistema de monitoramento e organizacao de tarefas e projetos
 * PHP Version 7.2
 *
 * @package    Nerdweb
 * @author     Rafael Rotelok rotelok@nerdweb.com.br
 * @author     Junior Neves junior@nerdweb.com.br
 * @author     Giovane Ferreira giovane.ferreira@nerdweb.com.br
 * @copyright  2012-2020 Extreme Hosting Servicos de Internet LTDA
 * @license    https://nerdpress.com.br/license.txt
 * @version    Release: 3.5.0
 * @revision   2020-02-10
 */

class Projeto {
    /** @var Elemento $element */
    public $objElemento;
    /** @var DataBase $database */
    protected $database;
    protected $tbl_Projeto = 'pop_Projeto';
    protected $tbl_ProjetoTipo = 'pop_ProjetoTipo';
    protected $tbl_ElementoProximo = 'pop_ElementoProximo';
    protected $tbl_ElementoAnterior = 'pop_ElementoAnterior';
    protected $tbl_ElementoPrimeiro = 'pop_ElementoPrimeiro';
    protected $tbl_ProjetoConteudo = 'pop_ProjetoConteudo';
    protected $tbl_ProjetoTipoNomeTipo = 'pop_ProjetoTipoNomeTipo';
    protected $tbl_ProjetoDadosTempoaraios = 'pop_ProjetoDadosTemporarios';

    /**
     * Projeto constructor.
     *
     * @param       $database
     * @param array $args
     */
    public function __construct($database, $args = []) {
        $this->database = $database;
        $this->objElemento = new Elemento($database);
    }


    /**
     * Le os dados do projeto pelo id passado
     *
     * @param      $palavras
     * @param bool $finalizados
     *
     * @return array
     */
    public function getProjectsByName($palavras, $finalizados = FALSE) {
        $busca = explode(" ", $palavras);
        $projeto = $this->database->searchByKeyWord($this->tbl_Projeto, ['nome'], $busca);
        if (isset($projeto[0])) {
            foreach ($projeto as $key => $value) {
                if ($value["finalizado"] == 1) {
                    unset($projeto[$key]);
                }
            }
            return $projeto;
        }
        return [];
    }


    /**
     * @param array $campos
     * @param array $valores
     *
     * @return int
     */
    public function getProjectCount($campos = [], $valores = []) {
        $listaIds = $this->database->selectPrepared($this->tbl_Projeto, $campos, $valores, "projeto");
        return count($listaIds);
    }


    /**
     * Le uma lista de todos os projetos registrados
     *
     * @param array  $campos
     * @param array  $valores
     * @param string $order
     * @param string $limit
     *
     * @return array
     */
    public function getProjectList($campos = [], $valores = [], $order = '', $limit = '') {
        if (count($campos) === 0) {
            $listaIds = $this->database->ngSelectPrepared($this->tbl_Projeto, [], "projeto,nome,prazo", $order, $limit);
        }
        else {
            $listaIds = $this->database->selectPrepared($this->tbl_Projeto, $campos, $valores, "projeto,nome,prazo", $order, $limit);
        }

        $lista_projetos = [];
        foreach ($listaIds as $id) {
            $lista_projetos[] = $this->getProjectById($id["projeto"]);
        }

        return $lista_projetos;
    }

    /**
     * Le os dados do projeto pelo id passado
     *
     * @param int $projetoId
     *
     * @return array
     */
    public function getProjectById($projetoId) {
        $projeto = $this->database->ngSelectPrepared($this->tbl_Projeto, ['projeto' => $projetoId], '', '', 1);

        if (isset($projeto['projeto'])) {
            $tipo = $this->database->ngSelectPrepared($this->tbl_ProjetoTipo, ['projetoTipo' => $projeto['projetoTipo']], '', '', 1);
            $lista_campos = $this->database->ngSelectPrepared($this->tbl_ProjetoTipoNomeTipo, ['projetoTipo' => $projeto['projetoTipo']]);
            if (isset($tipo['projetoTipo'])) {
                $conteudo = $this->database->ngSelectPrepared($this->tbl_ProjetoConteudo, ['projeto' => $projetoId]);
                $projeto["campos"] = [];
                $nomesCampos = [];
                foreach ($lista_campos as $key => $value) {
                    $nomesCampos[$value["nome"]] = $value["nomeExibicao"];
                }
                foreach ($conteudo as $subitem) {
                    $projeto["campos"][$subitem['chave']] = $subitem['valor'];
                    $projeto["nomesCampos"][$subitem["chave"]] = '';
                    if ((isset($nomesCampos[$subitem["chave"]]))) {
                        $projeto["nomesCampos"][$subitem["chave"]] = $nomesCampos[$subitem["chave"]];
                    }
                }
            }
            return $projeto;
        }

        return [];
    }


    /**
     * @return int
     */
    public function getProjectTypeCount() {
        $listaIds = $this->database->ngSelectPrepared($this->tbl_ProjetoTipo, [], "projetoTipo");
        return count($listaIds);
    }


    /**
     * Le uma lista de todos os tipos de projetos registrados
     *
     * @param array  $campos
     * @param array  $valores
     * @param string $order
     * @param string $limit
     *
     * @return array
     */
    public function getProjectTypeList($campos = [], $valores = [], $order = '', $limit = '') {
        $size = count($campos);

        if ($size === 0) {
            $listaIds = $this->database->ngSelectPrepared($this->tbl_ProjetoTipo, [], "projetoTipo,nome", $order, $limit);
        }
        else {
            $listaIds = $this->database->selectPrepared($this->tbl_ProjetoTipo, $campos, $valores, "projetoTipo,nome", $order, $limit);
        }

        $lista_tipo_projetos = [];
        foreach ($listaIds as $id) {
            $lista_tipo_projetos[] = $this->getProjectTypeById($id["projetoTipo"]);
        }

        return $lista_tipo_projetos;
    }


    /**
     * Le os dados de um Tipo de projeto pelo id tipo de projeto
     *
     * @param int $projetoTipoId
     *
     * @return array
     */
    public function getProjectTypeById($projetoTipoId) {
        $tipo = $this->database->ngSelectPrepared($this->tbl_ProjetoTipo, ['projetoTipo' =>$projetoTipoId], '', '', 1);

        if (isset($tipo['projetoTipo'])) {
            $lista_campos = $this->database->ngSelectPrepared($this->tbl_ProjetoTipoNomeTipo, ['projetoTipo' => $projetoTipoId]);
            $tipo["campos"] = [];
            $tipo["nomesCampos"] = [];
            foreach ($lista_campos as $lista_campo) {
                $tipo["campos"][$lista_campo["nome"]] = $lista_campo["tipo"];
                $tipo["nomesCampos"][$lista_campo["nome"]] = $lista_campo["nomeExibicao"];
            }
            return $tipo;
        }

        return [];
    }


    /**
     * @param int $projetoId
     *
     * @return null|string
     */
    public function getProjectPrazo($projetoId) {
        $projeto = $this->database->ngSelectPrepared($this->tbl_Projeto, ["projeto" => $projetoId], "projeto,prazo", "", 1);
        if (isset($projeto["projeto"])) {
            return $projeto["prazo"];
        }
        return NULL;

    }


    /**
     * Le os anteriores elementos de uma tarefa pelo id do projeto e id do elemento
     *
     * @param int $elementoId
     * @param int $projetoId
     *
     * @return array
     */
    public function getAnteriorEtapa($elementoId, $projetoId) {
        $proj = $this->getProjectTypeById($projetoId);
        $elem = $this->objElemento->getElementTypeByElementId($elementoId);

        if (isset($proj['projeto'])) {
            return $this->getProximaEtapaByProjectTypeId($elem['elementoTipo'], $proj['projetoTipo']);
        }

        return [];
    }


    /**
     * Le os proximos elementos de uma tarefa pelo id do tipo de projeto e id do tipo de elemento
     *
     * @param int $elementoTipoId
     * @param int $projetoTipoId
     *
     * @return array
     */
    public function getProximaEtapaByProjectTypeId($elementoTipoId, $projetoTipoId) {
        $nextList = $this->database->ngSelectPrepared($this->tbl_ElementoProximo, ['projetoTipo' => $projetoTipoId, 'elementoTipo' => $elementoTipoId]);

        if (isset($nextList[0])) {
            return $nextList;
        }

        return [];
    }


    /**
     * Le os primeiros elementos do projeto pelo id do projeto
     *
     * @param int $projetoId
     *
     * @return array
     */
    public function getProjectPrimeiros($projetoId) {
        $tipo = $this->getProjectTypeById($projetoId);

        if (isset($tipo['projetoTipo'])) {
            return $this->getProjectPrimeirosByProjectId($tipo['projetoTipo']);
        }

        return [];
    }


    /**
     * Le os primeiros elementos do projeto
     *
     * @param $projetoTipoId
     *
     * @return array
     */
    public function getProjectPrimeirosByProjectId($projetoTipoId) {
        $listaPrimeiros = $this->database->ngSelectPrepared($this->tbl_ElementoPrimeiro, ['projetoTipo' => $projetoTipoId]);

        if (isset($listaPrimeiros[0])) {
            return $listaPrimeiros;
        }

        return [];
    }


    /**
     * @param array $campos
     * @param array $valores
     *
     * @return bool
     */
    public function insertProjectType($campos = [], $valores = []) {
        if (count($campos) !== count($valores)) {
            die("Parametros passados errado");
        }
        $size = count($campos);
        $mixed = [];
        $dbCampos = [];
        $dbValores = [];

        for ($i = 0; $i < $size; $i++) {
            $mixed[$campos[$i]] = $valores[$i];
        }
        if (!isset($mixed["nome"])) {
            die("nome eh obrigatorio");
        }
        if (!isset($mixed["identifier"])) {
            die("identifier eh obrigatorio");
        }
        $mixed["descricao"] = $mixed["descricao"] ?? NULL;
        $mixed["cor"] = $mixed["cor"] ?? NULL;
        $mixed["icone"] = $mixed["icone"] ?? NULL;
        $camposTMP = ["nome", "descricao", "diasPrazo", "cor", "icone", "identifier"];
        foreach ($camposTMP as $campo) {
            $dbCampos[] = $campo;
            $dbValores[] = $mixed[$campo];
            unset($mixed[$campo]);
        }
        $this->database->insertPrepared($this->tbl_ProjetoTipo, $dbCampos, $dbValores);
        $pid = $this->database->returnLastInsertedId();
        foreach ($mixed as $key => $value) {
            $this->database->ngInsertPrepared($this->tbl_ProjetoTipoNomeTipo, ["projetoTipo" => $pid, "nome" => $key, "tipo" => $value]);
        }
        return $pid;
    }


    /**
     * @param       $projectTypeId
     * @param array $campos
     * @param array $valores
     *
     * @return bool
     */
    public function updateProjectType($projectTypeId, $campos = [], $valores = []) {
        $projetoTipo = $this->getProjectTypeById($projectTypeId);

        if (isset($projetoTipo['projetoTipo'])) {
            //var_dump($campos, $valores);
            $this->database->updatePrepared($this->tbl_ProjetoTipo, $campos, $valores, ['projetoTipo'], [$projectTypeId]);
            return TRUE;
        }

        return FALSE;
    }

    /**
     * @param $projetoId
     * @param $prazoEstimado
     * @param $prazoTarefa
     *
     * @return bool
     */
    public function updatePrazoProjeto($projetoId, $prazoEstimado, $prazoTarefa) {
        if ($prazoEstimado != NULL) {
            $dif = (strtotime($prazoTarefa) - strtotime(date('Y-m-d'))) / (60 * 60 * 24);
            $op = ($dif > 0) ? '-' : '+';
            $novoPrazo = date('Y-m-d', strtotime($op . abs($dif) . ' days', strtotime($prazoEstimado)));

            $this->updateProject($projetoId, ['prazoEstimado'], [$novoPrazo]);

            return TRUE;
        }

        return FALSE;
    }

    /**
     * @param       $projectId
     * @param array $campos
     * @param array $valores
     *
     * @return bool
     */
    public function updateProject($projectId, $campos = [], $valores = []) {
        $projeto = $this->getProjectById($projectId);

        if (isset($projeto['projeto'])) {
            //var_dump($campos, $valores);
            $this->database->updatePrepared($this->tbl_Projeto, $campos, $valores, ['projeto'], [$projectId]);
            return TRUE;
        }

        return FALSE;
    }

    /**
     * @param       $nome
     * @param       $tipo
     * @param       $dataEntrada
     * @param       $prazo
     * @param       $prazoEstimado
     * @param       $cliente
     * @param array $args
     *
     * @return bool
     */
    public function createProject($nome, $tipo, $dataEntrada, $prazo, $prazoEstimado, $cliente, $args = []) {
        $projectType = $this->getProjectTypeById($tipo);

        if (isset($projectType["projetoTipo"])) {
            $extraFields = $this->database->ngSelectPrepared($this->tbl_ProjetoTipoNomeTipo, ['projetoTipo' => $tipo]);

            $this->database->insertPrepared($this->tbl_Projeto, ['nome', 'dataEntrada', 'prazo', 'prazoEstimado', 'finalizado', 'projetoTipo', 'cliente'], [$nome, $dataEntrada, $prazo, $prazoEstimado, 0, $tipo, $cliente]);
            $pid = $this->database->returnLastInsertedId();
            $firstElements = $this->getProjectPrimeirosByProjectId($tipo);
            if (isset($firstElements[0])) {
                foreach ($firstElements as $element) {
                    $tipoElemento = $element['elementoTipo'];
                    $statusElemento = $args['elementoStatus'] ?? 1;
                    $criacaoElemento = $args['dataCriacao'] ?? date('Y-m-d H:i:s');
                    $atualizacaoElemento = $args['dataAtualizacao'] ?? date('Y-m-d H:i:s');
                    $lista_Areas = $this->objElemento->getElementTipoArea($tipoElemento);
                    $aid = NULL;
                    foreach ($lista_Areas as $area) {
                        $aid = $area["area"];
                    }

                    $this->objElemento->insertElement(['dataCriacao', 'dataAtualizacao', 'elementoTipo', 'elementoStatus', 'projeto', 'prioridade', 'area'], [$criacaoElemento, $atualizacaoElemento, $tipoElemento, $statusElemento, $pid, 102, $aid]);
                }
            }

            if (isset($extraFields[0])) {
                foreach ($extraFields as $extraField) {
                    // inicializa campos extra
                    $value = $args['campos'][$extraField['nome']] ?? NULL;
                    $this->updateExtraField($pid, $extraField['nome'], $value);
                }
            }

            return $pid;
        }

        return FALSE;

    }


    /**
     * @param int    $pid
     * @param string $chave
     * @param string $value
     *
     * @return bool
     */
    public function updateExtraField($pid, $chave, $value) {
        $projeto = $this->database->ngSelectPrepared($this->tbl_Projeto, ["projeto" => $pid], "", "", 1);
        if (isset($projeto["projeto"])) {
            $campo = $this->database->ngSelectPrepared($this->tbl_ProjetoConteudo, ["projeto" => $pid, "chave" => $chave], "", "", 1);
            if (isset($campo["projeto"])) {
                $this->database->ngUpdatePrepared($this->tbl_ProjetoConteudo, ["valor" => $value], ["projeto" => $pid, "chave" => $chave]);
            }
            else {
                $this->database->ngInsertPrepared($this->tbl_ProjetoConteudo, ["projeto" => $pid, "chave" => $chave, "valor" => $value]);
            }
            return TRUE;
        }
        return FALSE;
    }


    /**
     * Verifica se um projeto está apto a ser finalizado e caso positivo, finaliza
     *
     * @param $projetoId
     *
     * @return array|bool
     */
    public function arquivaProjeto($projetoId, $arquivado = FALSE) {
        $projeto = $this->getProjectById($projetoId);
        if (isset($projeto["projeto"]) && $projeto["projetoTipo"] == 9) {
            return FALSE;
        }

        if (isset($projeto['projeto']) && $projeto['finalizado'] == 0) {
            $elementosProjeto = $this->objElemento->getAllElements(['projeto'], [$projeto['projeto']]);
            $listaTarefas = [];

            // Finaliza todos os elementos que ainda tiverem em aberto
            foreach ($elementosProjeto as $elemento) {
                if ($elemento['elementoStatus'] != 9) {
                    if ($arquivado) {
                        $POPChat = new Chat($this->database);
                        $this->objElemento->setStatus($elemento["elemento"],16);
                        $POPChat->addProjectChat($projetoId, $_SESSION['adm_usuario'], "projeto arquivado", $elemento["elemento"], 1);
                    }else {
                        $this->objElemento->setStatusFinalizadoEProcessado($elemento["elemento"]);
                    }
                }
            }

            // verifica se existem elementos com status diferente de "finalizado e processado"
            foreach ($listaTarefas as $tarefa) {
                if ($tarefa['elementoStatus'] != '9') {
                    $finaliza = FALSE;
                }
            }
            $finaliza = TRUE;

            // se estiver ok, finaliza  o projeto
            if ($finaliza) {
                return $this->database->ngUpdatePrepared($this->tbl_Projeto, ['finalizado' => 1], ['projeto' => $projeto['projeto']]);
            }

            return FALSE;
        }

        return FALSE;
    }


    /**
     * Verifica se um projeto está apto a ser finalizado e caso positivo, finaliza
     *
     * @param $projetoId
     *
     * @return bool
     */
    public function finalizaProjeto($projetoId) {
        $projeto = $this->getProjectById($projetoId);
        // Evita fechar o projeto de Pedidos ( ateh achar um modo melhor esse hack vai ter q resolver )
        if (isset($projeto["projeto"]) && ($projeto["projetoTipo"] == 9 || $projeto["projetoTipo"] == 20)) {
            return FALSE;
        }

        if (isset($projeto['projeto']) && $projeto['finalizado'] == 0) {
            $elementosProjeto = $this->objElemento->getAllElements(['projeto'], [$projeto['projeto']]);
            $listaTarefas = [];

            // filtra os elementos que são tarefa (base == 1)
            foreach ($elementosProjeto as $elemento) {
                $base = $this->objElemento->getElementBase($elemento['elemento']);
                $arrayBase = [];

                foreach ($base as $aux) {
                    $arrayBase[] = $aux['colunaBase'];
                }

                if (in_array(1, $arrayBase)) {
                    $listaTarefas[] = $elemento;
                }
            }

            $finaliza = TRUE;
            // verifica se existem elementos com status diferente de "finalizado e processado"
            foreach ($listaTarefas as $tarefa) {
                if ($tarefa['elementoStatus'] != '9' && $tarefa["elementoStatus"] != '16') {
                    $finaliza = FALSE;
                }
            }

            // Hack pra criar as tarefas que precisa qdo os posts estao finalizados ou
            // qdo o website esta com todas as paginas internas concluidas
            if (($finaliza == TRUE) && ($projeto["projetoTipo"] == 2 || $projeto["projetoTipo"] == 10)) {
                /*
                if ($projeto["projetoTipo"] == 2) {
                    $encerrado = FALSE;
                    foreach ($listaTarefas as $tarefa) {
                        if ($tarefa["elementoTipo"] == 39) {
                            $encerrado = TRUE;
                        }
                    }
                    if (!$encerrado) {
                        $pid = $projeto["projeto"];
                        $tipoElemento = 39;
                        $aid = 2;
                        $statusElemento = 1;
                        $criacaoElemento = date('Y-m-d H:i:s');
                        $atualizacaoElemento = date('Y-m-d H:i:s');

                        $this->objElemento->insertElement(
                            ['dataCriacao', 'dataAtualizacao', 'elementoTipo', 'elementoStatus', 'projeto', 'prioridade', 'area'],
                            [$criacaoElemento, $atualizacaoElemento, $tipoElemento, $statusElemento, $pid, 102, $aid]
                        );
                        // Marca q nao eh pra finalizar o projeto ainda
                        $finaliza = FALSE;
                    }
                }
                */
                if ($projeto["projetoTipo"] == 10) {
                    $encerrado = FALSE;
                    foreach ($listaTarefas as $tarefa) {
                        if ($tarefa["elementoTipo"] == 74) {
                            $encerrado = TRUE;
                        }
                    }
                    if (!$encerrado) {
                        $pid = $projeto["projeto"];
                        $statusElemento = 1;
                        $criacaoElemento = date('Y-m-d H:i:s');
                        $atualizacaoElemento = date('Y-m-d H:i:s');

                        // Criar Lista de Conteudo
                        $tipoElemento = 68;
                        $aid = 3;
                        $this->objElemento->insertElement(
                            ['dataCriacao', 'dataAtualizacao', 'elementoTipo', 'elementoStatus', 'projeto', 'prioridade', 'area'],
                            [$criacaoElemento, $atualizacaoElemento, $tipoElemento, $statusElemento, $pid, 102, $aid]
                        );

                        // Montar HTML / CSS
                        //$tipoElemento = 70;
                        //$aid = 7;
                        //$this->objElemento->insertElement(['dataCriacao', 'dataAtualizacao', 'elementoTipo', 'elementoStatus', 'projeto', 'prioridade', 'area'], [$criacaoElemento, $atualizacaoElemento, $tipoElemento, $statusElemento, $pid, 102, $aid]);


                        // Criar Lista de Modulos
                        $tipoElemento = 73;
                        $aid = 3;
                        $this->objElemento->insertElement(
                            ['dataCriacao', 'dataAtualizacao', 'elementoTipo', 'elementoStatus', 'projeto', 'prioridade', 'area', 'Etapa'],
                            [$criacaoElemento, $atualizacaoElemento, $tipoElemento, $statusElemento, $pid, 102, $aid, NULL]
                        );

                        // Marca q nao eh pra finalizar o projeto ainda
                        $finaliza = FALSE;
                    }
                }
            }

            // se estiver ok, finaliza  o projeto
            if ($finaliza) {
                $this->database->ngUpdatePrepared($this->tbl_Projeto, ['finalizado' => 1], ['projeto' => $projeto['projeto']]);
                return TRUE;
            }

            return FALSE;
        }

        return FALSE;
    }

    /**
     * @param $elementoId
     *
     * @return bool
     */
    public function avancaEtapa($elementoId) {
        //var_dump($elementoId);
        $elemento = $this->objElemento->getElementById($elementoId);

        if (isset($elemento['elemento']) && $elemento['elementoStatus'] == 8) {
            //var_dump('concluido');
            $proximos = $this->getProximaEtapaByProjectId($elemento['elemento'], $elemento['projeto']);
            $pid = $elemento['projeto'];

            foreach ($proximos as $proximo) {
                $tipoElemento = $proximo['proximo'];
                $statusElemento = 1;
                $criacaoElemento = date('Y-m-d H:i:s');
                $atualizacaoElemento = date('Y-m-d H:i:s');

                $lista_Areas = $this->objElemento->getElementTipoArea($tipoElemento);
                $aid = NULL;
                foreach ($lista_Areas as $area) {
                    $aid = $area["area"];
                }
                $this->objElemento->insertElement(
                    ['dataCriacao', 'dataAtualizacao', 'elementoTipo', 'elementoStatus', 'projeto', 'prioridade', 'area'],
                    [$criacaoElemento, $atualizacaoElemento, $tipoElemento, $statusElemento, $pid, 102, $aid]
                );
            }

            // Muda o status pra finalizado e processado,
            // pra nao processar denovo caso rode outra vez o mesmo loop
            $this->objElemento->setStatusFinalizadoEProcessado($elemento["elemento"]);
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Le os proximos elementos de uma tarefa pelo id do elemento e id do projeto
     *
     * @param int $elementoId
     * @param int $projetoId
     *
     * @return array
     */
    public function getProximaEtapaByProjectId($elementoId, $projetoId) {
        $elemTipo = $this->objElemento->getElementTypeByElementId($elementoId);
        $projTipo = $this->getProjectTypeByProjectId($projetoId);

        if (isset($elemTipo['elementoTipo'], $projTipo['projetoTipo'])) {
            return $this->getProximaEtapaByProjectTypeId($elemTipo['elementoTipo'], $projTipo['projetoTipo']);
        }

        return [];
    }

    /**
     * Le os dados de um Tipo de projeto pelo id do projeto
     *
     * @param int $projetoId
     *
     * @return array
     */
    public function getProjectTypeByProjectId($projetoId) {
        $proj = $this->getProjectById($projetoId);

        if (isset($proj['projeto'])) {
            return $this->getProjectTypeById($proj['projetoTipo']);
        }

        return [];
    }

    /**
     * @param $elementoId
     *
     * @return bool
     */
    public function voltaEtapa($elementoId) {
        //var_dump($elementoId);
        $elemento = $this->objElemento->getElementById($elementoId);

        if (isset($elemento['elemento']) && $elemento['elementoStatus'] == 10) {
            //var_dump('concluido');
            $projeto = $this->getProjectTypeByProjectId($elemento['projeto']);
            $anteriores = $this->getAnteriorEtapaByProjectTypeId($elemento['elementoTipo'], $projeto['projetoTipo']);
            $pid = $elemento['projeto'];
            foreach ($anteriores as $anterior) {
                $tipoElemento = $anterior['anterior'];
                $statusElemento = 1;
                $criacaoElemento = date('Y-m-d H:i:s');
                $atualizacaoElemento = date('Y-m-d H:i:s');

                $lista_Areas = $this->objElemento->getElementTipoArea($tipoElemento);
                $aid = NULL;
                foreach ($lista_Areas as $area) {
                    $aid = $area["area"];

                }
                $this->objElemento->insertElement(
                    ['dataCriacao', 'dataAtualizacao', 'elementoTipo', 'elementoStatus', 'projeto', 'prioridade', 'area'],
                    [$criacaoElemento, $atualizacaoElemento, $tipoElemento, $statusElemento, $pid, 102, $aid]
                );
            }

            // Muda o status pra finalizado e processado,
            // pra nao processar denovo caso rode outra vez o mesmo loop
            $this->objElemento->updateElement($elemento['elemento'], ['elementoStatus'], [11]);
            return TRUE;
        }

        return FALSE;
    }


    /**
     * Le os anteriores elementos de uma tarefa pelo id do tipo de projeto e id do tipo de elemento
     *
     * @param int $elementoTipoId
     * @param int $projetoTipoId
     *
     * @return array
     */
    public function getAnteriorEtapaByProjectTypeId($elementoTipoId, $projetoTipoId) {
        $prevList = $this->database->ngSelectPrepared($this->tbl_ElementoAnterior, ['projetoTipo' => $projetoTipoId, 'elementoTipo' => $elementoTipoId]);
        if (isset($prevList[0])) {
            return $prevList;
        }

        return [];
    }


    /**
     * @param int $elementoId
     *
     * @return bool
     */
    public function avancaSubEtapa($elementoId) {
        return $this->processaSubEtapa($elementoId, "avanca");
    }

    /**
     * @param int    $elementoId
     * @param string $direcao
     *
     * @return bool
     */
    private function processaSubEtapa($elementoId, $direcao) {
        $elemento = $this->objElemento->getElementById($elementoId);
        if (isset($elemento['elemento'], $elemento["campos"]["Etapa"]) && ($elemento['elementoStatus'] == 14 || $elemento['elementoStatus'] == 15)) {
            $elemento_Tipo = $elemento["elementoTipo"];
            $etapa_atual = $elemento["campos"]["Etapa"];

            // Aqui precisa por o codigo que tenta descobrir qual a etapa inicial segundo o tipo do elemento,
            // nesse caso funciona apenas pra facebook, uma ideia seria ler da tabela pop_ElementoTipoSubEtapa, organizar por etapa
            // e pegar o elemento que o anterior eh NULL ( teoricamente esse eh o primeiro elemento )
            if ($etapa_atual === NULL) {
                $etapa_atual = 1;
            }

            $info = [];
            $etapa_atual_info = $this->objElemento->get_SubEtapa_Info($elemento_Tipo, $etapa_atual);
            if ($elemento['elementoStatus'] == 14 && $direcao === "avanca") {
                $proximaEtapa = $etapa_atual_info["proximo"];
                if ($proximaEtapa === NULL) {
                    $info["ultimo"] = TRUE;
                }
                else {
                    $info = $this->objElemento->get_SubEtapa_Info($elemento_Tipo, $proximaEtapa);
                }
            }
            elseif ($elemento['elementoStatus'] == 15 && $direcao === "retorna") {
                $anteriorEtapa = $etapa_atual_info["anterior"];
                if ($anteriorEtapa === NULL) {
                    $info["primeiro"] = TRUE;
                }
                else {
                    $info = $this->objElemento->get_SubEtapa_Info($elemento_Tipo, $anteriorEtapa);
                }
            }
            if ($info !== []) {
                $done = FALSE;
                //if (isset($info["elementoTipo"])) {
                if ($info["elementoTipo"] != 59 && $info["elementoTipo"] != 82 && $info["elementoTipo"] != 105) {
                    if ($direcao === "avanca") {
                        if (isset($info["ultimo"]) && $info["ultimo"] === TRUE) {
                            $this->objElemento->setStatusFinalizado($elemento["elemento"]);
                            $done = TRUE;
                        }
                        else {
                            $prazo = ($info['prazo'] !== NULL) ? date('Y-m-d', strtotime('+' . $info['prazo'] . ' days')) : NULL;
                            $this->objElemento->updateElement(
                                $elementoId,
                                ['elementoStatus', 'Etapa', 'responsavel', 'area', 'prazo'],
                                [1, $info['etapa'], $info['responsavel'], $info['area'], $prazo]
                            );
                            $done = TRUE;
                        }
                    }
                    if ($direcao === "retorna") {
                        if (isset($info["primeiro"]) && $info["primeiro"] === TRUE) {
                            $this->objElemento->setStatusAguardandoResponsavel($elemento["elemento"]);
                            $done = TRUE;
                        }
                        else {
                            $prazo = NULL;
                            if (($info['prazo'] !== NULL)) {
                                $prazo = date('Y-m-d', strtotime('+' . $info['prazo'] . ' days'));
                            }
                            $this->objElemento->updateElement(
                                $elementoId,
                                ['elementoStatus', 'Etapa', 'responsavel', 'area', 'prazo'],
                                [1, $info['etapa'], $info['responsavel'], $info['area'], $prazo]
                            );
                            $done = TRUE;
                        }
                    }
                }
                elseif ($info["elementoTipo"] == 59) {
                    $done = $this->processa_pedido_v1($elementoId, $direcao, $elemento);
                }
                elseif ($info["elementoTipo"] == 82) {
                    $done = $this->processa_pedido_v2($elementoId, $direcao, $elemento);
                }
                elseif ($info["elementoTipo"] == 105) {
                    $done = $this->processa_pedido_v3($elementoId, $direcao, $elemento);
                }
                return $done;
                //}
            }
        }
        return FALSE;
    }

    /**
     * @param $elementoId
     * @param $direcao
     * @param $elemento
     *
     * @return bool
     */
    private function processa_pedido_v1($elementoId, $direcao, $elemento): bool {
        $done = FALSE;
        if ($direcao === "avanca") {
            if ($elemento["campos"]["Etapa"] == 17) {
                $nomeRenomeado = $elemento["campos"]["Nome"];

                $this->objElemento->updateElement($elementoId, ["Etapa", "responsavel", "area", 'Nome'], [18, $elemento["campos"]["responsavel_para"], $elemento["campos"]["Para_Area"], $nomeRenomeado]);
                if ($elemento["campos"]["responsavel_para"] != NULL) {
                    $this->objElemento->setStatusAguardandoInicio($elemento["elemento"]);
                }
                else {
                    $this->objElemento->setStatusAguardandoResponsavel($elemento["elemento"]);
                }
                $done = TRUE;
            }
            elseif ($elemento["campos"]["Etapa"] == 18) {
                if (strpos($elemento["campos"]["Nome"], 'Alterar "') !== FALSE) {
                    $nomeRenomeado = str_replace('Alterar "', 'Aprovar "', $elemento["campos"]["Nome"]);
                }
                else {
                    $nomeRenomeado = 'Aprovar "' . $elemento["campos"]["Nome"] . '"';
                }

                $this->objElemento->updateElement($elementoId, ["Etapa", "responsavel", "area", 'Nome'], [19, $elemento["campos"]["responsavel_de"], $elemento["campos"]["De_Area"], $nomeRenomeado]);
                if ($elemento["campos"]["responsavel_de"] != NULL) {
                    $this->objElemento->setStatusAguardandoInicio($elemento["elemento"]);
                }
                else {
                    $this->objElemento->setStatusAguardandoResponsavel($elemento["elemento"]);
                }
                $done = TRUE;
            }
            elseif ($elemento["campos"]["Etapa"] == 19) {
                // Aqui finaliza o elemento, depois tem q remover eles das listagens
                $this->objElemento->updateElement($elementoId, ["Finalizado"], [TRUE]);
                $this->objElemento->setStatusFinalizadoEProcessado($elemento["elemento"]);
                $done = TRUE;
            }
            elseif ($elemento["campos"]["Etapa"] == 57) {
                // Nao Implementado ainda
            }
        }
        if ($direcao === "retorna") {
            if ($elemento["campos"]["Etapa"] == 17) {
                // Nao faz sentido voltar + doq o primeiro estagio
                $this->objElemento->setStatusProblema($elemento["elemento"]);
                $done = TRUE;
            }
            elseif ($elemento["campos"]["Etapa"] == 18) {
                $this->objElemento->updateElement($elementoId, ["Etapa", "responsavel", "area"], [17, $elemento["campos"]["responsavel_de"], $elemento["campos"]["De_Area"]]);
                $this->objElemento->setStatusProblema($elemento["elemento"]);
                $done = TRUE;
            }
            elseif ($elemento["campos"]["Etapa"] == 19) {
                $nomeRenomeado = str_replace('Aprovar "', 'Alterar "', $elemento["campos"]["Nome"]);

                $this->objElemento->updateElement($elementoId, ["Etapa", "responsavel", "area", 'Nome'], [18, $elemento["campos"]["responsavel_para"], $elemento["campos"]["Para_Area"], $nomeRenomeado]);
                $this->objElemento->setStatusProblema($elemento["elemento"]);
                $done = TRUE;
            }
            elseif ($elemento["campos"]["Etapa"] == 57) {

                // Nao Implementado ainda
            }
        }
        return $done;
    }

    /**
     * @param $elementoId
     * @param $direcao
     * @param $elemento
     *
     * @return bool
     */
    private function processa_pedido_v2($elementoId, $direcao, $elemento): bool {
        $done = FALSE;
        if ($direcao === "avanca") {
            $area_de = $elemento["campos"]["De_Area"];
            $area_para = $elemento["campos"]["Para_Area"];
            $responsavel = NULL;
            $de = NULL;
            $etapa = $elemento["campos"]["Etapa"];
            $nomeRenomeado = str_replace(["Alterar", "Aprovar", "  "], ["", "", " "], $elemento["campos"]["Nome"]);
            if ($etapa == 67) {
                // Avanca o pedido, Marca o responsavel
                $proximaEtapa = 68;
                $this->objElemento->updateElement($elementoId, ["Etapa", "responsavel", "area", 'Nome'], [$proximaEtapa, $responsavel, $area_para, $nomeRenomeado]);
                $this->objElemento->setStatusAguardandoResponsavel($elemento["elemento"]);
                $done = TRUE;
            }
            elseif ($etapa == 68) {
                $area_para = $this->retorna_area_de_gestao($area_para);

                $nomeRenomeado = "Aprovar " . $nomeRenomeado;

                $proximaEtapa = 69;
                // Se o pedido estiver passando pela area de atendimento projeto web e infra, nao eh necessario dupla aprovacao
                $areas_que_nao_precisa_revisao = [2, 4, 7, 8, 17, 24, 28];
                if (!function_exists("is_gestor_area")) {
                    function is_gestor_area($uid) {
                        $gestores = [8, 9];
                        if (in_array($uid, $gestores)) {
                            return TRUE;
                        }
                        return FALSE;
                    }
                }

                if (in_array($area_para, $areas_que_nao_precisa_revisao) || is_gestor_area($elemento["campos"]["responsavel"])) {
                    $proximaEtapa = 70;
                }
                if ($proximaEtapa == 70) {
                    $area_para = $area_de;
                }

                $this->objElemento->updateElement($elementoId, ["Etapa", "responsavel", "area", 'Nome'], [$proximaEtapa, $responsavel, $area_para, $nomeRenomeado]);
                $this->objElemento->setStatusAguardandoResponsavel($elemento["elemento"]);
                $done = TRUE;
            }
            elseif ($etapa == 69) {
                // Aprova o pedido, envia sempre pra quem pediu fazer a aprovacao final
                $nomeRenomeado = "Aprovar " . $nomeRenomeado;
                $proximaEtapa = 70;
                $this->objElemento->updateElement($elementoId, ["Etapa", "responsavel", "area", 'Nome'], [$proximaEtapa, $responsavel, $area_de, $nomeRenomeado]);
                $this->objElemento->setStatusAguardandoResponsavel($elemento["elemento"]);
                $done = TRUE;
            }
            elseif ($etapa == 70) {
                // Aqui finaliza o elemento, depois tem q remover eles das listagens, status aprovado
                // por quem fez o pedido
                $this->objElemento->updateElement($elementoId, ["Finalizado"], [TRUE]);
                $this->objElemento->setStatusFinalizadoEProcessado($elemento["elemento"]);
                $done = TRUE;
            }


        }
        elseif ($direcao === "retorna") {
            $area_de = $elemento["campos"]["De_Area"];
            $area_para = $elemento["campos"]["Para_Area"];
            $responsavel = NULL;
            $de = NULL;
            $etapa = $elemento["campos"]["Etapa"];
            $nomeRenomeado = str_replace(["Alterar", "Aprovar", "  "], ["", "", " "], $elemento["campos"]["Nome"]);
            if ($etapa == 67) {
                // Nao faz sentido voltar + doq o primeiro estagio
                $this->objElemento->setStatusProblema($elemento["elemento"]);
                $done = TRUE;
            }
            if ($elemento["campos"]["Etapa"] == 68) {
                // Retorna pra quem fez o pedido com info pra alterar
                $nomeRenomeado = "Alterar " . $nomeRenomeado;
                $etapaAnterior = 67;
                $this->objElemento->updateElement($elementoId, ["Etapa", "responsavel", "area", 'Nome'], [$etapaAnterior, $de, $area_de, $nomeRenomeado]);
                $this->objElemento->setStatusAguardandoResponsavel($elemento["elemento"]);
                $done = TRUE;
            }
            if ($etapa == 69) {
                // Retorna pra quem fez o pedido com info pra alterar
                $nomeRenomeado = "Alterar " . $nomeRenomeado;
                $etapaAnterior = 68;
                $this->objElemento->updateElement($elementoId, ["Etapa", "responsavel", "area", 'Nome'], [$etapaAnterior, $responsavel, $area_para, $nomeRenomeado]);
                $this->objElemento->setStatusAguardandoResponsavel($elemento["elemento"]);
                $done = TRUE;
            }
            if ($etapa == 70) {
                // Retorna pra quem fez o pedido com info pra alterar
                $nomeRenomeado = "Alterar " . $nomeRenomeado;
                $etapaAnterior = 68;
                $this->objElemento->updateElement($elementoId, ["Etapa", "responsavel", "area", 'Nome'], [$etapaAnterior, $responsavel, $area_para, $nomeRenomeado]);
                $this->objElemento->setStatusAguardandoResponsavel($elemento["elemento"]);
                $done = TRUE;
            }
        }
        return $done;
    }

    /**
     * @param $area_para
     *
     * @return int
     */
    private function retorna_area_de_gestao($area_para): int {
// Avanca o pedido, limpa o responsavel e manda pra area que tem revisor
        switch ($area_para) {
            case 3:
                $area_para = 13;
                break;
            case 6:
                $area_para = 12;
                break;
            case 9:
                $area_para = 11;
                break;
            case 10:
                $area_para = 27;
                break;
            case 20:
                $area_para = 21;
                break;
        }
        return $area_para;
    }

    /**
     * @param $elementoId
     * @param $direcao
     * @param $elemento
     *
     * @return bool
     */
    private function processa_pedido_v3($elementoId, $direcao, $elemento): bool {
        $done = FALSE;
        if ($direcao === "avanca") {
            $area_de = $elemento["campos"]["De_Area"];
            $area_para = $elemento["campos"]["Para_Area"];
            $responsavel = NULL;
            $de = NULL;
            $etapa = $elemento["campos"]["Etapa"];
            $nomeRenomeado = str_replace(["Alterar", "Aprovar", "  "], ["", "", " "], $elemento["campos"]["Nome"]);
            if ($etapa == 155) {
                // Avanca o pedido, Marca o responsavel
                $proximaEtapa = 156;
                $this->objElemento->updateElement($elementoId, ["Etapa", "responsavel", "area", 'Nome'], [$proximaEtapa, $responsavel, $area_para, $nomeRenomeado]);
                $this->objElemento->setStatusAguardandoResponsavel($elemento["elemento"]);
                $done = TRUE;
            }
            elseif ($etapa == 156) {
                $area_para = $this->retorna_area_de_gestao($area_para);
                $nomeRenomeado = "Aprovar " . $nomeRenomeado;
                $proximaEtapa = 157;
                $this->objElemento->updateElement($elementoId, ["Etapa", "responsavel", "area", 'Nome'], [$proximaEtapa, $responsavel, $area_para, $nomeRenomeado]);
                $this->objElemento->setStatusAguardandoResponsavel($elemento["elemento"]);
                $done = TRUE;
            }
            elseif ($etapa == 157) {
                // Aprova o pedido, envia sempre pra quem pediu fazer a aprovacao final
                $nomeRenomeado = "Aprovar " . $nomeRenomeado;
                $proximaEtapa = 158;
                $this->objElemento->updateElement($elementoId, ["Etapa", "responsavel", "area", 'Nome'], [$proximaEtapa, $responsavel, $area_de, $nomeRenomeado]);
                $this->objElemento->setStatusAguardandoResponsavel($elemento["elemento"]);
                $done = TRUE;
            }
            elseif ($etapa = 158) {
                // Aqui finaliza o elemento, depois tem q remover eles das listagens, status aprovado
                // por quem fez o pedido
                $this->objElemento->updateElement($elementoId, ["Finalizado"], [TRUE]);
                $this->objElemento->setStatusFinalizadoEProcessado($elemento["elemento"]);
                $done = TRUE;
            }


        }
        elseif ($direcao === "retorna") {
            $area_de = $elemento["campos"]["De_Area"];
            $area_para = $elemento["campos"]["Para_Area"];
            $responsavel = NULL;
            $de = NULL;
            $etapa = $elemento["campos"]["Etapa"];
            $nomeRenomeado = str_replace(["Alterar", "Aprovar", "  "], ["", "", " "], $elemento["campos"]["Nome"]);
            if ($etapa == 155) {
                // Nao faz sentido voltar + doq o primeiro estagio
                $this->objElemento->setStatusProblema($elemento["elemento"]);
                $done = TRUE;
            }
            if ($elemento["campos"]["Etapa"] == 156) {
                // Retorna pra quem fez o pedido com info pra alterar
                $nomeRenomeado = "Alterar " . $nomeRenomeado;
                $etapaAnterior = 155;
                $this->objElemento->updateElement($elementoId, ["Etapa", "responsavel", "area", 'Nome'], [$etapaAnterior, $de, $area_de, $nomeRenomeado]);
                $this->objElemento->setStatusAguardandoResponsavel($elemento["elemento"]);
                $done = TRUE;
            }
            if ($etapa == 157) {
                // Retorna pra quem fez o pedido com info pra alterar
                $nomeRenomeado = "Alterar " . $nomeRenomeado;
                $etapaAnterior = 156;
                $this->objElemento->updateElement($elementoId, ["Etapa", "responsavel", "area", 'Nome'], [$etapaAnterior, $responsavel, $area_para, $nomeRenomeado]);
                $this->objElemento->setStatusAguardandoResponsavel($elemento["elemento"]);
                $done = TRUE;
            }
            if ($etapa == 158) {
                // Retorna pra quem fez o pedido com info pra alterar
                $nomeRenomeado = "Alterar " . $nomeRenomeado;
                $etapaAnterior = 156;
                $this->objElemento->updateElement($elementoId, ["Etapa", "responsavel", "area", 'Nome'], [$etapaAnterior, $responsavel, $area_para, $nomeRenomeado]);
                $this->objElemento->setStatusAguardandoResponsavel($elemento["elemento"]);
                $done = TRUE;
            }
        }
        return $done;
    }

    /**
     * @param int $elementoId
     *
     * @return bool
     */
    public function voltaSubEtapa($elementoId) {
        return $this->processaSubEtapa($elementoId, "retorna");
    }

    /**
     * Insere uma linha na lista de primeiros do projeto
     *
     * @param int $projTipoId
     * @param int $elemTipoId
     *
     * @return bool
     */
    public function insertElementFirst($projTipoId, $elemTipoId) {
        $proj = $this->getProjectTypeById($projTipoId);
        $elem = $this->objElemento->getElementTypeById($elemTipoId);

        if (isset($proj["projetoTipo"], $elem["elementoTipo"])) {
            $this->database->ngInsertPrepared($this->tbl_ElementoPrimeiro, ["projetoTipo" => $projTipoId, "elementoTipo" => $elemTipoId]);
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Insere uma linha na lista de proximos da etapa atual
     *
     * @param int $projTipoId
     * @param int $elemTipoId
     * @param int $elemProxTipoId
     *
     * @return bool
     */
    public function insertElementNext($projTipoId, $elemTipoId, $elemProxTipoId) {
        $proj = $this->getProjectTypeById($projTipoId);
        $elem = $this->objElemento->getElementTypeById($elemTipoId);
        $elemProx = $this->objElemento->getElementTypeById($elemProxTipoId);

        if (isset($proj["projetoTipo"], $elem["elementoTipo"], $elemProx["elementoTipo"])) {
            $this->database->ngInsertPrepared($this->tbl_ElementoProximo, ["projetoTipo" => $projTipoId, "elementoTipo" => $elemTipoId, "proximo" => $elemProxTipoId]);
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Insere uma linha na lista de Anteriores da etapa atual
     *
     * @param int $projTipoId
     * @param int $elemTipoId
     * @param int $elemAntTipoId
     *
     * @return bool
     */
    public function insertElementPrev($projTipoId, $elemTipoId, $elemAntTipoId) {
        $proj = $this->getProjectTypeById($projTipoId);
        $elem = $this->objElemento->getElementTypeById($elemTipoId);
        $elemAnt = $this->objElemento->getElementTypeById($elemAntTipoId);

        if (isset($proj["projetoTipo"], $elem["elementoTipo"], $elemAnt["elementoTipo"])) {
            $this->database->ngInsertPrepared($this->tbl_ElementoAnterior, ["projetoTipo" => $projTipoId, "elementoTipo" => $elemTipoId, "anterior" => $elemAntTipoId]);
            return TRUE;
        }
        return FALSE;
    }

    /**
     * @param $uid
     * @param $projId
     * @param $conteudo
     */
    public function saveSession($uid, $projId, $conteudo) {
        $cuid = $this->database->ngSelectPrepared($this->tbl_ProjetoDadosTempoaraios, ["projeto" => $projId, "user" => $uid], 'projeto', '', 1);
        if (isset($cuid["projeto"])) {
            $this->database->ngUpdatePrepared($this->tbl_ProjetoDadosTempoaraios, ["conteudo" => $conteudo], ["projeto" => $projId, "user" => $uid]);
        }
        else {
            $this->database->ngInsertPrepared($this->tbl_ProjetoDadosTempoaraios, ["projeto" => $projId, "user" => $uid, "conteudo" => $conteudo]);
        }
    }

    /**
     * @param $uid
     * @param $projId
     *
     * @return string
     */
    public function restoreSession($uid, $projId) {
        $cuid = $this->database->ngSelectPrepared($this->tbl_ProjetoDadosTempoaraios, ["projeto" => $projId, "user" => $uid], '', '', 1);
        if (isset($cuid["projeto"])) {
            $conteudo = $cuid["conteudo"];
        }
        else {
            $conteudo = json_encode([]);
        }
        return $conteudo;
    }

    /**
     * @param $uid
     * @param $projId
     */
    public function cleanSession($uid, $projId) {
        $cuid = $this->database->ngSelectPrepared($this->tbl_ProjetoDadosTempoaraios, ["projeto" => $projId, "user" => $uid], 'projeto', '', 1);
        $conteudo = json_encode([]);
        if (isset($cuid["projeto"])) {
            $this->database->ngUpdatePrepared($this->tbl_ProjetoDadosTempoaraios, ["conteudo" => $conteudo], ["projeto" => $projId, "user" => $uid]);
        }
        else {
            $this->database->ngInsertPrepared($this->tbl_ProjetoDadosTempoaraios, ["projeto" => $projId, "user" => $uid, "conteudo" => $conteudo]);
        }
    }


}
