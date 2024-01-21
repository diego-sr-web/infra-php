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

class img extends nomeTipo {

    /**
     * @return string
     */
    public function retornaHtmlExibicao() {
        $imagem = $this->valor;
        $html = "";

        if ($imagem !== "" && $imagem !== NULL) {
            $html .= '<a class="open-lightbox" href="' . $imagem . '" target="_blank">
                           <img src="/backoffice/pop/utils/image_resizer.php?h=300&w=200&url=' . base64_encode($imagem) . '" style="border: 1px solid #E0E8E8;">
                          </a>';
        }
        else {
            $html .= '<img src="/backoffice/pop/utils/image_resizer.php?h=300&w=200&url=' . base64_encode("http://nerdweb.popflow.com.br/backoffice/pop/uploads/projeto/default.jpg") . '" style="border: 1px solid #E0E8E8;">';
        }
        return $html;
    }


    /**
     * @return string
     */
    public function retornaHtmlInsercao() {
        $html = '<div class="form-group">
					    <label>' . $this->valor . ' *</label>
                        <div class="input-group">
                            <span class="input-group-btn">
                                <span class="btn btn-primary btn-file btn-flat border-0">
                                    Procurar&hellip; <input type="file" class="upload-preview" name="' . $this->valor . '">
                                </span>
                            </span>
                            <input type="text" class="form-control" disabled="disabled" readonly>
                        </div>
                        <p class="help-block">Max. 5MB</p>
                        <img class="img-preview" style="width:100%; display:none;" src="" />
                    </div>';
        return $html;
    }
}
