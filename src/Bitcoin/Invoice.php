<?php
namespace Yellow\Bitcoin ; 

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;


class Invoice implements InvoiceInterface
{
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
    private $api_secert;

	public function __construct($api_key,$api_secert)
	{
		/// set custom API server 
		$custom_server_root = getenv("YELLOW_API_SERVER");
        if($custom_server_root){
            $this->server_root = $custom_server_root;
        }
        $this->api_key = $api_key;
        $this->api_secert = $api_secert;
        return $this;
	}

	public function createInvoice($amount,$currency)
	{
		try{
			if(!in_array($currency, array("USD" , "AED"))){
				throw new Exception("$currency is not supported by yellow");
			}	
			$payload = [
				'base_ccy' =>  $currency,
	        	'base_price'=> $amount 
			];
			$body = json_encode($payload);
			$nonce = round(microtime(true) * 1000);
	        $url = $this->server_root . $this->api_uri_create_invoice;
	        $message = $nonce . $url . $body;
	        $signature = hash_hmac("sha256", $message, $this->api_secert, false);
	        $data = [
	        	'headers' => [
		        	'API-Key'      => $this->api_key ,
		        	'API-Nonce'    => $nonce , 
		        	'API-Sign'     => $signature ,
		        	'API-Platform' => $this->getVersion(),
		            'API-Plugin'   => "SDK-v0.1",
		            'content-type' => 'application/json'
		        ],
		        'allow_redirects' => false,
    			'timeout'         => 300 , 
    			'body' 			  => $body
	        ];
	        $client = new Client();
	        $response = $client->post($url, $data);
	        $body = $response->getBody();
			return (string) $body ;
	    }catch(ClientException $e){
	    	if ($e->hasResponse()) {
		        return $e->getResponse();
		    }
	    }
    

	}

	public function checkInvoiceStatus($id)
	{
		try{
			$url = $this->server_root . str_replace("[id]", $id, $this->api_uri_check_payment);
	        $nonce = round(microtime(true) * 1000);
	        $message = $nonce . $url;
	        $signature = hash_hmac("sha256", $message, $this->api_secert, false);

			$data = [
	        	'headers' => [
		        	'API-Key'      => $this->api_key ,
		        	'API-Nonce'    => $nonce , 
		        	'API-Sign'     => $signature ,
		        	'API-Platform' => $this->getVersion(),
		            'API-Plugin'   => "SDK-v0.1",
		            'content-type' => 'application/json'
		        ],
		        'allow_redirects' => false,
    			'timeout'         => 300 
	        ];
	        $client = new Client();
	        $response = $client->get($url, $data);
	        $body = $response->getBody();
			return (string) $body ;	        
		}catch(ClientException $e){
			if ($e->hasResponse()) {
		        return $e->getResponse();
		    }
		}

	}	


	private function getVersion()
	{
		return PHP_OS . " - PHP " . PHP_VERSION ;
	}
}