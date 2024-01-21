<?php
$scriptName = basename($_SERVER["SCRIPT_FILENAME"]);
?>
<aside class="sidenav-main nav-expanded nav-lock nav-collapsible sidenav-dark gradient-45deg-indigo-blue sidenav-gradient sidenav-active-rounded">
    <div class="brand-sidebar blue darken-4">
        <h1 class="logo-wrapper">
            <a class="brand-logo" href="/backoffice/main.php">
                <img class="hide-on-med-and-down " src="/backoffice/img/logo-header-icon.png" alt=""/>
                <img class="show-on-medium-and-down hide-on-med-and-up" src="/backoffice/img/logo-header-icon.png" alt="materialize logo"/>
                <span class="logo-text hide-on-med-and-down">POPFLOW</span>
            </a>
        </h1>
    </div>
    <ul class="sidenav sidenav-collapsible leftside-navigation collapsible sidenav-fixed menu-shadow" id="slide-out" data-menu="menu-navigation" data-collapsible="menu-accordion">
        <li class="navigation-header">
            <a class="navigation-header-text">Tarefas Di&aacute;rias</a>
            <i class="navigation-header-icon material-icons">more_horiz</i>
        </li>
        <li class="bold">
            <a class="waves-effect waves-cyan <?php if ($scriptName == "pop-minhas-tarefas.php") {echo "active";}?>" href="/backoffice/pop/pop-minhas-tarefas.php">
                <i class="material-icons medium">assignment</i>
                <span class="menu-title">Minhas Tarefas</span>
            </a>
        </li>
        <li class="bold">
            <a class="waves-effect waves-cyan <?php if ($scriptName == "pop-meus-pedidos.php") {echo "active";}?>" href="/backoffice/pop/pop-meus-pedidos.php">
                <i class="material-icons medium">assignment_turned_in</i>
                <span class="menu-title">Meus Pedidos</span>
            </a>
        </li>
        <li class="bold">
            <a class="waves-effect waves-cyan <?php if ($scriptName == "pop-meus-apontamentos.php") {echo "active";}?>" href="/backoffice/pop/pop-meus-apontamentos.php">
                <i class="material-icons medium">assignment_late</i>
                <span class="menu-title">Meus Apontamentos</span>
            </a>
        </li>
        <li class="navigation-header">
            <a class="navigation-header-text">Monitores</a>
            <i class="navigation-header-icon material-icons">more_horiz</i>
        </li>
        <li class="bold">
            <a class="waves-effect waves-cyan <?php if ($scriptName == "pop-monitor-tarefas.php") {echo "active";}?>" href="/backoffice/pop/pop-monitor-tarefas.php">
                <i class="material-icons medium">assistant</i>
                <span class="menu-title">Tarefas</span>
            </a>
        </li>
        <li class="bold">
            <a class="waves-effect waves-cyan <?php if ($scriptName == "") {echo "active";}?>" href="/backoffice/pop/pop-monitor.php?tipo=9">
                <i class="material-icons medium">check_box</i>
                <span class="menu-title">Pedidos</span>
            </a>
        </li>
        <li class="bold">
            <a class="waves-effect waves-cyan <?php if ($scriptName == "") {echo "active";}?>" href="/backoffice/pop/pop-monitor.php?tipo=16">
                <i class="material-icons medium">mail_outline</i>
                <span class="menu-title">Email Marketing</span>
            </a>
        </li>
        <li class="bold">
            <a class="waves-effect waves-cyan <?php if ($scriptName == "") {echo "active";}?>" href="/backoffice/pop/pop-monitor.php?tipo=2">
                <i class="material-icons medium">photo_library</i>
                <span class="menu-title">Media Social</span>
            </a>
        </li>
        <li class="bold">
            <a class="waves-effect waves-cyan <?php if ($scriptName == "") {echo "active";}?>" href="/backoffice/pop/pop-monitor.php?tipo=14">
                <i class="material-icons medium">subject</i>
                <span class="menu-title">Producao de Textos</span>
            </a>
        </li>
        <li class="bold">
            <a class="waves-effect waves-cyan <?php if ($scriptName == "") {echo "active";}?>" href="/backoffice/pop/pop-monitor.php?tipo=24">
                <i class="material-icons medium">layers</i>
                <span class="menu-title">Peças de Midia</span>
            </a>
        </li>
        <li class="bold">
            <a class="waves-effect waves-cyan <?php if ($scriptName == "") {echo "active";}?>" href="/backoffice/pop/pop-monitor.php?tipo=10">
                <i class="material-icons medium">web</i>
                <span class="menu-title">Website</span>
            </a>
        </li>
        <li class="navigation-header">
            <a class="navigation-header-text">Backoffice</a>
            <i class="navigation-header-icon material-icons">more_horiz</i>
        </li>
        <li class="bold">
            <a class="waves-effect waves-cyan <?php if ($scriptName == "main.php") {echo "active";}?>" href="/backoffice/main.php">
                <i class="material-icons medium">dashboard</i>
                <span class="menu-title">Dev App</span>
            </a>
        </li>
        <li class="bold">
            <a class="waves-effect waves-cyan <?php if ($scriptName == "clientes.php") {echo "active";}?>" href="/backoffice/clientes.php">
                <i class="material-icons medium">group</i>
                <span class="menu-title">Clientes</span>
            </a>
        </li>
        <li class="bold">
            <a class="waves-effect waves-cyan <?php if ($scriptName == "usuarios.php") {echo "active";}?>" href="/backoffice/usuarios.php">
                <i class="material-icons medium">account_circle</i>
                <span class="menu-title">Usuários</span>
            </a>
        </li>
        <li class="bold">
            <a class="waves-effect waves-cyan <?php if ($scriptName == "areas.php") {echo "active";}?>" href="/backoffice/areas.php">
                <i class="material-icons medium">dvr</i>
                <span class="menu-title">Áreas</span>
            </a>
        </li>
        <li class="navigation-header">
            <a class="navigation-header-text">Ajuda</a>
            <i class="navigation-header-icon material-icons">more_horiz</i>
        </li>
        <li class="bold">
            <a class="waves-effect waves-cyan <?php if ($scriptName == "page-knowledge.php") {echo "active";}?>" href="/backoffice/page-knowledge.php">
                <i class="material-icons medium">library_books</i>
                <span class="menu-title">Manuais</span>
            </a>
        </li>
    </ul>
    <div class="navigation-background"></div>
    <a class="sidenav-trigger btn-sidenav-toggle btn-floating btn-medium waves-effect waves-light hide-on-large-only gradient-45deg-indigo-blue" href="#" data-target="slide-out">
        <i class="material-icons medium">menu</i>
    </a>
</aside>
