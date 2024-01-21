<?php
// Checking data
$class = $data["class"] ?? NULL;
$title = $data["title"] ?? NULL;
$msg = $data["message"] ?? NULL;
$type = $data["type"] ?? "alert-dismissable";
if(!isset($class,$title,$msg)) {
    die("Template Loading Failed");
}
?><div class="row">
    <div class="col-sm-12">
        <div class="callout <?= $class ?> <?= $type ?>" style="text-align: center;">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4><?= $title ?></h4>
            <p><?= $msg ?></p>
        </div>
    </div>
</div>
