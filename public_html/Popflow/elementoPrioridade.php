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

class elementoPrioridade {
    /** @var DataBase $database */
    protected $database;
    protected $tbl_ElementoPrioridade = 'pop_Prioridade';

    /**
     * elementoPrioridade constructor.
     *
     * @param $database
     */
    public function __construct($database) {
        $this->database = $database;
    }

    /**
     * @return array
     */
    public function getPrioridadeList() {
        $listaPrioridade = $this->database->ngSelectPrepared($this->tbl_ElementoPrioridade);

        if (isset($listaPrioridade[0])) {
            return $listaPrioridade;
        }

        return $listaPrioridade;
    }

    /**
     * @param $pid
     *
     * @return array
     */
    public function getPrioridadeById($pid) {
        $infoPrioridade = $this->database->ngSelectPrepared($this->tbl_ElementoPrioridade, ['prioridade' => $pid], "", "", 1);

        if (isset($infoPrioridade['prioridade'])) {
            return $infoPrioridade;
        }

        return [];
    }

}
