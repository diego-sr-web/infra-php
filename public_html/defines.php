<?php
// Esse arquivo vai ser transformado na classe utils na primeira oportunidade

/** @noinspection UntrustedInclusionInspection */
/** @noinspection PhpIncludeInspection */
//require_once '/home2/extra_software/mail.php';

date_default_timezone_set('America/Sao_Paulo');

@define('POP_URL', $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["SERVER_NAME"] . '/backoffice/pop');


define('USUARIOADMGERAL', 1);


/* áreas Nerdweb */
define('AREA_ADMIN', 1);


/* prioridades projeto POP */
define('POP_PRIORIDADE_ALTA', 101);
define('POP_PRIORIDADE_NORMAL', 102);
define('POP_PRIORIDADE_BAIXA', 103);


/* áreas que possuem contato com o cliente -> que tem o botão para mudar o status para "aguardando cliente" */
define('AREAS_CONTATO_CLIENTE', [4, 17]);


if (!function_exists("periodicidade")) {
    /**
     * @param $str
     *
     * @return string
     */
    function periodicidade($str) {
        if ($str == 1) {
            return "Mensal";
        }
        elseif ($str == 2) {
            return "Bimensal";
        }
        elseif ($str == 6) {
            return "Semestral";
        }
        elseif ($str == 12) {
            return "Anual";
        }
        return "";
    }
}

if (!function_exists("getNomeMes")) {
    /**
     * Retorna o nome de um mês
     *
     * @param (int) número do mês
     *
     * @return string (string) Nome do mês
     */
    function getNomeMes($numMes) {
        $numMes = (int)$numMes;
        switch ($numMes) {
            case 1:
                $retorno = 'Janeiro';
                break;
            case 2:
                $retorno = 'Fevereiro';
                break;
            case 3:
                $retorno = 'Março';
                break;
            case 4:
                $retorno = 'Abril';
                break;
            case 5:
                $retorno = 'Maio';
                break;
            case 6:
                $retorno = 'Junho';
                break;
            case 7:
                $retorno = 'Julho';
                break;
            case 8:
                $retorno = 'Agosto';
                break;
            case 9:
                $retorno = 'Setembro';
                break;
            case 10:
                $retorno = 'Outubro';
                break;
            case 11:
                $retorno = 'Novembro';
                break;
            case 12:
                $retorno = 'Dezembro';
                break;
            default:
                $retorno = '';
                break;
        }

        return $retorno;
    }
}

if (!function_exists('uploadArquivo')) {
    /**
     * Realiza upload de arquivo, para campos de upload de um único arquivo
     *
     * @param (string) name do campo de upload
     * @param string $destino
     * @param string $extensoes
     * @param string $inicioNomeArquivo
     *
     * @return array (array('arquivo'=>'', 'msg'=> '')) arquivo terá valor se o upload tiver sucesso, msg terá valor se houver algum erro
     */
    function uploadArquivo($chave, $destino = "", $extensoes = "", $inicioNomeArquivo = "", $single_file = TRUE) {
        if ($single_file === TRUE) {
            if (!$_FILES[$chave]['name']) {
                return ['arquivo' => '', 'msg' => 'Nenhum arquivo enviado'];
            }
        }
        if ($single_file === TRUE) {
            $nome = $_FILES[$chave]['name'];
        }
        else {
            $nome = $_FILES[$chave]['name'][$single_file];
        }
        $nomeTemporario = explode(".", $nome);
        $nomeTemporario = $nomeTemporario[0];
        // Extensão do arquivo

        if (preg_match("/\\.([^\\.]*)$/", $nome, $exts)) {
            $contExtencao = count($exts);
            $extencao = $exts[$contExtencao - 1]; // Última extensão
        }
        else {
            $extencao = "";
        }

        // Nome único para o arquivo
        $hash = substr(md5(uniqid(mt_rand(), 1)), 0, 5);
        $nomeArquivo = $nomeTemporario . '-' . $hash . '.' . $extencao;

        $extPermitidas = explode(",", strtolower($extensoes));

        if ($extensoes) {
            if (!in_array($extencao, $extPermitidas)) {
                if ($single_file === TRUE) {
                    $retorno = "'" . $_FILES[$chave]['name'] . "' é um arquivo inválido.";
                }
                else {
                    $retorno = "'" . $_FILES[$chave]['name'][$single_file] . "' é um arquivo inválido.";
                }
                return ['arquivo' => '', 'msg' => $retorno];
            }
        }

        if ($destino) {
            $destino .= '/';
        } //Adiciona uma barra '/' no fim do caminho
        $uploadfile = $destino . $nomeArquivo;

        $retorno = '';
        if ($single_file === TRUE) {
            $moved = !move_uploaded_file($_FILES[$chave]['tmp_name'], $uploadfile);
        }
        else {
            $moved = !move_uploaded_file($_FILES[$chave]['tmp_name'][$single_file], $uploadfile);
        }
        if ($moved) {
            if ($single_file === TRUE) {
                $retorno = "Não foi possível enviar o arquivo '" . $_FILES[$chave]['name'] . "'";
            }
            else {
                $retorno = "Não foi possível enviar o arquivo '" . $_FILES[$chave]['name'][$single_file] . "'";
            }
            if (!file_exists($destino)) {
                $retorno .= " : A pasta não existe.";
            }
            elseif (!is_writable($destino)) {
                $retorno .= " : A pasta não é editável.";
            }
            elseif (!is_writable($uploadfile)) {
                $retorno .= " : O arquivo não é editável.";
            }

            $nomeArquivo = NULL;
        }
        else {
            if ($single_file === TRUE) {
                $size = !$_FILES[$chave]['size'];
            }
            else {
                $size = !$_FILES[$chave]['size'][$single_file];
            }
            if ($size) {
                /** @noinspection PhpUsageOfSilenceOperatorInspection */
                @unlink($uploadfile); // Apaga o arquivo vazio
                $nomeArquivo = '';
                $retorno = "Arquivo vazio encontrado - use um arquivo válido.";
            }
            else {
                chmod($uploadfile, 0777);
            }
        }

        return ['arquivo' => $nomeArquivo, 'msg' => $retorno];
    }
}


if (!function_exists('uploadImagemBase64')) {
    function uploadImagemBase64($stringBase64 = NULL, $newName = '', $destino = __DIR__ . '/../uploads/') {
        $retorno = array(
            'arquivo' => '',
            'msg'     => ''
        );

        if ($stringBase64) {
            $data = explode(',', $stringBase64);

            // trata tipo (jpeg, png)
            $pos = strpos($data[0], ';');
            $tipo = explode('/', substr($data[0], 0, $pos))[1];

            // trata base64
            $data[1] = base64_decode($data[1]);
            $img_data = imagecreatefromstring($data[1]);

            $uniqer = time();
            $nomeArquivo = $newName . '-' . $uniqer;

            if (!file_exists($destino) && !is_dir($destino)) {
                mkdir($destino, 0777, TRUE);
            }

            if ($img_data) {
                switch ($tipo) {
                    case 'jpeg':
                        $nomeArquivo .= '.jpg';
                        $salvo = imagejpeg($img_data, $destino . $nomeArquivo, 100);
                        break;

                    case 'png':
                        $nomeArquivo .= '.png';
                        imagealphablending($img_data, FALSE);
                        imagesavealpha($img_data, TRUE);
                        $salvo = imagepng($img_data, $destino . $nomeArquivo, 6);
                        break;

                    default:
                        $salvo = FALSE;
                        break;
                }

                if ($salvo) {
                    $retorno['arquivo'] = $nomeArquivo;
                }
                else {
                    $retorno['msg'] = 'Formato de imagem não suportado.';
                }

            }
            else {
                $retorno['msg'] = 'Erro no base64 enviado.';
            }

        }
        else {
            $retorno['msg'] = 'Nenhum arquivo enviado.';
        }

        return $retorno;
    }
}


if (!function_exists('uploadArquivoMultiplo')) {
    /**
     * Realiza upload de arquivo, para campos de upload de um único arquivo
     *
     * @param (string) name do campo de upload
     * @param string $destino
     * @param string $extensoes
     * @param string $inicioNomeArquivo
     *
     * @return array (array(array('arquivo'=>'', 'msg'=> ''))) arquivo terá valor se o upload tiver sucesso, msg terá valor se houver algum erro
     */
    function uploadArquivoMultiplo($key, $destino = "", $extensoes = "", $inicioNomeArquivo = "") {
        $retorno = [];
        if ($_FILES[$key]["name"][0] !== "") {
            foreach ($_FILES[$key]["error"] as $posicao => $valor) {
                if ($valor == 0) {
                    $retorno[] = uploadArquivo($key, $destino, $extensoes, $inicioNomeArquivo, $posicao);
                }
                else {
                    $retorno[] = ["arquivo" => $_FILES[$key][$posicao]["name"], "msg" => "Nao foi possivel fazer o upload"];
                }
            }
        }
        return $retorno;
    }
}

$basePath = $_SERVER["DOCUMENT_ROOT"];
