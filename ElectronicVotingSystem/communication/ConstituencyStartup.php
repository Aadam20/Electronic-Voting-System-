<?php
include "lib.php";

$constituency = getConstituencies();

echo(json_encode($constituency));

 ?>
