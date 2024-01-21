<?php
require_once __DIR__ . "/../../../autoloader.php";
$database = new Database();
$Template = new Template();
$usuario = new AdmUsuario($database);
$campos = "area,nome,cor,hidden";

$filtro = $_POST["filtro"] ?? "todos";
switch ($filtro) {
    case "inativo":
        $listaAreas = $usuario->listArea($campos, TRUE);
        break;
    case "ativo":
        $listaAreas = [];
        $tmp = $usuario->listArea($campos, FALSE);
        foreach ($tmp as $usr) {
            if ($usr["hidden"] == 0) {
                $listaAreas[] = $usr;
            }
        }
        break;
    default:
        $listaAreas = array_merge($usuario->listArea($campos, TRUE), $usuario->listArea($campos, FALSE));
        break;
}
?>
<h4 class="card-title">Lista de Áreas</h4>
<div class="row">
    <div class="col s12">
        <table id="datatable-areas" class="display dataTable dtr-inline" style="width: 100%; white-space: nowrap;" role="grid" aria-describedby="datatable-areas_info">
            <thead>
            <tr role="row">
                <th data-field="id">ID</th>
                <th data-field="">Cor</th>
                <th data-field="">Nome</th>
                <th data-field="">Visibilidade</th>
                <th data-field="actions">Ações</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($listaAreas as $aux) { ?>
            <tr role="row" class="odd">
                <td><?php echo $aux['area']; ?></td>
                <td><i class="material-icons" style="font-size: 25px; color: <?php echo $aux['cor']; ?>;">lens</i></td>
                <td><?php echo $aux['nome']; ?></td>
                <td>
                    <?php
                        echo "<div class='switch'>";
                        echo "<label>";
                        echo "Oculta";
                        if($aux['hidden'] == 0){
                            echo "<input type='checkbox' checked>";
                        }else{
                            echo "<input type='checkbox' >";
                        }
                        ?>
                        <span class='lever' onclick='statusArea(<?php echo $aux['area']; ?>, "statusArea")'></span>

                        <?php
                        echo "Visivel";

                        echo "</label>";
                    ?>
                </td>
                <td>
                    <?php
                    $Template->insert("backoffice/botao-simples",
                        [
                            "acao" => "editar-area",
                            "secao" => "area",
                            "cor"=> "btn-primary",
                            "icone" => "material-icons",
                            "nomeIcone" => "edit",
                            "id" =>  $aux['area']
                        ]
                    );
                    ?>
                </td>
            </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#datatable-areas').DataTable({
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
    });
</script>
