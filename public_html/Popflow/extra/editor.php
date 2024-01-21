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

class editor extends nomeTipo {

    /**
     * @return string
     */
    public function retornaHtmlExibicao() {
        $text = $this->valor;
        $html = "";
        if ($text != FALSE) {
            $html .= '<span>' . $text . '</span>';
        }
        else {
            $html .= '<span>Nenhum Conteudo</span>';
        }
        return $html;
    }


    /**
     * @return string
     */
    public function retornaHtmlInsercao() {
        $html = '<div class="form-group">
						<label>' . $this->exibicao . '</label>
						<textarea name="' . $this->valor . '" class="form-control trumbowyg"></textarea>
					</div>
					<script type="text/javascript">$(".trumbowyg").trumbowyg();</script>';
        return $html;
    }


}
