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

class gdrive extends nomeTipo {
    /**
     * @return bool
     */
    public function check() {
        return $this->valor !== "" && $this->valor !== NULL;
    }

    /**
     * @return string
     */
    public function retornaHtmlExibicao() {
        return "<span><a href='" . $this->valor . "' target='_blank'>Google Drive</a></span>" . PHP_EOL;
    }

    /**
     * @return string
     */
    public function retornaHtmlInsercao() {
        $html = '<div class="form-group">
						<label>' . $this->valor . '</label>
						<input name="' . $this->valor . '" class="form-control" type="text" required>
					</div>';
        return $html;
    }
}
