ajaxGeralUrl = '../pop/ajax/ajax-geral.php';

ajaxTarefasUrl = '../pop/ajax/ajax-tarefas.php';
ajaxPedidosUrl = '../pop/ajax/ajax-pedidos.php';
ajaxUrl = '../pop/ajax/ajax.php';
ajaxFBUrl = '../pop/ajax/ajax-FB.php';
ajaxWSUrl = '../pop/ajax/ajax-WS.php';

var notificacoes_offset = 10;

/** TABELA DE DADOS **/
$(function () {
    if ($('#example1').length || $('.custom-datatable').length) {
        $("#example1, .custom-datatable").dataTable({
            //"iDisplayLength": 20,
            "bLengthChange": true,
            "bFilter": true,
        });
    }

    // if (typeof nivoLightbox == 'function') {
    // 	$('a.open-lightbox').nivoLightbox();
    // }

    if (jQuery.fn.lightGallery) {
        $('a.open-lightbox').lightGallery({
            selector: 'this'
        });
    }
});
/** //TABELA DE DADOS **/


/** CORREÇÃO COM ALTERAÇÃO **/
$(document).on('ifClicked', 'input[name=correcao_check]', function (event) {
    valor = $(this).val();

    if (valor == 1) {
        if ($('#correcao-texto').is(':hidden')) {
            $('#correcao-texto').toggle('slideDown');
        }
    } else if (valor == 0) {
        if ($('#correcao-texto').is(':visible')) {
            $('#correcao-texto').toggle('slideDown');
        }
    }
});


/** CARREGAR PESSOAS DA ÁREA NO PEDIDO **/
$(document).on('ifChecked', 'input[name=areaPara]', function () {
    var $input = $(this);
    var area = $input.val();
    var acao = 'pessoas-area';

    $.ajax({
        url: 'ajax/ajax-pop.php',
        type: 'POST',
        data: {
            secao: 'usuario',
            tipo: acao,
            area: area
        }
    })
        .done(function (retorno) {
            if (retorno.tipo == 'html') {
                $('.pessoas').find('.radio').remove();
                $('.pessoas .row').append(retorno.conteudo);

                $('input[name=pessoaPara]').iCheck({checkboxClass: "icheckbox_minimal", radioClass: "iradio_minimal"});
                $('input[name=responsavel]').iCheck({checkboxClass: "icheckbox_minimal", radioClass: "iradio_minimal"});
            } else {
                console.log(retorno);
            }
        })
        .fail(function (retorno) {
            console.log(retorno);
        });
});


/** CLIENTE APROVOU COM ALTERAÇÃO **/
$(document).on('ifToggled', 'input[name=alteracao_check]', function (event) {
    $('#cadastro-alteracao').toggle('slideDown');
});


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


$(window).on('load scroll', function (event) {
    if ($(window).scrollTop() > 500) {
        $('.bt-footer-hidden').fadeIn();
    } else {
        $('.bt-footer-hidden').fadeOut();
    }
});


/** SMOOTH SCROLL **/
$(document).on('click', 'a[href*=#]:not([href=#],[data-toggle])', function (event) {
    if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
        var target = $(this.hash);
        target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
        if (target.length) {
            $('html,body').animate({
                scrollTop: target.offset().top - 250
            }, 1000);

            return false;
        }
    }
});


/** PREVIEW DE UPLOAD DE IMAGENS */
function readUpload(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $(input).parent().parent().parent().parent().find('img.img-preview').attr('src', e.target.result).show();
        };

        reader.readAsDataURL(input.files[0]);
    }
}

$(document).on('change', '.upload-preview', function () {
    $(this).parent().parent().parent().parent().find('img.img-preview').hide();
    readUpload(this);
});


window.onload = $('div.callout').not('.callout-important').delay(5000).slideUp(300);


/* CORREÇÃO DE FUNDO E SCROLL PARA MULTIPLAS MODAIS
$(document).on('hidden.bs.modal', '.modal', function( event ) {
    $(this).removeClass( 'fv-modal-stack' );
    $('body').data( 'fv_open_modals', $('body').data( 'fv_open_modals' ) - 1 );

    if ( typeof( $('body').data( 'fv_open_modals' ) ) != 'undefined' && $('body').data( 'fv_open_modals' ) == 0){
    	$('body').removeClass('modal-open');
    }
});


$(document).on( 'shown.bs.modal', '.modal', function ( event ) {
   	// keep track of the number of open modals
	if ( typeof( $('body').data( 'fv_open_modals' ) ) == 'undefined' ){
		$('body').data( 'fv_open_modals', 0 );
	}

   	// if the z-index of this modal has been set, ignore.
	if ( $(this).hasClass( 'fv-modal-stack' ) ){
        return;
	}

    $(this).addClass( 'fv-modal-stack' );
    $('body').data( 'fv_open_modals', $('body').data( 'fv_open_modals' ) + 1 );
    $(this).css('z-index', 1040 + (10 * $('body').data( 'fv_open_modals' )));
    $( '.modal-backdrop' ).not( '.fv-modal-stack' ).css( 'z-index', 1039 + (10 * $('body').data( 'fv_open_modals' )));
	$( '.modal-backdrop' ).not( 'fv-modal-stack' ).addClass( 'fv-modal-stack' );
	
	$(".textarea-ajax").wysihtml5();
});
/CORREÇÃO PARA MULTIPLAS MODAIS */


// clique em uma notificação
$(document).on('click', '.js-notificacao', function (event) {
    event.preventDefault();
    var url_link = $(this).attr('href');
    var idNotif = $(this).attr('data-notificacao');

    $.ajax({
        url: 'ajax/ajax-pop.php',
        type: 'POST',
        data: {
            secao: 'notificacao',
            tipo: 'marcar-lido',
            notificacao: idNotif
        }
    })
        .done(function (retorno) {
            if (retorno.tipo === 'sucesso') {
                window.location.href = url_link;
            } else {
                console.log(retorno);
            }
        })
        .fail(function (retorno) {
            console.log(retorno);
        });
});


/*
// marcar todas as não lidas como lidas
$(document).on('click', '.js-marcar-lido', function (event) {
	event.preventDefault();
    var idUsuario = $(this).attr('data-usuario');

    $.ajax({
        url: 'ajax/ajax-pop.php',
        type: 'POST',
        data: {
            secao: 'notificacao',
            tipo: 'marcar-todas-lido',
            usuario: idUsuario
        }
    })
	.done(function(retorno) {
		if(retorno.tipo === 'sucesso') {
			$('.js-notificacao').parent().removeClass('nova');
			$('.js-num-notificacoes').hide();

			document.title = document.title.substring(document.title.indexOf(" ") + 1);
		} else {
			console.log(retorno);
		}
	})
	.fail(function(retorno){
		console.log(retorno);
	});
});
*/


// marcar como lidas somente as não lidas que estão aparecendo
$(document).on('click', '.js-marcar-lido', function (event) {
    event.preventDefault();
    var idUsuario = $(this).attr('data-usuario');
    var target = $(this).attr('data-target');
    var notificacoes = [];

    $(target).find('.nova').each(function () {
        notificacoes.push($(this).find('.js-notificacao').attr('data-notificacao'));
    });

    if (notificacoes.length > 0) {
        notificacoes = JSON.stringify(notificacoes);

        $.ajax({
            url: 'ajax/ajax-pop.php',
            type: 'POST',
            data: {
                secao: 'notificacao',
                tipo: 'marcar-lidos',
                notificacao: notificacoes
                // tipo: 'marcar-todas-lido',
                // usuario: idUsuario
            }
        })
            .done(function (retorno) {
                if (retorno.tipo === 'sucesso') {
                    $(target).find('.nova').removeClass('nova');
                    atualizaNaoLidas(parseInt(retorno.conteudo));
                } else {
                    console.log(retorno);
                }
            })
            .fail(function (retorno) {
                console.log(retorno);
            });
    }
});


$(".js-notificacoes-scroll").slimScroll({height: "500px", alwaysVisible: false}).bind('slimscroll', function (e, pos) {
    if (pos === 'bottom') {
        var deferred = carregaMaisNotificacoes(notificacoes_offset, '.js-notificacoes-scroll');
        $.when(deferred).done(function (retorno) {
            if (retorno.tipo === 'html') {
                notificacoes_offset = notificacoes_offset + 10;
                $('.js-notificacoes-scroll').slimScroll({scrollBy: '0px'});
            }
        });
    }
});


function atualizaNaoLidas(numero) {
    if (numero > 0) {
        $('.js-num-notificacoes').html(numero);
        document.title = '(' + numero + ') ' + document.title.substring(document.title.indexOf(" ") + 1);
    } else {
        $('.js-num-notificacoes').hide();
        document.title = document.title.substring(document.title.indexOf(" ") + 1);
    }
}


function carregaMaisNotificacoes(offset, classe_target) {
    return $.ajax({
        url: 'ajax/ajax-pop.php',
        type: 'POST',
        data: {
            secao: 'notificacao',
            tipo: 'notificacoes-usuario',
            usuario: idUsuarioLogado,
            offset: offset
        }
    })
        .done(function (retorno) {
            if (retorno.tipo === 'html') {
                // $(classe_target).append();
                $(classe_target).find('li.loading').before(retorno.conteudo);

            } else {
                $(classe_target).find('li.loading').hide();
                console.log(retorno);
            }
        })
        .fail(function (retorno) {
            console.log(retorno);
        });
}


function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function checkCookie(cname) {
    var value = getCookie(cname);
    if (value != "") {
        alert(value);
    } else {
        alert('no cookie');
    }
}
