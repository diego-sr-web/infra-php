<?php
$infoAreaUsuario = $usuario->getInfoArea2($dadosUsuario['area']);
?>

<header class="header">
    <a href="index.php" class="logo">
        <!-- Add the class icon to your logo image or logo icon to add the margining -->
        <img src="/backoffice/img/logo-header.png" style="width: 90%;"/>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>
        <div class="navbar-right">
            <ul class="nav navbar-nav">
                <?php
                $listaAreas = $usuario->getInfoArea2(0, TRUE);
                $listaAreas = array_slice($listaAreas, 1, count($listaAreas));
                $listaUsuario = $usuario->listAll("", "nome", FALSE);
                ?>
                <li class="dropdown user user-menu area-select">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-users"></i>
                        <span> <i class="caret"></i></span>
                    </a>
                    <ul class="dropdown-menu">
                        <?php
                        foreach ($listaUsuario as $user) {
                            $areasUsuario_tmp = $usuario->getAreasUsuario($user['usuarioNerdweb']);
                            $areasId_tmp = [];
                            foreach ($areasUsuario_tmp as $area) {
                                $areasId_tmp[] = $area['area'];
                            }

                            $show = FALSE;
                            foreach ($areasId as $aid) {
                                if (in_array($aid, $areasId_tmp)) {
                                    $show = TRUE;
                                }
                            }

                            if ($show) {
                                if ($user["usuarioNerdweb"] == $_SESSION['adm_usuario']) {
                                    ?>
                                    <li><p>
                                            <b><a href="pop-monitor-tarefas.php?uid=<?php echo $user['usuarioNerdweb']; ?>"><?php echo $user['nome']; ?></a></b>
                                        </p></li>
                                    <?php
                                }
                                else {
                                    ?>
                                    <li><p>
                                            <a href="pop-monitor-tarefas.php?uid=<?php echo $user['usuarioNerdweb']; ?>"><?php echo $user['nome']; ?></a>
                                        </p></li>
                                    <?php
                                }
                            }
                        }
                        ?>
                        <!--<li><p><a href="include/muda-usuario.php?uid=-1">Limpar Usuário Temporário</a></p></li>-->
                    </ul>
                </li>
                <li class="dropdown user user-menu area-select">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bar-chart"></i>
                        <span><i class="caret"></i></span>
                    </a>
                    <ul class="dropdown-menu">
                        <?php
                        function nomeSort($a, $b) {
                            return strcmp($a["nome"], $b["nome"]);
                        }

                        usort($listaAreas, "nomeSort");

                        foreach ($listaAreas as $aux) {
                            if (in_array($aux["area"], $areasId)) {
                                ?>
                                <li><p>
                                        <b><a href="pop-monitor-tarefas.php?aid=<?php echo $aux['area']; ?>"><?php echo $aux['nome']; ?></a></b>
                                    </p></li>
                                <?php
                            }
                            else {
                                ?>
                                <li><p>
                                        <a href="pop-monitor-tarefas.php?aid=<?php echo $aux['area']; ?>"><?php echo $aux['nome']; ?></a>
                                    </p></li>
                                <?php
                            }
                        }
                        ?>
                        <!--<li><p><a href="include/muda-area.php?area=-1">Limpa Área Temporária</a></p></li>-->
                    </ul>
                </li>

                <li class="dropdown user user-menu user-name">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="glyphicon glyphicon-user"></i>
                        <span><i class="caret"></i></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header bg-light-blue">
                            <img src="<?php echo $dadosUsuario['imagem']; ?>" class="img-circle" alt="User Image"/>
                            <p>
                                <?php echo $dadosUsuario['nome']; ?>
                                <?php foreach ($areasUsuario as $aux) { ?>
                                    <small><?php echo $aux['nome']; ?></small>
                                <?php } ?>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="/backoffice/alterar-dados.php" class="btn btn-default btn-flat">Meus Dados</a>
                            </div>
                            <div class="pull-right">
                                <a href="/backoffice/logout.php" class="btn btn-default btn-flat">Sair</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
