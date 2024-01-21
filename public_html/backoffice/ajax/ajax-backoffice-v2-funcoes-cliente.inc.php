<?php


function modal_desativa_cliente(Template $Template, $infoCliente): array {
    $html = $Template->insert("Modal/_header",
        ["titulo" => "Desativar Cliente - " . $infoCliente["nome"], "acao" => "apagar-cliente"]
    );
    $html .= $Template->insert("Modal/input-hidden", ["campo" => "cliente", "valor" => $infoCliente["cliente"]]);
    $html .= $Template->insert("Modal/show-text",
        ["conteudo" => "Tem certeza que desativar o Cliente <b>" . $infoCliente["nomeFantasia"] . "</b>?"]
    );
    $html .= $Template->insert("Modal/_footer", [
        "cancelar" => "Cancelar", "confirmar" => "Desativar", "confirmarIcone" => "fa-trash-o"
    ]);
    return ["tipo" => "html", "conteudo" => $html];
}

function modal_edita_cliente(Template $Template, $infoCliente): array {
    $html = $Template->insert("Modal/_header",
        ["titulo" => "Editar cliente", "acao" => "editar-cliente"]
    );
    // Nome
    $html .= $Template->insert("Modal/input-text",
        ["titulo" => "Marca Fantasia", "campo" => "nomeFantasia", "valor" => $infoCliente["nomeFantasia"] ]
    );
    // Responsavel
    $html .= $Template->insert("Modal/input-text",
        ["titulo" => "Responsável", "campo" =>"responsavel", "valor" => $infoCliente["responsavel"] ]
    );
    // e-mail de contato
    $html .= $Template->insert("Modal/input-email",
        ["titulo" => "E-Mail", "campo" =>"email", "valor" => $infoCliente["email"] ]
    );
    // informacoes de contato
    $html .= $Template->insert("Modal/input-textarea",
        ["titulo" => "Informacoes de Contato", "campo" =>"contato", "valor" => $infoCliente["contato"] ]
    );
    // Data de cadastro
    $html .= $Template->insert("Modal/input-date",
        ["titulo" => "Data de Entrada", "campo" =>"dataEntrada", "valor" => $infoCliente["dataEntrada"],"label" => "Data de Entrada" ]
    );
    // Observacoes
    $html .= $Template->insert("Modal/input-textarea",
        ["titulo" => "Observações", "campo" =>"observacao", "valor" => $infoCliente["observacao"]]
    );
    // ID Hidden Cliente
    $html .=$Template->insert("Modal/input-hidden", ["campo" => "cliente", "valor" => $infoCliente["cliente"]]);

    // Botoes da modal
    $html .= $Template->insert("Modal/_footer", [
        "cancelar" => "Cancelar", "confirmar" => "Confirmar"
    ]);

    return ["tipo" => "html", "conteudo" => $html];
}

function modal_exibe_cliente(Template $Template, $infoCliente): array {
    $html = '
<div class="modal-content">
    <button aria-hidden="true" class="close" data-dismiss="modal" type="button">&times;</button>
    <h4 class="modal-title">'.'</h4>
    <div class="row">
        <div class="col-sm-6">
            <dl class="dl-horizontal dl-informacoes">
                <dt>Nome Fantasia</dt>
                <dd>' . $infoCliente["nomeFantasia"] . '</dd>
                <dt>Responsável</dt>
                <dd>' . $infoCliente["responsavel"] . '</dd>
                <dt>Email</dt>
                <dd>' . $infoCliente["email"] . '</dd>
                <dt>Contato</dt>
                <dd>' . nl2br($infoCliente["contato"]) . '</dd>
                <dt>Data de Entrada</dt>
                <dd>' . $infoCliente["dataEntrada"] . '</dd>
                <dt>WHMCS ID</dt>
                <dd>DESATIVADO NO BANCO</dd>
                <dt>Observações</dt>
                <dd>' . nl2br($infoCliente["observacao"]) . '</dd>
                <dt>Logo</dt>
                <dd>[LOGO_CLIENTE]</dd>
            </dl>
        </div>
    </div>
</div>
<div class="modal-footer clearfix">
    <button type="button" class="btn btn-danger btn-flat border-0 pull-left" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
</div>
';
    return ["tipo" => "html", "conteudo" => $html];
}

function modal_adicionar_cliente(Template $Template) : array {

    $html = $Template->insert("Modal/_header",
        ["titulo" => "Cadastrar novo Cliente", "acao" => "adicionar-cliente"]);

    $html .= $Template->insert("Modal/input-text",  ["titulo" => "Nome Fantasia*", "campo" => "nomeFantasia", "placeholder" => "Nome Fantasia"]);
    $html .= $Template->insert("Modal/input-text",  ["titulo" => "Responsavel", "campo" => "responsavel", "placeholder" => "Nome do Responsável"]);
    $html .= $Template->insert("Modal/input-email",  ["titulo" => "Email de Contato*", "campo" => "email", "placeholder" => "exemplo@exemplo.com.br"]);
    $html .= $Template->insert("Modal/input-textarea",  ["titulo" => "Informações de Contato*", "campo" => "contato", "placeholder" => "Outras informações de contato"]);
    $html .= $Template->insert("Modal/input-date",  ["titulo" => "Data de Entrada*", "campo" => "dataEntrada", "label" => "Data de Entrada"]);
    $html .= $Template->insert("Modal/input-textarea",  ["titulo" => "Observações", "campo" => "observacao"]);
    $html .= $Template->insert("Modal/_footer", [
        "cancelar" => "Cancelar", "confirmar" => "Confirmar"
    ]);

    return ["tipo" => "html", "conteudo" => $html];
}
