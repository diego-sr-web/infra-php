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

class elementoCategoria {
    /** @var DataBase $database */
    protected $database;
    protected $tbl_ElementoCategoria = "pop_ElementoCategoria";

    /**
     * elementoCategoria constructor.
     *
     * @param $database
     */
    public function __construct($database) {
        $this->database = $database;
    }

    /**
     * @param array  $campos
     * @param array  $valores
     * @param string $order
     * @param string $limit
     *
     * @return array
     */
    public function getCategoryList($campos = [], $valores = [], $order = 'nome', $limit = '') {
        $size = count($campos);

        if ($size === 0) {
            $category_list = $this->database->ngSelectPrepared($this->tbl_ElementoCategoria, [], '', $order, $limit);
        }
        else {
            $category_list = $this->database->selectPrepared($this->tbl_ElementoCategoria, $campos, $valores, '', $order, $limit);
        }

        if (isset($category_list[0])) {
            return $category_list;
        }
        return $category_list;
    }

    /**
     * @return int
     */
    public function getCategoryCount() {
        $category_list = $this->database->ngSelectPrepared($this->tbl_ElementoCategoria);
        if (isset($category_list[0])) {
            return count($category_list);
        }
        return 0;
    }

    /**
     * @param $cid
     *
     * @return array
     */
    public function getCategoryById($cid) {
        $category = $this->database->ngSelectPrepared($this->tbl_ElementoCategoria, ["categoria" => $cid], "", "", 1);
        if (isset($category["categoria"])) {
            return $category;
        }
        return [];
    }

}
