<?php
namespace Yellow\Bitcoin;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;


class Invoice implements InvoiceInterface
{
    const VERSION = "0.1";
    /**
     * Server Root for Yellow API
     *
     * @var String
     */
    private $server_root = "https://api.yellowpay.co/v1";

    /**
     * create invoice URI
     *
     * @var String
     */
    private $api_uri_create_invoice = "/invoice/";

    /**
     * check invoice status URI
     *
     * @var String
     */
    private $api_uri_check_payment = "/invoice/[id]/";

    /**
     * api key
     *
     * @var String
     */
    private $api_key;

    /**
     * api secret
     *
     * @var String
     */
    private $api_secret;

    public function __construct($api_key, $api_secret)
    {
        /// set custom API server
        $custom_server_root = getenv("YELLOW_API_SERVER");
        if ($custom_server_root) {
            $this->server_root = $custom_server_root;
        }
        $this->api_key = $api_key;
        $this->api_secret = $api_secret;
        return $this;
    }

    public function createInvoice($amount, $currency,$callback = "")
    {
        try {
            $payload = [
                'base_ccy'   => $currency,
                'base_price' => $amount,
                'callback'   => $callback
            ];
            $body = json_encode($payload);
            $nonce = round(microtime(true) * 1000);
            $url = $this->server_root . $this->api_uri_create_invoice;
            $message = $nonce . $url . $body;
            $signature = hash_hmac("sha256", $message, $this->api_secret, false);
            $data = $this->createHTTPData($signature,$nonce);
            $data["body"] =  $body;
            $client = new Client();
            $response = $client->post($url, $data);
            $body = $response->getBody();
            return (string)$body;
        } catch (ClientException $e) {
            if ($e->hasResponse()) {
                return $e->getResponse();
            }
        }


    }

    public function checkInvoiceStatus($id)
    {
        try {
            $url = $this->server_root . str_replace("[id]", $id, $this->api_uri_check_payment);
            $nonce = round(microtime(true) * 1000);
            $message = $nonce . $url;
            $signature = hash_hmac("sha256", $message, $this->api_secret, false);
            $data = $this->createHTTPData($signature,$nonce);
            $client = new Client();
            $response = $client->get($url, $data);
            $body = $response->getBody();
            return (string)$body;
        } catch (ClientException $e) {
            if ($e->hasResponse()) {
                return $e->getResponse();
            }
        }

    }


    private function getOSVersion()
    {
        return PHP_OS . " - PHP " . PHP_VERSION;
    }

    private function createHTTPData($signature,$nonce)
    {
        return [
            'headers' => [
                'API-Key' => $this->api_key,
                'API-Nonce' => $nonce,
                'API-Sign' => $signature,
                'API-Platform' => $this->getOSVersion(),
                'API-Plugin' => self::VERSION,
                'content-type' => 'application/json'
            ],
            'allow_redirects' => false,
            'timeout' => 300
        ];
    }
}