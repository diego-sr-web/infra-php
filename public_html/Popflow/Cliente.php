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

class Cliente {
    /** @var DataBase $database */
    protected $database;
    protected $tblClientes = "back_Cliente";
    protected $tblServicoClient = "back_ServicoCliente";

    public function __construct($database) {
        $this->database = $database;
    }


    /**
     *
     * @param $cid
     *
     * @return array
     */
    public function getClientById($cid) {
        $cliente = $this->database->ngSelectPrepared($this->tblClientes, ["cliente" => $cid], "", "", 1);
        if (isset($cliente["cliente"])) {
            return $cliente;
        }
        return [];
    }

    /**
     * @param $whmcsId
     *
     * @return array
     */
    public function getClientByWHMCSID($whmcsId) {
        $cliente = $this->database->ngSelectPrepared($this->tblClientes, ["whmcsId" => $whmcsId], "", "", 1);
        if (isset($cliente["cliente"])) {
            return $cliente;
        }
        return [];

    }

    /**
     * Retorna a lista de clientes do banco, por padrao retorna a lista competa, podendo ser passado
     * "ativo" ou "inativo" como parametros de um filtro
     *
     * @param int $filtro
     *
     * @return array
     */
    public function getClientList($filtro = 0) {
        // Listagem completa de clientes
        if ($filtro === 0) {
            $clients = $this->database->ngSelectPrepared($this->tblClientes);
            // Filtro pra soh clientes ativos
        }
        elseif ($filtro === "ativos") {
            $clients = $this->database->ngSelectPrepared($this->tblClientes, ["ativo" => 1]);
            // Filtro pra soh clientes inativos
        }
        elseif ($filtro === "inativos") {
            $clients = $this->database->ngSelectPrepared($this->tblClientes, ["ativo" => 0]);
        }
        if (isset($clients[0])) {
            return $clients;
        }
        return [];
    }


    /**
     * @param $cid
     *
     * @return array
     */
    public function getServicesByClientID($cid) {
        $services = $this->database->ngSelectPrepared($this->tblServicoClient, ["cliente" => $cid], "servico,periodicidade,dataAssinatura,dataAtualizacao,observacao");
        if (isset($services[0])) {
            return $services;
        }
        return [];
    }

}
