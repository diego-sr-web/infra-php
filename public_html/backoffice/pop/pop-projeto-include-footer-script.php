<script type="text/javascript">
    var lastModal = lastElement = lastProject = '';

    $(document).ready(function () {
        var $grid = $('.item-box .row .row').imagesLoaded(function () {
            // init Masonry after all images have loaded
            $grid.masonry({
                itemSelector: '.item-info',
                stamp: '.stamp'
            });
        });
    });

    // clique nos botões que pegam informações do projeto
    $(document).on('click', '.js-getProjeto', function (event) {
        event.preventDefault();

        var idModal = $(this).attr('data-target');
        var idProjeto = $(this).attr('data-projeto');
        var acao = $(this).attr('data-acao');

        var loadModal = true;
        if (acao === lastModal && idProjeto === lastProject) {
            loadModal = false;
        }
        lastModal = acao;
        lastProject = idProjeto;
        lastElement = '';

        if (loadModal) {
            $(idModal).hide();
            $.ajax({
                url: 'ajax/ajax-pop.php',
                type: 'POST',
                data: {
                    secao: 'projeto',
                    tipo: acao,
                    projeto: idProjeto
                }
            })
                .done(function (retorno) {
                    if (retorno.tipo === 'html') {
                        $(idModal).html(retorno.conteudo);
                        $(idModal).show();
                    } else {
                        console.log(retorno);
                    }
                })
                .fail(function (retorno) {
                    console.log(retorno);
                });
        }
    });

    // clique nos botões que pegam informações do elemento
    $(document).on('click', '.js-getElemento', function (event) {
        event.preventDefault();

        var idModal = $(this).attr('data-target');
        var idElemento = $(this).attr('data-elemento');
        var acao = $(this).attr('data-acao');

        var loadModal = true;
        if (acao === lastModal && idElemento === lastElement) {
            loadModal = false;
        }
        lastModal = acao;
        lastElement = idElemento;
        lastProject = '';

        if (loadModal) {
            $(idModal).hide();
            $.ajax({
                url: 'ajax/ajax-pop.php',
                type: 'POST',
                data: {
                    secao: 'elemento',
                    tipo: acao,
                    elemento: idElemento
                }
            })
                .done(function (retorno) {
                    if (retorno.tipo === 'html') {
                        $(idModal).html(retorno.conteudo);
                        $(idModal).show();
                    } else {
                        console.log(retorno);
                    }
                })
                .fail(function (retorno) {
                    console.log(retorno);
                });
        }
    });

    // submit do formulário de comentários
    $(document).on('submit', '.form-comentario', function (event) {
        event.preventDefault();
        var dados = new FormData(this);
        dados.append('secao', 'projeto');
        dados.append('form', 'salva-comentario');
        //var dados = $(this).serializeArray();
        var form = $(this);

        $.ajax({
            url: 'ajax/ajax-pop.php',
            type: 'POST',
            data: dados,
            processData: false,
            contentType: false
        })
            .done(function (retorno) {
                if (retorno.tipo === 'html') {
                    form.parent().find('.chat').html(retorno.conteudo);
                    form.find('textarea[name=comentario]').val('');
                } else if (retorno.tipo === 'msg') {
                    alert(retorno.conteudo);
                }
            })
            .fail(function (retorno) {
            });
    });
</script>
