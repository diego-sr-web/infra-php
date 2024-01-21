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

class prioridade extends nomeTipo {
    protected $tblName = "pop_Prioridade";

    /**
     * @return bool
     */
    public function check() {
        $prioridade = $this->database->ngSelectPrepared($this->tblName, ["prioridade" => $this->valor], "prioridade", "", 1);
        return isset($status["prioridade"]);
    }

    /**
     * @return string
     */
    public function retornaHtmlExibicao() {
        $prioridade = $this->database->ngSelectPrepared($this->tblName, ["prioridade" => $this->valor], "", "", 1);
        $html = "";
        if (isset($prioridade["prioridade"], $prioridade["cor"], $prioridade["nome"])) {
            $html .= '<span class="dado-destaque chip" style="background-color: ' . $prioridade["cor"] . ';">' . $prioridade["nome"] . '</span>' . PHP_EOL;
        }
        else {
            $html .= '<span class="dado-destaque chip" style="background-color: #FF0000;">NOT FOUND</span>' . PHP_EOL;
        }
        return $html;
    }
}
