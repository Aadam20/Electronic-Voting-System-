<?php
include "lib.php";

$parties = getParties();
echo(json_encode($parties));
?>