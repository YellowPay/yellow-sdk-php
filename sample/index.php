<?php
include "../vendor/autoload.php";
use Yellow\Bitcoin\Invoice;
include("keys.php");
$yellow = new Invoice($api_key,$api_secret);
/// this will return an array with invoice data
$invoice  = $yellow->createInvoice(0.05,"USD","http://mediacity.co/sdk/sample/ipn.php");
?>
<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Bitcoin sample</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
<!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->
<iframe src="<?php echo $invoice["url"]?>" width="100%" height="400px"></iframe>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.2.min.js"><\/script>')</script>
<script>
    function invoiceListener(event) {
        switch (event.data) {
            case "authorizing":
                alert("your payment is authorizing");
                window.location = "status.php?id=<?php echo $invoice["id"];?>";
                break;
            case "expired":
            case "refund_requested":
                alert(event.data + "status");
                break;
        }
    }
    // Attach the message listener
    if (window.addEventListener) {
        addEventListener("message", invoiceListener, false)
    } else {
        attachEvent("onmessage", invoiceListener)
    }
</script>
</body>
</html>

