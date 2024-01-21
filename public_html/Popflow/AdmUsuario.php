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

class AdmUsuario {
    public $campos = ['usuarioNerdweb', 'nome', 'email', 'senha', 'imagem', 'area', 'administrador', 'ativo'];
    public $tblnameGrupoUsuario;
    public $camposGrupoUsuario;
    public $tblnameArea;
    public $camposArea;
    public $tblnameAreaUsuario;
    public $camposAreaUsuario;
    /** @var DataBase database */
    protected $database;
    private $passHashAlgo = 'sha256';

    /**
     * Construtor da classe AdmUsuario
     *
     * @param Database Object
     */
    public function __construct($database) {
        $this->database = $database;
        $this->tblname = 'back_AdmUsuario';

        $this->tblnameGrupoUsuario = 'back_AdmGrupoUsuario';
        $this->camposGrupoUsuario = ['usuarioNerdweb', 'grupo'];

        $this->tblnameArea = 'back_AdmArea';
        $this->camposArea = ['area', 'nome', 'cor', 'hidden'];

        $this->tblnameAreaUsuario = 'back_AdmAreaUsuario';
        $this->camposAreaUsuario = ['usuarioNerdweb', 'area'];
    }


    /**
     * Cria no banco o usuario
     *
     * Cria um novo usuario no banco de dados, grava as variaveis pertinentes
     * nao faz checagem de duplicidade na hora da insercao.
     *
     * @param array ( "nome", "email", "senha", "admFuncaoUser_id" )
     *
     * @return bool
     */
    public function insertIntoTheDatabase($arrayValores) {
        $arrayValores[2] = hash($this->passHashAlgo, $arrayValores[2]);
        $arrayCampos = array_slice($this->campos, 1);
        $result = $this->database->insertPrepared($this->tblname, $arrayCampos, $arrayValores);
        return $result;
    }


    /**
     * Atualiza no banco as informacoes do registro
     *
     * Retorna true caso tudo esteja certo
     * Retorna false em caso de algum erro
     *
     * @param      $arrayValores
     * @param bool $hashSenha
     *
     * @return bool
     * @internal param $array ($this->campos)
     *
     */
    public function updateElementOnTheDatabase($arrayValores, $hashSenha = TRUE) {
        if (count($arrayValores) >= 4) {
            if ($hashSenha) {
                $arrayValores[3] = hash($this->passHashAlgo, $arrayValores[3]);
            }
            $arrayCampos = $this->campos;
        }
        else {
            $arrayCampos = array_slice($this->campos, 0, 3);
        }

        $count = $this->database->updatePrepared($this->tblname, $arrayCampos, $arrayValores, ['usuarioNerdweb'], [$arrayValores[0]]);
        return $count > 0;
    }


    /**
     * Remove do banco os dados de um endereco a partir de um id
     *
     * @param int , id do registro
     *
     * @return boolean, TRUE se bem sucedido, FALSE caso contrario
     */
    public function removeElementFromTheDatabase($id) {
        $tmp = $this->database->deletePrepared($this->tblname, 'usuarioNerdweb', $id);
        $achouEnd = count($tmp);
        return $achouEnd > 0;
    }


    public function desativaUsuario($id) {
        $tmp = $this->database->ngUpdatePrepared($this->tblname, ['ativo' => 0], ['usuarioNerdweb' =>$id]);
        $achouEnd = count($tmp);
        return $achouEnd > 0;
    }


    /**
     * Lista os usuários
     *
     * Retorna todos os usuários do sistema cadastrados no banco, em um array com os campos
     * passados por parâmetro
     *
     * @param string $campos
     * @param string $orderBy
     *
     * @return array de usuários
     */
    public function listAll($campos = '', $orderBy = "nome", $listInativos = TRUE) {
        $filtro = [];
        if (!$listInativos) {
            $filtro =  ["ativo" => "1"];
        }
        return $this->database->ngSelectPrepared($this->tblname, $filtro, $campos, $orderBy);
    }

    /**
     * Retorna os dados de usuario a partir de um email
     *
     * retorna um array dos dados do usuario caso seja encontrado.
     * retorna FALSE caso contrario
     *
     * @param string , email do usuario
     *
     * @return bool
     */
    public function getUserDataWithEmail($email) {
        $retorno = $this->database->ngSelectPrepared($this->tblname, ['email' => $email]);
        $achou = count($retorno);
        if ($achou > 0) {
            return $retorno[0];
        }
        return FALSE;
    }

    /**
     * Retorna os dados de usuario a partir de um id
     *
     * retorna um array dos dados do usuario caso seja encontrado.
     * retorna FALSE caso contrario
     *
     * @param string , email do usuario
     *
     * @return array|bool
     */
    public function getUserDataWithId($userId) {
        $retorno = $this->database->ngSelectPrepared($this->tblname, ['usuarioNerdweb' => $userId]);
        $achou = count($retorno);
        if ($achou > 0) {
            return $retorno[0];
        }
        return FALSE;
    }

    /**
     * Funcao pra checar se o email jah existe no banco de dados
     *
     * Faz checagem de um email e retorna true caso encontrado,
     * retorna FALSE caso contrario
     *
     * @param string , email do usuario
     *
     * @return bool
     */
    public function checkIfEmailExist($email) {
        $retorno = $this->database->ngSelectPrepared($this->tblname, ['email' => $email]);
        $achou = count($retorno);
        return $achou > 0;
    }

    /**
     * Grava no banco uma nova senha para o usuario
     *
     * grava no banco a nova senha do usuario,
     * usada na parte de reset de senha,
     * menos segura que a funcao
     * updateUserPassword
     *
     * @param string , email do usuario
     * @param string , nova senha do usuario
     *
     * @return array
     */
    public function setPassword($email, $password) {
        $hashedPass = hash($this->passHashAlgo, $password);
        $retorno = $this->database->ngUpdatePrepared($this->tblname, ['senha' => $hashedPass], ['email' => $email]);
        return $retorno;
    }

    public function setAvatar($id, $avatar) {
        $avatar = '/backoffice/uploads/usuarios/' . $avatar;
        $retorno = $this->database->ngUpdatePrepared($this->tblname, ['imagem' => $avatar], ['usuarioNerdweb' => $id]);
        return TRUE;
    }

    /**
     * Grava no banco uma nova senha para o usuario
     *
     * grava no banco a nova senha do usuario,
     *
     * @param int    , id do usuario
     * @param string , senha antiga
     * @param string , nova senha
     *
     * @return array
     */
    public function updateUserPassword($user_id, $oldPassword, $newPassword) {
        $oldHashedPass = hash($this->passHashAlgo, $oldPassword);
        $newHashedPass = hash($this->passHashAlgo, $newPassword);
        $result = $this->database->ngUpdatePrepared($this->tblname, ['senha' =>$newHashedPass], ['usuarioNerdweb' => $user_id, 'senha' => $oldHashedPass]);
        return $result;
    }

    /**
     * Insere no banco um registro de grupo do usuário
     *
     * @param array ($this->camposGrupo)
     *
     * @return boolean, TRUE caso insira corretamente, FALSE em caso de algum erro
     */
    public function insertGrupo($arrayValores) {
        $result = $this->database->insertPrepared($this->tblnameGrupoUsuario, $this->camposGrupoUsuario, $arrayValores);
        return $result;
    }




    // *****************  FUNÇÕES GRUPOS  ***************** //

    /**
     * Lista os grupos do usuário
     *
     * Retorna todos os grupos do usuário pelo id passado como parâmetro
     *
     * @param int    id do usuario
     * @param string campos (padrão todos)
     *
     * @return array de grupos
     */
    public function getGruposUser($userId, $campos = '') {
        $result = $this->database->ngSelectPrepared($this->tblnameGrupoUsuario, ['usuarioNerdweb' => $userId], $campos);
        return $result;
    }

    /**
     * Remove as permissões do grupos
     *
     * Remove todos os grupos do usuário pelo id passado como parâmetro
     *
     * @param int    id do usuário
     * @param string campos (padrão todos)
     *
     * @return TRUE se grupos foram removidos, FALSE caso contrário
     */
    public function removeGruposUser($userId) {
        $tmp = $this->database->deletePrepared($this->tblnameGrupoUsuario, ['usuarioNerdweb'], [$userId]);
        $achouEnd = count($tmp);
        return $achouEnd > 0;
    }

    /**
     * Insere no banco um registro
     *
     * @param array ($this->campos)
     *
     * @return boolean, TRUE caso insira corretamente, FALSE em caso de algum erro
     */
    public function insertArea($arrayValores) {
        $arrayCampos = array_slice($this->camposArea, 1);
        $result = $this->database->insertPrepared($this->tblnameArea, $arrayCampos, $arrayValores);
        return $result;
    }





    // *****************  FUNÇÕES ÁREA ***************** //

    /**
     * Atualiza no banco as informacoes do registro
     *
     * @param array ($this->campos)
     *
     * @return TRUE caso tudo esteja certo, FALSE em caso de algum erro
     */
    public function updateArea($arrayValores) {
        $count = $this->database->updatePrepared($this->tblnameArea, $this->camposArea, $arrayValores, ['area'], [$arrayValores[0]]);
        return $count > 0;
    }

    /**
     * Remove do banco os dados de um endereco a partir de um id
     *
     * @param int , id do registro
     *
     * @return boolean, TRUE se bem sucedido, FALSE caso contrario
     */
    public function removeArea($id) {
        $tmp = $this->database->deletePrepared($this->tblnameArea, 'area', $id);
        $achouEnd = count($tmp);
        return $achouEnd > 0;
    }

    /**
     * Insere no banco um registro de área do usuário
     *
     * @param array ($this->camposArea)
     *
     * @return boolean, TRUE caso insira corretamente, FALSE em caso de algum erro
     */
    public function insertAreaUsuario($arrayValores) {
        $result = $this->database->insertPrepared($this->tblnameAreaUsuario, $this->camposAreaUsuario, $arrayValores);
        return $result;
    }

    /**
     * Lista as áreas do usuário
     *
     * Retorna todas as áreas do usuário pelo id passado como parâmetro
     *
     * @param int    id do usuario
     * @param string campos (padrão todos)
     *
     * @return array de áreas
     */
    public function getAreasUsuario($userId) {
        //$result = $this->database->ngSelectPrepared($this->tblnameAreaUsuario, ['usuarioNerdweb' => $userId], '');
        $result = $this->database->customQueryPDO("	SELECT a.* FROM $this->tblnameArea a
														INNER JOIN $this->tblnameAreaUsuario au
															ON a.area = au.area AND au.usuarioNerdweb = ?
													WHERE a.isUsed = 1 AND au.isUsed = 1 ORDER BY nome ASC
		", [$userId]);

        return $result;
    }

    /**
     * Lista os usuários de determinada área
     *
     * Retorna todos os usuários da área passada como parâmetro
     *
     * @param int id da area
     *
     * @return array de usuários
     */
    public function getUsuariosArea($areaId) {
        $result = $this->database->customQueryPDO("	SELECT u.* FROM $this->tblname u
													INNER JOIN $this->tblnameAreaUsuario au
														ON au.usuarioNerdweb = u.usuarioNerdweb AND au.area = ? 
													WHERE au.isUsed = 1 AND u.isUsed = 1 AND u.ativo = 1
													ORDER BY u.nome ASC", [$areaId]);

        return $result;
    }

    public function getUsuarios() {
        $result = $this->database->customQueryPDO("	SELECT u.* FROM $this->tblname u
													WHERE u.isUsed = 1 AND u.ativo = 1
													ORDER BY u.nome ASC", []);

        return $result;
    }


    /**
     * Remove as permissões do grupos
     *
     * Remove todos os grupos do usuário pelo id passado como parâmetro
     *
     * @param int    id do usuário
     * @param string campos (padrão todos)
     *
     * @return TRUE se grupos foram removidos, FALSE caso contrário
     */
    public function removeAreasUsuario($userId) {
        $tmp = $this->database->deletePrepared($this->tblnameAreaUsuario, ['usuarioNerdweb'], [$userId]);
        $achouEnd = count($tmp);
        return $achouEnd > 0;
    }

    /**
     * @param $idArea
     *
     * @return string
     */
    public function getInfoArea($idArea) {
        $idArea = (int)$idArea;
        $retorno = '';

        switch ($idArea) {
            case 1:
                $retorno = 'Admin';
                break;
            case 2:
                $retorno = 'Desenvolvimento';
                break;
            case 3:
                $retorno = 'Design';
                break;
            case 4:
                $retorno = 'Atendimento';
                break;
            case 5:
                $retorno = 'Comercial';
                break;
            case 6:
                $retorno = 'Marketing';
                break;
            default:
                $retorno = '';
                break;
        }

        return $retorno;
    }

    /**
     * @param            $idArea
     * @param bool|FALSE $lista
     *
     * @return array
     */
    public function getInfoArea2($idArea, $lista = FALSE) {
        if ($lista) {
            $retorno = $this->listArea();
        }
        else {
            $retorno = $this->getAreaWithId($idArea);
        }


        /*
                $arrayArea = array(
                                1 => array('area' => 1, 'nome' => 'Admin', 				'cor' => '#FFFFFF'),
                                2 => array('area' => 2, 'nome' => 'Desenvolvimento', 	'cor' => '#037F4C'),
                                3 => array('area' => 3, 'nome' => 'Design', 			'cor' => '#A25DDC'),
                                4 => array('area' => 4, 'nome' => 'Atendimento', 		'cor' => '#579BFC'),
                                5 => array('area' => 5, 'nome' => 'Comercial', 			'cor' => '#FDAB3D'),
                                6 => array('area' => 6, 'nome' => 'Marketing', 			'cor' => '#FDAB3D'),
                            );

                $retorno = array('area' => 0, 'nome' => '', 'cor' => '');

                if( $lista ){
                    $retorno = $arrayArea;
                } else {
                    if( isset($arrayArea[$idArea]) ){
                        $retorno = $arrayArea[$idArea];
                    }
                }
        */
        return $retorno;
    }

    /**
     * Lista as áreas
     *
     * Retorna todas as áreas do sistema cadastrados no banco, em um array com os campos
     * passados por parâmetro
     *
     * @param string campos (padrão todos)
     *
     * @return array de áreas
     */
    public function listArea($campos = '', $listHidden = FALSE) {
        $filtro = ["hidden" => "0"];
        if ($listHidden) {
            $filtro = [ "hidden" => "1" ];
        }
        return $this->database->ngSelectPrepared($this->tblnameArea, $filtro, $campos, " nome ASC ");
    }

    /**
     * Retorna os dados de um registro a partir de um id
     *
     * @param int , id do registro
     *
     * @return array|bool
     */
    public function getAreaWithId($itemId) {
        $retorno = $this->database->ngSelectPrepared($this->tblnameArea, ['area' => $itemId]);
        $achou = count($retorno);
        if ($achou > 0) {
            return $retorno[0];
        }
        return FALSE;
    }

    /**
     * Função de logout do usuario
     *
     * limpa a sessao do usuario,
     * e tem uma pequena chance de limpar sessoes expiradas
     * ( essa chance eh baixa para economizar processamento,
     * porem nao tao baixa para nao roda nunca )
     *
     */
    public function logout() {
        global $session;
        $sid = session_id();
        $session->destroy($sid);
        $_SESSION = [];
        // kda vez q logout eh usado ele tem 20% de chance de entrar no codigo abaixo
        // isso eh feito de modo a manter a tabela de sessoes o mais limpas possivel
        // porem sem consumir mto processamento do banco de dados
        $chance = mt_rand(0, 99);
        if ($chance >= 80) {
            //$session->clean();
        }
    }















    //	*************	FUNÇÕES DE LOGIN DE USUÁRIO   ************* //
    //	Encontrar um lugar melhor pra por ou nomes melhores de funcao
    //

    /**
     * Função de login do usuário no sistema
     *
     * Verifica as informações passadas pelo usuário e realiza o login no sistema
     *
     * @param string , endereco de email do usuario
     * @param string , senha do usuario
     *
     * @return boolean, TRUE caso login seja efetuado com sucesso, FALSE caso contrário
     */
    public function login($user, $pass) {
        $chance = mt_rand(0, 99);
        if ($chance >= 80) {
            global $session;
            //$session->clean();
        }

        $user = str_ireplace('%40', '@', $user);
        $user = mb_strtolower(rtrim($user));

        $encontrado = $this->checkUserPass($user, $pass);

        if (isset($encontrado)) {
            if ($encontrado['email'] == $user) {
                //session
                $_SESSION['hashedValues'] = $this->generateHash();
                $_SESSION['adm_usuario'] = $encontrado['usuarioNerdweb'];
                $_SESSION['adm_nome'] = $encontrado['nome'];
                $_SESSION['adm_email'] = $encontrado['email'];
                $_SESSION["adm_imagem"] = $encontrado["imagem"];
                $_SESSION['adm_is_admin'] = $encontrado['administrador'];
                $_SESSION['adm_ip'] = $_SERVER['REMOTE_ADDR'];

                return TRUE;
            }
        }
        return FALSE;
    }

    /**
     * Função pra checar se a combinação usuario + senha é válida
     *
     * Faz checagem de usuário + senha no banco
     *
     * @param string , email do usuario
     * @param string , senha sem codificação
     *
     * @return array|bool dos dados do usuário caso seja encontrado, NULL caso contrário
     */
    public function checkUserPass($user, $password) {
        $hashedPass = hash($this->passHashAlgo, $password);
        $retorno = $this->database->ngSelectPrepared($this->tblname, ['email' => $user, 'senha' => $hashedPass, 'ativo' => 1]);
        if ($retorno) {
            return $retorno[0];
        }
        return FALSE;
    }

    /**
     * Uso interno das funcoes de login e validacao
     *
     * gera um hash com algumas informacoes do usuario
     * serve para aumentar a complexidade de um possivel
     * ataque onde o usuario tem os seus cookies roubados
     *
     */
    private function generateHash() {
        // adicionando um poko de "aleatoriedade" na sessao pra dificultar o acesso indevido por usuarios maliciosos
        return hash('sha256', $_SERVER['HTTP_USER_AGENT']);
    }

    /**
     * Checa se a sessao é válida
     *
     * Analisa varios parametros para tentar descobrir
     * se a sessao eh valida e esta sendo usada pelo
     * usuario que logou no sistema
     *
     * retorna true se a sessao é válida
     * retorna false se alguma informação não está de acordo com o esperado
     */
    public function checkSession() {
        $sid = session_id();
        if (isset($_SESSION['adm_usuario'], $_SESSION['adm_ip']) && $_SESSION['hashedValues'] == $this->generateHash()) {
            return TRUE;
        }
        return FALSE;
    }
}
