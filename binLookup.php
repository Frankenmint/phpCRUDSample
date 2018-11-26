<?php
session_start();

require "myFuncs.php";

$myRes = [];


$res = binLookup($db, $_POST['binLookup']);



if ($res == "true"){

	echo json_encode( $myRes['whiteListed'] = true);
}

else {

	echo json_encode($myRes['whiteListed'] = false);
}

?>