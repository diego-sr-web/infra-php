var ajaxRestauraSessao = '../ajax/restaura-sessao.php';

setInterval(restauraSessao, 1800000);

function restauraSessao() {
    $.ajax({
        url: ajaxRestauraSessao,
        type: 'POST',
        data: null,
    })
        .done(function (retorno) {
        })
        .fail(function (retorno) {
        });
}
