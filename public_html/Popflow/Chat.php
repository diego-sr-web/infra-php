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

class Chat {
    /** @var DataBase $database */
    protected $database;

    protected $tbl_ElementoChat = 'pop_ElementoChat';
    protected $tbl_ProjetoChat = 'pop_ProjetoChat';
    protected $tbl_Elemento = 'pop_Elemento';
    protected $tbl_Projeto = 'pop_Projeto';
    protected $tbl_Responsavel = 'back_AdmUsuario';

    /**
     * chat constructor.
     *
     * @param $database
     */
    public function __construct($database) {
        $this->database = $database;
    }

    /**
     * @param     $elementoId
     * @param     $responsavelId
     * @param     $conteudo
     *
     * @param int $isSystem
     *
     * @return bool
     */
    public function addElementChat($elementoId, $responsavelId, $conteudo, $isSystem = 0) {
        $elemento = $this->database->ngSelectPrepared($this->tbl_Elemento, ['elemento' => $elementoId], '', '', 1);
        $responsavel = $this->database->ngSelectPrepared($this->tbl_Responsavel, ['usuarioNerdweb' => $responsavelId], '', '', 1);

        if (isset($elemento['elemento'], $responsavel['usuarioNerdweb'])) {
            $ok = $this->database->ngInsertPrepared($this->tbl_ElementoChat, ['elemento' => $elementoId, 'responsavel' => $responsavelId, 'conteudo' => $conteudo]);
            return $ok;
        }

        return FALSE;
    }

    /**
     * @param      $projetoId
     * @param      $responsavelId
     * @param      $conteudo
     *
     * @param null $elementoId
     * @param int  $isSystem
     *
     * @return bool
     */
    public function addProjectChat($projetoId, $responsavelId, $conteudo, $elementoId = NULL, $isSystem = 0) {
        $projeto = $this->database->ngSelectPrepared($this->tbl_Projeto, ['projeto' => $projetoId], '', '', 1);
        $responsavel = $this->database->ngSelectPrepared($this->tbl_Responsavel, ['usuarioNerdweb' => $responsavelId], '', '', 1);

        if (isset($projeto['projeto'], $responsavel['usuarioNerdweb'])) {
            $ok = $this->database->ngInsertPrepared($this->tbl_ProjetoChat, ['projeto' => $projetoId, 'elemento' => $elementoId, 'responsavel' => $responsavelId, 'conteudo' => $conteudo, 'sistema' => $isSystem]);
            return $ok;
        }

        return FALSE;
    }

    /**
     * @param int    $tipo
     * @param        $id
     * @param string $order
     */
    public function retornaHTMLExibicao($tipo = 1, $id, $order = 'ASC') {
        if ($tipo === 1) {
            $this->getElementChat($id, $order);
        }
        if ($tipo === 2) {
            $this->getProjetoChat($id, $order);
        }
    }

    /**
     * @param        $elementoId
     * @param string $order
     *
     * @return array
     */
    public function getElementChat($elementoId, $order = 'ASC') {
        $elemento = $this->database->ngSelectPrepared($this->tbl_Elemento, ['elemento' => $elementoId], '', '', 1);

        if (isset($elemento['elemento'])) {
            $chat = $this->database->ngSelectPrepared($this->tbl_ElementoChat, ['elemento' => $elementoId], '', 'data ' . $order);
            if (isset($chat[0]['elemento'])) {
                return $chat;
            }
        }

        return [];
    }

    /**
     * @param        $projetoId
     * @param null   $elementoId
     * @param string $order
     *
     * @param int    $sistema
     *
     * @return array
     */
    public function getProjetoChat($projetoId, $elementoId = NULL, $order = 'ASC', $sistema = 0) {
        $campos = ['projeto', "sistema"];
        $valores = [$projetoId, $sistema];

        $projeto = $this->database->ngSelectPrepared($this->tbl_Projeto, ['projeto' => $projetoId], '', '', 1);

        if ($elementoId != NULL) {
            $campos[] = 'elemento';
            $valores[] = $elementoId;
        }

        if (isset($projeto['projeto'])) {
            $chat = $this->database->selectPrepared($this->tbl_ProjetoChat, $campos, $valores, '', 'data ' . $order);
            if (isset($chat[0]['projeto'])) {
                return $chat;
            }
        }

        return [];
    }
}
