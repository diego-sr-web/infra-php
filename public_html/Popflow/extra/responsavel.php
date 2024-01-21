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

class responsavel extends nomeTipo {
    protected $tblName = "back_AdmUsuario";

    /**
     * @return bool
     */
    public function check() {
        $responsavel = $this->database->ngSelectPrepared($this->tblName, ["usuarioNerdweb" => $this->valor], "", "", 1);
        return isset($responsavel["usuarioNerdweb"]);
    }

    /**
     * @return string
     */
    public function retornaHtmlExibicao() {
        $responsavel = $this->database->ngSelectPrepared($this->tblName, ["usuarioNerdweb" => $this->valor], "", "", 1);
        $html = "";
        if (isset($responsavel["imagem"])) {
            $html .= '<img class="avatar-responsavel" src=" ' . $responsavel["imagem"] . ' " title="' . $responsavel["nome"] . '">';
        }
        else {
            $html .= '';
        }
        return $html;
    }

    /**
     * @return string
     */
    public function retornaNomeExibicao() {
        $responsavel = $this->database->ngSelectPrepared($this->tblName, ["usuarioNerdweb" => $this->valor], "", "", 1);
        $html = "";
        if (isset($responsavel["nome"])) {
            $html .= $responsavel["nome"];
        }
        else {
            $html .= '';
        }
        return $html;
    }
}
