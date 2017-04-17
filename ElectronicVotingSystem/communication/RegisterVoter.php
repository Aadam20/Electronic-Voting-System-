<?php
include "lib.php";

function check(){

  $id = -1;

  $db = getDBConnection();

  if($db == null){
    return $id; //will output and Exception error therefore the id will not be seen in javascript file(check).
  }
  $db->close();

  $constituency = $_POST['Constituency'];

  if(($constituency != "0")&& isset($_POST['Sex'])) $id = 1;

  return $id;


}

function registerVoter(){

  	$regNo = $_POST['RegistrationNumber'];
  	$constituency = $_POST['Constituency'];
  	$surname = $_POST['Surname'];
  	$names = $_POST['GivenNames'];
  	$sex = $_POST['Sex'];
  	$DOB = $_POST['DateOfBirth'];
  	$COB = $_POST['CountryOfBirth'];
  	$issued = $_POST['DateIssued'];
  	$expire = $_POST['ExpireDate'];
  	$heightFeet = $_POST['HeightFeet'];
  	$heightInches = $_POST['HeightInches'];
  	$phone = $_POST['Phone'];
  	$email = $_POST['E-Mail'];

///////////////////Variable Corrections and Password Setup/////////////////////////////////////////////////////////////////////////

    if($heightInches == "") $heightInches = "0";
    $password = randomPassword();
    $encryptedPass = sha1($password);

////////////////////Database Uploading////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $db = getDBConnection();
    $sql2 = "INSERT INTO `mailinglist` (`RegistrationNumber`, `Constituency`, `Password`) VALUES ('$regNo', '$constituency', '$password');";
    $sql = "INSERT INTO `voters` (`RegistrationNumber`, `Surname`, `GivenNames`, `Sex`, `DateOfBirth`, `CountryOfBirth`, `DateIssued`, `ExpireDate`, `HeightFeet`, `HeightInches`, `Constituency`, `Password`, `Phone`, `E-Mail`)";
    $sql .= " VALUES ('$regNo', '$surname', '$names', '$sex', '$DOB', '$COB' , '$issued', '$expire', '$heightFeet', '$heightInches', '$constituency', '$encryptedPass', '$phone', '$email');";

    if($db){

      $result = $db->query($sql);
      if($result){
        $id = 1;
        $db->query($sql2);
      }
      else{
        $id = -1;
      }

      $db->close();

    }

  return $id;

}

$function = $_POST['Function'];

if($function == "check"){

  $checkid = check();
  echo($checkid);

}

if($function == "register"){

$id = registerVoter();
echo($id);

}

 ?>
