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
	
	$party = $_POST['party'];
	if($party == "NDC"){
		
		$address = "1PjpKvHGQUXsVgFp2Ho9gWkvjrZjXT63Hi"; //Example address which will be a wallet generated with createAddress.php
	
	}
	
	else{
	
		$address = "12yooxDpCY3FfyXucsVaXFb3e4Lesd3f3B"; //Example address which will be a wallet generated with createAddress.php
	
	}
	
	
	
	$url = "http://localhost:$port/merchant/$wallet_id/address_balance?password=$password&address=$address";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	$ccc = curl_exec($ch);
	$json = json_decode($ccc, true);
	echo(json_encode($json)); //returns the balance in the given address.
	
	break;
	
	}
	
?>