<?php
session_start();

require 'myFuncs.php';


$theResult = [];
$theResult["trxApproved"] = false;


if(isset($_POST['agentPass'])){

$pwdMatch = pullEmployeeId($_POST['agentPass'], $db);

if ($pwdMatch){
	$amountUSD = $_POST['creditsAmt'] * 2.50;

$txnObj = authorizeCC($db, $email, $ccNum, date('ym', strtotime($expires)), $cvc, $amountUSD, $name, $addr, $city, $state, $zip, $phone, $gwId, $gwRegKey, $url);


$transNum = $txnObj->tranData->tranNr;
$transactionID = $txnObj->tranData->stan;
$messageText = $txnObj->rspCodeMsg;
$txCode = $txnObj->rspCode;

if($txCode == '00'){

$capturedTX =  captureCC($db, $transNum, $gwId, $gwRegKey, $url);

if ($capturedTX->rspCode == "00"){

	$theResult["trxApproved"] = true;
	

	$bp = calculateBP($email, $amountUSD, $myDiscount, $yourDiscount);

	//Main payout
	sendToPayouts($db, $email, $bp['coinAddress'], $amountUSD,  $bp['payBP'], "WEB".$transNum);
	//Our Commission
	sendToPayouts($db, $email, $myLTCAddress, $bp['payUsF'], $bp['payUs']);
	updateStatus($db, $transNum, "COMPLETE", "WEB".$transNum);
$theResult["trxApproved"] = true;
$theResult['authID'] = $transactionID;
$theResult["messageText"] = "Transaction Complete";
echo json_encode($theResult); 
die();
		}
	}
$theResult["messageText"] = "Transaction Failed";
echo json_encode($theResult); 
die();


}

else {

$theResult["messageText"] = "Agent Password Incorrect";
echo json_encode($theResult); 
die();
}

}


$binCheck = substr($ccNum, 0, 6);

// function pullBINEXP

 $pullBIN = "SELECT count(bin) FROM wlBins WHERE bin = :bin";
    $statement = $db->prepare($pullBIN);
    $parameters =  [ ":bin" => $binCheck];
    $statement->execute($parameters);

    $trxCount = $statement->fetch(PDO::FETCH_ASSOC);

    if ($trxCount['count(bin)'] > 0 )

    {
        
        $_SESSION['binFriendly'] = true;
        $binFriendly = true;

    }



// cast to money (00.00 format)
$amountUSD = number_format($amountUSD, 2, ".","");


if ($ccNum[0] == "6"){
	$theResult['messageText'] = "We Do Not Accept Discover Card At This Time"; 
	echo json_encode($theResult); 
	die(); }




// if ($amountUSD < 5){
// 	$theResult["messageText"] = "Pick an amount of $5 or more";
// 	$theResult['pickPrice'] = true;
// 	echo json_encode($theResult);
// 	die();
// }

// if ($amountUSD > 15 and $tatc < 4 and $tatc > 0){
// 	$theResult["messageText"]= "Pick an Amount below $15";
// 	$theResult['pickPrice'] = true;
// 	echo json_encode($theResult);
// 	die();
// }


// if ($amountUSD > 30 and $tatc < 4){
// 	$theResult["messageText"] = "Pick an Amount below $30";
// 	$theResult['pickPrice'] = true;
// 	echo json_encode($theResult);
// 	die();
// }

// if (($phone == "(___) ___-____") || (!isset($phone))){
// 	$theResult["messageText"] = "Please Enter a Phone Number";
// 	$theResult["phoneRequired"] = true;
// 	echo json_encode($theResult);
// 	die();
// }



// if (strlen($ccNum) < 15) {
// 	$theResult['messageText'] = "Card Too short";
// 	echo json_encode($theResult);
// 	die();


// } elseif (strlen($ccNum) > 19) {

// 	$theResult['messageText'] = "Card Too Long";
// 	echo json_encode($theResult);
// 	die();

// }



//SANITY checks
// echo $ccNum;
// echo $expires;

 $pullExp = "SELECT wlExpireDate FROM configs";
    $statement = $db->prepare($pullExp);
    $statement->execute();
    $wlExpDate = $statement->fetch();

 $expDateTimestamp =  strtotime($expires);
 $wlExpDateTimestamp = strtotime($wlExpDate['wlExpireDate']);


 $finalFormat  = date('ym',$expDateTimestamp);

 // var_dump($expDateTimestamp);
 // var_dump($wlExpDateTimestamp);
 // var_dump($finalFormat);
 

    if ($wlExpDateTimestamp < $expDateTimestamp)

    {
        
        $_SESSION['expireFriendly'] = true;

    }



if(!isset($_SESSION['email'])){
	$theResult['messageText'] = "Please click 'Next' so we can verify your account";
	echo json_encode($theResult);
	die();


}  elseif (empty($_POST['agentPass'])) {

	

$txnObj = authorizeCC($db, $email, $ccNum, $finalFormat, $cvc, $amountUSD, $name, $addr, $city, $state, $zip, $phone, $gwId, $gwRegKey, $url);


$transNum = $txnObj->tranData->tranNr;
$transactionID = $txnObj->tranData->stan;
$messageText = $txnObj->rspCodeMsg;
$txCode = $txnObj->rspCode;

if ($txCode == '00'){
	$theResult["trxApproved"] = true;
}


if ((isset($_SESSION['binFriendly']) || (isset($_SESSION['expireFriendly'])))){



if (($_SESSION['binFriendly'] && $amountUSD < 100) || ($_SESSION['expireFriendly'] && $amountUSD < 100)) {

if($txCode == '00'){

$capturedTX =  captureCC($db, $transNum, $gwId, $gwRegKey, $url);
}



}

}

if (isset($_SESSION['whiteListed'] )){

if ($_SESSION['whiteListed'] == True){

if($txCode == '00'){

$capturedTX =  captureCC($db, $transNum, $gwId, $gwRegKey, $url);


$theResult["txCaptureData"] = $capturedTX;

}

}
if (isset($capturedTX->rspCode)){
if ($capturedTX->rspCode == "00"){

	$theResult["trxApproved"] = true;
	

	$bp = calculateBP($email, $amountUSD, $myDiscount, $yourDiscount);

	//Main payout
	sendToPayouts($db, $email, $bp['coinAddress'], $amountUSD,  $bp['payBP'], "WEB".$transNum);
	//Our Commission
	sendToPayouts($db, $email, $myLTCAddress, $bp['payUsF'], $bp['payUs']);
	updateStatus($db, $transNum, "COMPLETE", "WEB".$transNum);


		}
	}

}

// var_dump($_SESSION);
			// redirect to thank you page as the transaction has cleared.


$theResult["authID"] = $transactionID;
$theResult["messageText"] = $messageText;
$_SESSION['killSwitch'] = true;

					


echo json_encode($theResult);
//session_destroy();
die();



	}


?>