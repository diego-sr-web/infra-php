<?php
$scriptName = basename($_SERVER["SCRIPT_FILENAME"]);

?>
<aside class="sidenav-main nav-expanded nav-lock nav-collapsible sidenav-dark gradient-45deg-deep-purple-blue sidenav-gradient sidenav-active-rounded">
    <div class="brand-sidebar">
        <h1 class="logo-wrapper"><a class="brand-logo darken-1" href="/backoffice/main.php"><img
                    class="hide-on-med-and-down " src="/backoffice/img/logo-header-icon.png" alt=""/><img
                    class="show-on-medium-and-down hide-on-med-and-up" src="/backoffice/img/logo-header-icon.png"
                    alt="materialize logo"/><span class="logo-text hide-on-med-and-down">POPFLOW</span></a><a
                class="navbar-toggler" href="#"><i class="material-icons">radio_button_checked</i></a></h1>
    </div>
    <ul class="sidenav sidenav-collapsible leftside-navigation collapsible sidenav-fixed menu-shadow" id="slide-out"
        data-menu="menu-navigation" data-collapsible="menu-accordion">
        <li class="bold">
            <a class="waves-effect waves-cyan <?php if ($scriptName == "pop-minhas-tarefas.php") {echo "active";}?>" href="./pop-minhas-tarefas.php">
                <i class="fas fa-tasks"></i>
                <span class="menu-title">Minhas Tarefas</span>
            </a>
        </li>
        <li class="bold">
            <a class="waves-effect waves-cyan <?php if ($scriptName == "pop-meus-pedidos.php") {echo "active";}?>" href="./pop-meus-pedidos.php">
                <i class="fas fa-exchange-alt"></i>
                <span class="menu-title">Meus Pedidos</span>
            </a>
        </li>
        <li class="bold">
            <a class="waves-effect waves-cyan <?php if ($scriptName == "pop-meus-apontamentos.php") {echo "active";}?>" href="./pop-meus-apontamentos.php">
                <i class="fas fa-user-clock"></i>
                <span class="menu-title">Meus Apontamentos</span>
            </a>
        </li>
        <li class="navigation-header"><a class="navigation-header-text"><hr> </a><i
                    class="navigation-header-icon material-icons">more_horiz</i>
        </li>
        <li class="bold">
            <a class="waves-effect waves-cyan <?php if ($scriptName == "pop-monitor.php") {echo "active";}?>" href="./pop-monitor.php">
                <i class="fas fa-chart-bar"></i>
                <span class="menu-title">Monitores</span>
            </a>
        </li>

        <li class="bold">
            <a class="waves-effect waves-cyan <?php if ($scriptName == "") {echo "active";}?>" href="./pop-monitor.php?tipo=16">
                <i class="fas fa-angle-double-right"></i>
                <span class="menu-title"> Email Marketing</span>
            </a>
        </li>
        <li class="bold">
            <a class="waves-effect waves-cyan <?php if ($scriptName == "") {echo "active";}?>" href="./pop-monitor.php?tipo=2">
                <i class="fas fa-angle-double-right"></i>
                <span class="menu-title"> Media Social</span>
            </a>
        </li>

        <li class="bold">
            <a class="waves-effect waves-cyan <?php if ($scriptName == "pop-monitor-tarefas.php") {echo "active";}?>" href="./pop-monitor-tarefas.php">
                <i class="fas fa-angle-double-right"></i>
                <span class="menu-title"> Tarefas</span>
            </a>
        </li>

        <li class="bold">
            <a class="waves-effect waves-cyan <?php if ($scriptName == "") {echo "active";}?>" href="./pop-monitor.php?tipo=9">
                <i class="fas fa-angle-double-right"></i>
                <span class="menu-title"> Pedidos</span>
            </a>
        </li>
        <li class="bold">
            <a class="waves-effect waves-cyan <?php if ($scriptName == "") {echo "active";}?>" href="./pop-monitor.php?tipo=14">
                <i class="fas fa-angle-double-right"></i>
                <span class="menu-title"> Producao de Textos</span>
            </a>
        </li>

        <li class="bold">
            <a class="waves-effect waves-cyan <?php if ($scriptName == "") {echo "active";}?>" href="./pop-monitor.php?tipo=24">
                <i class="fas fa-angle-double-right"></i>
                <span class="menu-title"> Pecas de Midia</span>
            </a>
        </li>

        <li class="bold">
            <a class="waves-effect waves-cyan <?php if ($scriptName == "") {echo "active";}?>" href="./pop-monitor.php?tipo=10">
                <i class="fas fa-angle-double-right"></i>
                <span class="menu-title"> Website</span>
            </a>
        </li>

        <li class="navigation-header"><a class="navigation-header-text"><hr> </a><i
                class="navigation-header-icon material-icons">more_horiz</i>
        </li>
        <li class="bold">
            <a class="waves-effect waves-cyan <?php if ($scriptName == "main.php") {echo "active";}?>" href="/backoffice/main.php">
                <i class="fas fa-building"></i>
                <span class="menu-title">Backoffice</span>
            </a>
        </li>
        <li class="bold">
            <a class="waves-effect waves-cyan <?php if ($scriptName == "clientes.php") {echo "active";}?>" href="/backoffice/clientes.php">
                <i class="fas fa-users"></i>
                <span class="menu-title">Clientes</span>
            </a>
        </li>
        <li class="bold">
            <a class="waves-effect waves-cyan <?php if ($scriptName == "usuarios.php") {echo "active";}?>" href="/backoffice/usuarios.php">
                <i class="fas fa-lock"></i>
                <span class="menu-title">Usuários</span>
            </a>
        </li>
        <li class="bold">
            <a class="waves-effect waves-cyan <?php if ($scriptName == "areas.php") {echo "active";}?>" href="/backoffice/areas.php">
                <i class="fas fa-lock"></i>
                <span class="menu-title">Áreas</span>
            </a>
        </li>
        <li class="bold">
            <a class="waves-effect waves-cyan <?php if ($scriptName == "alterar-dados.php") {echo "active";}?>" href="/backoffice/alterar-dados.php">
                <i class="far fa-user"></i>
                <span class="menu-title">Meus Dados</span>
            </a>
        </li>
    </ul>
    <div class="navigation-background"></div>
    <a class="sidenav-trigger btn-sidenav-toggle btn-floating btn-medium waves-effect waves-light hide-on-large-only" href="#" data-target="slide-out">
        <i class="material-icons">menu</i>
    </a>
</aside>
