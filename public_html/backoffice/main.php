<?php
require_once __DIR__ . "/../autoloader.php";
require_once __DIR__ . "/_init.inc.php";
?>
<!DOCTYPE html>
<html class="loading" lang="pt-br" data-textdirection="ltr">
<!-- BEGIN: Head-->
<head>
    <?php $Template->insert("backoffice/materialize-head", ["pageTitle" => "Blank Page | Backoffice Nerdweb"]); ?>
</head>
<!-- END: Head-->

<body class="vertical-layout page-header-light vertical-menu-collapsible vertical-menu-nav-dark preload-transitions 2-columns" data-open="click" data-menu="vertical-gradient-menu" data-col="2-columns">

<!-- BEGIN: Header-->
<header class="page-topbar" id="header">
    <?php $Template->insert("backoffice/materialize-header"); ?>
</header>
<!-- END: Header-->

<!-- BEGIN: SideNav-->
<?php $Template->insert("backoffice/materialize-sidebar"); ?>
<!-- END: SideNav-->

<!-- BEGIN: Page Main-->
<div id="main">
    <div class="row">
        <div class="col s12">
            <div class="container">
                <div class="section">
                    <div class="card">
                        <div class="card-content">

                            <h4>Manifesto - We Know You</h4>

                            <p>Em meio a tantas tecnologias, qual é a melhor estratégia de comunicação para o meu negócio?</p><br/>

                            <p>Há quem acredite que a publicidade ainda seja um remédio pronto para resolver um problema doloroso. É por isso que existem tantas soluções de prateleira com a mesma promessa. Afinal, elas funcionaram por décadas.</p><br/>

                            <p> As mutações do mercado hoje alcançam uma escala exponencial de possibilidades e não se resolvem com uma única fórmula. As agências necessitam de uma abordagem científica para desconstruir velhos hábitos e validar novas hipóteses.</p><br/>

                            <p>E essa lógica só funciona se for aplicada por mentes curiosas que questionam o tempo todo e só se contentam com a verdade, cientes de que no dia seguinte tudo pode mudar, exigindo novas respostas criativas.</p><br/>

                            <p> A capacidade de interpretar cada pessoa de maneira singular vai muito além do conhecimento. É preciso se colocar no lugar do outro, ter sensibilidade para compreender a diversidade e distinguir que cada indivíduo tem motivações e objetivos diferentes.</p><br/>

                            <p> E essa lógica só funciona se for aplicada por mentes curiosas que questionam o tempo todo e só se contentam com a verdade, cientes de que no dia seguinte tudo pode mudar, exigindo novas respostas criativas.</p><br/>

                            <p> A capacidade de interpretar cada pessoa de maneira singular vai muito além do conhecimento. É preciso se colocar no lugar do outro, ter sensibilidade para compreender a diversidade e distinguir que cada indivíduo tem motivações e objetivos diferentes.</p><br/>

                            <p> Mais do que entender sobre o que fazemos, entendemos para quem fazemos. Você.</p><br/>
                        </div>
                </div>
            </div>
            <div class="content-overlay"></div>
        </div>
    </div>
</div>
<!-- END: Page Main-->

<!-- BEGIN: Footer-->
<?php $Template->insert("backoffice/materialize-footer"); ?>
<!-- END: Footer-->
<!-- BEGIN: SCRIPTS-->
<?php $Template->insert("backoffice/materialize-scripts"); ?>
<!-- END: SCRIPTS-->
</body>

</html>
