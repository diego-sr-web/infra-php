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

class date_monthyear extends nomeTipo {

    /**
     * @return bool
     */
    public function check() {
        $date = str_replace('/', '-', $this->valor);
        $date = strtotime($date);
        return $date !== FALSE;
    }

    /**
     * @return string
     */
    public function retornaHtmlExibicao() {
        $date = strtotime($this->valor);
        $html = "";
        if ($date != FALSE) {
            $formatado = date('m/Y', $date);
            $html .= '<span>' . $formatado . '</span>';
        }
        else {
            $html .= '<span>--/----</span>';
        }
        return $html;
    }


    /**
     * @return string
     */
    public function retornaHtmlInsercao() {
        $valor = $this->exibicao;
        if ($valor === NULL) {
            $valor = $this->valor;
        }

        $html = '<div class="form-group">
					<label>' . $valor . '</label>
					<input name="' . $this->valor . '" class="form-control datepicker" data-date-language="pt-BR" data-provide="datepicker" data-date-format="mm/yyyy" type="text" data-date-min-view-mode="1" data-date-max-view-mode="1" required>
				</div>';
        return $html;
    }

}
