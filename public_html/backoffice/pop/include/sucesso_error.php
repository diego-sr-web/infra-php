<?php
if (isset($_SESSION["msg_sucesso"])) {
    $message_data = ["class" => "callout-success", "title" => "Sucesso", "message" => $_SESSION["msg_sucesso"]];
    unset($_SESSION["msg_sucesso"]);
}
elseif (isset($_SESSION["msg_erro"])) {
    $message_data = ["class" => "callout-error", "title" => "Erro", "message" => $_SESSION["msg_erro"]];
    unset($_SESSION["msg_erro"]);
}
if (isset($message_data['class'])) {
    $Template = new Template();
    $Template->insert("alerta", $message_data);
}
