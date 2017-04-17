<?php
//ONLY NEEDS TO BE PERFORMED ONCE.

$port = 3030;
$wallet_id = ""; //Enter WalletID Of Bitcoin Wallet, Previous walletID was not used because any transaction made on that id, will be deducted from Nathaniel Pollard Credit Card.
$password = ""; //Enter Password of Bitcoin Wallet.
$api = ""; //Enter API code

$url = "http://localhost:3030/merchant/$wallet_id/login?password=$password&$api_code=$api";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	$ccc = curl_exec($ch);
	$json = json_decode($ccc, true);
	echo "<pre>";
	var_dump($json); //Shows if login was successful.
	echo "</pre>";

?>