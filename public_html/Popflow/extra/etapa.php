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

class etapa extends nomeTipo {
    protected $tblName = "pop_ElementoTipoSubEtapa";

    /**
     * @return bool
     */
    public function check() {
        $area = $this->database->ngSelectPrepared($this->tblName, ["etapa" => $this->valor], "etapa", "", 1);
        return isset($area["area"]);
    }

    /**
     * @return string
     */
    public function retornaHtmlExibicao() {
        $etapa = $this->database->ngSelectPrepared($this->tblName, ["etapa" => $this->valor], "", "", 1);
        $html = "";
        if (isset($etapa["etapa"], $etapa["nome"], $etapa["elementoTipo"])) {
            $html .= "<span>" . $etapa["nome"] . "</span>" . PHP_EOL;
        }
        else {
            $html .= '<span class="dado-destaque" style="color:' . '#FF0000' . ' ">' . "NOT FOUND" . '</span>' . PHP_EOL;
        }
        return $html;
    }
}
