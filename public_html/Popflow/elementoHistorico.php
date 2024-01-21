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

class elementoHistorico {
    /** @var DataBase $database */
    protected $database;
    protected $tbl_ElementoHistorico = "pop_ElementoHistorico";
    protected $tbl_ElementoHistoricoAcao = "pop_ElementoHistoricoAcao";

    /**
     * elementoHistorico constructor.
     *
     * @param $database
     */
    public function __construct($database) {
        $this->database = $database;
    }

    /**
     * @param       $elementoId
     * @param       $modo
     * @param array $args
     *
     * @return bool
     */
    public function insert($elementoId, $modo, $args = []) {
        $uid = $args["usuario"] ?? $_SESSION["adm_usuario"];
        $data = $args["data"] ?? date("Y-m-d H:i:s");

        $acao = $modo;
        $this->database->ngInsertPrepared($this->tbl_ElementoHistorico, ["usuario" => $uid, "elemento" => $elementoId, "acao" => $acao, "data" => $data]);
        return TRUE;
    }

    /**
     * @param      $elementoId
     * @param null $usuarioId
     *
     * @return int
     */
    public function calculaTempoTrabalhado($elementoId, $usuarioId = NULL) {
        $historico = $this->listHistoricoTarefa($elementoId, $usuarioId);

        if ($historico) {
            $tempoTrabalhado = 0;
            $inicio = $fim = FALSE;

            foreach ($historico as $item) {
                if ($item['acao'] == 1 || $item['acao'] == 3) {
                    $inicio = $item['data'];
                }
                if ($item['acao'] == 2 || $item['acao'] == 4) {
                    $fim = $item['data'];
                }
                if ($inicio && $fim) {
                    $diferenca = strtotime($fim) - strtotime($inicio);
                    $tempoTrabalhado += $diferenca;

                    $inicio = $fim = FALSE;
                }
            }

            if ($inicio && !$fim) {
                $diferenca = (strtotime(date('Y-m-d H:i:s')) - strtotime($inicio));
                $tempoTrabalhado += $diferenca;
            }

            return $tempoTrabalhado;
        }

        return 0;
    }

    /**
     * @param      $elementoId
     * @param null $usuarioId
     *
     * @return array
     */
    public function listHistoricoTarefa($elementoId, $usuarioId = NULL) {
        $condicoes = ['elemento'];
        $valores = [$elementoId];

        if ($usuarioId) {
            $condicoes[] = 'usuario';
            $valores[] = $usuarioId;
        }

        $historico = $this->database->selectPrepared($this->tbl_ElementoHistorico, $condicoes, $valores, '', 'data');
        return $historico;
    }

}
