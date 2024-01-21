<?php

if ($usuario->checkSession() == FALSE) {
    $_SESSION["redirect_url"] = $_SERVER["REQUEST_URI"];
    Utils::redirect("/backoffice/");
}

$dadosUsuario = $usuario->getUserDataWithId($_SESSION['adm_usuario']);
$areasUsuario = $usuario->getAreasUsuario($_SESSION['adm_usuario']);
foreach ($areasUsuario as $indice => $area) {
    $areasUsuario[$indice]["primaria"] = FALSE;
    if ($dadosUsuario["area"] === $area["area"]) {
        $areasUsuario[$indice]["primaria"] = TRUE;
    }
}
$areasId = [];

foreach ($areasUsuario as $area) {
    $areasId[] = $area['area'];
}

$isUserAdmin = FALSE;
if (isset($dadosUsuario['administrador']) && $dadosUsuario['administrador'] == 1) {
    $isUserAdmin = TRUE;
}

