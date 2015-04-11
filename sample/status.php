<?php
include "../vendor/autoload.php";
use Yellow\Bitcoin\Invoice;
include("keys.php");
$id = $_GET["id"];
$y = new Invoice($api_key,$api_secert);
$status = $y->checkInvoiceStatus($id);
var_dump($status);