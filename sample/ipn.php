<?php
include "../vendor/autoload.php";
use Yellow\Bitcoin\Invoice;
include("keys.php");
$body   = file_get_contents("php://input") ;
$yellow = new Invoice($api_key,$api_secret);
$params =  array(
         "url"       => $yellow->getCurrentUrl(), //// or you can use your own method
         "API-Sign"  => $_SERVER["HTTP_API_SIGN"],
         "API-Key"   => $_SERVER["HTTP_API_KEY"],
         "API-Nonce" => $_SERVER["HTTP_API_NONCE"] ,
         "body"      => $body
);
$isValidIPN = $yellow->verifyIPN($params); //bool
$log_file = "ipn.log";
if($isValidIPN){
    file_put_contents($log_file , $body . "is valid IPN call\n " , FILE_APPEND);
    /// you can update your order , log to db , send email etc
    header("HTTP/1.0 200 OK");
}else{
    file_put_contents($log_file , $body . "is invalid IPN call\n " , FILE_APPEND);
    /// invalid/ fake IPN , no need to do anything
    header("HTTP/1.1 403 Unauthorized");
}