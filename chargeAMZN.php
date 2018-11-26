<?php

require 'myFuncs.php';



session_start();

date_default_timezone_set("America/Los_Angeles"); 
$now = date("Y-m-d H:i:s");

$theResult = [];


if(isset($_POST['agentPass'])){

$admin = pullAdmin($_POST['agentPass'], $db);
if (!$admin){

$employeeId = pullEmployeeId($_POST['agentPass'], $db);
} else {
	$employeeId = $admin;
}

if (!$employeeId){

	$theResult['messageText'] = "Incorrect Password!";
	echo json_encode($theResult);
	die();

}

}


if(!isset($email)){
	$theResult["trxApproved"] = false;
	$theResult['messageText'] = "Please click 'Next' so we can verify your account";
	echo json_encode($theResult);
	die();

}


//TESTING

if(!isset($employeeId)) {



if (($amznPhone == "(___) ___-____") || (!isset($amznPhone))){
	$theResult["messageText"] = "Please Enter a Phone Number";
	$theResult["phoneRequired"] = true;
	echo json_encode($theResult);
	die();
}





else {

					$successTrx = $db->prepare(
						'INSERT INTO `webTx` (`email`, `orderNumber`, `status`, `ip`, `phone`)
						VALUES (:email, :orderNumber, :status, :ip, :phone)'
						);

					$successTrx->execute([

						':email' => $email,
						':orderNumber' => $amznCard,
						':status' => "NEW",
						':ip' => $ip,
						':phone' => $amznPhone
						]);

$theResult["trxApproved"] = true;
$theResult["messageText"] = "Please Wait As we Confirm This Transaction";
$_SESSION['killSwitch'] = true;

					


echo json_encode($theResult);
session_destroy();
die();



	}
} 

elseif ($employeeId) {


	$successTrx = $db->prepare(
						'INSERT INTO `webTx` (`email`, `orderNumber`, `status`, `orderAmt`, `employeeId`)
						VALUES (:email, :orderNumber, :status, :amount, :eid)'
						);

					$successTrx->execute([

						':email' => $email,
						':orderNumber' => $amznCard,
						':status' => "COMPLETE",
						':amount' => $_POST['amount'],
						':eid' => $employeeId
						]);



	$bp = calculateBP($email, $_POST['amount'], $myDiscount, $yourDiscount);

	$webReference = "WEB".$amznCard;
	//Main payout
	sendToPayouts($db, $email, $bp['coinAddress'], $_POST['amount'],  $bp['payBP'], $webReference);
	//Our Commission
	sendToPayouts($db, $email, $myLTCAddress, $bp['payUsF'], $bp['payUs']);


$theResult["trxApproved"] = true;
$theResult["messageText"] = "";
$_SESSION['killSwitch'] = true;



echo json_encode($theResult);
die();


}




?>