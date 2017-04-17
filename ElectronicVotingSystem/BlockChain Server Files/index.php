<?php

  $url = "http://localhost:3030/merchant/fe8b08ee-ea8a-4a87-9b04-deb3eec31077/login?password=mercery1?";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	$ccc = curl_exec($ch);
	$json = json_decode($ccc, true);
	echo "<pre>";
	var_dump($json);
	echo "</pre>";

?>