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
    class Botao {
        /** @var Database $database */
        protected $database;
        protected $tbl_elementoTipoXbotao = "pop_ElementoTipoXelementoBotao";
        protected $tbl_BotaoGrupo = "pop_BotaoGrupo";
        protected $tbl_Botao = "pop_Botao";
        /** @var Elemento elemento */
        private $elemento;
        /** @var Projeto projeto */
        private $projeto;
        private $cacheBotao = [];

        public function __construct($database) {
            $this->database = $database;

        }

        public function getElementButtonsById($elemento) {
            if ($this->elemento === NULL) {
                $this->elemento = new Elemento($this->database);
            }
            $tipo = $elemento["elementoTipo"];
            $botoes = $this->database->ngSelectPrepared($this->tbl_elementoTipoXbotao, ["elementoTipo" => $tipo], "", "posicao");
            if (isset($botoes[0])) {
                if (isset($elemento["campos"]["etapa"])) {
                    $etapa = $elemento["campos"]["etapa"];
                    foreach ($botoes as $key => $value) {
                        if ($value["etapa"] != 0 && $value["etapa"] != $etapa) {
                            unset($botoes[$key]);
                        }
                    }
                }
                return $botoes;
            }
            return [];

        }


        public function getGroupButtonsByName($groupName) {
            return $this->database->ngSelectPrepared($this->tbl_BotaoGrupo, ["identifier" => $groupName]);
        }

        public function getProjectByButtonsById($projeto) {
            if ($this->projeto === NULL) {
                $this->projeto = new Projeto($this->database);
            }
            return [];
        }

        public function montaBotaoElemento($idElemento, $idBotao, $showName = TRUE) {
            $botao_data = $this->getButtonData($idBotao);

            $cor = $botao_data["cor"];
            $nome_tooltip = $botao_data["nome"];
            $icon = $botao_data["icon"];
            $nome_botao = "";
            if ($showName) {
                $nome_botao = $botao_data["nome"];
            }
            $class = "btn btn-block btn-flat border-0 btn-primary js-ajax-pop-2 ";
            $botao = '<button class="' . $class . '" style="background-color:' . $cor . '" data-elemento="' . $idElemento . '"
					data-toggle="modal" data-backdrop="static" data-target="#modal-elemento" data-botao="' . $idBotao . '" title="' . $nome_tooltip . '">
					<i class="fa ' . $icon . ' pull-left"></i> ' . $nome_botao . '
			</button>';
            return $botao;
        }

        private function getButtonData($idBotao) {
            $botao_data = $this->getButtonById($idBotao);
            if ($botao_data == []) {
                $botao_data["nome"] = "NAO ENCONTRADO";
                $botao_data["icon"] = "fa-exclamation-triangle fa-pulse";
                $botao_data["cor"] = "#ACACAC";
            }
            return $botao_data;
        }

        public function getButtonById($idBotao) {
            if (isset($this->cacheBotao[$idBotao])) {
                return $this->cacheBotao[$idBotao];
            }
            $botao = $this->database->ngSelectPrepared($this->tbl_Botao, ["botao" => $idBotao], "", "", 1);
            if (isset($botao["botao"])) {
                $this->cacheBotao[$idBotao] = $botao;
                return $botao;
            }
            return [];
        }

        public function montaBotaoMisc($idElemento, $idBotao, $tabelaElemento = NULL, $showName = TRUE) {
            $botao_data = $this->getButtonData($idBotao);
            $idElemento = ' data-elemento="' . $idElemento . '" ';
            $idBotao = 'data-botao="' . $idBotao . '"';
            $cor = ' style="background-color:' . $botao_data["cor"] . '" ';
            $nome_tooltip = '  title="' . $botao_data["nome"] . '">';
            $icon = ' <i class="fa ' . $botao_data["icon"] . ' pull-left"></i> ';
            $nome_botao = "";
            if ($showName) {
                $nome_botao = $botao_data["nome"];
            }
            if ($tabelaElemento !== NULL) {
                $tabelaElemento = ' data-tabela="' . $tabelaElemento . '" ';
            }
            $class = ' class="btn btn-flat border-0 btn-primary js-ajax-pop" ';
            $miscData = ' data-toggle="modal" data-backdrop="static" data-target="#modal" ';
            $retorno = '<button ' . $class . $cor . $idElemento . $miscData . $tabelaElemento . $idBotao . $nome_tooltip . $icon . $nome_botao . '</button>';
            return $retorno;
        }

        public function montaBotaoProjeto($idProjeto, $idBotao, $showname = TRUE) {
            return "NAO IMPLEMENTADO";
        }

        private function montaBotaoGenerico($class, $cor, $id) {
        }
    }
