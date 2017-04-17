<?php
include "lib.php";

function verifyLogin(){

  $registration = $_POST['RegistrationNumber'];
  $password = $_POST['Password'];

  $loginSuccess = login($registration, $password);

  return $loginSuccess;

}


echo(verifyLogin());

 ?>
