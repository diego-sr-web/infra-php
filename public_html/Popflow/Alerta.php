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

class Alerta {
    public $tipos = [
        'critico' => [
            'classe'    => 'callout-danger',
            'cor'       => '#000000',
            'expira_em' => 30 // tempo em dias
        ],
        'info'    => [
            'classe'    => 'callout-info',
            'cor'       => '#00C875',
            'expira_em' => 30 // tempo em dias
        ]
    ];
    /** @var DataBase $database */
    protected $database;
    protected $tblname = 'pop_Alerta';

    /**
     * Alerta constructor.
     *
     * @param $database
     */
    public function __construct($database) {
        $this->database = $database;
    }


    /**
     * @param        $titulo
     * @param        $texto
     * @param string $tipo
     * @param        $projeto
     * @param null   $elemento
     *
     * @return bool
     */
    public function criaAlerta($titulo, $texto, $tipo = 'default', $projeto, $elemento = NULL) {
        $ok = $this->database->ngInsertPrepared(
            $this->tblname,
            ['titulo'        => $titulo,
             'texto'         => $texto,
             'tipo'          => $tipo,
             'dataCriacao'   => date('Y-m-d H:i:s'),
             'dataExpiracao' => date('Y-m-d H:i:s', strtotime('+ ' . $this->tipos[$tipo]['expira_em'] . ' days')),
             'projeto'       => $projeto,
             'elemento'      => $elemento
            ]
        );

        return $ok;
    }


    /**
     * @param $alertaId
     *
     * @return array
     */
    public function getAlerta($alertaId) {
        $alerta = $this->database->ngSelectPrepared($this->tblname, ['alerta' => $alertaId], '', '', 1);
        if (isset($alerta[0])) {
            return $alerta[0];
        }

        return [];
    }


    /**
     * @param $projetoId
     *
     * @return array
     */
    public function getAlertasProjeto($projetoId) {
        $dtAtual = date('Y-m-d H:i:s');
        $alertas = $this->database->ngSelectPrepared($this->tblname, ['projeto' => $projetoId, 'elemento' => NULL, 'dataExpiracao >' => $dtAtual], '', 'dataCriacao DESC');

        if (isset($alertas[0])) {
            return $alertas;
        }

        return [];
    }


    /**
     * @param $elementoId
     *
     * @return array
     */
    public function getAlertasElemento($elementoId) {
        $dtAtual = date('Y-m-d H:i:s');
        $alertas = $this->database->ngSelectPrepared($this->tblname, ['elemento' => $elementoId, 'dataExpiracao >' => $dtAtual], '', 'dataCriacao DESC');

        if (isset($alertas[0])) {
            return $alertas;
        }

        return [];
    }
}
