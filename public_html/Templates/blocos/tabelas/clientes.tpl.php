<?php
require_once __DIR__ . "/../../../autoloader.php";
$database = new Database();
$Template = new Template();
$BNCliente = new BNCliente($database);
$campos = "cliente,nomeFantasia,responsavel,email,ativo,dataEntrada";

$filtro = $_POST["filtro"] ?? "todos";
$listaClientes = [];
switch ($filtro) {
    case "ativo":
        $tmp = $BNCliente->listAll($campos, "nomeFantasia");
        foreach ($tmp as $usr) {
            if ($usr["ativo"] == 1) {
                $listaClientes[] = $usr;
            }
        }
        break;
    case "inativo":
        $tmp = $BNCliente->listAll($campos, "nomeFantasia");
        foreach ($tmp as $usr) {
            if ($usr["ativo"] != 1) {
                $listaClientes[] = $usr;
            }
        }
        break;
    default:
        $listaClientes = $BNCliente->listAll($campos, "nomeFantasia");
        break;
}
?>
<h4 class="card-title">Lista de Clientes</h4>
<div class="row">
    <div class="col s12">
        <table id="datatable-clientes"  class="display dataTable dtr-inline" style="width: 100%; white-space: nowrap;" role="grid" aria-describedby="datatable-clientes_info">
            <thead>
            <tr role="row">
                <th>ID</th>
                <th>Marca Fantasia</th>
                <th>Responsável</th>
                <th>Email de contato</th>
                <th>Ativo</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($listaClientes as $aux) {

            $ativo = $aux['ativo'];
            $acaoStatus = 'statusCliente';
            ?>
            <tr role="row" class="odd">
                <td><?php echo $aux['cliente']; ?></td>
                <td><?php echo $aux['nomeFantasia']; ?></td>
                <td><?php echo $aux['responsavel']; ?></td>
                <td><?php echo $aux['email']; ?></td>
                <td>
                    <?php
                    echo "<div class='switch'>";
                    echo "<label>";
                    echo "Não"; /*nao*/
                    if($aux['ativo'] == 1){
                        echo "<input type='checkbox' checked>";
                    }else{
                        echo "<input type='checkbox' >";
                    }
                    ?>
                    <span class='lever' onclick='statusCliente(<?php echo $aux['cliente']; ?>, "statusCliente")'></span>

                    <?php
                    echo "Sim";/*sim*/
                    echo "</label>";
                    ?>
                </td>
                <td>
                    <?php
                    $Template->insert("backoffice/botao-simples",
                        [
                            "acao" => "editar-cliente",
                            "secao" => "cliente",
                            "cor"=> "btn-primary",
                            "icone" => "material-icons",
                            "nomeIcone" => "edit",
                            "id" => $aux['cliente']
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
<script>
    $(document).ready(function() {
        $('#datatable-clientes').DataTable({
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

