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

class text extends nomeTipo {
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
        $text = Utils::insert_href($this->valor);
        $campo = $this->campo;
        $html = "";
        if ($text != FALSE) {
            $html .= '<span>' . nl2br($text) . '</span>';
            if ($campo == "Observacoes" && isset($this->arg["elemento"])) {
                $html .= '<div class="ver-historico">
                            <a href="#" 
                                class="dpb js-getElemento" 
                                data-elemento="' . $this->arg['elemento'] . '" 
                                data-toggle="modal"
                                data-backdrop="static"  
                                data-target="#modal-elemento"
                                data-acao="ver-historico-observacao">Ver histórico
                            </a>
                        </div>';
            }
        }
        else {
            $html .= '<span>Nenhuma Observacao</span>';
        }
        return $html;
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
