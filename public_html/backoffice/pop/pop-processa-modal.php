<?php
require_once __DIR__ . "/../../autoloader.php";


/**
 * @param Elemento   $elemento
 * @param int        $eid
 * @param array      $dados
 * @param string     $nomeCampoElemento
 * @param string|int $valor
 */
function updateElementSaveBackup($elemento, $eid, $dados, $nomeCampoElemento, $valor) {
    $proximo = $elemento->proximo_campo_extra("xt_" . $nomeCampoElemento, $dados["campos"]);
    $proximo = "xt_" . $nomeCampoElemento . "_" . $proximo;
    // Cagando com backup dos campos, arrumar o bug assim que possivel
    //if (@$elemento->updateExtraField($eid, $proximo, $valor)) {
    $elemento->updateElement($eid, [$nomeCampoElemento], [$valor]);
    //}
}

$nova_database = new Database();
$novo_elemento = new Elemento($nova_database);
$novo_projeto = new Projeto($nova_database);
$POPChat = new Chat($nova_database);
$POPAlerta = new Alerta($nova_database);

// Habilita ou nao debug
//$debug = TRUE;

$tipo_elemento_post_facebook = 38;
$tipo_elemento_pagina_website = 72;
$tipo_elemento_blog_post = 107;
$tipo_elemento_social_post = 109;
$tipo_elemento_pecas_midia = 126;
// Pedido V1=58, Pedido V3=156
$tipo_elemento_pedido = 82;
$tipo_elemento_horas = 111;
$etapa_inicial_pedido = 68;
// Pedido V1 = 59, Pedido V2 = 82, Pedido V3 = 105 ( v3 nao esta sendo usado )
$array_tipo_pedido = [59, 82, 105];

$projeto_pedido = 13;

// alterar pro valor correto em producao
$projeto_horas = 3112;

$base_url = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["SERVER_NAME"] . "/backoffice/pop/uploads/projeto-facebook/";
//Gera url de retorno
$url_retorno = $_SERVER["REQUEST_URI"];

// Testa se foi feito um post
if (isset($_POST["form"])) {

    if (isset($debug)) {
        /** @noinspection ForgottenDebugOutputInspection */
        var_dump($_POST);
        if (isset($_FILES)) {
            /** @noinspection ForgottenDebugOutputInspection */
            var_dump($_FILES);
        }
    }

    // Manipulacao de elemento, no caso de campos extras, atualiza o campo referenciado e adiciona um novo campo ao fim do elemento
    $dados_elemento = [];
    $elementId = 0;
    if (isset($_POST["elemento"])) {
        $elementId = $_POST["elemento"];
        $dados_elemento = $novo_elemento->getElementById($elementId);
        if (isset($debug)) {
            /** @noinspection ForgottenDebugOutputInspection */
            var_dump($dados_elemento);
        }
        if ($dados_elemento["elementoTipo"] != $tipo_elemento_post_facebook) {
            if ($_POST["form"] === "adicionar-texto") {
                if ($_POST["texto"] !== "") {
                    updateElementSaveBackup($novo_elemento, $elementId, $dados_elemento, "Texto", $_POST["texto"]);
                    $_SESSION["msg_sucesso"] = "Texto Atualizado";
                }
            }
        }
        if ($dados_elemento["elementoTipo"] == $tipo_elemento_post_facebook) {
            // Adicionar imagem ao campos Extras do elemento
            if ($_POST["form"] === "adicionar-imagem") {
                // se realmente tiver sido feito upload de foto
                if (isset($_FILES['imagem']["name"], $_FILES['imagem']["error"], $_FILES['imagem']["type"])) {
                    if ($_FILES['imagem']["error"] === 0 && strpos($_FILES['imagem']["type"], "image") !== FALSE) {
                        $base_url = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["SERVER_NAME"] . "/backoffice/pop/uploads/projeto-facebook/";
                        $uploadFolder = $_SERVER["DOCUMENT_ROOT"] . '/backoffice/pop/uploads/projeto-facebook/';
                        $destino = $uploadFolder . $dados_elemento["projeto"] . '/';

                        // Cria o diretorio caso ele nao exista
                        if (!file_exists($destino)) {
                            /** @noinspection MkdirRaceConditionInspection */
                            mkdir($destino, 0777, TRUE);
                        }
                        // aceita soh imagens
                        $extensoes = 'png,jpg,jpeg,gif';

                        $arquivo = uploadArquivo('imagem', $destino, $extensoes, 'post');
                        //var_dump($arquivo);

                        $link = $base_url . $dados_elemento["projeto"] . '/' . $arquivo["arquivo"];

                        $proxima = $novo_elemento->proximo_campo_extra("xt_image", $dados_elemento["campos"]);
                        $proxima = "xt_image_" . $proxima;
                        if ($novo_elemento->updateExtraField($elementId, $proxima, $link)) {
                            $novo_elemento->updateElement($elementId, ["image"], [$link]);
                            $_SESSION["msg_sucesso"] = "Arquivo Enviado";
                        }
                        $texto = "Imagem Adicionada";
                        $POPChat->addProjectChat($dados_elemento["projeto"], $_SESSION['adm_usuario'], $texto, $dados_elemento["elemento"], 1);
                    }
                }
                else {
                    $_SESSION["msg_erro"] = "Escolha um arquivo de imagem";
                }
            }

            // Adicionar texto aos campos Extras do elemento
            if ($_POST["form"] === "adicionar-texto") {
                if ($_POST["texto"] == "") {
                    $_POST["texto"] = " ";
                }

                if ($_POST["texto"] !== "") {
                    updateElementSaveBackup($novo_elemento, $elementId, $dados_elemento, "Texto", $_POST["texto"]);
                    $_SESSION["msg_sucesso"] = "Texto Atualizado";
                }

                if ($_POST["textoImagem"] == "") {
                    $_POST["textoImagem"] = " ";
                }
                if ($_POST["textoImagem"] !== "") {
                    updateElementSaveBackup($novo_elemento, $elementId, $dados_elemento, "textoImagem", $_POST["textoImagem"]);
                    $_SESSION["msg_sucesso"] = "Texto Atualizado";
                }

                if (isset($_FILES["arquivos"])) {
                    $base_url = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["SERVER_NAME"] . "/backoffice/pop/uploads/projeto-facebook/";
                    $uploadFolder = $_SERVER["DOCUMENT_ROOT"] . '/backoffice/pop/uploads/projeto-facebook/';
                    $destino = $uploadFolder . $dados_elemento["projeto"] . '/';
                    // Cria o diretorio caso ele nao exista
                    if (!file_exists($destino)) {
                        /** @noinspection MkdirRaceConditionInspection */
                        mkdir($destino, 0777, TRUE);
                    }
                    // aceita soh imagens
                    $extensoes = 'png,jpg,jpeg,gif,docx,xlsx,xls,doc,csv,pdf,txt,psd,zip,rar,html,css';
                    $retorno = uploadArquivoMultiplo("arquivos", $destino, $extensoes, "extra-" . $elementId);
                    //var_dump($retorno);
                    foreach ($retorno as $arquivo) {
                        if ($arquivo["arquivo"] !== "") {
                            $base_url = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["SERVER_NAME"] . "/backoffice/pop/uploads/projeto-facebook/";
                            $link = $base_url . $dados_elemento["projeto"] . '/' . $arquivo["arquivo"];
                            $proxima_referencia = $novo_elemento->proximo_campo_extra("xt_referencia", $dados_elemento["campos"]);
                            $novo_elemento->updateExtraField($elementId, $proxima_referencia, $link);
                        }
                    }
                    $texto = "Referencia adicionada";
                    $POPChat->addProjectChat($dados_elemento["projeto"], $_SESSION['adm_usuario'], $texto, $dados_elemento["elemento"], 1);
                }

                if ($_POST["observacao"] == "") {
                    $_POST["observacao"] = " ";
                }
                if ($_POST["observacao"] !== "") {
                    updateElementSaveBackup($novo_elemento, $elementId, $dados_elemento, "Observacoes", $_POST["observacao"]);

                }

                $texto = "Texto Adicionado";
                $POPChat->addProjectChat($dados_elemento["projeto"], $_SESSION['adm_usuario'], $texto, $dados_elemento["elemento"], 1);
            }
            //exit;
        }
    }

    if ($_POST["form"] === "adicionar-briefing") {
        if ($_POST["texto"] !== "") {
            $novo_elemento->updateElement($elementId, ["briefing"], [$_POST["texto"]]);
        }

        $texto = "Briefing adicionado";
        $POPChat->addProjectChat($dados_elemento["projeto"], $_SESSION['adm_usuario'], $texto, $dados_elemento["elemento"], 1);
        $POPAlerta->criaAlerta('Alerta Importante: Briefing alterado', 'O briefing do projeto foi alterado em ' . date('d/m/Y H:i:s'), 'critico', $dados_elemento['projeto']);
    }

    if ($_POST["form"] === "adicionar-briefing-projeto") {
        if ($_POST["briefing"] !== "") {
            $novo_projeto->updateExtraField($_POST["projeto"], "briefing", $_POST["briefing"]);
            $POPAlerta->criaAlerta('Alerta Importante: Briefing alterado', 'O briefing do projeto foi alterado em ' . date('d/m/Y H:i:s'), 'critico', $_POST["projeto"]);
        }
    }


    if ($_POST["form"] === "adicionar-briefing-mail-mkt") {
        $dados_elemento = $novo_elemento->getElementById($elementId);
        $campos = $dados_elemento["campos"];
        $updates = [
            "briefing"       => $_POST["texto"] ?? "",
            "geraHtml"       => $_POST["geraHtml"] ?? NULL,
            "enviarInbound"  => $_POST["enviarInbound"] ?? NULL,
            "dataPublicacao" => $_POST["dataPublicacao"] ?? "",
            "textoApoio"     => $_POST["textoApoio"] ?? ""

        ];
        foreach ($updates as $key => $value) {
            if ($value !== "" && $value != $campos[$key]) {
                updateElementSaveBackup($novo_elemento, $elementId, $dados_elemento, $key, $value);
            }
        }

        $texto = "Briefing adicionado";
        $POPChat->addProjectChat($dados_elemento["projeto"], $_SESSION['adm_usuario'], $texto, $dados_elemento["elemento"], 1);
        $POPAlerta->criaAlerta('Alerta Importante: Briefing alterado', 'O briefing do projeto foi alterado em ' . date('d/m/Y H:i:s'), 'critico', $dados_elemento['projeto']);
    }

    if ($_POST["form"] === "editar-projeto") {
        $projId = $_POST["projeto"];
        if (isset($_POST["nome"], $_POST["dataEntrada"], $_POST["prazo"]) && $_POST["nome"] !== "" && $_POST["dataEntrada"] !== "") {
            $nome = $_POST["nome"];
            $entrada = $_POST["dataEntrada"];
            $prazo = $_POST["prazo"];
            $novo_projeto->updateProject($_POST["projeto"], ["nome", "dataEntrada", "prazo"], [$nome, $entrada, $prazo]);
            $_SESSION["msg_sucesso"] = "Projeto Editado com sucesso";
        }
        else {
            $_SESSION["msg_erro"] = "Preencha todos os campos";
        }
    }


    if ($_POST["form"] === "adicionar-wireframe") {
        if (isset($_FILES['imagem']["name"], $_FILES['imagem']["error"], $_FILES['imagem']["type"])) {
            if ($_FILES['imagem']["error"] === 0 && strpos($_FILES['imagem']["type"], "image") !== FALSE) {
                $base_url = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["SERVER_NAME"] . "/backoffice/pop/uploads/projeto-facebook/";
                $uploadFolder = $_SERVER["DOCUMENT_ROOT"] . '/backoffice/pop/uploads/projeto-facebook/';
                $destino = $uploadFolder . $dados_elemento["projeto"] . '/';

                // Cria o diretorio caso ele nao exista
                if (!file_exists($destino)) {
                    /** @noinspection MkdirRaceConditionInspection */
                    mkdir($destino, 0777, TRUE);
                }
                // aceita soh imagens
                $extensoes = 'png,jpg,jpeg,gif';

                $arquivo = uploadArquivo('imagem', $destino, $extensoes, 'post');

                $link = $base_url . $dados_elemento["projeto"] . '/' . $arquivo["arquivo"];

                $proxima = $novo_elemento->proximo_campo_extra("xt_wireframe", $dados_elemento["campos"]);
                $proxima = "xt_wireframe_" . $proxima;
                if ($novo_elemento->updateExtraField($elementId, $proxima, $link)) {
                    $novo_elemento->updateElement($elementId, ["wireframe"], [$link]);
                    $_SESSION["msg_sucesso"] = "Arquivo Enviado";
                }
                $texto = "Wireframe Adicionado";
                $POPChat->addProjectChat($dados_elemento["projeto"], $_SESSION['adm_usuario'], $texto, $dados_elemento["elemento"], 1);
            }
        }
        else {
            $_SESSION["msg_erro"] = "Escolha um arquivo de imagem";
        }
    }

    if ($_POST["form"] === "adicionar-layout") {
        if (isset($_FILES['imagem']["name"], $_FILES['imagem']["error"], $_FILES['imagem']["type"])) {
            if ($_FILES['imagem']["error"] === 0 && strpos($_FILES['imagem']["type"], "image") !== FALSE) {
                $base_url = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["SERVER_NAME"] . "/backoffice/pop/uploads/projeto-facebook/";
                $uploadFolder = $_SERVER["DOCUMENT_ROOT"] . '/backoffice/pop/uploads/projeto-facebook/';
                $destino = $uploadFolder . $dados_elemento["projeto"] . '/';

                // Cria o diretorio caso ele nao exista
                if (!file_exists($destino)) {
                    /** @noinspection MkdirRaceConditionInspection */
                    mkdir($destino, 0777, TRUE);
                }
                // aceita soh imagens
                $extensoes = 'png,jpg,jpeg,gif';

                $arquivo = uploadArquivo('imagem', $destino, $extensoes, 'post');

                $link = $base_url . $dados_elemento["projeto"] . '/' . $arquivo["arquivo"];

                $proxima = $novo_elemento->proximo_campo_extra("xt_layout", $dados_elemento["campos"]);
                $proxima = "xt_layout_" . $proxima;
                if ($novo_elemento->updateExtraField($elementId, $proxima, $link)) {
                    $novo_elemento->updateElement($elementId, ["layout"], [$link]);
                    $_SESSION["msg_sucesso"] = "Arquivo Enviado";
                }
                $texto = "Layout Adicionado ";
                $POPChat->addProjectChat($dados_elemento["projeto"], $_SESSION['adm_usuario'], $texto, $dados_elemento["elemento"], 1);
            }
        }
        else {
            $_SESSION["msg_erro"] = "Escolha um arquivo de imagem";
        }
    }

    if ($_POST["form"] === "adicionar-url") {
        if ($_POST["url"] !== "") {
            $novo_elemento->updateElement($elementId, ["link"], [$_POST["url"]]);
            $texto = "Url Adicionada - " . $_POST["url"];
            $POPChat->addProjectChat($dados_elemento["projeto"], $_SESSION['adm_usuario'], $texto, $dados_elemento["elemento"], 1);
        }
    }

    if ($_POST["form"] === "adicionar-listagem-emails") {
        if ($_POST["emails"] !== "") {
            $novo_elemento->updateElement($elementId, ["emails"], [$_POST["emails"]]);
            $texto = "Listagem de Emails Adicionada";
            $POPChat->addProjectChat($dados_elemento["projeto"], $_SESSION['adm_usuario'], $texto, $dados_elemento["elemento"], 1);
        }
    }


    if ($_POST["form"] === "adicionar-post" && isset($_POST["projeto"])) {
        $projectId = $_POST["projeto"];
        $tipoElemento = $tipo_elemento_post_facebook;
        $statusElemento = 1;
        $criacaoElemento = date('Y-m-d H:i:s');
        $atualizacaoElemento = date('Y-m-d H:i:s');

        $lista_Areas = $novo_projeto->objElemento->getElementTipoArea($tipoElemento);
        $aid = NULL;
        foreach ($lista_Areas as $area) {
            $aid = $area["area"];
        }
        $prioridade = 102;
        $n = count($_POST["dataPublicacao"]);
        /** @noinspection ForeachInvariantsInspection */
        for ($i = 0; $i < $n; $i++) {
            $criacaoElemento = date('Y-m-d H:i:s');
            $elementId = $novo_projeto->objElemento->insertElement(['dataCriacao', 'dataAtualizacao', 'elementoTipo', 'elementoStatus', 'projeto', 'prioridade', 'area'], [$criacaoElemento, $atualizacaoElemento, $tipoElemento, $statusElemento, $projectId, $prioridade, $aid]);
            $dados_elemento = $novo_elemento->getElementById($elementId);
            if (isset($_POST["dataPublicacao"][$i]) && $_POST["dataPublicacao"][$i] !== "") {
                $novo_elemento->updateElement($elementId, ["Dia"], [$_POST["dataPublicacao"][$i]]);
            }

            if (isset($_POST["categoria"][$i]) && $_POST["categoria"][$i] !== "") {
                $novo_elemento->updateElement($elementId, ["Categoria"], [$_POST["categoria"][$i]]);
            }
            $texto = "Post adicionado - " . $_POST["dataPublicacao"][$i];
            $POPChat->addProjectChat($dados_elemento["projeto"], $_SESSION['adm_usuario'], $texto, $dados_elemento["elemento"], 1);
        }
        $_SESSION["msg_sucesso"] = "Post(s) Criado Com Sucesso";
    }


    if ($_POST["form"] === "adicionar-material-extra" && isset($_POST["elemento"])) {
        $elementId = $_POST["elemento"];

        if (isset($_FILES["arquivos"])) {
            $base_url = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["SERVER_NAME"] . "/backoffice/pop/uploads/projeto-facebook/";
            $uploadFolder = $_SERVER["DOCUMENT_ROOT"] . '/backoffice/pop/uploads/projeto-facebook/';
            $destino = $uploadFolder . $dados_elemento["projeto"] . '/';

            // Cria o diretorio caso ele nao exista
            if (!file_exists($destino)) {
                /** @noinspection MkdirRaceConditionInspection */
                mkdir($destino, 0777, TRUE);
            }
            // aceita soh imagens
            $extensoes = 'png,jpg,jpeg,gif';
            $retorno = uploadArquivoMultiplo("arquivos", $destino, $extensoes, "extra-" . $elementId);
            foreach ($retorno as $arquivo) {
                if ($arquivo["arquivo"] !== "") {
                    $base_url = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["SERVER_NAME"] . "/backoffice/pop/uploads/projeto-facebook/";
                    $link = $base_url . $dados_elemento["projeto"] . '/' . $arquivo["arquivo"];
                    $proxima_referencia = $novo_elemento->proximo_campo_extra("xt_extra", $dados_elemento["campos"]);
                    $novo_elemento->updateExtraField($elementId, $proxima_referencia, $link);
                }
            }
            $texto = "Material Extra Adicionado";
            $POPChat->addProjectChat($dados_elemento["projeto"], $_SESSION['adm_usuario'], $texto, $dados_elemento["elemento"], 1);
        }

        $_SESSION["msg_sucesso"] = "Material Extra adicionado Com Sucesso";
    }

    if ($_POST["form"] === "adicionar-referencias" && isset($_POST["elemento"])) {
        $elementId = $_POST["elemento"];
        if (isset($_FILES["arquivos"])) {
            $base_url = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["SERVER_NAME"] . "/backoffice/pop/uploads/projeto-facebook/";
            $uploadFolder = $_SERVER["DOCUMENT_ROOT"] . '/backoffice/pop/uploads/projeto-facebook/';
            $destino = $uploadFolder . $dados_elemento["projeto"] . '/';
            // Cria o diretorio caso ele nao exista
            if (!file_exists($destino)) {
                /** @noinspection MkdirRaceConditionInspection */
                mkdir($destino, 0777, TRUE);
            }
            // aceita soh imagens
            $extensoes = 'png,jpg,jpeg,gif,docx,xlsx,xls,doc,csv,pdf,txt,psd,zip,rar,html,css';
            $retorno = uploadArquivoMultiplo("arquivos", $destino, $extensoes, "extra-" . $elementId);
            foreach ($retorno as $arquivo) {
                if ($arquivo["arquivo"] !== "") {
                    $base_url = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["SERVER_NAME"] . "/backoffice/pop/uploads/projeto-facebook/";
                    $link = $base_url . $dados_elemento["projeto"] . '/' . $arquivo["arquivo"];
                    $proxima_referencia = $novo_elemento->proximo_campo_extra("xt_referencia", $dados_elemento["campos"]);
                    $novo_elemento->updateExtraField($elementId, "xt_referencia_" . $proxima_referencia, $link);
                }
            }
            $texto = "Referencia adicionada";
            $POPChat->addProjectChat($dados_elemento["projeto"], $_SESSION['adm_usuario'], $texto, $dados_elemento["elemento"], 1);
        }

        $_SESSION["msg_sucesso"] = "Referencia(s) adicionada(s) Com Sucesso";
    }


    if ($_POST["form"] === "adicionar-identidade-visual" && isset($_POST["elemento"])) {
        if ($_POST["url-google"] !== "") {
            $url_id_visual = $_POST["url-google"];
            if (stripos($_POST["url-google"], "http") === FALSE) {
                $url_id_visual = "https://" . $_POST["url-google"];
            }

            $novo_elemento->updateElement($elementId, ["arquivos"], [$url_id_visual]);
        }
        $texto = "Identidade Visual Adicionada - " . "<a href='" . $url_id_visual . "' target=_blank>" . $url_id_visual . "</a>";
        $POPChat->addProjectChat($dados_elemento["projeto"], $_SESSION['adm_usuario'], $texto, $dados_elemento["elemento"], 1);
        $_SESSION["msg_sucesso"] = "Identidade Visual adicionada Com Sucesso";
    }

    if ($_POST["form"] === "adicionar-arquivos-recortados" && isset($_POST["elemento"])) {
        if ($_POST["url-google"] !== "") {
            $url_arquivos_recortados = $_POST["url-google"];
            if (stripos($_POST["url-google"], "http") === FALSE) {
                $url_arquivos_recortados = "https://" . $_POST["url-google"];
            }
            $texto = "Arquivos Recortados Adicionados - " . "<a href='" . $url_arquivos_recortados . "' target=_blank>" . $url_arquivos_recortados . "</a>";
            $POPChat->addProjectChat($dados_elemento["projeto"], $_SESSION['adm_usuario'], $texto, $dados_elemento["elemento"], 1);
            $novo_elemento->updateElement($elementId, ["link"], [$url_arquivos_recortados]);
        }

        $_SESSION["msg_sucesso"] = "Arquivos Recortados adicionados com sucesso";
    }

    if ($_POST["form"] === "adicionar-url-gdrive" && isset($_POST["elemento"])) {
        if ($_POST["url-google"] !== "") {
            $linkDrive = $_POST["url-google"];
            if (stripos($_POST["url-google"], "http") === FALSE) {
                $linkDrive = "https://" . $_POST["url-google"];
            }
            $texto = "Link para o Google Drive - " . "<a href='" . $linkDrive . "' target=_blank>" . $linkDrive . "</a>";
            $POPChat->addProjectChat($dados_elemento["projeto"], $_SESSION['adm_usuario'], $texto, $dados_elemento["elemento"], 1);
            $novo_elemento->updateElement($elementId, ["gdrive"], [$linkDrive]);
        }

        $_SESSION["msg_sucesso"] = "Link para o Google Drive adicionado com sucesso";
    }

    if ($_POST["form"] === "adicionar-url-html" && isset($_POST["elemento"])) {
        if ($_POST["url"] !== "") {
            $link = $_POST["url"];
            if (stripos($_POST["url"], "http") === FALSE) {
                $link = "https://" . $_POST["url"];
            }
            $texto = "Link  para o HTML - " . "<a href='" . $link . "' target=_blank>" . $link . "</a>";
            $POPChat->addProjectChat($dados_elemento["projeto"], $_SESSION['adm_usuario'], $texto, $dados_elemento["elemento"], 1);
            $novo_elemento->updateElement($elementId, ["url"], [$link]);
        }

        $_SESSION["msg_sucesso"] = "Link para o HTML adicionado com sucesso";
    }

    if ($_POST["form"] === "adicionar-url-teste" && isset($_POST["elemento"])) {
        if ($_POST["url-nerdpress"] !== "") {
            $url_teste = $_POST["url-nerdpress"];
            if (stripos($_POST["url-nerdpress"], "http") === FALSE) {
                $url_teste = "http://" . $_POST["url-nerdpress"];
            }
            $texto = "Url de testes adicionado - " . "<a href='" . $url_teste . "' target=_blank>" . $url_teste . "</a>";
            $POPChat->addProjectChat($dados_elemento["projeto"], $_SESSION['adm_usuario'], $texto, $dados_elemento["elemento"], 1);
            $novo_elemento->updateElement($elementId, ["link_2"], [$url_teste]);
        }

        $_SESSION["msg_sucesso"] = "Arquivos Recortados adicionados com sucesso";
    }

    if ($_POST["form"] === "adicionar-pagina") {
        $total = count($_POST["nome"]);
        /** @noinspection ForeachInvariantsInspection */
        for ($i = 0; $i < $total; $i++) {
            $projectId = $_POST["projeto"];
            $tipoElemento = $tipo_elemento_pagina_website;
            $statusElemento = 1;
            $criacaoElemento = date('Y-m-d H:i:s');
            $atualizacaoElemento = date('Y-m-d H:i:s');
            $lista_Areas = $novo_projeto->objElemento->getElementTipoArea($tipoElemento);
            $aid = NULL;
            foreach ($lista_Areas as $area) {
                $aid = $area["area"];
            }
            $prioridade = 102;
            $elementId = $novo_projeto->objElemento->insertElement(['dataCriacao', 'dataAtualizacao', 'elementoTipo', 'elementoStatus', 'projeto', 'prioridade', 'area'], [$criacaoElemento, $atualizacaoElemento, $tipoElemento, $statusElemento, $projectId, $prioridade, $aid]);
            $dados_elemento = $novo_elemento->getElementById($elementId);

            if (isset($_POST["nome"][$i]) && $_POST["nome"][$i] !== "") {
                updateElementSaveBackup($novo_elemento, $elementId, $dados_elemento, "Nome", $_POST["nome"][$i]);
            }

            //$texto = "Pagina Adicionada - " . "{Nome}";
            $texto = "Pagina Adicionada";
            $POPChat->addProjectChat($dados_elemento["projeto"], $_SESSION['adm_usuario'], $texto, $dados_elemento["elemento"]);
        }
    }

    if ($_POST["form"] == "adicionar-post-blog") {
        $total = count($_POST["dataPublicacao"]);
        /** @noinspection ForeachInvariantsInspection */
        for ($i = 0; $i < $total; $i++) {
            $projectId = $_POST["projeto"];
            $tipoElemento = $tipo_elemento_blog_post;
            $statusElemento = 1;
            $criacaoElemento = date('Y-m-d H:i:s');
            $atualizacaoElemento = date('Y-m-d H:i:s');
            $lista_Areas = $novo_projeto->objElemento->getElementTipoArea($tipoElemento);
            $aid = NULL;
            foreach ($lista_Areas as $area) {
                $aid = $area["area"];
            }
            $prioridade = 102;
            $elementId = $novo_projeto->objElemento->insertElement(['dataCriacao', 'dataAtualizacao', 'elementoTipo', 'elementoStatus', 'projeto', 'prioridade', 'area'], [$criacaoElemento, $atualizacaoElemento, $tipoElemento, $statusElemento, $projectId, $prioridade, $aid]);
            $dados_elemento = $novo_elemento->getElementById($elementId);
            $updates = [
                "tituloPost"      => $_POST["tituloPost"][$i] ?? "",
                "editoria"        => $_POST["editoria"][$i] ?? "",
                "referencias"     => $_POST["referencias"][$i] ?? "",
                "dataPublicacao"  => $_POST["dataPublicacao"][$i] ?? "",
                "temFacebookPost" => "off",
                "impulsionarPost" => "off"
            ];
            foreach ($updates as $key => $value) {
                if ($value !== "") {
                    updateElementSaveBackup($novo_elemento, $elementId, $dados_elemento, $key, $value);
                }
            }
            $texto = "Pauta Criada";
            $POPChat->addProjectChat($dados_elemento["projeto"], $_SESSION['adm_usuario'], $texto, $dados_elemento["elemento"], 1);
        }
    }

    if ($_POST["form"] == "adicionar-info-post-blog") {
        $updates = ["tituloPost"      => "titulo", "slugPost" => "slug", "dataPublicacao" => "dataPublicacao",
                    "temFacebookPost" => "publicarFB", "impulsionarPost" => "impulsionarPost", "referencias" => "referencia",
                    "verbaPost"       => "verba", "conteudoPost" => "conteudo", "Observacoes" => "observacao"
        ];
        $_POST["temFacebookPost"] = $_POST["temFacebookPost"] ?? "off";
        $_POST["impulsionarPost"] = $_POST["impulsionarPost"] ?? "off";
        $novo_elemento->updateElementFields($elementId, $updates);
    }

    if ($_POST["form"] == "adicionar-briefing-pecas-media") {
        $updates = ["briefing" => "briefing", "url" => "url", "dataPublicacao" => "dataPublicacao", "desdobrarPeca" => "desdobrarPeca"];
        $_POST["desdobrarPeca"] = $_POST["desdobrarPeca"] ?? "off";
        $novo_elemento->updateElementFields($elementId, $updates);
    }

    if ($_POST["form"] == "adicionar-dados-pecas-media") {
        $updates = ["conteudo" => "conteudo", "referencia" => "referencia"];
        $novo_elemento->updateElementFields($elementId, $updates);
    }

    if ($_POST["form"] == "adicionar-drive-pecas-media") {
        $updates = ["gdrive" => "gdrive"];
        $novo_elemento->updateElementFields($elementId, $updates);
    }

    if ($_POST["form"] == "adicionar-drive-2-pecas-media") {
        $novo_elemento->updateElementFields($elementId, $updates);
    }

    if ($_POST["form"] == "adicionar-nova-peca-media") {
        $pid = $_POST["projeto"] ?? NULL;
        $novas_pecas = $_POST["novas-pecas"] ?? NULL;
        if($_POST["projeto"] !== NULL  && $_POST["novas-pecas"] !== NULL ) {
            for ($i = 0; $i < $novas_pecas; $i++) {
                $tipoProjeto = $infoTipoProjeto["projetoTipo"];
                $tipoElemento = $tipo_elemento_pecas_midia;
                if ($tipoProjeto == 28) {
                    $tipoElemento = 132;
                }
                $statusElemento = 1;
                $criacaoElemento = date('Y-m-d H:i:s');
                $atualizacaoElemento = date('Y-m-d H:i:s');
                $lista_Areas = $novo_projeto->objElemento->getElementTipoArea($tipoElemento);
                $aid = NULL;
                foreach ($lista_Areas as $area) {
                    $aid = $area["area"];
                }
                $prioridade = 102;
                $elementId = $novo_projeto->objElemento->insertElement(
                    ['dataCriacao', 'dataAtualizacao', 'elementoTipo', 'elementoStatus', 'projeto', 'prioridade', 'area'],
                    [$criacaoElemento, $atualizacaoElemento, $tipoElemento, $statusElemento, $pid, $prioridade, $aid]);
            }
        }
    }

    if ($_POST["form"] == "adicionar-novo-email-mkt") {
        $pid = $_POST["projeto"] ?? NULL;
        $novas_pecas = $_POST["novas-pecas"] ?? NULL;
        if($_POST["projeto"] !== NULL  && $_POST["novas-pecas"] !== NULL ) {
            $tipoProjeto = $infoTipoProjeto["projetoTipo"];
            $tipoElemento = 125;
            if ($tipoProjeto == 25) {
                $tipoElemento = 130;
            }elseif( $tipoProjeto == 26) {
                $tipoElemento = 131;
            }
            for ($i = 0; $i < $novas_pecas; $i++) {
                $statusElemento = 1;
                $criacaoElemento = date('Y-m-d H:i:s');
                $atualizacaoElemento = date('Y-m-d H:i:s');
                $lista_Areas = $novo_projeto->objElemento->getElementTipoArea($tipoElemento);
                $aid = NULL;
                foreach ($lista_Areas as $area) {
                    $aid = $area["area"];
                }
                $prioridade = 102;
                $elementId = $novo_projeto->objElemento->insertElement(
                    ['dataCriacao', 'dataAtualizacao', 'elementoTipo', 'elementoStatus', 'projeto', 'prioridade', 'area'],
                    [$criacaoElemento, $atualizacaoElemento, $tipoElemento, $statusElemento, $pid, $prioridade, $aid]);
            }
        }
    }

    if ($_POST["form"] == "adicionar-info-post-social") {
        $updates = [
            "dataPublicacao" => "dataPublicacao", "impulsionarPost" => "impulsionarPost",
            "referencia"     => "referencia", "legenda" => "legenda", "mecanica" => "mecanica",
        ];
        $_POST["impulsionarPost"] = $_POST["impulsionarPost"] ?? "off";
        $novo_elemento->updateElementFields($elementId, $updates);

        $dados_elemento = $novo_elemento->getElementById($elementId);
        $campos = $dados_elemento["campos"];
        if (isset($_FILES["imagensPost"])) {
            $base_url = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["SERVER_NAME"] . "/backoffice/pop/uploads/projeto-facebook/";
            $uploadFolder = $_SERVER["DOCUMENT_ROOT"] . '/backoffice/pop/uploads/projeto-facebook/';
            $destino = $uploadFolder . $dados_elemento["projeto"] . '/';
            // Cria o diretorio caso ele nao exista
            if (!file_exists($destino)) {
                /** @noinspection MkdirRaceConditionInspection */
                mkdir($destino, 0777, TRUE);
            }
            // aceita soh imagens
            $extensoes = 'png,jpg,jpeg,gif,docx,xlsx,xls,doc,csv,pdf,txt,psd,zip,rar,html,css';
            $retorno = uploadArquivoMultiplo("imagensPost", $destino, $extensoes, "extra-" . $elementId);
            foreach ($retorno as $arquivo) {
                if ($arquivo["arquivo"] !== "") {
                    $base_url = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["SERVER_NAME"] . "/backoffice/pop/uploads/projeto-facebook/";
                    $link = $base_url . $dados_elemento["projeto"] . '/' . $arquivo["arquivo"];
                    $proxima_referencia = $novo_elemento->proximo_campo_extra("xt_arquivos", $dados_elemento["campos"]);
                    $novo_elemento->updateExtraField($elementId, "xt_arquivos-".$proxima_referencia, $link);
                    $novo_elemento->updateExtraField($elementId, "arquivos", $link);
                }
            }
        }
    }

    if ($_POST["form"] == "adicionar-info-mail-mkt") {
        $updates = ["assunto" => "assunto", "conteudo" => "conteudo"];
        $novo_elemento->updateElementFields($elementId, $updates);
    }

    if ($_POST["form"] == "adicionar-post-social") {
        $total = count($_POST["dataPublicacao"]);
        /** @noinspection ForeachInvariantsInspection */
        for ($i = 0; $i < $total; $i++) {
            $projectId = $_POST["projeto"];
            $tipoElemento = $tipo_elemento_social_post;
            $statusElemento = 1;
            $criacaoElemento = date('Y-m-d H:i:s');
            $atualizacaoElemento = date('Y-m-d H:i:s');
            $lista_Areas = $novo_projeto->objElemento->getElementTipoArea($tipoElemento);
            $aid = NULL;
            foreach ($lista_Areas as $area) {
                $aid = $area["area"];
            }
            $prioridade = 102;
            $elementId = $novo_projeto->objElemento->insertElement(['dataCriacao', 'dataAtualizacao', 'elementoTipo', 'elementoStatus', 'projeto', 'prioridade', 'area'], [$criacaoElemento, $atualizacaoElemento, $tipoElemento, $statusElemento, $projectId, $prioridade, $aid]);
            $dados_elemento = $novo_elemento->getElementById($elementId);
            if (isset($_POST["dataPublicacao"][$i]) && $_POST["dataPublicacao"][$i] !== "") {
                updateElementSaveBackup($novo_elemento, $elementId, $dados_elemento, "dataPublicacao", $_POST["dataPublicacao"][$i]);
                $responsavel = $_SESSION["adm_usuario"] ?? NULL;
                if($responsavel !== NULL) {
                    $novo_elemento->setStatus($elementId, 4);
                    $novo_elemento->updateExtraField($elementId, "responsavel", $responsavel);
                }
            }
            updateElementSaveBackup($novo_elemento, $elementId, $dados_elemento, "impulsionarPost", "off");
            $texto = "Peca Criada";
            $POPChat->addProjectChat($dados_elemento["projeto"], $_SESSION['adm_usuario'], $texto, $dados_elemento["elemento"], 1);
        }
    }


    if ($_POST["form"] === "adicionar-estrutura-nerdpress" || $_POST["form"] === "adicionar-estrutura-aws") {
        if (isset($_POST["dominios"]) && $_POST["dominios"] !== "") {
            updateElementSaveBackup($novo_elemento, $elementId, $dados_elemento, "dominio", $_POST["dominios"]);
            $texto = "Dominio Adicionado - " . $_POST["dominios"];
            $POPChat->addProjectChat($dados_elemento["projeto"], $_SESSION['adm_usuario'], $texto, $dados_elemento["elemento"], 1);
        }
    }

    if ($_POST["form"] === "editar-elemento-facebook") {
        $updates = ["Dia" => "dataPublicacao", "Categoria" => "categoria"];
        $novo_elemento->updateElementFields($_POST["elemento"], $updates);
    }


    if ($_POST["form"] === "adicionar-listagem-conteudo") {
        if (isset($_POST["texto"]) && $_POST["texto"] !== "") {
            updateElementSaveBackup($novo_elemento, $elementId, $dados_elemento, "conteudo", $_POST["texto"]);
            $texto = "Lista de conteudo adicionada";
            $POPChat->addProjectChat($dados_elemento["projeto"], $_SESSION['adm_usuario'], $texto, $dados_elemento["elemento"], 1);
        }
    }


    if ($_POST["form"] === "adicionar-lista-modulos") {
        if (isset($_POST["texto"]) && $_POST["texto"] !== "") {
            updateElementSaveBackup($novo_elemento, $elementId, $dados_elemento, "lista", $_POST["texto"]);
            $texto = "Lista de modulos adicionada";
            $POPChat->addProjectChat($dados_elemento["projeto"], $_SESSION['adm_usuario'], $texto, $dados_elemento["elemento"], 1);
        }
    }

    if ($_POST["form"] === "adicionar-listagem-conteudo") {
        if (isset($_POST["texto"]) && $_POST["texto"] !== "") {
            updateElementSaveBackup($novo_elemento, $elementId, $dados_elemento, "conteudo", $_POST["texto"]);
        }
    }


    if ($_POST["form"] === "adicionar-lista-modulos") {
        if (isset($_POST["texto"]) && $_POST["texto"] !== "") {
            updateElementSaveBackup($novo_elemento, $elementId, $dados_elemento, "lista", $_POST["texto"]);
        }
    }

    if ($_POST["form"] === "apagar-elemento") {
        $texto = "Pedido arquivado/removido";
        $POPChat->addElementChat($dados_elemento["elemento"], $_SESSION["adm_usuario"], $texto);
        $nova_database->updatePrepared("pop_Elemento", ["elementoStatus"], [16], ['elemento'], [$dados_elemento["elemento"]]);
        $nova_database->updatePrepared("pop_ElementoConteudo", ["valor"], [NULL], ['elemento', "chave"], [$dados_elemento["elemento"], "responsavel"]);
        //$novo_elemento->updateElement($eid, ["isUsed"], [0]);
    }


    if ($_POST["form"] === "aprovar-elemento") {
        $tipo = $dados_elemento["elementoTipo"];
        $add_Observacao = FALSE;
        if (isset($dados_elemento["campos"]["Etapa"])) {
            if ($dados_elemento["campos"]["Etapa"] !== NULL) {
                $etapa_atual = $dados_elemento["campos"]["Etapa"];
            }
            else {
                // carregar a primeira etapa desse tipo de elemento
                $etapa_atual = 1;
            }
            $info = $novo_elemento->get_SubEtapa_Info($tipo, $etapa_atual);
            if ($info !== [] && $dados_elemento["elementoStatus"] !== 14) {
                $novo_elemento->updateElement($elementId, ["elementoStatus"], [14]);
                $nome = $info["nome"];
            }
        }
        else {
            $novo_elemento->updateElement($elementId, ["elementoStatus"], [8]);
            $tipoTarefa = $novo_elemento->getElementTypeById($dados_elemento['elementoTipo']);
            $nome = $tipoTarefa['nome'];
        }

        if (!isset($nome)) {
            $nome = "";
        }

        if (isset($dados_elemento['campos']['Dia']) && $dados_elemento['campos']['Dia']) {
            $nome = $nome . ' (' . date('d/m/Y', strtotime($dados_elemento['campos']['Dia'])) . ')';
        }
        $tempo = "";
        if ($_POST['tempo-em-minutos'] != "") {
            $tempo = ' tempo trabalhado ' . $_POST["tempo-em-minutos"] . " minutos";
        }
        $texto = "Tarefa - " . $nome . " - Aprovada" . "," . $tempo;
        if (!in_array($tipo, $array_tipo_pedido)) {
            $POPChat->addProjectChat($dados_elemento["projeto"], $_SESSION['adm_usuario'], $texto, $dados_elemento["elemento"], 1);
        }
        if (in_array($tipo, $array_tipo_pedido)) {
            $POPChat->addElementChat($dados_elemento["elemento"], $_SESSION["adm_usuario"], $texto, 1);
            $url_retorno = 'pop-minhas-tarefas.php';
            $url_pedido = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["SERVER_NAME"] . "/backoffice/pop/pop-pedido.php?eid=" . $dados_elemento["elemento"];
            $_SESSION["msg_sucesso"] = 'Pedido finalizado com sucesso.<br/> ' . $url_pedido;
        }
    }

    if ($_POST["form"] === "reprovar-elemento" || $_POST["form"] === "reprovar-elemento-condicional") {
        $tipo = $dados_elemento["elementoTipo"];
        if (isset($dados_elemento["campos"]["Etapa"])) {
            if ($dados_elemento["campos"]["Etapa"]) {
                $etapa_atual = $dados_elemento["campos"]["Etapa"];
            }
            else {
                // carregar a primeira etapa desse tipo de elemento
                $etapa_atual = 1;
            }

            $info = $novo_elemento->get_SubEtapa_Info($tipo, $etapa_atual);
            if ($info !== [] && $dados_elemento["elementoStatus"] !== 15) {
                $novo_elemento->updateElement($elementId, ["elementoStatus"], [15]);
                $nome = $info["nome"];
            }
        }
        else {
            $novo_elemento->updateElement($elementId, ["elementoStatus"], [10]);
            $tipoTarefa = $novo_elemento->getElementTypeById($dados_elemento['elementoTipo']);
            $nome = $tipoTarefa['nome'];
        }

        // CODIGO CONDICIONAL DA REPROVACAO
        //
        if (isset($_POST["etapaReprovacao"])) {
            $novo_elemento->updateElement($elementId, ["EtapaReprovacao"], [$_POST["etapaReprovacao"]]);
        }
        //
        //

        if (!isset($nome)) {
            $nome = "";
        }

        if (isset($dados_elemento['campos']['Dia']) && $dados_elemento['campos']['Dia']) {
            $nome = $nome . ' (' . date('d/m/Y', strtotime($dados_elemento['campos']['Dia'])) . ')';
        }

        if (isset($_POST["observacao"]) && $_POST["observacao"] !== "") {
            updateElementSaveBackup($novo_elemento, $elementId, $dados_elemento, "Observacoes", $_POST["observacao"]);
        }

        $area = $novo_elemento->getAreaById($dados_elemento["campos"]["area"]);
        $area = $area["nome"];
        $tempo = "";
        if ($_POST['tempo-em-minutos'] != "") {
            $tempo = ' --- tempo trabalhado ' . $_POST["tempo-em-minutos"] . " minutos";
        }

        $texto = "A Tarefa " . $nome . " foi reprovada pela área " . $area . " pelo motivo : " . $_POST["observacao"] . $tempo;

        if (!in_array($tipo, $array_tipo_pedido)) {
            $POPChat->addProjectChat($dados_elemento["projeto"], $_SESSION['adm_usuario'], $texto, $dados_elemento["elemento"]);
        }
        if (in_array($tipo, $array_tipo_pedido)) {
            $POPChat->addElementChat($dados_elemento["elemento"], $_SESSION["adm_usuario"], $texto);
            $url_retorno = 'pop-minhas-tarefas.php';
            $url_pedido = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["SERVER_NAME"] . "/backoffice/pop/pop-pedido.php?eid=" . $dados_elemento["elemento"];
            $_SESSION["msg_sucesso"] = 'Pedido reprovado com sucesso.<br/> ' . $url_pedido;
        }
    }

    if ($_POST["form"] === "aguardar-cliente") {
        $tipo = $dados_elemento["elementoTipo"];
        if (isset($dados_elemento["campos"]["Etapa"])) {
            if ($dados_elemento["campos"]["Etapa"]) {
                $etapa_atual = $dados_elemento["campos"]["Etapa"];
            }
            else {
                // carregar a primeira etapa desse tipo de elemento
                $etapa_atual = 1;
            }

            $info = $novo_elemento->get_SubEtapa_Info($tipo, $etapa_atual);
            if ($info !== [] && $dados_elemento["elementoStatus"] !== 3) {
                $novo_elemento->updateElement($elementId, ["elementoStatus"], [3]);
                $nome = $info["nome"];
            }
        }
        else {
            $novo_elemento->updateElement($elementId, ["elementoStatus"], [3]);
            $tipoTarefa = $novo_elemento->getElementTypeById($dados_elemento['elementoTipo']);
            $nome = $tipoTarefa['nome'];
        }


        if (!isset($nome)) {
            $nome = "";
        }

        if (isset($dados_elemento['campos']['Dia']) && $dados_elemento['campos']['Dia']) {
            $nome = $nome . ' (' . date('d/m/Y', strtotime($dados_elemento['campos']['Dia'])) . ')';
        }

        $observacao = '';

        if (isset($_POST["observacao"]) && $_POST["observacao"] !== "") {
            updateElementSaveBackup($novo_elemento, $elementId, $dados_elemento, "Observacoes", $_POST["observacao"]);
        }

        $texto = 'Tarefa - ' . $nome . "\nEnviado para o cliente em " . date('d/m/Y H:i:s') . "\nObservação: " . $observacao;

        if (!in_array($tipo, $array_tipo_pedido)) {
            $POPChat->addProjectChat($dados_elemento["projeto"], $_SESSION['adm_usuario'], $texto, $dados_elemento["elemento"], 1);
        }
        if (in_array($tipo, $array_tipo_pedido)) {
            $POPChat->addElementChat($dados_elemento["elemento"], $_SESSION["adm_usuario"], $texto, 1);
            $url_retorno = 'pop-minhas-tarefas.php';
            $_SESSION["msg_sucesso"] = 'Status atualizado com sucesso.';
        }
    }


    if ($_POST["form"] === "adicionar-arquivo") {
        if (isset($_FILES['arquivo']["name"], $_FILES['arquivo']["error"], $_FILES['arquivo']["type"])) {
            if ($_FILES['arquivo']["error"] === 0) {
                $base_url = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["SERVER_NAME"] . "/backoffice/pop/uploads/projeto-facebook/";
                $uploadFolder = $_SERVER["DOCUMENT_ROOT"] . '/backoffice/pop/uploads/projeto-facebook/';
                $destino = $uploadFolder . $dados_elemento["projeto"] . '/';

                // Cria o diretorio caso ele nao exista
                if (!file_exists($destino)) {
                    /** @noinspection MkdirRaceConditionInspection */
                    mkdir($destino, 0777, TRUE);
                }
                // aceita soh imagens

                $extensoes = 'png,jpg,jpeg,gif,docx,xlsx,xls,doc,csv,pdf,txt,psd,zip,rar,html,css';

                $arquivo = uploadArquivo('arquivo', $destino, $extensoes, 'post');

                $link = $base_url . $dados_elemento["projeto"] . '/' . $arquivo["arquivo"];

                $indice = $novo_elemento->proximo_campo_extra("xt_arquivos", $dados_elemento["campos"]);
                $proxima = "xt_arquivos_" . $indice;
                if ($novo_elemento->updateExtraField($elementId, $proxima, $link)) {
                    $observacao = "";
                    if ($_POST["observacao"] !== "") {
                        $observacao = $_POST["observacao"];
                    }
                    $proximo_obs = "xt_Observacao_Arquivo_" . $indice;
                    $novo_elemento->updateExtraField($elementId, $proximo_obs, $observacao);
                    $novo_elemento->updateExtraField($elementId, "xt_responsavel_" . $indice, $_SESSION["adm_usuario"]);
                    $_SESSION["msg_sucesso"] = "Arquivo Enviado";
                }
            }
        }
        else {
            $_SESSION["msg_erro"] = "Escolha um arquivo de imagem";
        }
    }


    if ($_POST["form"] === "adicionar-arquivos") {
        if (isset($_FILES["arquivos"])) {
            $base_url = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["SERVER_NAME"] . "/backoffice/pop/uploads/projeto-facebook/";
            $uploadFolder = $_SERVER["DOCUMENT_ROOT"] . '/backoffice/pop/uploads/projeto-facebook/';
            $destino = $uploadFolder . $dados_elemento["projeto"] . '/';

            // Cria o diretorio caso ele nao exista
            if (!file_exists($destino)) {
                /** @noinspection MkdirRaceConditionInspection */
                mkdir($destino, 0777, TRUE);
            }
            // aceita soh imagens
            $extensoes = 'png,jpg,jpeg,gif,docx,xlsx,xls,doc,csv,pdf,txt,psd,zip,rar,html,css';

            $retorno = uploadArquivoMultiplo("arquivos", $destino, $extensoes, "pedido-" . $elementId);
            //var_dump($retorno);
            $i = $novo_elemento->proximo_campo_extra("xt_arquivos", $dados_elemento["campos"]);
            foreach ($retorno as $arquivo) {
                if ($arquivo["arquivo"] !== "") {
                    $base_url = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["SERVER_NAME"] . "/backoffice/pop/uploads/projeto-facebook/";
                    $link = $base_url . $dados_elemento["projeto"] . '/' . $arquivo["arquivo"];
                    //$proxima_referencia = "xt_arquivos_" . $i;
                    $novo_elemento->updateExtraField($elementId, "xt_arquivos_" . $i, $link);

                    $novo_elemento->updateExtraField($elementId, "xt_Observacao_Arquivo_" . $i, $_POST["observacao"]);
                    $novo_elemento->updateExtraField($elementId, "xt_responsavel_" . $i, $_SESSION["adm_usuario"]);
                }
                $i++;
            }

        }
        else {
            $_SESSION["msg_erro"] = "Escolha um arquivo de imagem";
        }
    }


    if ($_POST["form"] === "novo-hora") {
        $projectId = $projeto_horas;
        $tipoElemento = $tipo_elemento_horas;
        $statusElemento = 9;
        $criacaoElemento = date('Y-m-d H:i:s');
        $prioridade = 102;
        $elementId = $novo_projeto->objElemento->insertElement(
            [
                'dataCriacao', 'dataAtualizacao', 'elementoTipo', 'elementoStatus',
                'projeto', 'prioridade', 'area', 'titulo', 'cliente',
                "descricao", "Observacoes", "tempo", "responsavel"
            ],
            [
                $criacaoElemento, $criacaoElemento, $tipoElemento, $statusElemento,
                $projectId, $prioridade, $_POST["areaDe"], $_POST["titulo"], $_POST["cliente"],
                $_POST["descricao"], "", $_POST["tempo-em-minutos"], $_SESSION["adm_usuario"]
            ]
        );

        $dados_elemento = $novo_elemento->getElementById($elementId);
        if (isset($_FILES["arquivos"])) {
            $base_url = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["SERVER_NAME"] . "/backoffice/pop/uploads/projeto-facebook/";
            $uploadFolder = $_SERVER["DOCUMENT_ROOT"] . '/backoffice/pop/uploads/projeto-facebook/';
            $destino = $uploadFolder . $dados_elemento["projeto"] . '/';

            // Cria o diretorio caso ele nao exista
            if (!file_exists($destino)) {
                /** @noinspection MkdirRaceConditionInspection */
                var_dump($destino);
                mkdir($destino, 0777, TRUE);
            }

            $extensoes = 'png,jpg,jpeg,gif,docx,xlsx,xls,doc,csv,pdf,txt,psd,zip,rar,html,css';
            $retorno = uploadArquivoMultiplo("arquivos", $destino, $extensoes, "pedido-" . $elementId);
            $i = 1;
            foreach ($retorno as $arquivo) {
                if ($arquivo["arquivo"] !== "") {
                    $base_url = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["SERVER_NAME"] . "/backoffice/pop/uploads/projeto-facebook/";
                    $link = $base_url . $dados_elemento["projeto"] . '/' . $arquivo["arquivo"];
                    $proxima_referencia = $novo_elemento->proximo_campo_extra("xt_arquivos", $dados_elemento["campos"]) + 1;
                    $proxima_referencia = "xt_arquivos_" . $i;
                    $novo_elemento->updateExtraField($elementId, $proxima_referencia, $link);

                    $novo_elemento->updateExtraField($elementId, "xt_Observacao_Arquivo_" . $i, "Adicionado na criacao do pedido");
                    $novo_elemento->updateExtraField($elementId, "xt_responsavel_" . $i, $_SESSION["adm_usuario"]);
                }
                $i++;
            }
        }

        $texto = "Apontamento - " . $_POST["titulo"] . " - Aprovada" . ", tempo trabalhado " . $_POST["tempo-em-minutos"] . " minutos";
        $POPChat->addElementChat($dados_elemento["elemento"], $_SESSION["adm_usuario"], $texto, 1);
        $url_retorno = 'pop-meus-apontamentos.php';
        $_SESSION["msg_sucesso"] = 'Apontamento Criado com sucesso com sucesso.';
    }


    if ($_POST["form"] === "novo-pedido") {
        $retorno = $_POST["geraRetorno"];
        $areaPara = 16;
        if ($_POST["areaPara"] !== "") {
            $areaPara = $_POST["areaPara"];
        }
        // Trecho de criacao do pedido propriamente dito
        $projectId = $projeto_pedido;
        $tipoElemento = $tipo_elemento_pedido;
        $statusElemento = 1;
        $criacaoElemento = date('Y-m-d H:i:s');
        $atualizacaoElemento = date('Y-m-d H:i:s');


        if (isset($_POST["prioridade"]) && $_POST["prioridade"] !== "") {
            $prioridade = $_POST["prioridade"];
        }
        else {
            $prioridade = 102;
        }

        $elementId = $novo_projeto->objElemento->insertElement(
            ['dataCriacao', 'dataAtualizacao', 'elementoTipo', 'elementoStatus', 'projeto', 'prioridade', 'area', 'Nome', 'Cliente', 'Etapa'],
            [$criacaoElemento, $atualizacaoElemento, $tipoElemento, $statusElemento, $projectId, $prioridade, $areaPara, $_POST["nome"], $_POST["cliente"], $etapa_inicial_pedido]
        );

        $dados_elemento = $novo_elemento->getElementById($elementId);
        $novo_elemento->updateElement($elementId, ["responsavel_de"], [$_SESSION['adm_usuario']]);

        if ($_POST["areaDe"] !== "" && $_POST["override_area_de"] == "false") {
            $novo_elemento->updateElement($elementId, ["De_Area"], [$_POST["areaDe"]]);
        }
        elseif ($_POST["override_area_de"] == "true") {
            $novo_elemento->updateElement($elementId, ["De_Area"], [4]);
        }

        if ($_POST["areaPara"] !== "") {
            $novo_elemento->updateElement($elementId, ["Para_Area"], [$_POST["areaPara"]]);
        }

        if (isset($_POST["responsavel"]) && $_POST["responsavel"] !== "") {
            $novo_elemento->updateElement($elementId, ["responsavel_para"], [$_POST["responsavel"]]);
            $novo_elemento->updateElement($elementId, ["responsavel"], [$_POST["responsavel"]]);
            $novo_elemento->updateElement($elementId, ["elementoStatus"], [2]);
        }

        if (isset($_POST["produto"]) && $_POST["produto"] !== "") {
            $novo_elemento->updateElement($elementId, ["Produto"], [$_POST["produto"]]);
        }

        if (isset($_POST["prazo"]) && $_POST["prazo"] !== "") {
            $novo_elemento->updateElement($elementId, ["prazo"], [$_POST["prazo"]]);
        }

        if (isset($_POST["observacao"]) && $_POST["observacao"] !== "") {
            updateElementSaveBackup($novo_elemento, $elementId, $dados_elemento, "Descricao", $_POST["observacao"]);
        }

        if (isset($_FILES["arquivos"])) {
            $base_url = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["SERVER_NAME"] . "/backoffice/pop/uploads/projeto-facebook/";
            $uploadFolder = $_SERVER["DOCUMENT_ROOT"] . '/backoffice/pop/uploads/projeto-facebook/';
            $destino = $uploadFolder . $dados_elemento["projeto"] . '/';

            // Cria o diretorio caso ele nao exista
            if (!file_exists($destino)) {
                /** @noinspection MkdirRaceConditionInspection */
                mkdir($destino, 0777, TRUE);
            }
            // aceita soh imagens
            $extensoes = 'png,jpg,jpeg,gif,docx,xlsx,xls,doc,csv,pdf,txt,psd,zip,rar,html,css';

            $retorno = uploadArquivoMultiplo("arquivos", $destino, $extensoes, "pedido-" . $elementId);
            //var_dump($retorno);
            $i = 1;
            foreach ($retorno as $arquivo) {
                if ($arquivo["arquivo"] !== "") {
                    $base_url = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["SERVER_NAME"] . "/backoffice/pop/uploads/projeto-facebook/";
                    $link = $base_url . $dados_elemento["projeto"] . '/' . $arquivo["arquivo"];
                    $proxima_referencia = $novo_elemento->proximo_campo_extra("xt_arquivos", $dados_elemento["campos"]) + 1;
                    $proxima_referencia = "xt_arquivos_" . $i;
                    $novo_elemento->updateExtraField($elementId, $proxima_referencia, $link);

                    $novo_elemento->updateExtraField($elementId, "xt_Observacao_Arquivo_" . $i, "Adicionado na criacao do pedido");
                    $novo_elemento->updateExtraField($elementId, "xt_responsavel_" . $i, $_SESSION["adm_usuario"]);
                }
                $i++;
            }
        }
        $url_pedido = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["SERVER_NAME"] . "/backoffice/pop/pop-pedido.php?eid=" . $elementId;
        echo json_encode($url_pedido);
        //$_SESSION["msg_sucesso"] = "Pedido Criado Com Sucesso<br/> " . $url_pedido;
    }


    if ($_POST["form"] === "editar-pedido") {
        $updates = [
            "Cliente"          => "cliente", "Produto" => "produto", "prioridade" => "prioridade",
            "responsavel_para" => "pessoaPara", "Nome" => "nome", "prazo" => "prazo", "Descricao" => "observacao"
        ];
        $novo_elemento->updateElementFields($_POST["pedido"], $updates);
        $_SESSION["msg_sucesso"] = "Pedido Alterado com sucesso.";
    }

    // Trava o Redirect caso esteja em uma secao de debug
    if (isset($debug)) {
        exit;
    }
    Utils::redirect($url_retorno);
}
