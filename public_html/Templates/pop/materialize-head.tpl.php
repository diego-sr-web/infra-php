<?php
$pageTitle = $data["pageTitle"] ?? "PAGE TITLE UNDEFINED";
$pageStyle = $data["style"] ?? "vertical-gradient-menu-template";
?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="author" content="Nerdweb">
<title><?= $pageTitle ?></title>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<!-- BEGIN: VENDOR CSS-->

<link rel="stylesheet" type="text/css" href="/assets/vendors/vendors.min.css">

<!-- FontAwesome -->
<link rel="stylesheet" type="text/css" href="/assets/vendors/fontawesome/css/all.min.css">
<link rel="stylesheet" type="text/css" href="/assets/vendors/fontawesome-4/css/font-awesome.min.css">

<!-- Datatable -->
<link rel="stylesheet" type="text/css" href="/assets/vendors/data-tables/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="/assets/vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="/assets/vendors/data-tables/css/select.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="/assets/vendors/sweetalert/sweetalert.css">


<!-- END: VENDOR CSS-->
<!-- BEGIN: Page Level CSS-->
<link rel="stylesheet" type="text/css" href="/assets/css/themes/<?= $pageStyle ?>/materialize.css">
<link rel="stylesheet" type="text/css" href="/assets/css/themes/<?= $pageStyle ?>/style.css">
<!-- END: Page Level CSS-->
<!-- BEGIN: Custom CSS-->
<link rel="stylesheet" type="text/css" href="/assets/css/custom/custom.css">
<!-- END: Custom CSS-->
