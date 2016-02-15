<?php

include("MGM.php");

$mgm = new MGM();

$mgm->location = "İstanbul";

echo $mgm->getWheaterCondition();


?>
