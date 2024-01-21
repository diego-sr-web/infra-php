<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">[TITULO-1]</h4>
        </div>
        <div class="modal-body box box-solid">
            <?php
            if (isset($options["show_conteudo"]) && $options["show_conteudo"] == TRUE) {
                ?>
                <div class="row">
                    [ELEMENTO_CONTEUDO]
                </div>
                <?php
            }
            ?>
            <div class="row" style="margin-top: 30px;">
                <div class="col-sm-12">
                    <h4>[TEXTO-1]</h4>
                    <form class="form-referencias" action="" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="form" value="[INPUT-1]"/>
                        <input name="elemento" value="[ELEMENTO_ID]" type="hidden">

                        <div class="form-group">
                            <label>[LABEL-2]</label>
                            <div class="input-group">
								<span class="input-group-btn">
									<span class="btn btn-primary btn-file btn-flat border-0">
										Procurar&hellip; <input type="file" class="upload-preview" name="[INPUT-2]"
                                                                multiple>
									</span>
								</span>
                                <input class="form-control" disabled="disabled" readonly="" type="text">
                            </div>
                            <p class="help-block">Max. 5MB</p>
                            <?php if (isset($options["show_image_preview"]) && $options["show_image_preview"] == TRUE) { ?>
                                <img class="img-preview" style="width:100%; display:none;" src=""/>
                            <?php } ?>
                        </div>
                        <div id="msg-erro"></div>
                        <div class="row">
                            <div class="col-lg-12">
                                <button class="btn btn-primary btn-flat border-0 pull-right" type="submit"><i
                                            class="fa [BOTAO_OK_ICON]"></i>[BOTAO_OK_TEXT]
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        if (jQuery.fn.lightGallery) {
            $('a.open-lightbox').lightGallery({
                selector: 'this'
            });
        }
    });
</script>
