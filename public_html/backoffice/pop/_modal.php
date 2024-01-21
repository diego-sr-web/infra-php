<?php
require_once __DIR__ . "/../../autoloader.php";
require_once __DIR__ . "/../_init.inc.php";
?>

<!DOCTYPE html>
<html>

<head>
    <!--Datatables-->
    <link rel="stylesheet" type="text/css" href="/assets/css/pages/data-tables.css">
    <!--Datatables-->
    <?php 
    $Template->insert("backoffice/materialize-head", ["pageTitle" => "Blank Page | Backoffice Nerdweb"]);    
    ?>
</head>
<header class="page-topbar" id="header">
        <?php $Template->insert("backoffice/materialize-header"); ?>
 </header>

<div id="top-of-page" class="nav-wrapper row-offcanvas row-offcanvas-left">

    <!-- ################### Nav-Lateral ################## -->
         <!-- BEGIN: SideNav-->
         <?php  $Template->insert("backoffice/materialize-sidebar"); ?>
        <!-- END: SideNav-->
    <!-- ################### Nav-Lateral ################## -->

    <div id="main">
        <div class="row">
            <div class="pt-2 pb-0" id="breadcrumbs-wrapper">
                <!-- header clientes -->
                <div class="container">
                    <div class="row">
                        <div class="col s12 m6 l6">
                            <h4 class="mt-0 mb-0"><span></span></h4>
                        </div>
                        <div class="col s12 m6 l6 right-align-md">
                            <ol class="breadcrumbs mt-0 mb-0">
                                <li class="breadcrumb-item"><a href="main.php">Home</a>
                                </li>
                                <li class="breadcrumb-item active">Modal
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <p>
            <div class="col s12">
                <div class="container">
                <div class="col s3">
                <!-- adicionar Pedido Botão -->
                <button 
                href="#novohora";
                id="bt0";
                class="waves-effect waves-light btn red mt-1  w100 mt-md-1 modal-trigger js-modal">
                    <i class="fa fa-plus-circle" aria-hidden="true"></i>
                    Adicionar novo pedido
                </button>

                </div>

                <div class="col s3">
                <!-- adicionar Pedido Botão -->
                <button
                id="bt1";
                class="waves-effect waves-light btn black mt-1 right w100 mt-md-1 modal-trigger js-modal"
                    data-toggle="modal" 
                    data-target="#modal-pedido" 
                    data-backdrop="static"     
                    data-acao="adicionar-cliente">
                    <i class="fa fa-plus-circle" aria-hidden="true"></i>
                    Adicionar novo pedido
                </button>

                </div>
                
                <div class="col s3">
                <!-- adicionar Pedido Botão -->
                <button 
                id="bt2";
                class="waves-effect waves-light btn purple mt-1 right w100 mt-md-1 modal-trigger js-modal"
                    data-toggle="modal" 
                    data-target="#modal-pedido" 
                    data-backdrop="static"     
                    data-acao="adicionar-cliente">
                    <i class="fa fa-plus-circle" aria-hidden="true"></i>
                    
                    Adicionar novo pedido
                </button>

                </div>
                </div>          
            </div>

            <!--Main-->            
            <div class="col s12">
                <div class="container">
                    <div class="section section-data-tables">
                        <!-- Meus Pedidos -->
                        <div class="row">
                            <div class="col s12">
                                <div class="card">
                                    <div class="card-content" id="tabela">

                                    <div id="novo" class="modal-dialog">
<h6>O que é Lorem Ipsum? 
Lorem Ipsum é simplesmente uma simulação de texto da indústria tipo
gráfica e de impressos, e vem sendo utilizado desde o século XVI, quando um imp
ressor desconhecido pegou uma bandeja de tipos e os embaralhou para fazer um livro de mod
elos de tipos. Lorem Ipsum sobreviveu não só a cinco séculos, como também ao salto para a edit
oração eletrônica, permanecendo essencialmente inalterado. Se popularizou na década de 60, quando a Letras

et lançou decalques contendo passagens de Lorem Ipsum, e mais recentemente quando passou a ser integrado a softw
ares de editoração eletrônica como Aldus PageMaker.
Porque nós o usamos?

É um fato conhecido de todos que um leitor se distrairá com o conteúdo de texto legível de uma página quando
estiver examinando sua diagramação. A vantagem de usar Lorem Ipsum é que ele tem uma distribuição normal de
letras, ao contrário de "Conteúdo aqui, conteúdo aqui", fazendo com que ele tenha uma aparência similar a de
um texto legível. Muitos softwares de publicação e editores de páginas na internet agora usam Lorem Ipsum como t
exto-modelo padrão, e uma rápida busca por 'lorem ipsum' mostra vários websites ainda em sua fase de construção.
Várias versões novas surgiram ao longo dos anos, eventualmente por acidente, e às vezes de propósito (injetando hu
mor, e coisas do gênero).</h6>                              
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <div class="content-overlay"></div>
            <!-- END: Page Main -->
       
        </div>
    </div>
    
    <!-- END: Footer-->
    <?php $Template->insert("backoffice/materialize-footer"); ?>
    <!-- BEGIN: SCRIPTS-->    
    <?php $Template->insert("backoffice/materialize-scripts"); ?>

</div>

<!--######################################### MODAL novo-hora.php #############################################-->

<div class="modal" id="novohora">
            <!--fechar modal-->   
        <div class="row modal-head">
            <a href="#!" class="modal-action modal-close right waves-effect waves-teal btn-flat"><i class="fa fa-window-close " aria-hidden="true"></i> </a>
        </div>   

    <!--Formulario modal-->
    <div class="modal-content">  

           
        <!--topo-->
        <div class="modal-header card-title center-align">
            <i class="medium material-icons indigo-text text-darken-2 ">access_time</i>
            <h4>Apontar tempo Job</h4>
        </div>
        <!--formulario-->
        <form action="" enctype="multipart/form-data" method="post" id="formApontarJob">
                <input name="form" type="hidden" value="novo-hora"/>
                <!--Main Form-->
                <div class="row">
                    <div class="col s12">   
                    <!--Conteudo-->
                      
                        <!--Titulo-->   
                        <div class="input-field col s12">
                            <label for="horaTitulo" class="card-title">Titulo *</label>
                            <input id="horaTitulo" name="horaTitulo" type="text" data-error=".errorTxt1">
                            <small class="errorTxt1"></small>
                        </div>                                            
                        <!--Seleção-->
                        <div class="input-field col s12">
                            <h6><label for="horaSelect">Área *</label></h6>
                            <select class="icons" id="horaSelect" name="horaSelect">
                                <option value="" disabled selected> Selecione um Cliente </option>
                                <option value="1">Roca</option>
                                <option value="2">NerdWeb</option>
                                <option value="3">Enalta</option>
                            </select>
                            [CLIENTE_PEDIDO]
                            <small class="erroTxt2"></small>
                        </div>

                        <!--area-->
                        <div class="input-field col s12  custom-radio">
                            <div class="row">
                                <div class="col-xs-12">
                                    <label >Área*</label>
                                </div>
                                [AREA_USUARIO]
                                <h6>Confirmar com Rafael este campo.</h6>
                            </div>
                        </div> 

                        <!--Add Arquivo-->
                        <label for="horaBuscar">Adicionar arquivos</label>
                        <div id="horaBuscar" class="input-field col s12">
                            <div class="file-field input-field">
                                <div class="btn indigo lighten-3">
                                    <span><i class="fa fa-search" aria-hidden="true"></i>  Procurar </span>
                                    <input class="upload-preview" multiple name="arquivos[]" type="file">
                                </div>
                                <div class="file-path-wrapper">
                                    <input class="file-path validate" type="text">
                                    <p class="right"> Max. 5MB </p>
                                </div>
                            </div>  
                        </div>
                       
                        <!--Textarea-->
                        <div class="row">
                            <div class="input-field col s12">
                            <textarea id="horaDescricao" name="horaDescricao" class="" data-error=".errorTxt3" data-length="1500"></textarea>
                            <label for="horaDescricao" class=""> Detalhe seu Job *</label>
                            <small class="errorTxt3"></small>
                        </div>

                        <!--Horas Trabalhadas-->
                        <div class="input-field col s12">
                            <label for="horaHora" class="card-title"><h6>Tempo Trabalhado</h6></label>
                            <input id="horaHora" name="horaHora" autocomplete="off" class="col s2" name="tempo-em-minutos"
                            placeholder="Ex:30min." type="number" value="" data-error=".errorTxt4">
                            <h5>Minutos.</h5>
                            <div class="col s12" >*Exemplo: 1 hora = 60 minutos</div>
                            <small class="errorTxt4"></small>
                        </div>                    

                    </div><!--Conteudo-->
                </div><!--End Main-->
                <!--Botões de enviar-->
                <div class="col s12">
                    <div class="modal-footer">
                        <button class="red lighten-2 waves-effect waves-red btn" data-dismiss="modal" type="reset">
                            <i class="fa fa-times"></i> Cancelar
                        </button>
                        <button class="teal waves-effect waves-green btn" type="submit">
                            <i class="fa fa-save"></i> Salvar
                        </button>
                        <p>
                    </div>
                </div>
        </form>
    </div><!--ROW-->           
</div>

<!-- END: SCRIPTS-->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.min.js" ></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/additional-methods.min.js" ></script>

<script type="text/javascript">
//Textarea efeito de contagem de Caracteres
$(document).ready(function() {
    $('input#input_text, textarea#horaDescricao').characterCounter();
    });

//Validação de Formulário
    $("#formApontarJob").validate({
    rules: {
        horaTitulo: {
                required: true,
                minlength: 2
            },
            horaSelect: {
                required: true,
            },

            horaDescricao: {
                required: true,
                min: 1,
            },
            horaHora: {
                required: true,
                min: 1,
            },
            
        },

        messages: {
            horaTitulo: {
                required: "Por favor preencha este campo.",
                minlength: "O Campo nome deve ter no minimo 5 caracteres."
            },
            horaHora: {
                required: "Aponte os minutos trabalhado.",
                min: "Digite corretamente seus minutos trabalhados.",
            },       
            horaDescricao: {
                required: "Por favor preencha este campo.",
            },
            horaSelect: {
                required: "Selecione um cliente.",
               
            },
           
        },
        errorElement : 'div',
        errorPlacement: function(error, element) {
        var placement = $(element).data('error');
        if (placement) {
                $(placement).append(error)
            } else {
                error.insertAfter(element);
            }
        }
    });

</script>

<!-- SCRIPTS DA PAGINA -->


</body>
</html>
