/**
 * Created by rotelok on 15/09/16.
 */
var txtHeader = 'Campanha de Facebook - NOME DA CAMPANHA';

var programacaoDefault = '<?php echo $jsProgramacaoCampanha; ?>';
programacaoDefault = JSON.parse(programacaoDefault);

var mesCampanha = '<?php echo (isset($mesCampanha) && $mesCampanha) ? date(';
m;
', strtotime($mesCampanha)) : 1 ;?>';
var anoCampanha = '<?php echo (isset($mesCampanha) && $mesCampanha) ? date(';
Y;
', strtotime($mesCampanha)) : 2016 ;?>';

var elementoDia = null;
var datasSelecionadas = [];
var elementosSelecionados = [];

$(function () {
    var transEndEventNames = {
            'WebkitTransition': 'webkitTransitionEnd',
            'MozTransition': 'transitionend',
            'OTransition': 'oTransitionEnd',
            'msTransition': 'MSTransitionEnd',
            'transition': 'transitionend'
        },
        transEndEventName = transEndEventNames[Modernizr.prefixed('transition')],
        $wrapper = $('#custom-inner'),
        $calendar = $('#calendar'),
        cal = $calendar.calendario({
            onDayClick: function ($el, $contentEl, dateProperties) {
                elementoDia = $el;
            },
            caldata: programacaoDefault,
            weeks: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
            weekabbrs: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
            months: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            monthabbrs: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            displayWeekAbbr: true,
            startIn: 0,
            month: mesCampanha,
            year: anoCampanha
        }),

        $month = $('#custom-month').html(cal.getMonthName()
        ),
        $year = $('#custom-year').html(cal.getYear()
        );
});

var $postDiv = $('.post-wrap');
var boxHeight = totalHeight = 0;
var paginaInicial = true;
var htmlBreak = '<div class="break"></div>' +
    '<div class="box-header">' +
    '<div class="box-logo-nerd"><img src="../img/logo.png"></div>' +
    '<div class="box-nome-pagina">' + txtHeader + '</div>' +
    '</div>';

$postDiv.each(function (i) {
    boxHeight = $(this).height();

    $(this).find('.numero-post').css({
        'line-height': boxHeight + 'px',
    });
});

$postDiv.each(function (i) {
    totalHeight = totalHeight + $(this).height();

    if (paginaInicial == true) {
        if (totalHeight > 640) {
            alturaFaltante = (640 + $(this).height()) - totalHeight;
            $(this).before('<div style="clear:both; height:' + alturaFaltante + 'px"></div>' + htmlBreak);
            totalHeight = 0;
            paginaInicial = false;
        }
    } else {
        if (totalHeight > 840) {
            alturaFaltante = (840 + $(this).height()) - totalHeight;
            $(this).before('<div style="clear:both; height:' + alturaFaltante + 'px"></div>' + htmlBreak);
            totalHeight = 0;
        }
    }
});
