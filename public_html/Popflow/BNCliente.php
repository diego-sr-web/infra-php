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

class BNCliente {
    public $valoresPadrao;
    public $tblnameServicoCliente;
    public $camposServicoCliente;
    public $valoresPadraoServicoCliente;
    public $path = '/backoffice/uploads/clientes/';
    public $uploadFolder;
    /** @var DataBase $database */
    protected $database;

    /**
     * Construtor da classe BNCliente
     *
     * @param Database Object
     *
     */

    /** @var DataBase $database */
    public function __construct($database) {
        $this->database = $database;
        $this->tblname = 'back_Cliente';

        $this->uploadFolder = $_SERVER['DOCUMENT_ROOT'] . '/backoffice/uploads/clientes/';

        $this->campos = ['cliente', 'nomeFantasia', 'responsavel', 'email', 'contato', 'logo', 'logo_full', 'dataEntrada', 'dataCriacao', 'observacao', 'ativo', 'whmcsId'];
        $this->valoresPadrao = ['cliente' => NULL, 'nomeFantasia' => NULL, 'responsavel' => NULL, 'email' => NULL, 'contato' => NULL, 'logo' => 'default.png', 'logo_full' => 'default.png', 'dataEntrada' => date('Y-m-d'), 'dataCriacao' => date('Y-m-d H:i:s'), 'observacao' => NULL, 'ativo' => 1, 'whmcsId' => NULL];

        $this->tblnameServicoCliente = 'back_ServicoCliente';
        $this->camposServicoCliente = ['servicoCliente', 'cliente', 'servico', 'periodicidade', 'dataAssinatura', 'dataAtualizacao', 'observacao'];
        $this->valoresPadraoServicoCliente = ['servicoCliente' => NULL, 'cliente' => NULL, 'servico' => NULL, 'periodicidade' => NULL, 'dataAssinatura' => NULL, 'dataAtualizacao' => date('Y-m-d H:i:s'), 'observacao' => NULL,];
    }


    /**
     * @param $arrayValores
     *
     * @return bool
     */
    public function insertIntoTheDatabase($arrayValores) {
        $result = $this->database->insertPrepared($this->tblname, $this->campos, $arrayValores);
        return $result;
    }


    /**
     * @param $arrayValores
     *
     * @return bool
     */
    public function updateElementOnTheDatabase($arrayValores) {
        $count = $this->database->updatePrepared($this->tblname, $this->campos, $arrayValores, [$this->campos[0]], [$arrayValores[0]]);
        return $count > 0;
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function getDataWithId($id) {
        $retorno = $this->database->ngSelectPrepared($this->tblname, [$this->campos[0] => $id]);
        $achou = count($retorno);
        if ($achou > 0) {
            return $retorno[0];
        }
        return FALSE;
    }


    /**
     * @param string $campos
     *
     * @param string $orderBy
     *
     * @return array
     */
    public function listAll($campos = '', $orderBy = "") {
        $result = $this->database->ngSelectPrepared($this->tblname, [], $campos, $orderBy);
        return $result;
    }
}
