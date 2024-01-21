<?php

function modal_apaga_area(Template $Template, $infoArea): array {
    $html = $Template->insert("Modal/_header",
        ["titulo" => "Apagar área - " . $infoArea["nome"], "acao" => "apagar-area"]
    );
    $html .= $Template->insert("Modal/input-hidden", ["campo" => "area", "valor" => $infoArea["area"]]);
    $html .= $Template->insert("Modal/show-text",
        ["conteudo" => "Tem certeza que deseja apagar a área <b>" . $infoArea["nome"] . "</b>?"]
    );
    $html .= $Template->insert("Modal/_footer", [
        "cancelar" => "Cancelar", "confirmar" => "Apagar", "confirmarIcone" => "fa-trash-o"
    ]);
    return ["tipo" => "html", "conteudo" => $html];
}


/**
 * @param Template $Template
 * @param   $infoArea
 *
 * @return array
 */
function modal_editar_area(Template $Template, $infoArea): array {
// Busca o html da modal e substitui as strings com os dados do registro
    $html = $Template->insert("/Modal/_header",
        ["titulo" => "Editar área - " . $infoArea["nome"], "acao" => "editar-area"]);
    $html .= $Template->insert("Modal/input-hidden", ["campo" => "area", "valor" => $infoArea["area"]]);
    $html .= $Template->insert("Modal/input-text",
        ["titulo" => "Nome da Área*", "campo" => "nome", "valor" => $infoArea['nome']]);
    $html .= $Template->insert("Modal/input-cor",
        ["titulo" => "Cor*", "campo" => "cor", "valor" => $infoArea['cor']]);
    $html .= $Template->insert("Modal/_footer", [
        "cancelar" => "Cancelar", "confirmar" => "Salvar", "confirmarIcone" => "fa-save"
    ]);
    return ["tipo" => "html", "conteudo" => $html];
}

function modal_adicionar_area(Template $Template): array {
    $html = $Template->insert("/Modal/_header",
    ["titulo" => "Adicionar nova área", "acao" => "adicionar-area"]);

    $html .= $Template->insert("Modal/input-text",
        ["titulo" => "Nome da Área*", "campo" => "nome", "valor" => ""]);
    $html .= $Template->insert("Modal/input-cor",
        ["titulo" => "Cor*", "campo" => "cor", "valor" => ""]);

    $html .= $Template->insert("Modal/_footer", [
        "cancelar" => "Cancelar", "confirmar" => "Salvar", "confirmarIcone" => "fa-save"
    ]);
    return ["tipo" => "html", "conteudo" => $html];
}
