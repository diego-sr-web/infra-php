<?php
require_once __DIR__ . "/pop-projeto-facebook-impressao-includes.php";

$cabecalho = '
	<div class="break"></div>
	<div class="cabecalho only-print">
		<div class="logo"><img src="../img/logo.png"></div>
		<div class="txt">
			Campanha de Redes Sociais - ' . $cliente['nomeFantasia'] . ' - ' . $infoProjeto['nome'] . '
		</div>
	</div>';
?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Lista de Posts | POP Nerdweb</title>
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet"
          type="text/css"/>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,700' rel='stylesheet' type='text/css'>

    <style type="text/css">
        * {
            background: transparent;
            color: #000;
            text-shadow: none !important;
            filter: none !important;
            -ms-filter: none !important;
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            margin: 0;
            padding: 0;
            line-height: 1.4em;
            font-family: 'Open Sans', sans-serif;
            font-size: 13px;
        }

        img {
            max-width: 100%;
            display: block;
        }

        .print {
            display: block;
        }

        .no-print {
            display: none;
        }

        .break {
            page-break-before: always;
        }

        .txt-ct {
            text-align: center;
        }

        .content {
            width: 900px;
            margin: 0 auto;
        }

        .cabecalho {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #f9f9f9;
            max-height: 55px;
            margin-bottom: 20px;
        }

        .cabecalho.only-print {
            display: none;
        }

        .cabecalho .logo {
            width: 150px;
        }

        .cabecalho .txt {
            font-size: 14px;
            font-weight: bold;
        }

        .box-alert {
            padding: 10px 0;
            text-align: center;
            background-color: #bc5504;
            color: #ffffff;
            font-size: 16px;
            font-weight: 600;
        }

        .titulo {
            font-size: 20px;
            font-weight: bold;
            border-bottom: 1px solid #367FA9;
            padding: 0 0 5px;
            margin: 40px 0 0;
        }

        .grid {
            background-color: #ebebeb;
            margin-top: 10px;
            padding-bottom: 10px;
        }

        .grid .linha {
            display: flex;
            flex-wrap: wrap;
            padding: 0 10px;
        }

        .grid .titulo + .linha, .grid .linha:first-of-type {
            padding-top: 10px;
        }

        .grid .post {
            width: 33.33333%;
            padding: 2px;
        }

        .lista {
        }

        .lista .linha {
            display: flex;
            padding: 0 0 50px;
            margin: 0 -10px;
        }

        .lista .post {
            width: 33.33333%;
            padding: 0 10px;
        }

        .lista .titulo + .linha, .lista .linha:first-of-type {
            padding-top: 20px;
        }

        .lista .post .num {
            background-color: #666666;
            color: #fff;
            text-align: center;
            padding: 5px 0;
            margin: 0 0 5px;
        }

        .lista .post .sub {
            font-weight: bold;
            margin: 5px 0 0;
        }

        .assinatura {
            width: 900px;
            font-size: 15px;
            line-height: 25px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin: 50px auto 0;
        }

        .assinatura .w-100 {
            width: 100%;
        }

        .assinatura .w-50 {
            width: calc(50% - 40px);
        }

        .assinatura p {
            margin: 0 0 10px;
        }

        @page {
            margin: 0.5cm;
        }

        @media print {
            .cabecalho.only-print {
                display: flex;
            }

            .titulo {
                margin: 0;
            }

            .box-alert {
                display: none;
            }
        }
    </style>
</head>
<body class="report">
<div class="content">
    <div class="cabecalho">
        <div class="logo"><img src="../img/logo.png"></div>
        <div class="txt">
            Campanha de Redes Sociais - <?php echo $cliente['nomeFantasia']; ?> - <?php echo $infoProjeto['nome']; ?>
        </div>
    </div>

    <?php if ($incompleto) { ?>
        <div class="box-alert">*** CAMPANHA AINDA NAO FINALIZADA ***</div>
    <?php } ?>

    <div class="titulo">Grid de Posts</div>
    <div class="grid">
        <?php
        $i = 1;
        $j = 1;

        foreach (array_reverse($elementosProjeto) as $e) {
            if (isset($e["campos"][$nomeCampoImagem]) && $e["campos"][$nomeCampoImagem]) {
                if (($j == 1)) {
                    echo '<div class="linha">';
                }
                ?>
                <div class="post">
                    <img src="<?php echo $e["campos"][$nomeCampoImagem]; ?>"/>
                </div>
                <?php
                $i++;
                $j++;

                if ($j > 12 && $i <= count($elementosProjeto)) {
                    echo '</div>';
                    echo $cabecalho;
                    echo '<div class="titulo">Grid de Posts</div>';

                    $j = 1;
                }

                if ($i > count($elementosProjeto)) {
                    echo '</div>';
                }
            }
        }
        ?>
    </div>

    <?php echo $cabecalho; ?>

    <div class="titulo">Posts da Campanha</div>
    <div class="lista">
        <?php
        $i = 1;
        $j = 1;

        foreach ($elementosProjeto as $e) {
            if (isset($e["campos"][$nomeCampoImagem]) && $e["campos"][$nomeCampoImagem]) {
                if (($j == 1)) {
                    echo '<div class="linha">';
                }
                ?>
                <div class="post">
                    <div class="num"><?php echo $i; ?></div>

                    <img src="<?php echo $e["campos"][$nomeCampoImagem]; ?>"/>
                    <p class="sub">Data de Publicação</p>
                    <p><?php echo date('d/m/Y', strtotime($e['campos'][$nomeCampoData])); ?></p>

                    <p class="sub">Texto</p>
                    <p><?php echo nl2br($e["campos"][$nomeCampoTexto]); ?></p>
                </div>
                <?php
                $i++;
                $j++;

                if ($j > 3 && $i <= count($elementosProjeto)) {
                    echo '</div>';
                    echo $cabecalho;
                    echo '<div class="titulo">Posts da Campanha</div>';
                    $j = 1;
                }

                if ($i > count($elementosProjeto)) {
                    echo '</div>';
                }
            }
        }
        ?>
    </div>

</div>

<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
</body>
</html>
