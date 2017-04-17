<?php
include "lib.php";

$votes = getVotes();
echo(json_encode($votes));
?>