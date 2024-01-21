<?php
require_once __DIR__ . "/../../../autoloader.php";
$database = new Database();
$Template = new Template();
$usuario = new AdmUsuario($database);
$campos = "usuarioNerdweb,nome,email,ativo";

$filtro = $_POST["filtro"] ?? "todos";
switch ($filtro) {
    case "ativo":
        $listaUsuarios = $usuario->listAll($campos,"nome", FALSE);
    break;
    case "inativo":
        $listaUsuarios = [];
        $tmp = $usuario->listAll($campos,"nome", TRUE);
        foreach ($tmp as $usr) {
            if ($usr["ativo"] == 0) {
                $listaUsuarios[] = $usr;
            }
        }
    break;
    default:
        $listaUsuarios = $usuario->listAll($campos, "nome");
        break;
}
?>
<style>

    .areas-atuacao .collapsible-header {
        padding: unset;
        background-color: unset;
        border: unset;
    }

    .areas-atuacao .multiplas-areas {
        display:flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .areas-atuacao .multiplas-areas i {
        margin-right: 10px;
    }

    .areas-atuacao li {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 9px;
    }

</style>
<h4 class="card-title">Lista de Usuários</h4>
<div class="row">
    <div class="col s12">
        <table id="datatable-usuarios"  class="display dataTable dtr-inline" style="width: 100%; white-space: nowrap;" role="grid" aria-describedby="datatable-usuarios_info">
            <thead>
            <tr role="row">
                <th data-field="id">ID</th>
                <th data-field="name">Nome</th>
                <th data-field="email">E-Mail</th>
                <th data-field="area">Áreas</th>
                <th data-field="status">Status</th>
                <th data-field="actions">Ações</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($listaUsuarios as $aux) {
                $areasUsuario = $usuario->getAreasUsuario($aux['usuarioNerdweb']);
                $maisDeUmaArea = count($areasUsuario) > 1;
                ?>
            <tr role="row" class="odd">
                <td><?php echo $aux['usuarioNerdweb']; ?></td>
                <td><?php echo $aux['nome']; ?></td>
                <td><?php echo $aux['email']; ?></td>
                    <td class="areas-atuacao">
                        <ul class="collapsible">
                            <li>
                                <?php if ($maisDeUmaArea) { ?>
                                    <div class="collapsible-header" onclick="changeSeeMore(this);">
                                        <p>Veja Mais</p>
                                        <i class="material-icons">arrow_drop_down</i>
                                    </div>
                                    <div class="collapsible-body">
                                        <?php foreach ($areasUsuario as $areaUsu) { ?>
                                            <div class="multiplas-areas">
                                                <i class="material-icons"
                                                   style="color:<?php echo $areaUsu['cor'] ?>;">lens</i>
                                                <span class="text-colapse"><?php echo $areaUsu['nome'] ?></span>
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php } else { ?>
                                    <i class="material-icons single-area"
                                       style="color:<?php echo $areasUsuario[0]['cor'] ?>;">lens</i>
                                    <p style="margin-left: 10px"><?php echo $areasUsuario[0]['nome'] ?></p>
                                <?php } ?>
                            </li>
                        </ul>
                    </td>
                <td>
                    <?php
                    echo "<div class='switch'>";
                    echo "<label>";
                    echo "Não";
                    if($aux['ativo'] == 1){
                        echo "<input type='checkbox' checked>";
                    }else{
                        echo "<input type='checkbox' >";
                    }
                    ?>
                    <span class='lever' onclick='statusUsuario(<?php echo $aux['usuarioNerdweb']; ?>, "statusUsuario")'></span>

                    <?php
                    echo "Sim";
                    echo "</label>";
                    ?>
                </td>
                <td>
                    <?php

                    $Template->insert("backoffice/botao-simples",
                        [
                            "acao" => "editar-usuario",
                            "secao" => "usuario",
                            "cor"=> "btn-primary",
                            "icone" => "material-icons",
                            "nomeIcone" => "edit",
                            "id" =>  $aux['usuarioNerdweb']
                        ]
                    );
                    ?>
                </td>
            </tr>
            <?php } ?>
            </tbody>
            <tfoot>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>E-Mail</th>
                <th>Áreas</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
            </tfoot>
        </table>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#datatable-usuarios').DataTable({
            "responsive": true,
            "order": [
                [2, 'asc']
            ],
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            "bLengthChange": false,
            "bFilter": true,
        });
        $('.collapsible').collapsible({
            accordion: true
        });
    });

    function changeSeeMore(objeto){
        let textObj = objeto.getElementsByTagName('p')[0].innerHTML;
        objeto.getElementsByTagName('p')[0].innerHTML = (textObj === "Veja Mais") ? "Veja Menos" : "Veja Mais";
    }
</script>
