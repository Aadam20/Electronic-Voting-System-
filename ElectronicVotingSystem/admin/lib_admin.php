<?php
if (isset($_POST['Constituency']) && isset($_POST['NNP']) && isset($_POST['NDC'])) {
	$constituency = $_POST['Constituency'];
	$NNP = $_POST['NNP'];
	$NDC = $_POST['NDC'];
	$res = updatePoll($constituency, $NNP, $NDC);
}

if (isset($_POST['Constituency']) && isset($_POST['DeleteConstituency'])) {
	$constituencyDel = $_POST['Constituency'];
	$res = deleteConstituency($constituencyDel);
	//echo $res;
} 

function getDBConnection(){
	try{ // Uses try and catch to handle any unforeseen errors
		$db = new mysqli("localhost","root","","evsdatabase");
		if ($db == null && $db->connect_errno > 0) return null;
		return $db;
	}catch(Exception $e){ } // We currently do nothing in the catch, but later we can log
	return null;
}

function getVotes() {
	$votes = [];
	$db = getDBConnection();
	$sql = "SELECT * FROM votes";
	if ($db) {
		$results = $db->query($sql);
		while ($results && $row = $results->fetch_array(MYSQLI_ASSOC)) {
			$votes [] = $row;
		}
		$db->close();
	}
	return $votes;
}

function updatePoll($constituency, $NNP,  $NDC) {
	$success = False;
	$db = getDBConnection();
	$sql = "UPDATE votes SET NNPTraditional = $NNP, NDCTraditional = $NDC WHERE Constituency = '$constituency';";
	if ($db) {
		$success = $db->query($sql);
		$db->close();
	}
	return $success;
}

function deleteConstituency($constituency) {
	$success = False;
	$db = getDBConnection();
	$sql = "DELETE FROM `evsdatabase`.`votes` WHERE `votes`.`Constituency` = '$constituency';";
	if ($db) {
		$success = $db->query($sql);
		$db->close();
	}
	if ($success == False) {
		echo "<script type='text/javascript'>alert('Ooops Delete Fail');</script>";
	}
	return $success;
}

function addConstituency($constituency) {
	$success = False;
	$db = getDBConnection();
	$sql = "INSERT INTO votes (Constituency) VALUES ('$constituency');";
	if ($db) {
		$success = $db->query($sql);
		$db->close();
	}
	return $success;
}

function editConstituency($old, $new) {
	$success = False;
	$db = getDBConnection();
	$sql = "UPDATE  `evsdatabase`.`votes` SET  `Constituency` =  '$new' WHERE  `votes`.`Constituency` =  '$old';";
	if ($db) {
		$success = $db->query($sql);
		$db->close();
	}
	return $success;
}

?>