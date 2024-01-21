<h5>Apontamentos</h5>
    <!--
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-exchange"></i> Apontamentos</a></li>
        <li class="active">Meus Apontamentos</li>
    </ol>
    -->

 <?//php require_once __DIR__ . "/include/sucesso_error.php"; ?>

    <div class="row">
        <div class="col s12">
            <div class="box box-solid">
                <div class="box-body table-responsive no-padding">
                    <table id="datatable-meuPedido" class="table table-striped table-hover tabela-apontamentos response"  style=" white-space: nowrap; width: 100%" role="grid" aria-describedby="datatable-clientes_info">
                            <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Titulo</th>
                                <th>Cliente</th>
                                <th>Área</th>
                                <th>Tempo</th>
                                <th>Data</th>
                                <th>Ações</th>
                            </tr>
                            </thead>
                            <?php
                            foreach ($elementosProjeto as $e) {
                            $area = $POPElemento->getAreaById($e["campos"]["area"]);
                            if (!isset($area["nome"])) {
                                $area["nome"] = "";
                            }
                            if (!isset($area["cor"])) {
                                $area["cor"] = "";
                            }
                            ?>
                            <tr class="tarefa-box">

                                <td><span class="icone-tipo" style="background-color: #3c8dbc;"><i class="fa fa-user"></i></span>
                </div>
                    </td>
                    <td><?php echo $e["campos"]["titulo"] ?></td>
                    <td><?php echo $e["campos"]["cliente"]; ?></td>
                    <td><?php echo $area["nome"]; ?></td>
                    <td><?php echo $e["campos"]["tempo"] ?></td>
                    <td><?php echo date('Y-m-d', strtotime($e["dataAtualizacao"])); ?></td>
                    <td>
                        <div class="projeto-btns" style="width: 100px">
                            <a href="pop-horas.php?eid=<?php echo $e["elemento"]; ?>"
                                class="btn btn-flat btn-primary border-0" data-toggle="tooltip"
                                title="Ver Apontamento">
                                <i class="fa fa-search"></i>
                            </a>
                        </div>
                    </td>
                    </tr>
                    <?php } ?>
                    </table><!-- /row pedidos -->
            </div>
        </div>
    </div>
</div>
