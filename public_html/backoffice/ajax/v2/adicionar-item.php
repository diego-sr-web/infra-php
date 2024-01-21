<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">[TITULO-1]</h4>
        </div>
        <form action="" enctype="multipart/form-data" method="post">
            <input type="hidden" name="form" value="[INPUT-1]"/>
            <input type="hidden" name="elemento" value="[ELEMENTO_ID]"/>

            <div class="modal-body">
                <div class="form-group">
                    <?php
                    if (isset($options["show_textarea"]) && $options["show_textarea"] == TRUE) {
                        ?>
                        <label>[LABEL-TEXTAREA]</label>
                        <textarea name="[INPUT-TEXTAREA]" class="form-control"
                                  style="height:120px;">[CONTEUDO_TEXTAREA]</textarea>
                        <?php
                    }

                    if (isset($options["show_editor"]) && $options["show_editor"] == TRUE) {
                        ?>
                        <label>[LABEL-EDITOR]</label>
                        <textarea name="[INPUT-EDITOR]" class="form-control trumbowyg">[CONTEUDO_EDITOR]</textarea>
                        <?php
                    }

                    if (isset($options["show_arquivo"]) && $options["show_arquivo"] == TRUE) {
                        ?>
                        <label>[LABEL-ARQUIVO]</label>
                        <div class="input-group">
							<span class="input-group-btn">
								<span class="btn btn-primary btn-file btn-flat border-0">
									Procurar&hellip; <input type="file" class="upload-preview" name="[INPUT-ARQUIVO]">
								</span>
							</span>
                            <input type="text" class="form-control" disabled="disabled" readonly>
                        </div>
                        <p class="help-block">Max. 10MB</p>
                        <?php
                        if (isset($options["show_preview_arquivo"]) && $options["show_preview_arquivo"] == TRUE) {
                            ?>
                            <img class="img-preview" style="width:100%; display:none;" src="[PATH_ARQUIVO]"/>
                            <?php
                        }
                        ?>
                        <?php
                    }

                    if (FALSE) { ?>
                        <!-- Ainda falta implementar alguns tipos, esse bloco aqui serve de exemplo de como deve ser feito -->
                        <?php
                    }

                    if (isset($options["show_observacao"]) && $options["show_observacao"] == TRUE) {
                        ?>
                        <label>[LABEL-OBSERVACAO]</label>
                        <textarea name="[INPUT-OBSERVACAO]" class="form-control" style="height:120px;"></textarea>
                        <?php
                    }
                    ?>
                </div>
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

<?php if (isset($options["show_editor"]) && $options["show_editor"] == TRUE) { ?>
    <script type="text/javascript">
        $('.trumbowyg').trumbowyg();
    </script>
<?php } ?>
