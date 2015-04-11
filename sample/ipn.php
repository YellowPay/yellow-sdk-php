<?php
include "../vendor/autoload.php";
use Yellow\Bitcoin\Invoice;
include("keys.php");
$yellow = new Invoice($api_key,$api_secret);
$isValidIPN = $yellow->verifyIPN(); //bool
$log_file = "ipn.log";
$payload = file_get_contents("php://");
if($isValidIPN){
    file_put_contents($log_file , $payload . "is valid IPN call " , FILE_APPEND);
    header("HTTP/1.0 200 OK");
}else{
    file_put_contents($log_file , $payload . "is invalid IPN call " , FILE_APPEND);
    header("HTTP/1.1 403 Unauthorized");
}