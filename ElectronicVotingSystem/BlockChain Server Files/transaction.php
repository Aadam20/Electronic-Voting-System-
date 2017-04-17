<?php 
	
	//This file will register a transaction of 547 satoshis to the to party the selected party.
	//Number of votes placed for a specific party = Balance In The Desired Party's Address / 547
	
	$port = 3030;
	$wallet_id = ""; //Enter WalletID Of Bitcoin Wallet, Previous walletID was not used because any transaction made on that id, will be deducted from Nathaniel Pollard Credit Card.
	$password = ""; //Enter Password of Bitcoin Wallet.
	
	$password2 = "";//Password of second wallet.
	$amount = 547; //Minimum amount of satoshi that can be sent across wallets is 546.  
	$fee = 10;

	switch ($_SERVER['HTTP_ORIGIN']) {
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
	
	$url = "http://localhost:$port/merchant/$wallet_id/payment?password=$main_password&amount=$amount&to=$address&fee=$fee&from=0";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	$ccc = curl_exec($ch);
	$json = json_decode($ccc, true);
	echo(json_encode($json)); //Will return transaction details.
	
	
	break;
	
	}
	
	
	
	
	
	


?>