<!-- MODAL GERAL PROJETO -->
<div class="modal fade" id="modal-projeto" tabindex="-1" role="dialog" aria-hidden="true"></div>

<!-- MODAL GERAL ELEMENTO -->
<div class="modal fade" id="modal-elemento" tabindex="-1" role="dialog" aria-hidden="true"></div>

<!-- BOTÃO VOLTAR PARA O TOPO -->
<a href="#top-of-page">
    <div class="back-to-top bt-footer-hidden" data-toggle="tooltip" data-placement="top" title="Voltar para o topo">
        <i class="fa fa-arrow-up"></i>
    </div>
</a>

<?php if ($fazerTarefa) { ?>
    <!-- BOTÃO FINALIZAR TAREFA -->
    <a href="pop-minhas-tarefas.php?finaliza_tarefa=<?php echo $_GET['tarefa']; ?>">
        <div class="bt-finaliza-tarefa bt-footer-hidden" data-toggle="tooltip" data-placement="top"
             title="Finalizar tarefa">
            <i class="fa fa-check"></i>
        </div>
    </a>
<?php } ?>

<script type="text/javascript">
    var idUsuarioLogado = '<?php echo $dadosUsuario["usuarioNerdweb"]; ?>';
</script>

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="//code.jquery.com/ui/1.11.1/jquery-ui.min.js" type="text/javascript"></script>
<script src="../js/plugins/bootstrap/bootstrap.min.js" type="text/javascript"></script>
<script src="../js/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>
<script src="../js/plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="../js/plugins/datepicker/locales/bootstrap-datepicker.pt-BR.js" type="text/javascript"></script>
<script src="../js/plugins/iCheck/icheck.min.js" type="text/javascript"></script>
<script src="../js/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="../js/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
<script src="../js/plugins/trumbowyg/trumbowyg.min.js" type="text/javascript"></script>
<script src="../js/plugins/masonry/masonry.js" type="text/javascript"></script>
<script src="../js/plugins/masonry/imagesloaded.js" type="text/javascript"></script>
<script src="../js/plugins/light-gallery/js/lightgallery.min.js" type="text/javascript"></script>
<script src="../js/plugins/light-gallery/modules/lg-zoom.min.js" type="text/javascript"></script>
<script src="../js/plugins/light-gallery/modules/lg-video.min.js" type="text/javascript"></script>
<script src="../js/AdminLTE/app.js" type="text/javascript"></script>
<script src="../js/jqueryform.js" type="text/javascript"></script>
<script src="../js/backoffice.js" type="text/javascript"></script>
<script src="../js/pop.js" type="text/javascript"></script>
