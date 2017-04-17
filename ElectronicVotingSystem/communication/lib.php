<?php

function getDBConnection(){
	try{ // Uses try and catch to handle any unforeseen errors
		$db = new mysqli("localhost","root","","evsdatabase");
		if ($db == null || $db->connect_errno > 0)return null;
		return $db;
	}catch(Exception $e){} // We currently do nothing in the catch, but later we can log
	return null;
}

function register($regNo, $constituency, $surname, $names, $sex, $DOB, $COB, $issued, $expire, $heightFeet, $heightInches, $phone, $email) {
	$password = randomPassword();
	$encryptedPass = sha1($password);
	$sql = "INSERT INTO `voters` (`RegistrationNumber`, `Constituency`, `Surname`, `GivenNames`, `Sex`, `DateOfBirth`, `CountryOfBirth`, `DateIssued`, `ExpireDate`, `HeightFeet`,`HeightInches`, `Voted`, `Password`, `Phone`, `E-Mail`) VALUES ('$regNo', '$constituency', '$surname', '$names', '$sex', '$DOB', '$COB', '$issued', '$expire', '$heightFeet', '$heightInches','0', '$encryptedPass', '$phone', '$email');";

	$sql2 = "INSERT INTO `mailinglist` (`RegistrationNumber`, `Constituency`, `Password`) VALUES ('$regNo', '$constituency', '$password');";
	$db = getDBConnection();
	// Needs to be completed (Successful)
	$successful = True;
	if ($db) {
		$successful = $db->query($sql);
		$db->query($sql2);
		$db->close();
	}
	return True;
}

function getConstituencies() {
	$db = getDBConnection();
	$constituencies = [];
	if ($db) {
		$sql = "SELECT `Constituency` FROM `votes`;";
		$results = $db->query($sql);
		while ($results && $row = $results->fetch_array(MYSQLI_ASSOC)) {
			$constituencies[] = $row['Constituency'];
		}
	}
	return $constituencies;
}

function login($regNo, $password) {
	$success = 0;
	$pass = sha1($password);
	$sql = "SELECT `RegistrationNumber`, `Voted`, `Password` FROM `voters` WHERE RegistrationNumber = $regNo";
	$db = getDBConnection();
	if ($db) {
		if ($result = $db->query($sql)) {
			$row = $result->fetch_array(MYSQLI_ASSOC);
			if ($row['Voted'] == 1) return -1; // Citizen already voted.
			if ($row) {
				if ($row['Password'] == $pass) {
					$success = 1;
				}
			}
		}
		$db->close();
	}
	return $success;
}

function vote($regNo, $party) {
	$success = 0;
	$db = getDBConnection();
	$sql = "SELECT `RegistrationNumber`, `Constituency`, `Voted` FROM voters WHERE RegistrationNumber = $regNo";
	if ($db && $result = $db->query($sql)) {
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$sql = "UPDATE votes_online SET $party = $party + 1 WHERE Constituency = '".$row['Constituency']."'";
		if ($db->query($sql)) $success = True;
	}

	if ($success) {
		$sql = "UPDATE voters SET Voted = 1 WHERE RegistrationNumber = $regNo";
		$db->query($sql);
	}
	return $success;
}

function randomPassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array();
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 10; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

// function getVotes() {
// 	$votes = [];
// 	$db = getDBConnection();
// 	$sql = "SELECT * FROM votes";
// 	if ($db) {
// 		$results = $db->query($sql);
// 		while ($results && $row = $results->fetch_array(MYSQLI_ASSOC)) {
// 			$votes [] = $row;
// 		}
// 		$db->close();
// 	}
// 	return $votes;
// }

function getVotes() {
	$votes_online = [];
	$db = getDBConnection();
	$sql = "SELECT * FROM votes_online;";
	if ($db) {
		$results = $db->query($sql);
		while ($results && $row = $results->fetch_array(MYSQLI_ASSOC)) {
			$votes_online [] = $row;
		}
	}

	$votes_offline = [];
	$sql2 = "SELECT * FROM votes_offline;";
	if ($db) {
		$results = $db->query($sql2);
		while ($results && $row = $results->fetch_array(MYSQLI_ASSOC)) {
			$votes_offline [] = $row;
		}
		$db->close();
	}
	$votes [] = $votes_online;
	$votes [] = $votes_offline;
	return $votes;
}

function getParties() {
	$parties = [];
	$db = getDBConnection();
	$sql = "SELECT * FROM parties";
	if ($db) {
		$results = $db->query($sql);
		while ($results && $row = $results->fetch_array(MYSQLI_ASSOC)) {
			$parties [] = $row;
		}
		$db->close();
	}
	return $parties;
}

function checkResults(){

	$released = - 1;
	$db = getDBConnection();
	$sql = "SELECT * FROM results";

	if ($db) {
		$results = $db->query($sql);
		while ($results && $row = $results->fetch_array(MYSQLI_ASSOC)) {
			$released = $row['released'];
		}

		$db->close();
	}
	return $released;

}

?>
