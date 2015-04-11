<?php
include "../vendor/autoload.php";
use Yellow\Bitcoin\Invoice;
include("keys.php");
$yellow = new Invoice($api_key,$api_secret);
/// this will return an array with invoice data
$invoice  = $yellow->createInvoice(0.05,"USD","http://mediacity.co/sdk/sample/ipn.php");
var_dump($invoice);