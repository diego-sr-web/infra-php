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

    abstract class nomeTipo {
        protected $database;
        protected $valor;
        protected $campo;
        protected $exibicao;
        protected $arg = [];

        /**
         * Construtor base das classes de tipo de input,
         * Esse construtor tenta ser o mais simples possivel, caso seja necessario um comportamento + inteligente eh
         * necessario implementar um construtor novo nas classes de tipo de input
         *
         * @param string          $campo
         * @param string|int|bool $valor
         * @param null|Database   $database
         * @param array           $args
         */
        public function __construct($campo, $valor, $database = NULL, $args = []) {
            $this->valor = $valor;
            $this->campo = $campo;
            $this->exibicao = NULL;
            $this->database = $database;
            foreach ($args as $key => $value) {
                $this->arg[$key] = $value;
            }

        }

        /**
         * Checa pra ver se a variavel esta do tipo esperado
         *
         * @return bool
         */
        public function check() {
            return TRUE;
        }

        /**
         * Retorna o valor cru da variavel, sem nenhum tipo de processamento
         *
         * @return null|string
         */
        public function getValor() {
            return $this->valor;
        }


        /**
         * Retorna o nome do campo da variavel
         * @return null|string
         */
        public function getCampo() {
            return $this->campo;
        }


        /**
         * Retorna o nome do campo da variavel
         * @return null|string
         */
        public function getExibicao() {
            return $this->exibicao;
        }

        /**
         * @param $valor
         *
         * @return null
         */
        public function setValor($valor) {
            $this->valor = $valor;
            return NULL;
        }

        /**
         * @param $campo
         *
         * @return null
         */
        public function setCampo($campo) {
            $this->campo = $campo;
            return NULL;
        }

        /**
         * @param $exibicao
         *
         * @return null
         */
        public function setExibicao($exibicao) {
            $this->exibicao = $exibicao;
            return NULL;
        }

        /**
         * Retorna codigo html que pode ser usado para montar um form
         *
         * @return string
         */
        public function retornaHtmlInsercao() {
            return "Insercao";
        }


        /**
         * Retorna codigo html que esta no formato apropriado para exibicao
         *
         * @return string
         */
        public function retornaHtmlExibicao() {
            return "Exibicao";
        }
    }
