<style type="text/css">
    .stories-list {
        width: 100%;
        margin: 0;
        padding: 12px 10px 7px;
        position: absolute;
        background-color: #FFF;
        top: 50px;
    }

    .stories-list img {
        border: 2px solid #DDDDDD;
        width: 40px;
    }

    .stories-list li {
        margin-bottom: 5px;
        text-align: center;
    }

    .stories-list li:nth-child(-n+5) img {
        border-color: #F39C12;
    }

    .stories-list li:hover img {
        border-color: #000000;
    }

    .stories-list li a p {
        color: #000000;
        font-size: 10px;
    }

    .minha-historia {
        width: 40px;
        height: 40px;
        background-color: #3C8DBC;
        color: #FFFFFF;
        line-height: 40px;
        border-radius: 50%;
    }

    .minha-historia:hover {
        background-color: #000000;
    }

    .content-header {
        margin-top: 65px;
    }
</style>

<?php
$listaStories = $database->customQueryPDO('SELECT * FROM back_AdmUsuario WHERE usuarioNerdweb != ' . $_SESSION['adm_usuario'] . ' AND isUsed = 1 ORDER BY rand() LIMIT 15', []);

echo '<ul class="list-inline stories-list">';
echo "<li>
		<a target=\"_blank\" href=\"http://lmgtfy.com/?q=1+de+abril\">
			<div class='minha-historia'><i class='fa fa-plus'></i></div>
			<p>Minha</p>
		</a>
	</li>";

foreach ($listaStories as $story) {
    $nome = explode(' ', $story['nome'])[0];
    echo "<li>
			<a target=\"_blank\" href=\"http://lmgtfy.com/?q=1+de+abril\">
				<img src=\"$story[imagem]\" class=\"img-circle\" alt=\"$story[nome]\"/>
				<p>$nome</p>
			</a>
		</li>";
}
echo '</ul>';
?>
