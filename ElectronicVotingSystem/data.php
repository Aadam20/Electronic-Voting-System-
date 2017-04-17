<?php

//setting header to json
header('Content-Type: application/json');

//database
define('DB_HOST', '104.131.65.53');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'mercery1?');
define('DB_NAME', 'evsdatabase');

//get connection
function getDBConnection(){
	$db_connect =  mysqli_connect("localhost", "root", "", "evsdatabase");
// Evaluate the connection
if (mysqli_connect_errno()) {
    echo mysqli_connect_error();
    exit();
}
else{
   //successful connection
    return $db_connect;
	}
}

$db = getDBConnection();


//query to get data from the table
$query = "SELECT * FROM `votes`";

//execute query
$result = $db->query($query);


//loop through the returned data
$data = array();
foreach ($result as $row) {
	$data[] = $row;
}

//free memory associated with result
//$result->close();

//close connection
//$db->close();

//now print the data

echo json_encode($data);
?>