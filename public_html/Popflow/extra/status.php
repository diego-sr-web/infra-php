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

class status extends nomeTipo {
    protected $tblName = "pop_ElementoStatus";

    /**
     * @return bool
     */
    public function check() {
        $status = $this->database->ngSelectPrepared($this->tblName, ["elementoStatus" => $this->valor], "elementoStatus", "", 1);
        return isset($status["elementoStatus"]);
    }

    /**
     * @return string
     */
    public function retornaHtmlExibicao() {
        $status = $this->database->ngSelectPrepared($this->tblName, ["elementoStatus" => $this->valor], "", "", 1);
        $html = "";
        if (isset($status["elementoStatus"], $status["cor"], $status["nome"])) {
            $html .= '<span class="dado-destaque chip" style="background-color: ' . $status["cor"] . ';font-size: 12px;">' . $status["nome"] . '</span>';
        }
        else {
            $html .= '<span class="dado-destaque chip" style="background-color: #FF0000;">NOT FOUND</span>';
        }
        return $html;
    }
}
