<?php
if (isset($alertasProjeto) && is_array($alertasProjeto)) {
    $Template = new Template();
    foreach ($alertasProjeto as $alerta) {
        $Template->insert("alerta",
            [
                "class" => "callout-important",
                "title" => $alerta['titulo'],
                "message" => $alerta['texto'],
                "type" => $POPAlerta->tipos[$alerta['tipo']]['classe']
            ]
        );
    }
}

