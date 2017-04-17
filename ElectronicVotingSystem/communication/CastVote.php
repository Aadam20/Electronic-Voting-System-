<?php
include "lib.php";

function castVote(){

  $registration = $_POST['RegistrationNumber'];
  $party = $_POST['Party'];

  return vote($registration, $party);

}

echo(castVote());

 ?>
