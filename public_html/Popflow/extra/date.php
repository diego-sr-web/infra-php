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

class date extends nomeTipo {

    /**
     * @return bool
     */
    public function check() {
        $date = strtotime($this->valor);
        return $date !== FALSE;
    }

    /**
     * @return string
     */
    public function retornaHtmlExibicao() {
        $date = strtotime($this->valor);
        $html = "";
        if ($date != FALSE) {
            $formatado = date('d/m/Y', $date);
            $html .= '<span>' . $formatado . '</span>';
        }
        else {
            $html .= '<span>--/--/----</span>';
        }
        return $html;
    }

    /**
     * @return string
     */
    public function retornaHtmlExibicaoPrazo() {
        $date = strtotime($this->valor);

        $prazoCor = '';
        if ($this->valor < date('Y-m-d')) {
            $prazoCor = 'style="color: #E2445C;"';
        }
        elseif ($this->valor > date('Y-m-d')) {
            $prazoCor = 'style="color: #00C875;"';
        }
        else {
            $prazoCor = 'style="color: #FDAB3D;"';
        }

        $html = '';

        if ($date != FALSE) {
            $formatado = date('d/m/Y', $date);
            $html .= '<span style="display: none;">' . $this->valor . '</span>&nbsp;';
            $html .= '<span class="dado-destaque" ' . $prazoCor . '><i class="material-icons small">fiber_manual_record</i></span>&nbsp;';
            $html .= '<span>' . $formatado . '</span>';
        }
        else {
            $formatado = date('d/m/Y');
            $html .= '<span style="display: none;">' . $this->valor . '</span>&nbsp;';
            $html .= '<span class="dado-destaque" ' . $prazoCor . '><i class="material-icons small">fiber_manual_record</i></span>&nbsp;';
            $html .= '<span>'. $formatado . '</span>';
        }
        return $html;
    }


    /**
     * @return string
     */
    public function retornaHtmlInsercao() {
        $html = '<div class="form-group">
						<label>' . $this->valor . '</label>
						<input name="' . $this->valor . '" class="form-control datepicker" data-date-language="pt-BR" data-provide="datepicker" data-date-format="yyyy-mm-dd" type="text" required>
					</div>';
        return $html;
    }

}
