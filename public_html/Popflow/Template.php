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

class Template {
    private $data;
    private $basePath;
    private $extension;
    private $buffer;

    public function __construct($args = []) {
        $this->basePath = $args["path"] ?? __DIR__ . "/../Templates/";
        $this->extension = $args["ext"] ?? ".tpl.php";
        $this->buffer = $args["buffer"] ?? FALSE;
    }

    public function insert($template, $data = [], $customPath = NULL) {
        $template = $this->basePath . $template . $this->extension;
        if (isset($customPath)) {
            $template = $customPath . $template . $this->extension;
        }
        $this->data = $data;
        if (is_dir($template)) {
            echo "ERROR: " .$template . " is a directory";
            die;
        }
        if (!file_exists($template)) {
            echo "ERROR: Template " . $template . " not found";
            die;
        }
        if ($this->buffer) {
            ob_start();
            echo "\n<!-- Loading TEMPLATE '".$template."' -->\n";
            /** @noinspection PhpIncludeInspection */
            require $template ;
            return ob_get_clean(). PHP_EOL;
        }
        echo "\n<!-- Loading TEMPLATE '".$template."' -->\n";
        /** @noinspection PhpIncludeInspection */
        require $template ;

    }
}
