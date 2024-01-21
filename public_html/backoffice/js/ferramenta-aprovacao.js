ajaxUrl = '../ferramenta-aprovacao/ajax/ajax-aprovacao.php';

$(document).on('click', '.projeto-click', function (event) {
    event.preventDefault();
    idModal = $(this).attr('data-target');
    idProj = $(this).attr('data-projeto');

    tipoAcao = false;

    switch (idModal) {
        case '#modal-projeto':
            tipoAcao = 'getProjeto';
            break;
        case '#modal-editar-projeto':
            tipoAcao = 'edtProjeto';
            break;
        case '#modal-novo-briefing':
            $(idModal + ' input[name=projeto]').val(idProj);
            tipoAcao = false;
            break;
        case '#modal-briefing':
            tipoAcao = 'getProposta';
            break;
        case '#modal-editar-briefing':
            tipoAcao = 'edtProposta';
            break;

        default:
            break;
    }

    if (tipoAcao) {
        $(idModal + ' .modal-dialog .modal-content').hide();

        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: {
                tipo: tipoAcao,
                projeto: idProj
            },
        })
            .done(function (retorno) {
                if (retorno.tipo == 'html') {
                    $(idModal + ' .modal-dialog .modal-content').html(retorno.conteudo);
                    $(idModal + ' .modal-dialog .modal-content').show();
                } else {
                    console.log(retorno);
                }
            })
            .fail(function (retorno) {
                console.log(retorno);
            });
    }
});


$(document).on('click', '.itemProjeto-click', function (event) {
    event.preventDefault();
    idModal = $(this).attr('data-target');
    idItemProj = $(this).attr('data-itemProjeto');

    tipoAcao = false;

    switch (idModal) {
        case '#modal-itemProjeto':
            tipoAcao = 'getItemProjeto';
            break;
        case '#modal-editar-itemProjeto':
            tipoAcao = 'edtItemProjeto';
            break;
        case '#modal-versoes-itemProjeto':
            tipoAcao = 'versoesItemProjeto';
            break;
        case '#modal-comentario':
            tipoAcao = 'comentarioItemProjeto';
            break;
        case '#modal-novo-briefing-itemProjeto':
            $(idModal + ' input[name=itemProjeto]').val(idItemProj);
            tipoAcao = false;
            break;
        case '#modal-briefing-itemProjeto':
            tipoAcao = 'getPropostaItemProjeto';
            break;
        case '#modal-editar-briefing-itemProjeto':
            tipoAcao = 'edtPropostaItemProjeto';
            break;

        default:
            break;
    }

    if (tipoAcao) {
        $(idModal + ' .modal-dialog .modal-content').hide();

        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: {
                tipo: tipoAcao,
                itemProjeto: idItemProj
            },
        })
            .done(function (retorno) {
                if (retorno.tipo == 'html') {
                    $(idModal + ' .modal-dialog .modal-content').html(retorno.conteudo);
                    $(idModal + ' .modal-dialog .modal-content').show();
                } else {
                    console.log(retorno);
                }
            })
            .fail(function (retorno) {
                console.log(retorno);
            });
    }
});


$(document).on('click', '.categoria-click', function (event) {
    event.preventDefault();
    idModal = $(this).attr('data-target');
    idCategoria = $(this).attr('data-categoria');

    tipoAcao = false;

    switch (idModal) {
        case '#modal-categoria':
            tipoAcao = 'getCategoria';
            break;
        case '#modal-editar-categoria':
            tipoAcao = 'edtCategoria';
            break;
        case '#modal-apagar-categoria':
            tipoAcao = 'delCategoria';
            break;
        default:
            break;
    }

    if (tipoAcao) {
        $(idModal + ' .modal-dialog .modal-content').hide();

        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: {
                tipo: tipoAcao,
                categoria: idCategoria
            },
        })
            .done(function (retorno) {
                if (retorno.tipo == 'html') {
                    $(idModal + ' .modal-dialog .modal-content').html(retorno.conteudo);
                    $(idModal + ' .modal-dialog .modal-content').show();
                } else {
                    console.log(retorno);
                }
            })
            .fail(function (retorno) {
                console.log(retorno);
            });
    }
});


$(document).on('click', '.notificacao-click', function (event) {
    //event.preventDefault();
    idNotificacao = $(this).attr('data-notificacao');
    tipoAcao = 'notificacaoLida';

    if (tipoAcao) {
        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: {
                tipo: tipoAcao,
                notificacao: idNotificacao
            },
        })
            .done(function (retorno) {
                if (retorno.tipo == 'boolean' && retorno.conteudo == true) {
                    return true;
                } else {
                    console.log(retorno);
                    return false;
                }
            })
            .fail(function (retorno) {
                console.log(retorno);
            });
    }
});


$(document).on('submit', '.form-comentario', function (event) {
    event.preventDefault();
    tipoAcao = 'salvaComentario';
    elemento = this;

    var options = {
        url: ajaxUrl,
        data: {tipo: tipoAcao},
        clearForm: true,
        resetForm: true,
        //target:        '#output1',
        beforeSubmit: function (formData, jqForm, options) {
            $('#msg-erro').html('');
            var form = jqForm[0];
            if (!form.comentario.value) {
                msg = '<div class="callout callout-danger" style="text-align: center;"><p>Digite um comentário.</p></div>';
                $('#msg-erro').html(msg);
                return false;
            }
            return true;
        },
        success: function (responseText, statusText, xhr, $form) {
            if (responseText.tipo == 'html') {
                $('.sem-comentarios').hide();
                if ($(elemento).attr('data-fix') == 1) {
                    $('#chat-box2').append(responseText.conteudo);
                } else {
                    $('#chat-box').append(responseText.conteudo);
                }
            } else if (responseText.tipo == 'msg') {
                $('#msg-erro').html(responseText.conteudo);
            }
        },
    };

    $(this).ajaxSubmit(options);
});


$(document).on('click', '.paginacao-notif', function (event) {
    event.preventDefault();
    elemento = $(this);
    tipoNotif = $(this).attr('data-tipo');
    numPagina = $(this).attr('data-pagina');

    switch (tipoNotif) {
        case 'projetos':
            classe = '.box-notif-projetos';
            idNotificacao = 1;
            break;
        case 'atualizacoes':
            classe = '.box-notif-atualizacoes';
            idNotificacao = 2;
            break;
        case 'lembretes':
            classe = '.box-notif-lembretes';
            idNotificacao = 3;
            break;
        case 'comentarios':
            classe = '.box-notif-comentarios';
            idNotificacao = 4;
            break;
        default:
            break;
    }

    if (tipoNotif) {
        $(classe + ' .paginacao-notif').removeClass('ativo');

        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: {
                tipo: 'paginacaoNotificacoes',
                tipoNotificacao: idNotificacao,
                pagina: numPagina
            },
        })
            .done(function (retorno) {
                if (retorno.tipo == 'html') {
                    $(classe + ' .box-body .lista-notificacoes').html(retorno.conteudo);
                    $(elemento).addClass('ativo');
                } else {
                    console.log(retorno);
                }
            })
            .fail(function (retorno) {
                console.log(retorno);
            });
    }
});


$(document).on('ifToggled', 'input[name=proposta_check]', function (event) {
    $('#cadastro-proposta').toggle('slideDown');
});


/** CORREÇÃO DE FUNDO E SCROLL PARA MULTIPLAS MODAIS **/
$(document).on('hidden.bs.modal', '.modal', function (event) {
    $(this).removeClass('fv-modal-stack');
    $('body').data('fv_open_modals', $('body').data('fv_open_modals') - 1);

    if (typeof ($('body').data('fv_open_modals')) != 'undefined' && $('body').data('fv_open_modals') == 0) {
        $('body').removeClass('modal-open');
    }
});

$(document).on('shown.bs.modal', '.modal', function (event) {
    // keep track of the number of open modals
    if (typeof ($('body').data('fv_open_modals')) == 'undefined') {
        $('body').data('fv_open_modals', 0);
    }

    // if the z-index of this modal has been set, ignore.
    if ($(this).hasClass('fv-modal-stack')) {
        return;
    }

    $(this).addClass('fv-modal-stack');
    $('body').data('fv_open_modals', $('body').data('fv_open_modals') + 1);
    $(this).css('z-index', 1040 + (10 * $('body').data('fv_open_modals')));
    $('.modal-backdrop').not('.fv-modal-stack').css('z-index', 1039 + (10 * $('body').data('fv_open_modals')));
    $('.modal-backdrop').not('fv-modal-stack').addClass('fv-modal-stack');

    $(".textarea-ajax").wysihtml5();
});
/** /CORREÇÃO PARA MULTIPLAS MODAIS **/


/** INPUT FILE CUSTOMIZADO **/
$(document).on('change', '.btn-file :file', function () {
    var input = $(this),
        numFiles = input.get(0).files ? input.get(0).files.length : 1,
        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    input.trigger('fileselect', [input, numFiles, label]);
});

$(document).on('fileselect', function (event, element, numFiles, label) {
    var input = $(element).parents('.input-group').find(':text'),
        log = numFiles > 1 ? numFiles + ' files selected' : label;

    if (input.length) {
        input.val(log);
    }
});
/** //INPUT FILE CUSTOMIZADO **/
