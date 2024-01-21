<?php
// Registra o autoloader se nao tiver registrado ainda
require_once __DIR__ . "/defines.php";

if (!function_exists("popFlowLoader")) {
    /**
     * Carrega as classes de base do framework Nerdweb
     *
     * @param $fullClass
     */
    function popFlowLoader($fullClass) {
        $popflowClass = __DIR__ . "/Popflow/".$fullClass.".php";
        if (file_exists($popflowClass)) {
            /** @noinspection PhpIncludeInspection */
            require_once $popflowClass;
            return;
        }
        $popflowClass = __DIR__ . "/Popflow/extra/" . $fullClass . ".php";
        if (file_exists($popflowClass)) {
            /** @noinspection PhpIncludeInspection */
            require_once $popflowClass;
            return;
        }
        $exclusion = ["PHPMailer", "Nerdweb", "Intranet", "json"];
        if (in_array($fullClass,$exclusion)) {
            return;
        }
        echo "Revise os includes e chamadas de classe\n<br>";
        echo "Classe desejada: <b>" . $fullClass . "</b>\n<br>";
        exit;
    }
}

// Autoloader de funcoes extras
spl_autoload_register("popFlowLoader", NULL);

if (session_status() == PHP_SESSION_NONE) {
    new Session();
}
