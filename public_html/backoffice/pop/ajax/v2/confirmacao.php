<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">[TITULO-1]</h4>
        </div>
        <form action="" enctype="multipart/form-data" method="post">
            <input type="hidden" name="form" value="[INPUT-1]"/>
            <input type="hidden" name="elemento" value="[ELEMENTO_ID]"/>
            <input type="hidden" name="aprovacao" value="1"/>

            <div class="modal-body">
                <div class="txt-center" style="padding: 20px;">
                    [TEXTO-1]
                </div>
                <?php if (isset($options["show_observacao"]) && $options["show_observacao"] == TRUE) { ?>
                    <div class="form-group">
                        <label>*Observações</label>
                        <textarea name="observacao" class="form-control" style="height:120px;"></textarea>
                    </div>
                <?php } ?>
            </div>
            <div class="modal-footer clearfix">
                <button type="button" class="btn btn-danger btn-flat border-0 pull-left" data-dismiss="modal"><i
                            class="fa [BOTAO_CANCELAR_ICON]"></i> [BOTAO_CANCELAR_TEXT]
                </button>
                <button type="submit" class="btn btn-primary btn-flat border-0"><i class="fa [BOTAO_OK_ICON]"></i>
                    [BOTAO_OK_TEXT]
                </button>
            </div>
        </form>
    </div>
</div>
