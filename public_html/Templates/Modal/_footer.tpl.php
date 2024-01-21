<?php
$cancelar = $data["cancelar"] ?? "";
$confirmar = $data["confirmar"] ?? NULL;
$confirmarIcone = $data["confirmarIcone"] ?? NULL;
if (!isset($confirmar)) {
    die("ERROR:".__FILE__);
}
?>
    </div>
    <div class="modal-footer">
        <?php
        if ($cancelar !== "") {
        ?>
            <button class="btn waves-effect waves-light btn-small red darken-1 modal-close" type="button">
                <i class="material-icons left">cancel</i> <?=$cancelar?>
            </button>
        <?php
        }
        ?>

        <button class="btn waves-effect waves-light btn-small red darken-1" type="submit" name="action">
            <i class="material-icons left">check</i> <?= $confirmar?>
        </button>
    </div>
</form>
