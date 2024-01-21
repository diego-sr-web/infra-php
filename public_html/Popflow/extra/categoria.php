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

class categoria extends nomeTipo {
    protected $tblName = "pop_ElementoCategoria";

    /**
     * @return bool
     */
    public function check() {
        $categoria = $this->database->ngSelectPrepared($this->tblName, ["categoria" => $this->valor], "categoria", "", 1);
        return isset($categoria["categoria"]);
    }

    /**
     * @return string
     */
    public function retornaHtmlExibicao() {
        $categoria = $this->database->ngSelectPrepared($this->tblName, ["categoria" => $this->valor], "", "", 1);
        $html = "";
        if (isset($categoria["categoria"], $categoria["nome"])) {
            $html .= '<span class="dado-destaque" style="color:' . $categoria["cor"] . ' ">' . $categoria["nome"] . '</span>' . PHP_EOL;
        }
        else {
            $html .= '<span class="dado-destaque" style="color:' . '#FF0000' . ' ">' . "Sem Categoria" . '</span>' . PHP_EOL;
        }
        return $html;
    }
}
