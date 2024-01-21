<?php $infoUsuario = $usuario->getUserDataWithId($_SESSION['adm_usuario']); ?>

<section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
        <div class="image txt-center">
            <img src="<?php echo $infoUsuario['imagem']; ?>" class="img-circle" alt="User Image"/>
        </div>
        <div class="info txt-center" style="padding: 15px 0 0 0;">
            <p>Olá, <?php echo $_SESSION['adm_nome']; ?></p>
        </div>
    </div>

    <?php
    $arquivo = explode('?', $_SERVER['REQUEST_URI']);
    $arquivo = $arquivo[0];
    $arquivo = explode('/', $arquivo);
    $arquivo = $arquivo[count($arquivo) - 1];

    ?>
    <ul class="sidebar-menu">
        <?php $arquivos_menu = ['pop-minhas-tarefas.php']; ?>
        <li <?php echo ($arquivo === 'pop-minhas-tarefas.php') ? 'class="active"' : ''; ?>>
            <a href="pop-minhas-tarefas.php"><i class="fa fa-tasks"></i> <span>Minhas Tarefas</span></a>
        </li>

        <?php $arquivos_menu = ['pop-meus-pedidos.php']; ?>
        <li <?php echo ($arquivo === 'pop-meus-pedidos.php') ? 'class="active"' : ''; ?>>
            <a href="pop-meus-pedidos.php"><i class="fa fa-exchange"></i> <span>Meus Pedidos</span></a>
        </li>

        <?php $arquivos_menu = ['pop-meus-apontamentos.php']; ?>
        <li <?php echo ($arquivo === 'pop-meus-apontamentos.php') ? 'class="active"' : ''; ?>>
            <a href="pop-meus-apontamentos.php"><i class="fa fa-user"></i> <span>Meus Apontamentos</span></a>
        </li>
        <!--
		<li <?php echo ($arquivo === 'pop-projetos.php') ? 'class="active"' : ''; ?>><a href="pop-projetos.php"><i class="fa fa-angle-double-right"></i> Projetos Ativos</a></li>
		-->

        <?php if ($infoUsuario['administrador'] == AREA_ADMIN) {
        } ?>

        <?php $arquivos_monitor = ['pop-monitor.php', 'pop-monitor-facebook.php']; ?>
        <li class="treeview <?php if (in_array($arquivo, $arquivos_monitor, FALSE)) {
            echo 'active';
        } ?>">
            <a href="#">
                <i class="fa fa-bar-chart"></i> <span>Monitores</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu" style="display:block;">
                <li><a href="pop-monitor.php?tipo=16"><i class="fa fa-angle-double-right"></i> Email Marketing</a></li>
                <li><a href="pop-monitor.php?tipo=2"><i class="fa fa-angle-double-right"></i> Facebook</a></li>
                <li><a href="pop-monitor-tarefas.php"><i class="fa fa-angle-double-right"></i> Monitor de Tarefas</a>
                </li>
                <li><a href="pop-monitor.php?tipo=9"><i class="fa fa-angle-double-right"></i> Pedidos</a></li>
                <li><a href="pop-monitor.php?tipo=14"><i class="fa fa-angle-double-right"></i> Producao de Textos</a>
                </li>
                <li><a href="pop-monitor.php?tipo=24"><i class="fa fa-angle-double-right"></i> Pecas de Midia</a></li>
                <li><a href="pop-monitor.php?tipo=10"><i class="fa fa-angle-double-right"></i> Website</a></li>
                <!-- <li><a href="pop-monitor.php?tipo=11"><i class="fa fa-angle-double-right"></i> Campanha Display</a></li> -->

                <!-- <li><a href="pop-monitor.php?tipo=13"><i class="fa fa-angle-double-right"></i> Material Gráfico</a></li> -->


            </ul>
        </li>

        <?php
        /*
        $arquivos_menu = ['pop-tarefas.php', 'pop-projeto.php']; ?>
        <?php if ($infoUsuario['administrador'] == AREA_ADMIN) { ?>
            <li class="treeview <?php if (in_array($arquivo, $arquivos_menu, FALSE)) {echo 'active';} ?>">
                <a href="#">
                    <i class="fa fa-folder-open"></i> <span>Monitores Adm</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="pop-projetos.php?finalizado=1"><i class="fa fa-angle-double-right"></i> Projetos Finalizados</a></li>
                </ul>
            </li>
        <?php }
        */ ?>


        <li <?php if ($arquivo === 'main.php') {
            echo 'class="active"';
        } ?>>
            <a href="/backoffice/main.php"><i class="fa fa-arrow-left"></i> <span>Voltar para Back-Office</span></a>
        </li>
    </ul>
    <?php if ($_SERVER["SERVER_NAME"] == "dev.popflow.com.br" || $_SERVER["SERVER_NAME"] == "popv3.rotelok.nerdweb") { ?>
        <p class="message-servidor">
            <marquee scrollamount="5">Servidor de Teste, OS DADOS NESSE SERVIDOR PODEM SER REMOVIDOS A QUALQUER
                MOMENTO
            </marquee>
        </p>
    <?php } ?>
</section>
