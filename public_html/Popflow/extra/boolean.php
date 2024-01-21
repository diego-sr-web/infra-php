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

class boolean extends nomeTipo {

    /**
     * @return string
     */
    public function retornaHtmlExibicao() {
        $checkbox = $this->valor;
        $html = "";
        if ($checkbox === "on") {
            $formatado = '<i style="color:green;" class="fa fa-check-circle"> Sim</i>';
        }
        else {
            $formatado = '<i style="color:red;" class="fa fa-times-circle"> N&atilde;o</i>';
        }
        $html .= '<span>' . $formatado . '</span>';
        return $html;
    }
}
