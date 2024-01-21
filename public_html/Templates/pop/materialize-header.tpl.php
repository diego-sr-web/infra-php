<?php
$nome = $data["nome"] ?? $_SESSION["adm_nome"] ?? "NAO_DEFINIDO";
$imagem = $data["imagem"] ?? $_SESSION["adm_imagem"] ?? "/backoffice/uploads/usuarios/default.png";
?>
<div class="navbar navbar-fixed">
    <nav class="navbar-main navbar-color nav-collapsible sideNav-lock navbar-light">
        <div class="nav-wrapper">
            <ul class="navbar-list right">

                <li class="dropdown-language">
                    <a class="waves-effect waves-block waves-light translation-button" href="#" data-target="users-dropdown">
                        <span class="fas fa-users"></span>
                    </a>
                </li>

                <li>
                    <a class="waves-effect waves-block waves-light profile-button" href="javascript:void(0);" data-target="profile-dropdown">
                        <span class="avatar-status avatar-online"><img src="<?= $imagem; ?>" alt="avatar/"><i></i></span>
                    </a>
                </li>
                <li class="hide-on-med-and-down">
                    <a class="waves-effect waves-block waves-light toggle-fullscreen" href="javascript:void(0);">
                        <i class="material-icons">settings_overscan</i>
                    </a>
                </li>
            </ul>
            <!-- translation-button-->
            <ul class="dropdown-content" id="users-dropdown">
                <li class="dropdown-item"><a class="grey-text text-darken-1" href="#!" data-language="en"><i class="far fa-user"></i> Nao Implementado</a></li>
            </ul>
            <!-- dropdown info usuario-->
            <ul class="dropdown-content" id="profile-dropdown">
                <li><a class="grey-text text-darken-1" href="/backoffice/alterar-dados.php"><i class="material-icons">person_outline</i> Meus Dados</a></li>
                <li class="divider"></li>
                <li><a class="grey-text text-darken-1" href="/backoffice/logout.php"><i class="material-icons">keyboard_tab</i> Sair</a></li>
            </ul>
        </div>
    </nav>
</div>
