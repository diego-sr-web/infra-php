<?php
require_once __DIR__ . "/pop-projeto-facebook-impressao-includes.php";
?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Lista de Posts | POP Nerdweb</title>
    <link href="../css/reset.css" rel="stylesheet" type="text/css"/>
    <link href="../css/relatorios.css" rel="stylesheet" type="text/css"/>
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet"
          type="text/css"/>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,700' rel='stylesheet' type='text/css'>
    <link href="../css/calendario/calendar.css" rel="stylesheet" type="text/css"/>
    <link href="../css/calendario/custom_2.css" rel="stylesheet" type="text/css"/>
    <style type="text/css">
        .mt-80 {
            margin-top: 80px;
        }

        .box-title {
            height: 30px;
        }

        .hr-sub {
            color: #367FA9;
        }

        .custom-header {
            border-top: 0;
        }

        .custom-calendar-wrap {
            margin: 0 auto;
        }

        .fc-calendar .fc-row > div:hover {
            border: none;
            border-right: 1px solid #ddd;
        }

        .fc-calendar .fc-row > div:last-child:hover, .fc-calendar .fc-head > div:last-child:hover {
            border: none;
        }

        .fc-calendar-container {
            height: 220px;
            padding: 0;
            background: none;
        }

        .legenda-calendario {
            float: left;
            margin-top: 20px;
            width: 100%;
        }

        .legenda-calendario .categorias {
            margin-top: 10px;
        }

        .legenda-calendario .categorias .categoria {
            float: left;
            font-size: 13px;
            list-style: outside none none;
            margin-right: 30px;
            margin-bottom: 5px;
        }

        .post-wrap {
            float: left;
            position: relative;
            width: 100%;
            padding: 20px 0;
        }

        .post-wrap:nth-child(2n) {
            background-color: #CCCCCC;
        }

        dl.post {
            font-size: 14px;
            margin-bottom: 20px;
            margin-top: 0;
        }

        dl.post dt {
            clear: both;
            float: left;
            overflow: hidden;
            text-align: right;
            text-overflow: ellipsis;
            white-space: nowrap;
            width: 160px;
        }

        dt {
            font-weight: 700;
        }

        dd:after, dd:before {
            content: " ";
            clear: both;
            display: table;
        }

        dd {
            margin-left: 0;
        }

        dt, dd {
            line-height: 1.42857;
            box-sizing: border-box;
        }

        dl.post dd {
            margin-left: 180px;
            display: table;
        }

        .cx-aprovacao {
            position: absolute;
        }

        /*.cx-aprovacao ul li{ color: #666666; border: 1px solid #666666; float: left; height: 50px; line-height: 45px; margin-left: 5px; text-align: center; text-transform: uppercase; width: 120px; }*/
        .cx-aprovacao ul li {
            background-color: #afafaf;
            color: #EEEEEE;
            float: left;
            height: 50px;
            line-height: 47px;
            margin-left: 5px;
            text-align: center;
            text-transform: uppercase;
            width: 120px;
        }

        /*.cx-aprovacao ul li p{ opacity: 0.5; }*/

        .sem-imagem .post-wrap dl.post {
            float: left;
            max-width: 820px;
        }

        .sem-imagem .post-wrap .numero-post {
            background-color: #666666;
            color: #ffffff;
            float: left;
            min-height: 180px;
            line-height: 180px;
            padding: 0;
            margin: 0 10px 0 20px;
            text-align: center;
            width: 40px;
        }

        .sem-imagem .cx-aprovacao {
            top: 20px;
            right: 20px;
        }

        .com-imagem .post-wrap {
            width: 100%;
            padding: 20px 0; /*border-bottom: 1px solid #D3D3D3; */
        }

        .com-imagem .post-wrap .img-post {
            float: left;
        }

        .com-imagem .post-wrap .img-post img {
            max-width: 280px;
        }

        .com-imagem .post-wrap dl.post {
            width: 530px;
            float: left;
            padding-bottom: 50px;
        }

        .com-imagem .post-wrap .img-post .numero-post {
            background-color: #666666;
            color: #fff;
            float: left;
            margin: 0 10px 0 20px;
            min-height: 280px;
            line-height: 280px;
            padding: 0;
            text-align: center;
            width: 40px;
        }

        .com-imagem dl.post dd {
            margin-left: 120px;
        }

        .com-imagem dl.post dt {
            width: 100px;
        }

        .com-imagem .cx-aprovacao {
            bottom: 20px;
            right: 20px;
        }

        .assinaturas-wrapper {
            padding: 20px 10px;
            font-size: 15px;
        }

        .assinaturas {
            float: left;
            width: 440px;
        }

        .assinaturas p.assinatura-desc {
            text-align: center;
            margin-top: 10px;
            font-size: 15px;
        }
    </style>
</head>

<body>
<div class="content">
    <div class="box-header">
        <div class="box-logo-nerd"><img src="../img/logo.png"></div>
        <div class="box-nome-pagina">Campanha de Redes Sociais - <?php echo $cliente['nomeFantasia']; ?>
            - <?php echo $infoProjeto['nome']; ?></div>
    </div>

    <div class="box-title fl mt-20">
        <h3 class="color-3">Programação da Campanha</h3>
        <hr class="hr-sub">
    </div>
    <?php if ($incompleto) { ?>
        <div class="box-title fl mt-20">
            <h3 class="color-red">*** CAMPANHA AINDA NAO FINALIZADA ***</h3>
            <hr class="hr-sub">
        </div>
    <?php } ?>

    <?php
    $i = 1;
    foreach ($elementosProjeto as $e) {
        $categoria = $POPElemento->getCategoryById($e["campos"]["Categoria"]);

        if (isset($e["campos"][$nomeCampoImagem]) && $e["campos"][$nomeCampoImagem]) {
            ?>
            <div class="box-posts com-imagem fl mt-20">
                <div class="post-wrap">
                    <div class="img-post">
                        <div class="numero-post"><span><?php echo $i; ?></span></div>
                        <img src="<?php echo $e["campos"][$nomeCampoImagem]; ?>"/>
                    </div>
                    <dl class="post">
                        <dt>Publicação</dt>
                        <dd><?php echo date('d/m/Y', strtotime($e['campos'][$nomeCampoData])); ?></dd>
                        <br/>
                        <br/>
                        <dt>Texto</dt>
                        <dd><?php echo nl2br($e["campos"][$nomeCampoTexto]); ?></dd>
                    </dl>
                </div>
            </div>
            <?php
            $i++;
        }
    } ?>

</div>

<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="../js/plugins/calendario/modernizr.custom.js" type="text/javascript"></script>
<script src="../js/plugins/calendario/jquery.calendario.js" type="text/javascript"></script>
<script type="text/javascript">
    /**
     * Created by rotelok on 15/09/16.
     */
    var txtHeader = 'Campanha de Facebook - NOME DA CAMPANHA';

    var programacaoDefault = '<?php echo $jsProgramacaoCampanha; ?>';
    programacaoDefault = JSON.parse(programacaoDefault);

    var mesCampanha = '<?php if ((isset($mesCampanha) && $mesCampanha)) {
        echo date('m', strtotime($mesCampanha));
    } else {
        echo 1;
    }?>';
    var anoCampanha = '<?php if ((isset($mesCampanha) && $mesCampanha)) {
        echo date('Y', strtotime($mesCampanha));
    } else {
        echo 2016;
    }?>';

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

            $month = $('#custom-month').html(cal.getMonthName()),
            $year = $('#custom-year').html(cal.getYear());
    });
</script>
<script type="text/javascript">
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
</script>
</body>
</html>
