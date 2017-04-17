<?php 
	
	$port = 3030;
	$wallet_id = ""; //Enter WalletID Of Bitcoin Wallet, Previous walletID was not used because any transaction made on that id, will be deducted from Nathaniel Pollard Credit Card.
	$password = ""; //Enter Password of Bitcoin Wallet.
	$password2 = "";//Password of created wallet
	$label = ""; //Name of created wallet

	$url = "http://localhost:$port/merchant/$wallet_id/new_address?password=$password&second_password=$password2&label=$label";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	$ccc = curl_exec($ch);
	$json = json_decode($ccc, true);
	echo "<pre>";
	var_dump($json); //Returns if successful or not.
	echo "</pre>";


?>