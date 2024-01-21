<?php
require_once __DIR__ . "/../autoloader.php";
require_once __DIR__ . "/_init.inc.php";

if (isset($_POST["form"])) {
    switch ($_POST["form"]) {
        case 'statusArea':
            $idArea = $_POST['idArea'];

            $dadosArea = $usuario->getAreaWithId($idArea);

            $atualizaArea = array_slice($dadosArea, 0, count($dadosArea) - 1);
            if ($dadosArea['hidden'] == 1) {
                $atualizaArea['hidden'] = 0;
            }
            else {
                $atualizaArea['hidden'] = 1;
            }
            $atualizaArea = array_values($atualizaArea);
            $retorno = $usuario->updateArea($atualizaArea);
            if ($retorno) {
                $msgRetorno = 1;
            }
            else {
                $msgRetorno = 0;
            }
            echo $msgRetorno;
            exit;
            break;

        case 'statusUsuario':
            $idUsuario = $_POST['idUsuario'];
            $dadosUsuario = $usuario->getUserDataWithId($idUsuario);

            $atualizaUsuario = array_slice($dadosUsuario, 0, count($dadosUsuario) - 1);
            if ($dadosUsuario['ativo'] == 1) {
                $atualizaUsuario['ativo'] = 0;
            }
            else {
                $atualizaUsuario['ativo'] = 1;
            }
            $atualizaUsuario = array_values($atualizaUsuario);
            $retorno = $usuario->updateElementOnTheDatabase($atualizaUsuario);
            if ($retorno) {
                $msgRetorno = 1;
            }
            else {
                $msgRetorno = 0;
            }
            echo $msgRetorno;
            exit;

            break;

        case 'statusCliente':
            $idCliente = $_POST['idCliente'];

            $dadosCliente = $BNCliente->getDataWithId($idCliente);

            $atualizaCliente = array_slice($dadosCliente, 0, count($dadosCliente) - 1);
            if ($dadosCliente['ativo'] == 1) {
                $atualizaCliente['ativo'] = 0;
            }
            else {
                $atualizaCliente['ativo'] = 1;
            }
            $atualizaCliente = array_values($atualizaCliente);
            $retorno = $BNCliente->updateElementOnTheDatabase($atualizaCliente);
            if ($retorno) {
                $msgRetorno = 1;
            }
            else {
                $msgRetorno = 0;
            }
            echo $msgRetorno;
            exit;

            break;


        case 'adicionar-cliente':
            if ($_POST['nomeFantasia'] && $_POST['dataEntrada']) {
               /* $logo = uploadImagemBase64($_POST['image-data'], 'cliente', $BNCliente->uploadFolder);
                $logo_full = uploadArquivo('image', $BNCliente->uploadFolder, 'png,jpg,gif', 'cliente-full');*/

                $dados = $BNCliente->valoresPadrao;
                $dados['nomeFantasia'] = $_POST['nomeFantasia'];
                $dados['responsavel'] = $_POST['responsavel'];
                $dados['email'] = $_POST['email'];
                $dados['contato'] = $_POST['contato'];
                /*$dados['logo'] = $logo['arquivo'];
                $dados['logo_full'] = $logo_full['arquivo'];*/
                $dados['dataEntrada'] = $_POST['dataEntrada'];
                $dados['dataCriacao'] = date('Y-m-d H:i:s');
                $dados['observacao'] = $_POST['observacao'];
                $dados = array_values($dados);

                $retorno = $BNCliente->insertIntoTheDatabase($dados);

                if ($retorno) {
                    $msgRetorno = 1 ;
                }
                else {
                    $msgRetorno = 0 ;
                }
            }
            else {
                $msgRetorno = 0 ;
            }

            echo $msgRetorno;
            break;

        case 'editar-cliente':
            if ($_POST['cliente'] && $_POST['nomeFantasia'] && $_POST['dataEntrada']) {
                $infoCliente = $BNCliente->getDataWithId($_POST['cliente']);

                $atualizaCliente = array_slice($infoCliente, 0, count($infoCliente) - 1);
                $atualizaCliente['nomeFantasia'] = $_POST['nomeFantasia'];
                $atualizaCliente['responsavel'] = $_POST['responsavel'];
                $atualizaCliente['email'] = $_POST['email'];
                $atualizaCliente['contato'] = $_POST['contato'];
                $atualizaCliente['dataEntrada'] = date("Y-m-d", Utils::strtotime($_POST['dataEntrada']));
                $atualizaCliente['dataCriacao'] = $infoCliente['dataCriacao'];
                $atualizaCliente['observacao'] = $_POST['observacao'];
                $atualizaCliente = array_values($atualizaCliente);

                $retorno = $BNCliente->updateElementOnTheDatabase($atualizaCliente);

                if ($retorno) {
                    $msgRetorno = 1 ;
                }
                else {
                    $msgRetorno = 0;
                }
            }
            else {
                $msgRetorno = 0;
            }

            echo $msgRetorno;

            break;


        case 'adicionar-area':
            if ($_POST['nome'] && $_POST['cor']) {
                $dados = [$_POST['nome'], $_POST['cor'], $_POST['hidden'] = 1];

                try {
                    $retorno = $usuario->insertArea($dados);
                } catch (Exception $e){
                    $msgRetorno = 0;
                }

                if ($retorno) {
                    $msgRetorno = 1;
                    //$_SESSION["msg_sucesso"] = 'Área cadastrada com sucesso.';
                }
                else {
                    $msgRetorno = 0;
                    //$_SESSION["msg_erro"] = 'Houve algum erro ao salvar os dados, tente novamente.';
                }
            }
            echo $msgRetorno;
            exit;
            break;

        case 'editar-area':
            if ($_POST['area'] && $_POST['nome'] && $_POST['cor']) {
                $atualizaArea = [$_POST["area"], $_POST['nome'], $_POST['cor'],$_POST['hidden'] = 1];

                try {
                    $retorno = $usuario->updateArea($atualizaArea);
                } catch (Exception $e) {
                    $msgRetorno = 0;
                }

                if ($retorno) {
                    //$_SESSION["msg_sucesso"] = 'Área atualizada com sucesso.';
                    $msgRetorno = 1;
                }
                else {
                    /*$_SESSION["msg_erro"] = 'Houve algum erro ao salvar o registro.';*/
                    $msgRetorno = 0;
                }
            }
            else {
               /*$_SESSION["msg_erro"] = 'Preencha todos os campos obrigatórios.';*/
                $msgRetorno = 0;
            }

            echo $msgRetorno;
            break;

        case 'adicionar-usuario':
            if ($_POST['nome'] && $_POST['email'] && $_POST['senha'] && $_POST['senha2']) {
                if ($_POST['senha'] === $_POST['senha2']) {
                    $administrador = 0;
                    if ((isset($_POST['administrador']) && ($_POST['administrador'] == 'on'))) {
                        $administrador = 1;
                    }
                    $dados = [$_POST['nome'], $_POST['email'], $_POST['senha'], 'https://nerdweb.popflow.com.br/backoffice/uploads/usuarios/user-boy.png', NULL, $administrador, 1];
                    $retorno = $usuario->insertIntoTheDatabase($dados);

                    if ($retorno) {
                        $userId = $database->returnLastInsertedId();
                        if (isset($_POST['areas'])) {
                            foreach ($_POST['areas'] as $areaAux) {
                                $dados = [$userId, $areaAux];
                                $retorno = $usuario->insertAreaUsuario($dados);
                            }
                        }

                        if ($retorno) {
                            $htmlEmail = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html>';
                            $htmlEmail .= '<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></head>';
                            $htmlEmail .= '<body>';
                            $htmlEmail .= 'Prezado(a) ' . $_POST['nome'] . ',<br><br>';
                            $htmlEmail .= 'Informamos que seu cadastro no sistema de back-office da Nerdweb foi efetuado com sucesso.<br><br>';
                            $htmlEmail .= 'Abaixo segue as informações para login na ferramenta.<br><br>';
                            $htmlEmail .= 'Usuário: ' . $_POST['email'] . '<br>';
                            $htmlEmail .= 'Senha: ' . $_POST['senha'] . '<br><br>';
                            $htmlEmail .= 'URL de acesso: <a href="https://nerdweb.popflow.com.br/backoffice">https://nerdweb.popflow.com.br/backoffice</a><br><br>';
                            $htmlEmail .= '*** GUARDE ESSAS INFORMAÇÕES EM LOCAL SEGURO ***<br><br>';
                            $htmlEmail .= 'Atenciosamente,<br>';
                            $htmlEmail .= 'Nerdweb<br><a href="https://www.nerdweb.com.br">https://www.nerdweb.com.br</a><br>';
                            $htmlEmail .= '</body>';
                            $htmlEmail .= '</html>';
                            $htmlEmail = Utils::htmlSpecialCodes($htmlEmail);

                            $enviado = Utils::enviaEmail('Nerdweb', $_POST['email'], 'Cadastro Backoffice Nerdweb', $htmlEmail, $_POST['nome'], $_POST['email']);

                            if ($enviado) {
                                $msgRetorno = 1;
                            }
                            else {
                                $msgRetorno = 1;
                            }
                        }
                        else {
                            $msgRetorno = 0;
                        }

                    }
                    else {
                        $msgRetorno = 0;
                    }
                }
                else {
                    $msgRetorno = 0;
                }
            }
            else {
                $msgRetorno = 0;
            }

            echo $msgRetorno;

            break;


        case 'editar-usuario':
            if ($_POST['usuario'] && $_POST['nome'] && $_POST['email']) {
                $infoUsuario = $usuario->getUserDataWithId($_POST['usuario']);

                $atualizaUsuario = array_slice($infoUsuario, 0, count($infoUsuario) - 1);
                $atualizaUsuario['nome'] = $atualizaUsuario['nome']  ??$_POST['nome'];
                $atualizaUsuario['email'] = $atualizaUsuario['email'] ?? $_POST['email'];
                $hashSenha = FALSE;
                if (!empty($_POST['senha'])) {
                    $atualizaUsuario['senha'] = $_POST['senha'];
                    $hashSenha = TRUE;
                }
                $atualizaUsuario['administrador'] = 0;
                if ((isset($_POST['administrador']) && $_POST['administrador'] == 'Sim')) {
                    $atualizaUsuario['administrador'] = 1;
                }
                $atualizaUsuario = array_values($atualizaUsuario);

                $retorno = $usuario->updateElementOnTheDatabase($atualizaUsuario, $hashSenha);
                if ($retorno) {
                    $msgRetorno = 1;
                    $removido = $usuario->removeAreasUsuario($infoUsuario['usuarioNerdweb']);
                    if (isset($_POST['areas'])) {
                        foreach ($_POST['areas'] as $areaAux) {
                            $dados = [$infoUsuario['usuarioNerdweb'], $areaAux];
                            $retorno = $usuario->insertAreaUsuario($dados);
                        }
                    }
                }
                else {
                    $msgRetorno = 0;
                }
            }
            else {
                $msgRetorno = 0;
            }

            echo $msgRetorno;

            break;


    }
}
if (FALSE) {
    if (isset($_POST['form'])) {
        switch ($_POST['form']) {



            case 'editar-area':
                if ($_POST['area'] && $_POST['nome'] && $_POST['cor']) {
                    $atualizaArea = [$_POST["area"], $_POST['nome'], $_POST['cor']];

                    $retorno = $usuario->updateArea($atualizaArea);
                    if ($retorno) {
                        $_SESSION["msg_sucesso"] = 'Área atualizada com sucesso.';
                    }
                    else {
                        $_SESSION["msg_erro"] = 'Houve algum erro ao salvar o registro.';
                    }
                }
                else {
                    $_SESSION["msg_erro"] = 'Preencha todos os campos obrigatórios.';
                }
                break;

            case 'adicionar-usuario':
                if ($_POST['nome'] && $_POST['email'] && $_POST['senha'] && $_POST['senha2']) {
                    if ($_POST['senha'] === $_POST['senha2']) {
                        $administrador = 0;
                        if ((isset($_POST['administrador']) && ($_POST['administrador'] == 1))) {
                            $administrador = 1;
                        }
                        $dados = [$_POST['nome'], $_POST['email'], $_POST['senha'], 'https://nerdweb.popflow.com.br/backoffice/uploads/usuarios/user-boy.png', NULL, $administrador, 1];
                        $retorno = $usuario->insertIntoTheDatabase($dados);

                        if ($retorno) {
                            $userId = $database->returnLastInsertedId();
                            $_SESSION["msg_sucesso"] = 'Usuário cadastrado com sucesso.';
                            if (isset($_POST['grupo'])) {
                                $dados_grupo = [$userId, $_POST['grupo']];
                                $retorno = $usuario->insertGrupo($dados_grupo);
                            }

                            if (isset($_POST['area'])) {
                                foreach ($_POST['area'] as $areaAux) {
                                    $dados = [$userId, $areaAux];
                                    $retorno = $usuario->insertAreaUsuario($dados);
                                }
                            }

                            if ($retorno) {

                                $htmlEmail = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html>';
                                $htmlEmail .= '<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></head>';
                                $htmlEmail .= '<body>';
                                $htmlEmail .= 'Prezado(a) ' . $_POST['nome'] . ',<br><br>';
                                $htmlEmail .= 'Informamos que seu cadastro no sistema de back-office da Nerdweb foi efetuado com sucesso.<br><br>';
                                $htmlEmail .= 'Abaixo segue as informações para login na ferramenta.<br><br>';
                                $htmlEmail .= 'Usuário: ' . $_POST['email'] . '<br>';
                                $htmlEmail .= 'Senha: ' . $_POST['senha'] . '<br><br>';
                                $htmlEmail .= 'URL de acesso: <a href="https://nerdweb.popflow.com.br/backoffice">https://nerdweb.popflow.com.br/backoffice</a><br><br>';
                                $htmlEmail .= '*** GUARDE ESSAS INFORMAÇÕES EM LOCAL SEGURO ***<br><br>';
                                $htmlEmail .= 'Atenciosamente,<br>';
                                $htmlEmail .= 'Nerdweb<br><a href="https://www.nerdweb.com.br">https://www.nerdweb.com.br</a><br>';
                                $htmlEmail .= '</body>';
                                $htmlEmail .= '</html>';
                                $htmlEmail = Utils::htmlSpecialCodes($htmlEmail);

                                $enviado = Utils::enviaEmail('Nerdweb', $_POST['email'], 'Cadastro Backoffice Nerdweb', $htmlEmail, $_POST['nome'], $_POST['email']);

                                if ($enviado) {
                                    $_SESSION["msg_sucesso"] = 'Usuário cadastrado com sucesso.';
                                }
                                else {
                                    $_SESSION["msg_sucesso"] = 'Usuário cadastrado com sucesso, mas houve um erro ao enviar email.';
                                }
                            }
                            else {
                                $_SESSION["msg_erro"] = 'Houve algum erro ao salvar os dados, tente novamente.';
                            }

                        }
                        else {
                            $_SESSION["msg_erro"] = 'Houve algum erro ao salvar os dados, tente novamente.';
                        }
                    }
                    else {
                        $_SESSION["msg_erro"] = 'Os campos senha e confirmação de senha precisar ser iguais.';
                    }
                }
                else {
                    $_SESSION["msg_erro"] = 'Preencha todos os campos obrigatórios.';
                }
                break;



            case 'adicionar-cliente':
                if ($_POST['nomeFantasia'] && $_POST['dataEntrada']) {
                    $logo = uploadImagemBase64($_POST['image-data'], 'cliente', $BNCliente->uploadFolder);
                    $logo_full = uploadArquivo('image', $BNCliente->uploadFolder, 'png,jpg,gif', 'cliente-full');

                    $dados = $BNCliente->valoresPadrao;
                    $dados['nomeFantasia'] = $_POST['nomeFantasia'];
                    $dados['responsavel'] = $_POST['responsavel'];
                    $dados['email'] = $_POST['email'];
                    $dados['contato'] = $_POST['contato'];
                    $dados['logo'] = $logo['arquivo'];
                    $dados['logo_full'] = $logo_full['arquivo'];
                    $dados['dataEntrada'] = $_POST['dataEntrada'];
                    $dados['dataCriacao'] = date('Y-m-d H:i:s');
                    $dados['observacao'] = $_POST['observacao'];
                    $dados['whmcsId'] = $_POST['whmcsId'];
                    $dados = array_values($dados);

                    $retorno = $BNCliente->insertIntoTheDatabase($dados);

                    if ($retorno) {
                        $_SESSION["msg_sucesso"] = 'Cliente cadastrado com sucesso.';
                    }
                    else {
                        $_SESSION["msg_erro"] = 'Houve algum erro ao salvar os dados, tente novamente.';
                    }
                }
                else {
                    $_SESSION["msg_erro"] = 'Preencha todos os campos obrigatórios.';
                }
                break;

            case 'editar-cliente':
                if ($_POST['cliente'] && $_POST['nomeFantasia'] && $_POST['dataEntrada']) {
                    $infoCliente = $BNCliente->getDataWithId($_POST['cliente']);

                    $logo = uploadImagemBase64($_POST['image-data'], 'cliente', $BNCliente->uploadFolder);
                    $logo_full = uploadArquivo('image', $BNCliente->uploadFolder, 'png,jpg,gif', 'cliente-full');

                    $atualizaCliente = array_slice($infoCliente, 0, count($infoCliente) - 1);
                    $atualizaCliente['nomeFantasia'] = $_POST['nomeFantasia'];
                    $atualizaCliente['responsavel'] = $_POST['responsavel'];
                    $atualizaCliente['email'] = $_POST['email'];
                    $atualizaCliente['contato'] = $_POST['contato'];
                    $atualizaCliente['logo'] = ($logo['arquivo']) ? $logo['arquivo'] : $infoCliente['logo'];
                    $atualizaCliente['logo_full'] = ($logo_full['arquivo']) ? $logo_full['arquivo'] : $infoCliente['logo_full'];
                    $atualizaCliente['dataEntrada'] = $_POST['dataEntrada'];
                    $atualizaCliente['observacao'] = $_POST['observacao'];
                    $atualizaCliente['whmcsId'] = $_POST['whmcsId'];
                    $atualizaCliente = array_values($atualizaCliente);

                    $retorno = $BNCliente->updateElementOnTheDatabase($atualizaCliente);

                    if ($retorno) {
                        $_SESSION["msg_sucesso"] = 'Usuário editado com sucesso.';
                    }
                    else {
                        $_SESSION["msg_erro"] = 'Houve algum erro ao salvar os dados, tente novamente.';
                    }
                }
                else {
                    $_SESSION["msg_erro"] = 'Preencha todos os campos obrigatórios.';
                }
                break;


        }
    }
}
