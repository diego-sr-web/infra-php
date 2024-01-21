<!-- MODAL DE PROPOSITO GERAL -->
<div class="modal" id="modal-backoffice" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>
</div>

<footer class="page-footer footer footer-static footer-light white navbar-border navbar-shadow">
    <div class="footer-copyright">
        <div class="container">
            <?php if ($_SERVER["SERVER_NAME"] == "dev.popflow.com.br" || $_SERVER["SERVER_NAME"] == "popv3.rotelok.nerdweb" || $_SERVER["SERVER_NAME"] == "localhost") { ?>
                <span class="message-servidor">
                    <marquee scrollamount="10"> SERVIDOR DE TESTES, OS DADOS NESSE SERVIDOR PODEM SER REMOVIDOS A QUALQUER MOMENTO </marquee>
                </span>
            <?php } else {?>
                <span>&copy; 2020 <a href="https://nerdweb.com.br" target="_blank">Nerdweb - Popflow </a>, todos os direitos reservados..</span>
                <span class="right hide-on-small-only">Design and Developed by <a href="https://nerdweb.com.br/">Nerdweb - Extreme Hosting</a></span>
            <?php } ?>
        </div>
    </div>
</footer>
