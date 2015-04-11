<?php
include "../vendor/autoload.php";
use Yellow\Bitcoin\Invoice;
include("keys.php");
$id = $_GET["id"];
$y = new Invoice($api_key,$api_secret);
$status = $y->checkInvoiceStatus($id);?>
<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Bitcoin status sample</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
<!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->
<pre>
    <?php var_dump($status);?>
</pre>
</html>