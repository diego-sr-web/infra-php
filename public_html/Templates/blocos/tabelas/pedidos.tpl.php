<aside class="right-side">
    <section class="content-header">
        <h4 class="card-title">Lista de Pedidos</h4>
            <!--Não compreendi a necessidade desse trecho de codigo?
               
            <ol class="breadcrumb">
                <li><a class =" left    grey-text text-darken-4 card-title" href="#">Lista de pedidos</a></li>
                <li class=" left    grey-text text-darken-4 card-title">Meus Pedidos</li>
            </ol>
         
        -->

            <div class="page-filter">
                <ul>
                    <li <?php
                    if ((!isset($_GET['finalizados']))) {
                        echo 'class="active"';
                    }
                    ?>><a class=" waves-effect waves-light blue darken-3 btn left" href= "pop-meus-pedidos.php" ><i class="fa fa-pencil-square-o" ></i> PEDIDOS ATIVOS</a></li>
                    <li <?php if (isset($_GET['finalizados']) && $_GET['finalizados'] == 1) {
                        echo 'class="active"';
                    }
                    ?>>
                        <a class=" waves-effect waves-light btn cyan darken-2 right " href="pop-meus-pedidos.php?finalizados=1"> <i class="fa fa-check-square-o" aria-hidden="true"></i> FINALIZADOS</a></li>
                </ul>
            </div>

        </section>

        <section class="content">
            <?php 
                    /*require_once __DIR__ . include/sucesso_error.php;*/
                    include "include/sucesso_error.php";
            ?>

            <div class="row">
                <div class="col s12">
                    <div class="box box-solid ">
                        <div class="box-header">
                            <?php if ($finalizados) { ?>
                                <h5 class="box-title grey-text text-darken-5"><i class="fa fa-exchange"></i> &nbsp; Finalizados</h5>
                            <?php } else { ?>
                                <h5 class="box-title grey-text text-darken-3"><i class="fa fa-exchange"></i> &nbsp; Pedidos Ativos</h5>
                            <?php } ?>
                        </div>

                        <div class="box-body">
                            <div class="row">
                                <div class="col s12 filtros">
                                    <div class="row">
                                        <div class="col s4">
                                            <div class="acao-bts">
                                                
                                            </div>
                                        </div>
                                        <div class="col s8">
                                            <div class="filtro-btns">

                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /filtros -->
                            </div><!-- /row filtros -->
                        </div>
                        <h4 class="card-title">Lista de Clientes</h4>
                 
                 
                 
                        <div class="row">
                            <div class="col s12">
                                <table id="datatable-meuPedido" class="table table-striped table-hover tabela-meus-pedidos response" class="" style=" white-space: normal;" role="grid" aria-describedby="datatable-clientes_info">
                                    <thead>
                                    <tr>
                                        <th style="width:50px;">Tipo</th>
                                        <th>Projeto</th>
                                        <th>Cliente</th>
                                        <th>Pedido</th>
                                        <th>De</th>
                                        <th>Depto</th>
                                        <th>Para</th>
                                        <th>Área</th>
                                        <th>Prazo</th>
                                        <th>Prioridade</th>
                                        <th>Status</th>
                                        <th style="width: 60px;">Ações</th>
                                    </tr>
                                    </thead>
                                    <?php
                                    foreach ($elementosProjeto as $e) {
                                        $projeto = $POPProjeto->getProjectById($e['projeto']);
                                        $tipoProjeto = $POPProjeto->getProjectTypeByProjectId($projeto['projeto']);
                                        $status = $POPElemento->getElementStatusById($e["elementoStatus"]);
                                        $prioridade = $POPElemento->getPrioridadeById($e["campos"]["prioridade"]);
                                        $areaDe = $POPElemento->getAreaById($e["campos"]["De_Area"]);
                                        $area = $POPElemento->getAreaById($e["campos"]["area"]);

                                        $areaResponsavel = new area('area', $e['campos']['area'], $database);
                                        $prazo = new date('prazo', $e['campos']['prazo'], $database);
                                        $prioridade = new prioridade('prioridade', $e['campos']['prioridade'], $database);
                                        $status = new status('elementoStatus', $e['elementoStatus'], $database);

                                        if (!isset($area["nome"])) {
                                            $area["nome"] = "";
                                        }
                                        if (!isset($area["cor"])) {
                                            $area["cor"] = "";
                                        }

                                        ?>
                                        <tr class="tarefa-box">
                                            <td><span class="icone-tipo"
                                                        style="background-color: <?php echo $tipoProjeto['cor']; ?>;"><i
                                                            class="fa <?php echo $tipoProjeto['icone']; ?>"></i></span><span
                                                        style="display: none;"><?php echo $tipoProjeto['nome']; ?></span>
                                            </td>
                                            <td><?php echo $e["campos"]["Produto"]; ?></td>
                                            <td><?php echo $e["campos"]["Cliente"]; ?></td>
                                            <td><?php echo $e["campos"]["Nome"] ?></td>
                                            <td>
                                                <?php
                                                $responsavel = new responsavel('responsavel_de', $e['campos']['responsavel_de'], $database);
                                                echo $responsavel->retornaHtmlExibicao();
                                                ?>
                                            </td>
                                            <td><?php if ($areaDe) { ?><span class="dado-destaque"
                                                                                style="color: <?php echo $areaDe['cor']; ?>;"><?php echo $areaDe["nome"] ?></span><?php } ?>
                                            </td>
                                            <td>
                                                <?php
                                                $responsavel = new responsavel('responsavel', $e['campos']['responsavel'], $database);
                                                echo $responsavel->retornaHtmlExibicao();
                                                ?>
                                            </td>
                                            <td><?php echo $areaResponsavel->retornaHtmlExibicao(); ?></td>
                                            <td><?php echo $prazo->retornaHtmlExibicaoPrazo(); ?></td>
                                            <td><?php echo $prioridade->retornaHtmlExibicao(); ?></td>
                                            <td><?php echo $status->retornaHtmlExibicao(); ?></td>
                                            <td>
                                                <div class="projeto-btns" style="width: 100px">
                                                    <a href="pop-pedido.php?eid=<?php echo $e["elemento"]; ?>"
                                                        class="btn btn-flat btn-primary border-0" data-toggle="tooltip"
                                                        title="Ver Pedido">
                                                        <i class="fa fa-search"></i>
                                                    </a>
                                                    <button class="btn btn-flat btn-primary border-0 js-getTarefa"
                                                            data-toggle="modal" data-backdrop="static"
                                                            data-target="#modal-pedido" data-acao="arquivar-pedido"
                                                            data-tarefa="<?php echo $e["elemento"]; ?>"
                                                            title="Arquivar Pedido"><i class="fa fa-times"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </table><!-- /row pedidos -->
                            </div>
                        </div>                 
                    </div>
                </div>
            </div>
        </div>          
    </section>
</aside>
<!--
<script>
    $(document).ready(function() {
        $('#datatable-meuPedido').DataTable({
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
-->
