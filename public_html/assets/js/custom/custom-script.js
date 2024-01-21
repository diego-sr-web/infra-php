// Chamada generica pra parte de ajax, base de toda a parte que carrega dinamico
function ajaxCall(elementId, page, dataArray) {
    const urlAjax = '../Templates/blocos/ajaxify.php?page=' + page;
    $.ajax({
        url: urlAjax,
        type: 'POST',
        dataType: "json",
        async: true,
        data: dataArray
    }).done(function (retorno) {
        if (retorno.tipo === 'html') {
            $("#" + elementId).html(retorno.conteudo);
        } else {
            console.log("erro:" + retorno.toString());
        }
    }).fail(function (retorno) {
        console.log("Chamada Falhou:" + urlAjax);
    });
}

// Funcoes para carregar as tabelas do pop
function getListaUsuario(filtro, tabelaPrimaria = "tabela") {
    ajaxCall(tabelaPrimaria, "tabelas/usuarios.tpl", {"ajax": true, "filtro": filtro});
}

function getListaCliente(filtro, tabelaPrimaria = "tabela") {
    ajaxCall(tabelaPrimaria, "tabelas/clientes.tpl", {"ajax": true, "filtro": filtro});
}

function getListaArea(filtro, tabelaPrimaria = "tabela") {
    ajaxCall(tabelaPrimaria, "tabelas/areas.tpl", {"ajax": true, "filtro": filtro});
}

// Funcoes para habilitar e desabilitar areas/usuarios/clientes, de maneira simples e sem recarregar a pagina
function status(id, acao, campo) {
    const urlAjax = "./_process-post.inc.php";
    $.ajax({
        url: urlAjax,
        type: 'POST',
        dataType: 'json',
        async: true,
        data: "form=" + acao + "&" + campo + "=" + id,
        success: function (data) {
            if (data === 0) {
                swal({
                    title: "ERRO",
                    icon: 'error',
                    text: "Houve algum erro ao alterar o status, tente novamente.",
                    timer: 2100,
                    buttons: false
                });
            } else {
                swal({
                    title: "SUCESSO",
                    icon: 'success',
                    text: "Status alterado com sucesso.",
                    timer: 2100,
                    buttons: false
                });
            }
        }
    })
}


function statusUsuario(idUsuario, acao) {
    status(idUsuario, acao, "idUsuario");
}

function statusCliente(idCliente, acao) {
    status(idCliente, acao, "idCliente");
}

function statusArea(idArea, acao) {
    status(idArea, acao, "idArea");
}


// Funcao de abertura e carregamento das modais, aqui vai codigo pra acessar o
// ajax-backoffice-v2.php e precisa decidir quais os dados e como eh passado
// esses pontos pro script de geracao de modal
$(document).on('click', '.js-modal', function (event) {
    event.preventDefault();
    let idModal = $(this).attr('data-target');
    let id = $(this).attr('data-id');
    let acao = $(this).attr('data-acao');
    let secao = $(this).attr('data-secao');
    $(idModal + ' .modal-dialog .modal-content').hide();

    $.ajax({
        url: './ajax/ajax-backoffice-v2.php',
        type: 'POST',
        async: true,
        data: {
            secao: secao,
            tipo: acao,
            id: id,
        },
    }).done(function (retorno) {
        if (retorno.tipo === 'html') {
            $(idModal + ' .modal-dialog .modal-content').html(retorno.conteudo);
            $(idModal + ' .modal-dialog .modal-content').show();
            $('#modal-backoffice').modal('open');
        } else {
            console.log(retorno);
        }
    }).fail(function (retorno) {
        console.log(retorno);
    });
});

$(document).on('submit', '#modal-backoffice', function (event) {
    event.preventDefault();
    let dadosForm = $("#modal-backoffice form").serializeArray();
    const urlAjax = "./_process-post.inc.php";

    $('#modal-backoffice').modal('close');

    swal({
        title: "Processando...",
        icon: 'warning',
        text: "Aguarde enquanto finalizamos o processamento",
        buttons: false
    });


    $.ajax({
        url: urlAjax,
        type: 'POST',
        dataType: 'json',
        async: true,
        data: dadosForm
    }).done(function (data) {
        if (data === 0) {
            swal({
                title: "ERRO",
                icon: 'error',
                text: "Houve algum erro ao alterar o status, tente novamente.",
                timer: 2100,
                buttons: false
            });
            location.reload();
        } else {
            swal({
                title: "SUCESSO",
                icon: 'success',
                text: "Status alterado com sucesso.",
                timer: 2100,
                buttons: false
            });
            location.reload();
        }
    }).fail(function () {
        swal({
            title: "ERRO",
            icon: 'error',
            text: "Houve algum erro ao alterar o status, tente novamente.",
            timer: 2100,
            buttons: false
        });
        location.reload();
    });
})

//Abre Modal
$(document).ready(function () {
    $('.modal').modal({
        outDuration: 100,
        dismissible: false,
    });
});


//Preview de imagens
$(function () {
    $('#preview').change(function () {
        const file = ($(this)[0].files[0]);
        const fileReader = new FileReader();

        fileReader.onloadend = function () {
            $('#preview-img').attr('src', fileReader.result);
        }
        fileReader.readAsDataURL(file)
    })
})

$(function() {
    $("body").delegate(".datepicker", "focusin", function(){
        $(this).datepicker({
            format: 'dd/mmmm/yyyy',
            defaultDate: '29/Jun/2020',
            disableWeekends: true,
            showClearBtn: true,
            lang: 'pt',

            i18n: {
                months: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                monthsShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                weekdays: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sabádo'],
                weekdaysAbbrev: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                today: 'Hoje',
                clear: 'Limpar',
                close: 'Pronto',
                labelMonthNext: 'Próximo mês',
                labelMonthPrev: 'Mês anterior',
                labelMonthSelect: 'Selecione um mês',
                labelYearSelect: 'Selecione um ano',
                selectMonths: true,
                selectYears: 15,
                cancel: 'Cancelar',
            }
        });
    });
});

