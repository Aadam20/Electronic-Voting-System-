<?php
	
	function getDBConnection(){
	try{ // Uses try and catch to handle any unforeseen errors
		$db = new mysqli("localhost","root","","evsdatabase");
		if ($db == null && $db->connect_errno > 0) return null;
		return $db;
	}catch(Exception $e){ } // We currently do nothing in the catch, but later we can log
	return null;
}
	
	function login($regNo, $password){
	$success = 0;
	$sql = "SELECT `adminID`, `password` FROM `adminList` WHERE adminID = $regNo";
	$db = getDBConnection();
	if ($db) {
		if ($result = $db->query($sql)) {
			$row = $result->fetch_array(MYSQLI_ASSOC);
			if ($row) {
				if ($row['password'] == $password) {
					$success = 1;
				}
			}
		}
		$db->close();
	}
	return $success;
	}

	$reg = $_POST['admin'];
	$pass = $_POST['pass'];
	
	$success = login($reg, $pass);
	echo($success);	
	
?>