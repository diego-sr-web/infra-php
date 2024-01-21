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

class area extends nomeTipo {
    protected $tblName = "back_AdmArea";

    /**
     * @return bool
     */
    public function check() {
        $area = $this->database->ngSelectPrepared($this->tblName, ["area" => $this->valor], "area", "", 1);
        return isset($area["area"]);
    }

    /**
     * @return string
     */
    public function retornaHtmlExibicao() {
        $area = $this->database->ngSelectPrepared($this->tblName, ["area" => $this->valor], "", "", 1);
        $html = "";
        if (isset($area["area"], $area["cor"], $area["nome"])) {
            $html .= '<span class="dado-destaque chip" style="background-color:' . $area["cor"] . ';font-size: 12px;">' . $area["nome"] . '</span>' . PHP_EOL;
        }
        else {
            $html .= '<span class="dado-destaque chip" style="background-color:' . '#FF0000' . ' ">' . "NOT FOUND" . '</span>' . PHP_EOL;
        }
        return $html;
    }
}
