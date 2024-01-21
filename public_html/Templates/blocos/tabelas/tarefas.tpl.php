<?php
require_once __DIR__ . "/../../../autoloader.php";
$filtro = $_POST["filtro"] ?? "todas";
$database =    new Database();
$Template =    new Template();
$POPElemento = new Elemento($database);
$POPUsuario =  new AdmUsuario($database);
$BNClientes =  new BNCliente($database);

$areasUsuario = $POPUsuario->getAreasUsuario($_SESSION['adm_usuario']);

$areasId = [];
foreach ($areasUsuario as $area) {
    $areasId[] = $area['area'];
}
$filtrados = [];
switch ($filtro) {
    case "todas":
        $filtrados = $POPElemento->getAllElementsFiltered(TRUE, $areasId);
        break;
    case "minhas":
        $filtrados = $POPElemento->getAllElementsFiltered(TRUE, $areasId, $_SESSION["adm_usuario"]);
        break;
    default:
        break;
}
if (!empty($filtrados)) {
    $auxTiposProjeto = $POPProjeto->getProjectTypeList(['escondido'], [0], 'nome ASC');
    foreach ($auxTiposProjeto as $tipo) {
        $listaTiposProjeto[$tipo['projetoTipo']] = $tipo;
    }
    $clientes = $BNClientes->listAll();
    //$status = $POPElemento->
    foreach ($filtrados as $elemento) {

    }
}
?>

<div class="box box-solid">
    <div class="box-body table-responsive">
        <table id="datatable-tarefas" class="display" style="width: 100%">
            <thead>
            <tr>
                <th data-field="">Tipo</th>
                <th data-field="">Projeto</th>
                <th data-field="">Cliente</th>
                <th data-field="">Tarefa</th>
                <th data-field="">Prazo</th>
                <th data-field="">Prioridade</th>
                <th data-field="actions">Ações</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($filtrados as $aux) { ?>
                <tr>
                    <td><?php echo $aux['elementoTipo']; ?></td>
                    <td><?php echo $aux['projeto']; ?></td>
                    <td><?php echo $aux['campos']["cliente"]; ?></td>
                    <td><?php echo $aux['campos']["Nome"]; ?></td>
                    <td><?php echo $aux['campos']["prazo"]; ?></td>
                    <td><?php echo $aux["campos"]["prioridade"] ?></td>
                    <td>
                        <?php
                        $Template->insert("backoffice/botao-simples",
                            [
                                "acao" => "placeholder",
                                "secao" => "placeholder",
                                "cor"=> "btn-primary",
                                "icone" => "material-icons",
                                "nomeIcone" => "remove_red_eye",
                                "id" =>  $aux['elemento']
                            ]
                        );
                        ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
            <tfoot>
            <tr>
                <th data-field="">Tipo</th>
                <th data-field="">Projeto</th>
                <th data-field="">Cliente</th>
                <th data-field="">Tarefa</th>
                <th data-field="">Prazo</th>
                <th data-field="">Prioridade</th>
                <th data-field="actions">Ações</th>
            </tr>
            </tfoot>
        </table>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#datatable-tarefas').DataTable({
            "responsive": true,
            "order": [
                [1, 'asc']
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
