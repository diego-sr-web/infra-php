<?php
function modal_editar_usuario($Template, $infoUsuario,$todasAreas,$usuario) : array {

    $areasUsuario = $usuario->getAreasUsuario($infoUsuario['usuarioNerdweb']);
    if (!empty($areasUsuario)) {
        foreach ($areasUsuario as $areasUser) {
            foreach ($todasAreas as $chaveTodasAreas => $todasArea) {
                if ($todasArea['area'] == $areasUser['area']){
                    $todasAreas[$chaveTodasAreas]['isChecked'] = true;
                }
            }
        }
    }

    $html = $Template->insert("Modal/_header",
        ["titulo" => "Editar usuário", "acao" => "editar-usuario"]
    );


    $radiosAdmin = [
        0 => ["valor" => "Não", "checked" => $infoUsuario["administrador"] == 0 ? "checked" : ""],
        1 => ["valor" => "Sim", "checked" => $infoUsuario["administrador"] == 1 ? "checked" : ""]
    ];

    $optionsCheckbox = [
        "campo" => "areas",
        "labelForm" => "Areas",
        "labelCheck" => "nome",
        "value" => "area",
        "indice" => "nome",
        "array" => $todasAreas
    ];

    $html .=$Template->insert("Modal/input-hidden", ["campo" => "usuario", "valor" => $infoUsuario["usuarioNerdweb"]]);
    $html .=$Template->insert("Modal/input-text", ["titulo" => "Nome", "campo" => "nome", "valor" => $infoUsuario["nome"]]);
    $html .=$Template->insert("Modal/input-email", ["titulo" => "E-mail", "campo" => "email", "valor" => $infoUsuario["email"]]);
    $html .= $Template->insert("Modal/input-password",  ["titulo" => "Resetar Senha", "campo" => "senha"]);
    $html .= $Template->insert("Modal/input-multiplos-checkbox",  $optionsCheckbox);
    $html .=$Template->insert("Modal/input-radio", ["titulo" => "Administrador", "campo" => "administrador", "conteudo" => $radiosAdmin]);
    $html .= $Template->insert("Modal/_footer", ["cancelar" => "Cancelar", "confirmar" => "Confirmar", "confirmarIcone" => "material-icons left"]);

    return ["tipo" => "html", "conteudo" => $html];
}

function modal_desativa_usuario(Template $Template, $infoUsuario): array {
    $html = $Template->insert("Modal/_header",
        ["titulo" => "Desativar Usuario - " . $infoUsuario["nome"], "acao" => "apagar-usuario"]
    );
    $html .= $Template->insert("Modal/input-hidden", ["campo" => "usuario", "valor" => $infoUsuario["usuarioNerdweb"]]);
    $html .= $Template->insert("Modal/show-text",
        ["conteudo" => "Tem certeza que desativar o Usuario <b>" . $infoUsuario["nome"] . "</b>?"]
    );
    $html .= $Template->insert("Modal/_footer", [
        "cancelar" => "Cancelar", "confirmar" => "Desativar", "confirmarIcone" => "fa fa-trash-o"
    ]);
    return ["tipo" => "html", "conteudo" => $html];
}

function modal_ver_usuario(Template $Template, $infoUsuario) : array {
    $html = $Template->insert("Modal/_header",
        ["titulo" => $infoUsuario["nome"], "acao" => "lista-usuario"]
    );
    $html .= "<dl class='dl-horizontal dl-informacoes'";
    $status = "Ativo";
    if ($infoUsuario["ativo"] == 0) {
        $status = "Inativo";
    }
    $admin = "Sim";
    if ($infoUsuario["administrador"] == 0) {
        $admin = "Não";
    }
    $listaAreas = "";
    foreach ($areasUsuario as $area) {
        $listaAreas.= $area["nome"]. "<br>";
    }
    $html .= $Template->insert("Modal/show-label-text", ["titulo" => "Id", "conteudo" => $infoUsuario["usuarioNerdweb"]]);
    $html .= $Template->insert("Modal/show-label-text", ["titulo" => "Nome", "conteudo" => $infoUsuario["nome"]]);
    $html .= $Template->insert("Modal/show-label-text", ["titulo" => "E-mail", "conteudo" => $infoUsuario["email"]]);
    $html .= $Template->insert("Modal/show-label-text", ["titulo" => "Status", "conteudo" => $status]);
    $html .= $Template->insert("Modal/show-label-text", ["titulo" => "Administrador", "conteudo" => $admin]);
    $html .= $Template->insert("Modal/show-label-text", ["titulo" => "Áreas do Usuario", "conteudo" => $listaAreas]);
    $html .= "</dl>";
    $html .= $Template->insert("Modal/_footer", [
        "confirmar" => "Fechar Modal"
    ]);
    return ["tipo" => "html", "conteudo" => $html];
}

function modal_adicionar_usuario(Template $Template, $todasAreas) : array {
    $radiosAdmin = [
        0 => ["valor" => "Não", "checked" => ""],
        1 => ["valor" => "Sim", "checked" => ""]
    ];

    $optionsCheckbox = [
        "campo" => "areas",
        "labelForm" => "Areas",
        "labelCheck" => "nome",
        "value" => "area",
        "indice" => "nome",
        "array" => $todasAreas
    ];


    $html = $Template->insert("Modal/_header",
    ["titulo" => "Cadastrar novo usuário", "acao" => "adicionar-usuario"]
    );
    $html .= $Template->insert("Modal/input-text",  ["titulo" => "Nome*", "campo" => "nome", "placeholder" => "Nome do Usuário"]);
    $html .= $Template->insert("Modal/input-text",  ["titulo" => "E-mail*", "campo" => "email", "placeholder" => "E-mail do Usuário"]);
    $html .= $Template->insert("Modal/input-password",  ["titulo" => "Senha *", "campo" => "senha"]);
    $html .= $Template->insert("Modal/input-password",  ["titulo" => "Confirmação de Senha *", "campo" => "senha2"]);
    $html .= $Template->insert("Modal/input-multiplos-checkbox",  $optionsCheckbox);
    $html .= $Template->insert("Modal/input-radio", ["titulo" => "Administrador", "campo" => "administrador", "conteudo" => $radiosAdmin]);


    $html .= $Template->insert("Modal/_footer", ["cancelar" => "Cancelar", "confirmar" => "Confirmar"]);
    return ["tipo" => "html", "conteudo" => $html];
}
