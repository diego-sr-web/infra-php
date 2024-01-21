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

class Elemento {
    public $historico;
    /** @var DataBase $database */
    protected $database;
    protected $tbl_Elemento = 'pop_Elemento';
    protected $tbl_ElementoStatus = 'pop_ElementoStatus';
    protected $tbl_ElementoTipo = 'pop_ElementoTipo';
    protected $tbl_ElementoTipoSubEtapa = 'pop_ElementoTipoSubEtapa';
    protected $tbl_ElementoConteudo = 'pop_ElementoConteudo';
    protected $tbl_ElementoTipoNomeTipo = "pop_ElementoTipoNomeTipo";
    protected $tbl_ColunasBaseXelementoTipo = "pop_ColunasBaseXelementoTipo";
    protected $tbl_ColunasBaseNomeTipo = "pop_ColunasBaseNomeTipo";
    protected $tbl_ColunasBase = "pop_ColunasBase";
    protected $tbl_Area = "back_AdmArea";
    protected $cache_Tipos = [];
    protected $cache_Base = [];


    /**
     * Elemento constructor.
     *
     * @param $database
     */
    public function __construct($database) {
        $this->database = $database;
        $this->historico = new elementoHistorico($database);
        $this->categoria = new elementoCategoria($database);
        $this->prioridade = new elementoPrioridade($database);
    }

    /**
     * Le uma lista com todos os elementos do tipo elemento
     *
     * Le uma lista com todos os elementos do tipo elemento e retorna um array formatado com as informacoes
     * o array eh no padrao nerdweb sendo um array de arrays com array[indice][campo]=valor
     * opcionalmente pode se passar o arrays com campos e valores como filtros de elemento
     *
     *
     * @param bool   $limpa_Finalizados
     * @param array  $area
     * @param string $reponsavel
     *
     * @param array  $arrayStatusDescartado
     * @param null   $projetoTipo
     *
     * @return array
     */
    public function getAllElementsFiltered($limpa_Finalizados = FALSE, $area = [], $reponsavel = "", $arrayStatusDescartado = [8, 9, 11, 14, 15, 16], $projetoTipo = NULL) {
        if ($arrayStatusDescartado == NULL) {
            $arrayStatusDescartado = [8, 9, 11, 14, 15, 16];
        }

        $campos = $valores = $lista_elementos = [];

        if ($limpa_Finalizados) {
            // TODO: utilizando uma "burlagem" pra otimizar a velocidade da consulta (filtrar por != no sql ao invés do código)
            $campos = ['elementoStatus!', 'elementoStatus!', 'elementoStatus!', 'elementoStatus!', 'elementoStatus!', 'elementoStatus!'];
            $valores = $arrayStatusDescartado;
        }

        if ($projetoTipo) {
            $campos[] = 'projetoTipo';
            $valores[] = $projetoTipo;
        }

        if (count($campos) === 0) {
            $lista_ids = $this->database->ngSelectPrepared("ViewElementoComplexo");
        }
        else {
            $lista_ids = $this->database->selectPrepared("ViewElementoComplexo", $campos, $valores);
        }

        $temp = [];
        foreach ($lista_ids as $elemento) {
            $id = $elemento["elemento"];
            if (!isset($temp[$id])) {
                $temp[$id]['elemento'] = $elemento['elemento'];
                $temp[$id]['dataCriacao'] = $elemento['dataCriacao'];
                $temp[$id]['dataAtualizacao'] = $elemento['dataAtualizacao'];
                $temp[$id]['elementoTipo'] = $elemento['elementoTipo'];
                $temp[$id]['elementoStatus'] = $elemento['elementoStatus'];
                $temp[$id]['projeto'] = $elemento['projeto'];
            }
            if (isset($elemento['chave'])) {
                if ($elemento["chave"] === "area") {
                    $temp[$id]['area'] = $elemento["valor"];
                }
                if ($elemento['chave'] === "Etapa") {
                    $temp[$id]['Etapa'] = $elemento["valor"];
                }
                if ($elemento['chave'] === "responsavel") {
                    $temp[$id]['responsavel'] = $elemento["valor"];
                }
            }
        }
        $lista_ids = [];
        foreach ($temp as $key => $value) {
            if ((isset($value["responsavel"]) && $reponsavel === $value["responsavel"]) || (isset($value["area"]) && in_array($value["area"], $area))) {
                $lista_ids[] = $value;
            }
            elseif ($area === []) {
                $lista_ids[] = $value;
            }
        }

        foreach ($lista_ids as $id) {
            $lista_elementos[] = $this->getElementById($id['elemento']);
        }

        return $lista_elementos;
    }

    /**
     * Le um elemento por id
     *
     * Le um elemento da tabela de elementos, le o tipo deste elemento na tabela $tbl_ElementoTipo,
     * le as linhas referentes a esse elemento na tabela $tbl_ElementoConteudo e formata a saida de acordo com o padrao adotado na nerdweb
     * voltando um array com campos e valores
     *
     * @param int $elementoId
     *
     * @return mixed
     */
    public function getElementById($elementoId) {
        $elemento = $this->database->ngSelectPrepared($this->tbl_Elemento, ['elemento' => $elementoId], '', '', 1);

        if (isset($elemento['elemento'])) {
            if (!isset($this->cache_Tipos[$elemento['elementoTipo']])) {
                $tipo = $this->database->ngSelectPrepared($this->tbl_ElementoTipo, ['elementoTipo' => $elemento['elementoTipo']], '', '', 1);
                $this->cache_Tipos[$elemento['elementoTipo']] = $tipo;
            }
            else {
                $tipo = $this->cache_Tipos[$elemento['elementoTipo']];
            }
            if (isset($tipo['elementoTipo'])) {
                $conteudo = $this->database->ngSelectPrepared($this->tbl_ElementoConteudo, ['elemento' => $elementoId]);
                $elemento["campos"] = [];
                foreach ($conteudo as $subitem) {
                    if ($subitem["chave"] == "Etapa" && $subitem["valor"] == NULL) {
                        $subitem["valor"] = $this->getElementTypeFirstSubEtapa($tipo['elementoTipo']);
                        //var_dump($subitem);
                    }
                    $elemento["campos"][$subitem['chave']] = $subitem['valor'];
                }
            }
            return $elemento;
        }
        return [];
    }

    /**
     * Tenta encontrar o primeiro elemento das subetapas
     *
     * @param $etid
     *
     * @return int
     */
    public function getElementTypeFirstSubEtapa($etid) {
        $lista = $this->database->ngSelectPrepared($this->tbl_ElementoTipoSubEtapa, ["elementoTipo" => $etid], "", "etapa ASC");
        //var_dump($etid);
        if (isset($lista[0]["etapa"])) {
            return $lista[0]["etapa"];
        }
        return NULL;
    }

    public function getAllElementsFiltered2($limpa_Finalizados = FALSE, $area = [], $reponsavel = "", $arrayStatusDescartado = [8, 9, 11, 14, 15, 16], $projetoTipo = NULL) {
        if ($arrayStatusDescartado == NULL) {
            $arrayStatusDescartado = [8, 9, 11, 14, 15, 16];
        }

        $campos = $valores = $lista_elementos = [];

        if ($limpa_Finalizados) {
            // TODO: utilizando uma "burlagem" pra otimizar a velocidade da consulta (filtrar por != no sql ao invés do código)
            $campos = ['elementoStatus!', 'elementoStatus!', 'elementoStatus!', 'elementoStatus!', 'elementoStatus!', 'elementoStatus!'];
            $valores = $arrayStatusDescartado;
        }

        if ($projetoTipo) {
            $campos[] = 'projetoTipo';
            $valores[] = $projetoTipo;
        }

        if (count($campos) === 0) {
            $lista_ids = $this->database->ngSelectPrepared("ViewElementoComplexo");
        }
        else {
            $lista_ids = $this->database->selectPrepared("ViewElementoComplexo", $campos, $valores);
        }

        $temp = [];
        foreach ($lista_ids as $elemento) {
            $id = $elemento["elemento"];
            if (!isset($temp[$id])) {
                $temp[$id]['elemento'] = $elemento['elemento'];
                $temp[$id]['dataCriacao'] = $elemento['dataCriacao'];
                $temp[$id]['dataAtualizacao'] = $elemento['dataAtualizacao'];
                $temp[$id]['elementoTipo'] = $elemento['elementoTipo'];
                $temp[$id]['elementoStatus'] = $elemento['elementoStatus'];
                $temp[$id]['projeto'] = $elemento['projeto'];
            }
            if (isset($elemento['chave'])) {
                if ($elemento["chave"] === "area") {
                    $temp[$id]['area'] = $elemento["valor"];
                }
                if ($elemento['chave'] === "Etapa") {
                    $temp[$id]['Etapa'] = $elemento["valor"];
                }
                if ($elemento['chave'] === "responsavel") {
                    $temp[$id]['responsavel'] = $elemento["valor"];
                }
            }
        }
        $lista_ids = [];
        foreach ($temp as $key => $value) {
            if ($reponsavel === $value["responsavel"] || in_array($value["area"], $area)) {
                $lista_ids[] = $value;
            }
            elseif ($area === []) {
                $lista_ids[] = $value;
            }
        }

        foreach ($lista_ids as $id) {
            $agora = strtotime('-6 months');
            $tempo_elemento = strtotime($id["dataAtualizacao"]);
            if ($tempo_elemento > $agora) {
                $lista_elementos[] = $this->getElementById($id['elemento']);
            }
        }

        return $lista_elementos;
    }

    /**
     * Le os dados de um status de elemento pelo id do status
     *
     * @param int $statusId
     *
     * @return array
     */
    public function getElementStatusById($statusId) {
        $status = $this->database->ngSelectPrepared($this->tbl_ElementoStatus, ['elementoStatus' => $statusId], '', '', 1);

        if (isset($status['elementoStatus'])) {
            return $status;
        }

        return [];
    }

    /**
     * Filtra a lista de elementos pela base passada como parametro
     *
     * @param $listaElementos
     * @param $baseNecessaria
     *
     * @return array
     */
    public function filtraElementosPorBase($listaElementos, $baseNecessaria) {
        $listaFiltrada = [];

        foreach ($listaElementos as $elemento) {
            //pega todas as bases do elemento
            if (isset($elemento["elemento"])) {
                $base = $this->getElementBase($elemento['elemento']);
                $arrayBase = [];
                // coloca os ids das bases em um array
                foreach ($base as $aux) {
                    $arrayBase[] = $aux['colunaBase'];
                }
                // verifica se o elemento possui a base necessária, caso positivo insere no array
                if (in_array($baseNecessaria, $arrayBase)) {
                    $listaFiltrada[] = $elemento;
                }
            }
        }

        return $listaFiltrada;
    }

    /**
     * @param $elementoId
     *
     * @return array
     */
    public function getElementBase($elementoId) {
        $elementoTipo = $this->getElementTypeByElementId($elementoId);
        if (isset($elementoTipo["elementoTipo"])) {
            if (!isset($this->cache_Base[$elementoTipo["elementoTipo"]])) {
                $base = $this->getElementBaseByElementTypeId($elementoTipo["elementoTipo"]);
                $this->cache_Base[$elementoTipo["elementoTipo"]] = $base;
            }
            else {
                $base = $this->cache_Base[$elementoTipo["elementoTipo"]];
            }
            return $base;
        }
        return [];
    }

    /**
     * Le os dados de um tipo de elemento pelo id de um elemento passado
     *
     * @param int $elementoId
     *
     * @return array
     */
    public function getElementTypeByElementId($elementoId) {
        $elemento = $this->getElementById($elementoId);

        if (isset($elemento['elemento'])) {
            if (!isset($this->cache_Tipos[$elemento['elementoTipo']])) {
                $tipo = $this->getElementTypeById($elemento['elementoTipo']);
                //$this->cache_Tipos[$elemento['elementoTipo']] = $tipo; // TODO: CACHE COM PROBLEMA
            }
            else {
                //$tipo = $this->cache_Tipos[$elemento['elementoTipo']]; // TODO: CACHE COM PROBLEMA
                $tipo = $this->getElementTypeById($elemento['elementoTipo']);
            }
            return $tipo;
        }

        return [];
    }

    /**
     * Le as informacoes de um tipo de elemento pelo id do tipo de elemento passado
     *
     * @param $elementoTipoId
     *
     * @return array
     */
    public function getElementTypeById($elementoTipoId) {
        if (!isset($this->cache_Tipos[$elementoTipoId])) {
            $type = $this->database->ngSelectPrepared($this->tbl_ElementoTipo, ['elementoTipo' => $elementoTipoId], '', '', 1);
            $this->cache_Tipos[$elementoTipoId] = $type;
        }
        else {
            $type = $this->cache_Tipos[$elementoTipoId];
        }

        if (isset($type['elementoTipo'])) {
            $lista_campos = $this->database->ngSelectPrepared($this->tbl_ElementoTipoNomeTipo, ['elementoTipo' => $elementoTipoId], "", "");
            $base = $this->database->ngSelectPrepared($this->tbl_ColunasBaseXelementoTipo, ["elementoTipo" => $elementoTipoId]);
            $lista_base = [];
            foreach ($base as $elem) {
                if (isset($elem["colunasBase"])) {
                    $lista_base[] = $this->database->ngSelectPrepared($this->tbl_ColunasBaseNomeTipo, ["colunaBase" => $elem["colunasBase"]]);
                }
            }
            $type["campos"] = [];
            foreach ($lista_campos as $lista_campo) {
                $type["campos"][$lista_campo["nome"]] = $lista_campo["tipo"];
                $type["nomesCampos"][$lista_campo["nome"]] = $lista_campo["nomeExibicao"];
            }
            foreach ($lista_base as $base) {
                foreach ($base as $item) {
                    if (isset($item["nome"], $item["tipo"])) {
                        $type["campos"][$item["nome"]] = $item["tipo"];
                        $type["nomesCampos"][$item["nome"]] = $item["nomeExibicao"];
                    }
                }
            }
            return $type;
        }
        return [];
    }

    /**
     * @param $elementoTipoId
     *
     * @return array
     */
    public function getElementBaseByElementTypeId($elementoTipoId) {
        $base = $this->database->ngSelectPrepared($this->tbl_ColunasBaseXelementoTipo, ["elementoTipo" => $elementoTipoId]);
        $lista_base = [];
        foreach ($base as $elem) {
            if (isset($elem["colunasBase"])) {
                $lista_base[] = $this->database->ngSelectPrepared($this->tbl_ColunasBase, ["colunaBase" => $elem["colunasBase"]], '', '', 1);
            }
        }
        return $lista_base;
    }

    /**
     *  Filtra um array de elementos, pelo campo passado por parametro, e pelo array de valores passados
     *  alem de comparar igualdade ou desigualdade do valor escolhido
     *  inicialmente tenta encontrar o campo na raiz do elemento
     * $elemento[$campo] caso encontre faz o filtro por esse elemento
     * caso nao encontre faz o filtro pelo array campos $elemento["campos"][$campo]
     * caso nao encontre nao faz nada ( essa funcao pode ser facilmente expandida pra tratar de outros casos e outros
     * arrays que virarem padrao nos elementos )
     *
     * @param array        $arrayElementos
     * @param string       $campo
     * @param array|string $valor
     * @param bool         $igualdade
     *
     * @return array
     */
    public function filtraCampoValor($arrayElementos, $campo, $valor, $igualdade = TRUE) {
        if (!is_array($valor)) {
            $valor = [$valor];
        }
        $raiz = FALSE;
        $campos = FALSE;
        //var_dump($arrayElementos);
        if (isset($arrayElementos[0]) && array_key_exists($campo, $arrayElementos[0])) {
            $raiz = TRUE;
        }
        elseif (isset($arrayElementos[0]["campos"]) && array_key_exists($campo, $arrayElementos[0]["campos"])) {
            $campos = TRUE;
            $sub_chave = "campos";
        }// Se precisar dah pra expandir por aqui
        foreach ($arrayElementos as $key => $value) {
            if ($igualdade === TRUE) {
                if ($raiz) {
                    if (in_array($arrayElementos[$key][$campo], $valor)) {
                        unset($arrayElementos[$key]);
                    }
                }
                elseif ($campos) {
                    if (in_array($arrayElementos[$key][$sub_chave][$campo], $valor)) {
                        unset($arrayElementos[$key]);
                    }
                }
            }
            elseif ($igualdade === FALSE) {
                if ($raiz) {
                    if (!in_array($arrayElementos[$key][$campo], $valor)) {
                        unset($arrayElementos[$key]);
                    }
                }
                elseif ($campos) {
                    if (!in_array($arrayElementos[$key][$sub_chave][$campo], $valor)) {
                        unset($arrayElementos[$key]);
                    }
                }
            }
        }
        return $arrayElementos;
    }

    /**
     * Agrupa a lista de tarefas/elementos passada como parâmetro quando possuem um determinado tipo passado como segundo parâmetro
     * Retorna um array com a nova lista de tarefas, e os itens que foram agrupados
     *
     * @param $listaTarefas
     *
     * @return array ['tarefas' => array, 'itensAgrupados' => array];
     */
    public function agrupaTarefas($listaTarefas) {
        $arrayProjetos = $listaTarefasAux = [];

        $elementosComSubetapa = [];
        $arrayETSubetapa = $this->getElementTypeListWithSubEtapa();
        foreach ($arrayETSubetapa as $aux) {
            $elementosComSubetapa[] = $aux['elementoTipo'];
        }
        $array_tipo_pedido = [59, 82, 105];
        // o array aqui fica organizado em arrayPrincipal[$idProjeto][$idEtapa][arrayTarefas]
        foreach ($listaTarefas as $key => $val) {
            if (in_array($val['elementoTipo'], $elementosComSubetapa)) {
                if (!in_array($val['elementoTipo'], $array_tipo_pedido)) {
                    if (isset($val['campos']['Etapa']) && $val['campos']['Etapa'] !== NULL) {
                        $arrayProjetos[$val['projeto']][$val['campos']['Etapa']][] = $val;
                    }
                    else {
                        // Forca uma subetapa se Etapa for NULL
                        $etapa = $this->getElementTypeFirstSubEtapa($val['elementoTipo']);
                        $arrayProjetos[$val['projeto']][$etapa][] = $val;
                    }
                    unset($listaTarefas[$key]);
                }
            }
        }
        foreach ($arrayProjetos as $arrayEtapas) {
            foreach ($arrayEtapas as $arrayTarefas) {
                if (isset($arrayTarefas[0], $arrayTarefas[0]['projeto'], $arrayTarefas[0]['elementoTipo'])) {
                    $listaTarefasAux[] = $arrayTarefas[0];

                    $total = $this->getAllElements(['projeto', 'elementoTipo'], [$arrayTarefas[0]['projeto'], $arrayTarefas[0]['elementoTipo']]);
                    $listaTarefasAux[count($listaTarefasAux) - 1]['campos']['contagem'] = ['parcial' => count($arrayTarefas), 'total' => count($total)];
                }
            }
        }
        $listaTarefas = array_merge($listaTarefas, $listaTarefasAux);
        return ['tarefas' => $listaTarefas, 'itensAgrupados' => $arrayProjetos];
    }

    /**
     * Retorna um array com os tipos de elementos que possuem subetapas
     *
     * @return array
     */
    public function getElementTypeListWithSubEtapa() {
        $lista = $this->database->ngSelectPrepared($this->tbl_ElementoTipoSubEtapa, [], "DISTINCT elementoTipo", "elementoTipo");
        if (isset($lista[0])) {
            return $lista;
        }
        return [];
    }

    /**
     * Le uma lista com todos os elementos do tipo elemento
     *
     * Le uma lista com todos os elementos do tipo elemento e retorna um array formatado com as informacoes
     * o array eh no padrao nerdweb sendo um array de arrays com array[indice][campo]=valor
     * opcionalmente pode se passar o arrays com campos e valores como filtros de elemento
     *
     *
     * @param array $campos
     * @param array $valores
     * @param bool  $limpa_Finalizados
     *
     * @return array
     */
    public function getAllElements($campos = [], $valores = [], $limpa_Finalizados = FALSE) {
        if ($limpa_Finalizados) {
            $arrayStatusDescartado = [8, 9, 14, 15, 16];

            // TODO: utilizando uma "burlagem" pra otimizar a velocidade da consulta (filtrar por != no sql ao invés do código)
            $campos = array_merge(['elementoStatus!', 'elementoStatus!', 'elementoStatus!', 'elementoStatus!', 'elementoStatus!'], $campos);
            $valores = array_merge($arrayStatusDescartado, $valores);
        }

        $size = count($campos);
        $lista_elementos = [];

        if ($size === 0) {
            $lista_ids = $this->database->ngSelectPrepared($this->tbl_Elemento, [], 'elemento,elementoStatus');
        }
        else {
            $lista_ids = $this->database->selectPrepared($this->tbl_Elemento, $campos, $valores, 'elemento,elementoStatus');
        }

        foreach ($lista_ids as $id) {
            $lista_elementos[] = $this->getElementById($id['elemento']);
        }

        return $lista_elementos;
    }

    public function getAllElementsCron($campos = [], $valores = []) {
        $arrayStatusDescartado = [9, 11, 13, 16];
        $campos = array_merge(['elementoStatus!', 'elementoStatus!', 'elementoStatus!', 'elementoStatus!'], $campos);
        $valores = array_merge($arrayStatusDescartado, $valores);
        $lista_elementos = [];
        /** @noinspection PhpDeprecationInspection */
        $lista_ids = $this->database->selectPrepared($this->tbl_Elemento, $campos, $valores, 'elemento,elementoStatus');
        foreach ($lista_ids as $id) {
            $lista_elementos[] = $this->getElementById($id['elemento']);
        }

        return $lista_elementos;
    }


    /**
     * Insere uma linha na lista de colunas base X elementoTipo
     *
     * @param int $elemTipoId
     * @param int $colunaBaseId
     *
     * @return bool
     */
    public function insertElementBase($elemTipoId, $colunaBaseId) {
        $elemTipo = $this->getElementTypeById($elemTipoId);
        $elementoBase = $this->database->ngSelectPrepared($this->tbl_ColunasBase, ["colunaBase" => $colunaBaseId], "", "", 1);

        if (isset($elemTipo["elementoTipo"], $elementoBase["colunaBase"])) {
            $this->database->ngInsertPrepared($this->tbl_ColunasBaseXelementoTipo, ["elementoTipo" => $elemTipoId, "colunasBase" => $colunaBaseId]);
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Insere um elemento no banco de dados
     *
     * Retorna o ID do elemento inserido
     *
     * @param array $campos
     * @param array $valores
     *
     * @return null|int
     */
    public function insertElement($campos = [], $valores = []) {
        $mixed = $this->mixArray($campos, $valores);

        if (!isset($mixed['prazo'])) {
            $elTipo = $this->getElementTypeById($mixed['elementoTipo']);

            if ($elTipo['prazo']) {
                $mixed['prazo'] = date('Y-m-d', strtotime('+' . $elTipo['prazo'] . ' days'));
            }
        }

        // Arrays dos dados q vao pro banco
        $camposPrimarios = [];
        $valoresPrimarios = [];
        $opt = [];

        // $mixed e $opt sao passados por referencia
        $this->mixCamposAdicionais($mixed, $opt);

        $colunas = ['dataCriacao', 'dataAtualizacao', 'elementoTipo', 'elementoStatus', 'projeto'];
        // $mixed, $camposPrimarios, $valoresPrimarios sao passados por referencia
        $this->mixArrayPrimario($mixed, $camposPrimarios, $valoresPrimarios, $colunas);

        // Checa se sobrou algum elemento em mixed, se sim mata o script
        $this->checasobra($mixed);

        $this->database->insertPrepared($this->tbl_Elemento, $camposPrimarios, $valoresPrimarios);
        $eid = $this->database->returnLastInsertedId();

        foreach ($opt as $key => $value) {
            $this->database->ngInsertPrepared($this->tbl_ElementoConteudo, ['elemento' => $eid, 'chave' => $key, 'valor' => $value]);
        }
        return $eid;
    }

    /**
     * @param $campos
     * @param $valores
     *
     * @return array
     */
    private function mixArray($campos, $valores) {
        if (count($campos) !== count($valores)) {
            die('Número de parametros passados pro elemento estao errados<br/>Campos:' . count($campos) . '<br/>Valores:' . count($valores) . '<br/>');
        }

        $size = count($campos);
        $mixed = [];

        // Cria um array unico chave=>valor, pra poder separar denovo baseado nos campos primarios e secundarios
        for ($i = 0; $i < $size; $i++) {
            $key = $campos[$i];
            $value = $valores[$i];
            $mixed[$key] = $value;
        }

        // Setando as datas padroes caso elas nao tenham sido passadas
        if (!isset($mixed['dataCriacao'])) {
            $mixed['dataCriacao'] = date('Y-m-d H:i:s');
        }

        if (!isset($mixed['dataAtualizacao'])) {
            $mixed['dataAtualizacao'] = $mixed['dataCriacao'];
        }

        return $mixed;
    }

    /**
     * @param $mixed
     * @param $opt
     *
     * @return bool
     */
    private function mixCamposAdicionais(&$mixed, &$opt) {
        // Descobre os campos extra que o elemento tem
        $elementoTipo = $this->getElementTypeById($mixed['elementoTipo']);

        if ($elementoTipo["campos"] !== NULL) {
            // monta o array de campos e array de valores secundarios
            foreach ($elementoTipo["campos"] as $campo => $valor) {
                if (isset($mixed[$campo]) || array_key_exists($campo, $mixed)) {
                    $opt[$campo] = $mixed[$campo];
                    unset($mixed[$campo]);
                }
                else {
                    $opt[$campo] = NULL;
                }
            }
        }
        return TRUE;
    }

    /**
     * @param $mixed
     * @param $camposPrimarios
     * @param $valoresPrimarios
     * @param $campos
     */
    private function mixArrayPrimario(&$mixed, &$camposPrimarios, &$valoresPrimarios, $campos) {
        foreach ($campos as $campo) {
            $camposPrimarios[] = $campo;
            $valoresPrimarios[] = $mixed[$campo];
            unset($mixed[$campo]);
        }

        // conta o numero de campos do array primario (montado acima)
        // faltou variaveis a serem passadas, imprime os campos e valores recebidos e para o script
        if (count($valoresPrimarios) !== count($campos)) {
            echo 'Faltou dados do array primario.<br/>';
            /** @noinspection ForgottenDebugOutputInspection */
            var_dump('Campos:', $camposPrimarios, 'Valores', $valoresPrimarios, 'Campos2:', $campos);
            die();
        }
    }

    /**
     * @param $mixed
     */
    private function checasobra($mixed) {
        // conta o array $mixed pra ver se sobrou algum elemento q nao foi usado no array de valores primario ou secundario
        // se sim imprime oq sobrou e para o script pois foi passado coisas erradas.
        if (count($mixed) > 0) {
            echo 'Parametros passados errados,<br/>Foi passado um ou mais campos que nao existem no tipo de elemento<br/>';
            /** @noinspection ForgottenDebugOutputInspection */
            var_dump($mixed);
            die();
        }
    }

    /**
     * Insere um tipo novo de elemento no banco de dados
     *
     * Retorna o ID do tipo de elemento criado
     *
     * @param array $campos
     * @param array $valores
     *
     * @return null|int
     */
    public function insertElementType($campos = [], $valores = []) {
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
        $dbCampos[] = "nome";
        $dbValores[] = $mixed["nome"];
        unset($mixed["nome"]);

        $this->database->insertPrepared($this->tbl_ElementoTipo, $dbCampos, $dbValores);
        $eid = $this->database->returnLastInsertedId();
        foreach ($mixed as $key => $value) {
            $this->database->ngInsertPrepared($this->tbl_ElementoTipoNomeTipo, ["elementoTipo" => $eid, "nome" => $key, "tipo" => $value]);
        }
        return $eid;
    }


    /**
     * @param       $elementTypeId
     * @param array $campos
     * @param array $valores
     *
     * @return array
     */
    public function updateElementType($elementTypeId, $campos = [], $valores = []) {
        $ok = $this->database->updatePrepared($this->tbl_ElementoTipo, $campos, $valores, ['elementoTipo'], [$elementTypeId]);
        return $ok;
    }

    /**
     * @param        $eid
     * @param        $chave
     * @param        $value
     *
     * Insere campos especiais no elemento, ou atualiza caso o campo exista, retorna TRUE caso tenha conseguido inserir/atualizar ou FALSE caso nao tenha encontrado o elemento
     *
     * @return bool
     */
    public function updateExtraField($eid, $chave, $value) {
        $elemento = $this->database->ngSelectPrepared($this->tbl_Elemento, ["elemento" => $eid], "", "", 1);
        if (isset($elemento["elemento"])) {
            $novo_campo = $this->database->ngSelectPrepared($this->tbl_ElementoConteudo, ["elemento" => $eid, "chave" => $chave]);
            if (isset($novo_campo[0]["elemento"])) {
                $this->database->ngUpdatePrepared($this->tbl_ElementoConteudo, ["valor" => $value], ["elemento" => $eid, "chave" =>$chave]);
            }
            else {
                $this->database->ngInsertPrepared($this->tbl_ElementoConteudo, ["elemento" => $eid, "chave" => $chave, "valor" => $value]);
            }
            return TRUE;
        }
        return FALSE;
    }

    /**
     * @param $elementoId
     *
     * @return bool
     */
    public function marcaInicio($elementoId) {
        return $this->historico->insert($elementoId, "1");
    }
    /*

        public function getElementArea($elementId) {

            return [];
        }
    */

    /**
     * @param $elementoId
     *
     * @return bool
     */
    public function marcaPausa($elementoId) {
        return $this->historico->insert($elementoId, "2");
    }

    /**
     * @param $elementoId
     *
     * @return bool
     */
    public function marcaRetomada($elementoId) {
        return $this->historico->insert($elementoId, "3");
    }

    /**
     * @param $elementoId
     *
     * @return bool
     */
    public function marcaFinalizada($elementoId) {
        return $this->historico->insert($elementoId, "4");
    }


    // Bloco de funcoes auxiliares                               //
    // nesse bloco tem somente funcoes de uso interno da classe //
    //                                                         //

    /**
     * @param      $elementoId
     * @param null $usuarioId
     *
     * @return array
     */
    public function listHistoricoTarefa($elementoId, $usuarioId = NULL) {
        return $this->historico->listHistoricoTarefa($elementoId, $usuarioId);
    }

    /**
     * @param      $elementoId
     * @param null $usuarioId
     *
     * @return int
     */
    public function calculaTempoTrabalhado($elementoId, $usuarioId = NULL) {
        return $this->historico->calculaTempoTrabalhado($elementoId, $usuarioId);
    }

    /**
     * @param $elementoTipoId
     * @param $aid
     *
     * @return bool
     */
    public function insertElementoTipoArea($elementoTipoId, $aid) {
        $area = $this->database->ngSelectPrepared("back_AdmArea", ["area" => $aid], "", "", 1);
        if (isset($area["area"])) {
            $elementoTipo = $this->database->ngSelectPrepared($this->tbl_ElementoTipo, ["elementoTipo" =>$elementoTipoId], "", "", 1);
            if (isset($elementoTipo["elementoTipo"])) {
                $this->database->ngInsertPrepared("pop_AdmAreaXelementoTipo", ["area" => $aid, "elementoTipo" => $elementoTipoId]);
                return TRUE;
            }
        }
        return FALSE;
    }

    /**
     * @param $elementTipoId
     *
     * @return array
     */
    public function getElementTipoArea($elementTipoId) {
        $lista = $this->database->ngSelectPrepared("pop_AdmAreaXelementoTipo", ["elementoTipo" => $elementTipoId]);
        if (isset($lista[0]["area"])) {
            return $lista;
        }
        return [];
    }







    /* ***************** INICIO FUNÇÕES CATEGORIA ***************** */

    /**
     * @param $elementoId
     * @param $aid
     */
    public function insertArea($elementoId, $aid) {
        $area = $this->database->ngSelectPrepared("back_AdmArea", ["area" => $aid], "", "", 1);
        if (isset($area["area"])) {
            $elemento = $this->database->ngSelectPrepared($this->tbl_ElementoConteudo, ["elemento" => $elementoId, "chave" => "area"]);
            if (isset($elemento["elemento"])) {
                $this->database->ngUpdatePrepared($this->tbl_ElementoConteudo, ["valor" => $aid], ["elemento" => $elementoId, "chave" => "area"]);
            }
            else {
                $this->database->ngInsertPrepared($this->tbl_ElementoConteudo, ["elemento" => $elementoId, "chave" => "area", "valor" => $aid]);
            }
        }
    }

    /**
     * @param array  $campos
     * @param array  $valores
     * @param string $order
     * @param string $limit
     *
     * @return array
     */
    public function getCategoryList($campos = [], $valores = [], $order = 'nome', $limit = '') {
        return $this->categoria->getCategoryList($campos, $valores, $order, $limit);
    }

    /**
     * @return int
     */
    public function getCategoryCount() {
        return $this->categoria->getCategoryCount();
    }
    /* ***************** FIM FUNÇÕES CATEGORIA ***************** */


    /* ***************** INICIO FUNÇÕES PRIORIDADE ***************** */

    /**
     * @param $cid
     *
     * @return array
     */
    public function getCategoryById($cid) {
        return $this->categoria->getCategoryById($cid);
    }

    /**
     * @return array
     */
    public function getPrioridadeList() {
        return $this->prioridade->getPrioridadeList();
    }
    /* ***************** FIM FUNÇÕES PRIORIDADE ***************** */

    /**
     * @param $pid
     *
     * @return array
     */
    public function getPrioridadeById($pid) {
        return $this->prioridade->getPrioridadeById($pid);
    }

    /**
     * @param $aid
     *
     * @return array
     */
    public function getAreaById($aid) {
        $area = $this->database->ngSelectPrepared($this->tbl_Area, ["area" => $aid], "", "", 1);
        if (isset($area["area"])) {
            return $area;
        }
        return [];
    }

    /**
     * @param $elemento_Tipo
     * @param $etapa_atual
     *
     * @return array
     */
    public function get_SubEtapa_Proximo($elemento_Tipo, $etapa_atual) {
        $etapa = $this->get_SubEtapa_Info($elemento_Tipo, $etapa_atual);
        if (isset($etapa["etapa"])) {
            return $this->get_SubEtapa_Info($elemento_Tipo, $etapa["proximo"]);
        }
        return [];
    }

    /**
     * @param $elemento_Tipo
     * @param $etapa_atual
     *
     * @return array
     */
    public function get_SubEtapa_Info($elemento_Tipo, $etapa_atual) {
        $info = $this->database->ngSelectPrepared($this->tbl_ElementoTipoSubEtapa, ["etapa" => $etapa_atual, "elementoTipo" => $elemento_Tipo], "", "", 1);
        if (isset($info["etapa"])) {
            return $info;
        }
        return [];
    }

    /**
     * @param $elemento_Tipo
     * @param $etapa_atual
     *
     * @return array
     */
    public function get_SubEtapa_Anterior($elemento_Tipo, $etapa_atual) {
        $etapa = $this->get_SubEtapa_Info($elemento_Tipo, $etapa_atual);
        if (isset($etapa["etapa"])) {
            return $this->get_SubEtapa_Info($elemento_Tipo, $etapa["anterior"]);
        }
        return [];
    }

    /**
     * @param $prefixo
     * @param $array
     *
     * @return int|mixed
     */
    public function proximo_campo_extra($prefixo, $array) {
        $xt_array = [];
        foreach ($array as $key => $value) {
            if (stripos($key, $prefixo) !== FALSE) {
                $xt_array[] = strtolower($key);
            }
        }
        rsort($xt_array, SORT_NATURAL);
        if (isset($xt_array[0])) {
            $maior_campo = str_replace($prefixo, "", $xt_array[0]);
        }
        else {
            $maior_campo = 0;
        }
        $maior_campo = str_replace("_", "", $maior_campo);
        $maior_campo++;// como foi ordenado do maior pro menor esse aqui eh o valor da maior imagem em numero + 1
        //var_dump($maior_campo);
        return $maior_campo;
    }

    /**
     * Retorna lista de subetapas de um determinado tipo de elemento
     *
     * @param int $etid
     *
     * @return array
     */
    public function getSubEtapaByElementoTipo($etid) {
        $lista = $this->database->ngSelectPrepared($this->tbl_ElementoTipoSubEtapa, ["elementoTipo" => $etid]);
        if (isset($lista[0])) {
            return $lista;
        }
        return [];
    }

    /**
     * Retorna o texto correto para o botão
     *
     * @param $elemento
     * @param $campo
     * @param $atributo
     *
     * @return string
     */
    public function adicionarOuAlterar($elemento, $campo, $atributo) {
        $texto = '';

        if (isset($elemento['campos'])) {
            if (isset($elemento['campos'][$campo]) && $elemento['campos'][$campo]) {
                $texto = 'Alterar';
            }
            else {
                $texto = 'Adicionar';
            }
        }

        $texto = $texto . ' ' . $atributo;
        return $texto;
    }

    public function setStatusAguardandoResponsavel($eid) {
        return $this->setStatus($eid, 1);
    }

    public function setStatus($eid, $sid) {
        return $this->updateElement($eid, ['elementoStatus'], [$sid]);
    }

    /**
     * Caminha pelos vetores de campo e valor fazendo um update por campo
     *
     * Caminha pelos vetores de campo e valor fazendo um update por campo
     * antes de comecar a fazer os updates ele checa se o numero de elementos no array
     * de campos e valores sao iguais, e se nao foi passado nenhum elemento extra nao reconhecido
     *
     * @param int   $elementoId
     * @param array $campos
     * @param array $valores
     *
     * @return bool
     */
    public function updateElement($elementoId, $campos = [], $valores = []) {
        $colunas = ['dataCriacao', 'dataAtualizacao', 'elementoTipo', 'elementoStatus', 'projeto'];

        $infoElementoAntes = $this->getElementById($elementoId);

        $mixed = $this->mixArray($campos, $valores);

        // faz um segundo array pra testar se todos os elementos foram
        // deve ter um geito melhor, + por enquanto fica assim
        $mixedTest = $this->mixArray($campos, $valores);

        $elementoTipo = $this->getElementTypeByElementId($elementoId);
        $colunas_secundarias = $elementoTipo['campos'];

        foreach ($colunas as $chave) {
            if (isset($mixedTest[$chave]) || array_key_exists($chave, $mixedTest)) {
                unset($mixedTest[$chave]);
            }
        }

        foreach ($colunas_secundarias as $chave => $valor) {
            if (isset($mixedTest[$chave]) || array_key_exists($chave, $mixedTest)) {
                unset($mixedTest[$chave]);
            }
        }

        // caso a chamada de checasobra falhe ele mata o script
        $this->checasobra($mixedTest);
        unset($colunas[0]);
        foreach ($colunas as $chave) {
            if (isset($mixed[$chave]) || array_key_exists($chave, $mixed)) {
                $this->database->ngUpdatePrepared($this->tbl_Elemento, [$chave => $mixed[$chave]], ['elemento' => $elementoId]);
            }
        }

        foreach ($colunas_secundarias as $chave => $valor) {
            if (isset($mixed[$chave]) || array_key_exists($chave, $mixed)) {
                $dado = $this->database->ngSelectPrepared($this->tbl_ElementoConteudo, ['elemento' => $elementoId, 'chave' => $chave], '', '', 1);

                if (isset($dado['elemento'])) {
                    $this->database->ngUpdatePrepared($this->tbl_ElementoConteudo, ['valor' => $mixed[$chave]], ['elemento' => $elementoId, 'chave' => $chave]);
                }
                else {
                    $this->database->ngInsertPrepared($this->tbl_ElementoConteudo, ['elemento' => $elementoId, 'chave' => $chave, 'valor' => $mixed[$chave]]);
                }
            }
        }
        return TRUE;
    }

    public function setStatusAguardandoInicio($eid) {
        return $this->setStatus($eid, 2);
    }

    public function setStatusProblema($eid) {
        return $this->setStatus($eid, 7);
    }

    public function setStatusFinalizado($eid) {
        return $this->setStatus($eid, 8);
    }

    public function setStatusFinalizadoEProcessado($eid) {
        return $this->setStatus($eid, 9);
    }

    public function setStatusEtapaReprovada($eid) {
        return $this->setStatus($eid, 9);
    }

    public function isAprovado($eid) {

    }

    public function isReprovado($eid) {

    }

    public function getNomeEtapa($etapa) {
        $etapa_info = $this->database->ngSelectPrepared("pop_ElementoTipoSubEtapa", ["etapa" => $etapa]);
        $nomeEtapa = "ETAPA_NAO_ENCONTRADA";
        if (isset($etapa_info[0])) {
            $nomeEtapa = $etapa_info[0]["nome"];
        }
        return $nomeEtapa;
    }

    public function limpaExtras($tipo, $extra) {
        $sub_TMP = [];
        $sub_I = [];
        $nomesCampos = $tipo['nomesCampos'];
        // Limpa o conteudo que estao contidos nos tipos de elemento e
        // nos elementos base, separa os tipos extras ( imgs e etc que sao adicionais, de
        // kda instancia de elemento )
        foreach ($tipo['campos'] as $chave => $valor) {
            $tmp_subelemento['nome'] = $chave;
            $tmp_subelemento['tipo'] = $valor;
            $tmp_subelemento['valor'] = $extra[$chave];
            switch ($tmp_subelemento['nome']) {
                case "prazo":
                case "dtFim":
                case "dtInicio":
                    break;
                case "Etapa":
                case "area":
                case "Dia":
                    switch ($tmp_subelemento["nome"]) {
                        case "Etapa":
                            $sub_I[0] = $tmp_subelemento;
                            break;
                        case "area":
                            $sub_I[1] = $tmp_subelemento;
                            break;
                        case "Dia":
                            $sub_I[3] = $tmp_subelemento;
                            break;
                    }
                    break;
                default:
                    $sub_TMP[] = $tmp_subelemento;
                    break;
            }
            unset($extra[$chave]);
        }

        $subE = $sub_I;
        ksort($sub_TMP);
        foreach ($sub_TMP as $tmp) {
            $subE[] = $tmp;
        }
        $retorno[0] = $subE;
        $retorno[1] = $nomesCampos;
        return $retorno;
    }

    /**
     * @param array      $elemento
     * @param string     $nomeCampoDecisao
     * @param int|string $proximaEtapa
     */
    public function etapaDecisaoSistema($elemento, $nomeCampoDecisao, $proximaEtapa = NULL) {
        $proximaEtapa = $proximaEtapa ?? ($elemento["campos"]["Etapa"] + 1);
        if ($elemento["campos"][$nomeCampoDecisao] === "on") {
            // Campo de decisao Ativo, mudar a etapa pra proxima
            $this->trocaSubEtapa($elemento["elemento"], $proximaEtapa);
            return;
        }
        // Marcar o campo como finalizado pq nao tem proxima etapa
        $this->setStatus($elemento["elemento"], 8);
    }

    public function trocaSubEtapa($eid, $novaEtapa, $removeResponsavel = TRUE) {
        $novaArea = $this->getAreaEtapa($novaEtapa);
        if ($removeResponsavel) {
            $this->updateElement($eid, ['responsavel', "elementoStatus"], [NULL, 1]);
        }
        else {
            $this->updateElement($eid, ["elementoStatus"], [2]);
        }
        $this->updateElement($eid, ["Etapa", "area"], [$novaEtapa, $novaArea]);

    }

    public function getAreaEtapa($etapa) {
        $etapa_info = $this->database->ngSelectPrepared("pop_ElementoTipoSubEtapa", ["etapa" => $etapa]);
        $areaEtapa = "AREA_NAO_ENCONTRADA";
        if (isset($etapa_info[0])) {
            $areaEtapa = $etapa_info[0]["area"];
        }
        return $areaEtapa;
    }

    public function updateElementFields($elementId, $fieldNames) {
        $dados_elemento = $this->getElementById($elementId);
        $campos = $dados_elemento["campos"];
        $updates = [];
        foreach ($fieldNames as $key => $value) {
            $updates[$key] = $_POST[$value] ?? "";
        }
        foreach ($updates as $key => $value) {
            if ($value !== "" && $value != $campos[$key]) {
                $this->updateElement($elementId, [$key], [$value]);
            }
        }
    }
}
