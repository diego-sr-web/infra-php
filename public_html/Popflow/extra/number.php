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

class number extends nomeTipo {

    /**
     * @return string
     */
    public function retornaHtmlInsercao() {
        $htmlInsercao = '<div class="form-group">
                    <label>' . $this->valor . '</label>
                    <input type="text" name="' . $this->valor . '" class="form-control"  required/>
                </div>';

        return $htmlInsercao;
    }
}
