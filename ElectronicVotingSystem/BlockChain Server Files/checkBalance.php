<?php

$port = 3030;
$wallet_id = ""; //Enter WalletID Of Bitcoin Wallet, Previous walletID was not used because any transaction made on that id, will be deducted from Nathaniel Pollard Credit Card.
$password = ""; //Enter Password of Bitcoin Wallet.


switch ($_SERVER['HTTP_ORIGIN']){
    case 'http://evsgrenada.com': //server will only respond to requests that originated from this domain.
    header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
   
	
	$url = "http://localhost:$port/merchant/$wallet_id/balance?password=$password";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	$ccc = curl_exec($ch);
	$json = json_decode($ccc, true);
	
	echo(json_encode($json)); //returns the balance of the main wallet.
	
	
	break;
	
}

?>