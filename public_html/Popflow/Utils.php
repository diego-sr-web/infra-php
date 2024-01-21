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

final class Utils {

    /** Construtor privado pra forcar a classe a ser usada somente de maneira estatica
     * Utils constructor.
     */
    private function __construct() {
    }

    public static function enviaEmail($NOME, $EMAIL, $Subject, $htmlContent, $nomeContato, $emailContato, $attachments = [], $altBody = 'Visualize esse email com um cliente que possua a 
    capacidade de exibir html') {
        /** @noinspection UntrustedInclusionInspection */
        /** @noinspection PhpIncludeInspection */
        require_once '/home2/extra_software/phpmailer/class.phpmailer.php';
        /** @noinspection UntrustedInclusionInspection */
        /** @noinspection PhpIncludeInspection */
        require_once '/home2/extra_software/phpmailer/class.smtp.php';
        $mail_user = 'AKIA4NFGNLBVCIKWNFI2';
        $mail_password = 'BJSgTM7iXexNLJNWPqGYmc2gRwFUjVeVHOGXDMLZ9Fpr';
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth = TRUE;
        $mail->Host = "email-smtp.us-west-2.amazonaws.com";          // sets the SMTP server, lembrar q subdominio nao tem
        $mail->Port = 587;                         // set the SMTP port
        $mail->Username = $mail_user; // SMTP account username
        $mail->Password = $mail_password;    // SMTP account password
        $mail->SMTPSecure = 'tls';
        $mail->AddReplyTo($emailContato, $nomeContato);
        $mail->SetFrom("infra@nerdweb.com.br", 'Formulario Contato');
        $mail->Subject = $Subject;
        $mail->MsgHTML($htmlContent);
        $mail->AltBody = $altBody;
        $mail->AddAddress($EMAIL, $NOME);
        if ($attachments != "") { // testa se for vazio, se nao for deve obrigatoriamente ser um array
            foreach ($attachments as $attachment) {
                $mail->AddAttachment($attachment);      // attachment array
            }
        }

        if (!$mail->Send()) {
            //echo "Mailer Error: " . $mail->ErrorInfo;
            return FALSE;
        }
        else {
            //echo "Message sent!";
            return TRUE;
        }
    }

    public static function htmlSpecialCodes($html) {
        $acentos = array("á", "Á", "à", "À", "â", "Â", "å", "Å", "ã", "Ã", "ä", "Ä", "æ", "Æ", "ç", "Ç", "é", "É", "è", "È", "ê", "Ê", "ë", "Ë", "í", "Í", "ì", "Ì", "î", "Î", "ï", "Ï", "ñ", "Ñ", "ó", "Ó", "ò", "Ò", "ô", "Ô", "ø", "Ø", "õ", "Õ", "ö", "Ö", "ß", "ú", "Ú", "ù", "Ù", "û", "Û", "ü", "Ü", "ÿ");
        $codigos = array("&aacute;", "&Aacute;", "&agrave;", "&Agrave;", "&acirc;", "&Acirc;", "&aring;", "&Aring;", "&atilde;", "&Atilde;", "&auml;", "&Auml;", "&aelig;", "&AElig;", "&ccedil;", "&Ccedil;", "&eacute;", "&Eacute;", "&egrave;", "&Egrave;", "&ecirc;", "&Ecirc;", "&euml;", "&Euml;", "&iacute;", "&Iacute;", "&igrave;", "&Igrave;", "&icirc;", "&Icirc;", "&iuml;", "&Iuml;", "&ntilde;", "&Ntilde;", "&oacute;", "&Oacute;", "&ograve;", "&Ograve;", "&ocirc;", "&Ocirc;", "&oslash;", "&Oslash;", "&otilde;", "&Otilde;", "&ouml;", "&Ouml;", "&szlig;", "&uacute;", "&Uacute;", "&ugrave;", "&Ugrave;", "&ucirc;", "&Ucirc;", "&uuml;", "&Uuml;", "&yuml;");
        $html = str_replace($acentos, $codigos, $html);
        return $html;
    }


    /**
     * @param $length
     *
     * @return string
     */
    public static function generatePassword($length) {
        $chars = "abcdefghijklmnopqrstuvxwyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        $count = mb_strlen($chars);
        $result = "";
        for ($i = 0; $i < $length; $i++) {
            $index = rand(0, $count - 1);
            $result .= mb_substr($chars, $index, 1);
        }
        return $result;
    }


    /**
     * @param      $array
     * @param      $subkey
     * @param bool $sort_ascending
     *
     * @return array
     */
    public static function sksort($array, $subkey, $sort_ascending = FALSE) {
        $temp_array = [];
        if (count($array)) {
            $temp_array[key($array)] = array_shift($array);
        }

        foreach ($array as $key => $val) {
            $offset = 0;
            $found = FALSE;
            foreach ($temp_array as $tmp_key => $tmp_val) {
                if (!$found and strtolower($val[$subkey]) > strtolower($tmp_val[$subkey])) {
                    $temp_array = array_merge((array)array_slice($temp_array, 0, $offset), [$key => $val], array_slice($temp_array, $offset));
                    $found = TRUE;
                }
                $offset++;
            }
            if (!$found) {
                $temp_array = array_merge($temp_array, [$key => $val]);
            }
        }


        if ($sort_ascending) {
            $array = array_reverse($temp_array);
        }
        else {
            $array = $temp_array;
        }
        return $array;
    }

    /**
     * Ordena um array pela chave passada.
     *
     * Ordena um array de arrays, usando a chave do array interno como parametro pra ordenacao
     *
     * @param      $array
     * @param      $subkey
     * @param bool $sort_ascending
     *
     * @return array
     */
    public static function subKeySort(array $array, $subkey, $sort_ascending = FALSE) {
        return self::sksort($array, $subkey, $sort_ascending);
    }

    /**
     * @param string $conteudo
     *
     * @return string
     */
    public static function insert_href($conteudo) {
        $text_hyperlink = [];
        //preg_match_all("/(http|https)(([[:alnum:]]|\/|\.|\-|\&|\:|\?|\_|\=|\!|\+|\%|\&|\#|\,|\@)*)/i", $conteudo,$text_hyperlink);
        preg_match_all("/(http|https)(([[:alnum:]]|\/|\.|\-|\&|\:|\?|\_|\=|\!|\+|\%|\&|\#|\,|\@)*)/i", $conteudo, $text_hyperlink);
        if (isset($text_hyperlink[0][0])) {
            $hyperlinks = array_unique($text_hyperlink[0]);
            foreach ($hyperlinks as $hyperlink) {
                $href_url = "<a href='" . $hyperlink . "' target='_blank'>" . $hyperlink . "</a>";

                $conteudo = str_replace($hyperlink, $href_url, $conteudo);
            }
        }
        return $conteudo;
    }


    /**
     * Converte um número de segundos em horas, minutos e segundos
     * e retorna um array contendo esses valores
     *
     * @param integer $segundosInput número de segundos
     *
     * @return array
     */
    public static function secondsToTime($segundosInput) {
        $segundosPorMinuto = 60;
        $segundosPorHora = 60 * $segundosPorMinuto;
        $segundosPorDia = 24 * $segundosPorHora;

        // extract days
        $dias = floor($segundosInput / $segundosPorDia);

        // extract hours
        $hourSeconds = $segundosInput % $segundosPorDia;
        $horas = floor($hourSeconds / $segundosPorHora);

        // extract minutes
        $minuteSeconds = $hourSeconds % $segundosPorHora;
        $minutos = floor($minuteSeconds / $segundosPorMinuto);

        // extract the remaining seconds
        $remainingSeconds = $minuteSeconds % $segundosPorMinuto;
        $segundos = ceil($remainingSeconds);

        // return the final array
        $obj = ['d' => str_pad((int)$dias, 2, 0, STR_PAD_LEFT), 'h' => str_pad((int)$horas, 2, 0, STR_PAD_LEFT), 'm' => str_pad((int)$minutos, 2, 0, STR_PAD_LEFT), 's' => str_pad((int)$segundos, 2, 0, STR_PAD_LEFT),];
        return $obj;
    }

    /**
     * Corta uma string em determinado tamanho
     *
     * Corta uma string $texto com o tamanho $limite, caso a string seja maior que $limite concatena o resultado com $sufixo e retorna
     * a string resultante, ou retorna a propria string $texto caso esta seja menor que $limite
     *
     * @param int    $limite
     * @param string $texto
     * @param string $sufixo
     *
     * @return string
     */
    public static function cortaTexto($limite, $texto, $sufixo = "...") {
        $tamTotal = strlen($texto);
        $tamSufixo = strlen($sufixo);
        $texto = trim(strip_tags($texto));
        if ($tamTotal > $limite) {
            $tmpStr = substr($texto, 0, $limite - $tamSufixo);
            return $tmpStr . $sufixo;
        }
        return $texto;
    }

    /**
     * Limpa os caracteres especiais de uma string
     *
     * Limpa os caracteres especiais de uma string removendo acentos, e outros caracteres como ' " / \ |
     * logo apos removendo espacos duplos e substituindo por -
     *
     * @param string $str
     *
     * @return string
     */
    public static function limpa_nome($str) {
        $str = self::remove_acento($str);
        $str = self::remove_caracteres_especiais($str);
        $str = trim($str);
        $str = self::limpa_espacos($str);
        $str = strtolower($str);
        return $str;
    }

    /**
     * Substitui acentuacao de uma string
     *
     * Substitui acentuacao de uma string trocando os caracteres acentuados por caracteres nao acentuados
     * ie: À => A
     *
     * @param string $str
     *
     * @return string
     */
    public static function remove_acento($str) {
        $a = ['À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ', 'ª', '.'];
        $b = ['A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o', '', '-]'];
        return str_replace($a, $b, $str);
    }

    /**
     * Remove caracteres especiais
     *
     * Remove caracteres especiais de acentuacao
     * ie: "~" => ""
     *
     * @param string $str
     *
     * @return string
     */
    public static function remove_caracteres_especiais($str) {
        return str_replace(["\\", "/", ",", "'", "–", "‒", "–", "—", "_", "\"", "`", "~", ";", ":", "|", "[", "]", "{", "}", "(", ")", "*", "&", "^", "%", '$', "@", "!", "?", "<", ">", "ª", "º", "°", "#", "®", "“", "”"], "", $str);
    }

    /**
     * Remove espacos duplos
     *
     * Remove espacos duplos e substitui espacos simples por hifens
     *
     * @param string $str
     *
     * @return string
     */
    public static function limpa_espacos($str) {
        return str_replace(["\xc2\xa0", "  ", " "], [" ", " ", "-"], $str);
    }

    /**
     * Transforma uma string (normalmente títulos) em uma string que pode ser usada para URLs amigáveis
     * substituindo acentos, espaços, etc
     *
     * @param (string) texto a ser transformado
     *
     * @return mixed|string (string) string tranformada
     */
    public static function stringToURL($str) {
        $str = strtolower(utf8_decode($str));
        $str = strtr($str, utf8_decode('àáâãäåæçèéêëìíîïñòóôõöøùúûýýÿ'), 'aaaaaaaceeeeiiiinoooooouuuyyy');
        $str = preg_replace("/([^a-z0-9])/", '-', utf8_encode($str));

        $i = 1;
        while ($i > 0) {
            $str = str_replace('--', '-', $str, $i);
        }

        if (substr($str, -1) === '-') {
            $str = substr($str, 0, -1);
        }

        return $str;
    }


    /**
     * Retorna o tempo passado a partir de uma data/hora até a hora atual
     *
     * @param (string) data a ser calculada (ideal no formato Y-m-d H:i:s)
     *
     * @return string (string) tempo passado
     */
    public static function printDate($data) {
        return date('d/m/Y H:i', strtotime($data));
    }


    /**
     * @return bool
     */
    public static function is_cli() {
        if (defined('STDIN')) {
            return TRUE;
        }
        if (empty($_SERVER['REMOTE_ADDR']) and !isset($_SERVER['HTTP_USER_AGENT']) and count($_SERVER['argv']) > 0) {
            return TRUE;
        }
        return FALSE;
    }

    public static function redirect($destino) {
        if (!headers_sent()) {
            header("Location: " . $destino, TRUE, 302);
            exit;
        }
        echo '<script language="javascript">window.location.href="' . $destino . '";</script>';
        exit;
    }

    /**
     * Combina dois arrays com chave => valor, quebra a execucao se for passado arrays de tamanhos diferentes
     * @param $keys
     * @param $values
     *
     * @return array
     */
    public static function combineOrDie($keys, $values) {
        $arrayCamposValores = array_combine($keys, $values);
        if ($arrayCamposValores === FALSE ) {
            die("Foi Passado um numero errado de campos/valores");
        }
        return $arrayCamposValores;
    }

    public static function strtotime($time, $now = NULL) {
        $now = $now ?? time();

        if (Utils::checkIsNumericDate($time)) {
            return Utils::strotimeNumericDate($time);
        }

        $mes_pt = ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro", "/", "\\"];
        $mes_en = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December", " ", " "];
        return strtotime(str_ireplace($mes_pt,$mes_en,$time));
    }

    public static function checkIsNumericDate($date){
        $dateDescriptive = explode("/", $date);
        return is_numeric($dateDescriptive[1]);
    }

    public static function strotimeNumericDate($date){
        $timestamp = strtotime($date);
        if ($timestamp === FALSE) {
            $timestamp = strtotime(str_replace('/', '-', $date));
        }
        return $timestamp;
    }



}
