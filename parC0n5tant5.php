<?php 

require "dBcOnNLocal.php" ;

//PRODUCTION
//require("dBcOnN.php");


//var_dump($_SESSION);

$myLTCAddress = "LXdhkP2hVkhYTXJN4fDpXqGy5Lfs1ifRyR";
$gwId = "7777779070";
$gwRegKey  = 'FYBQ79K6GSNXAF4Q';
$url = "https://cert.web.transaction.transactionexpress.com/TransFirst.Transaction.Web/api/SendTran";

$myDiscount = 0.74;
$yourDiscount =  0.861;



$binFriendly = false;


// //chargeCard.php Vars
$ip = $_SERVER["REMOTE_ADDR"];
$email = filter_input(INPUT_POST, "email");
$name = filter_input(INPUT_POST, "name");
$amountUSD = filter_input(INPUT_POST, "dollarAmt");
$ccNum = trim(filter_input(INPUT_POST, "ccNum"));
$expires = trim(filter_input(INPUT_POST, "expires"));
$ccNum = str_replace(' ','',$ccNum);
$cvc = filter_input(INPUT_POST, "cvc");
$addr = filter_input(INPUT_POST, "addr");
$city = filter_input(INPUT_POST, "city");
$state = filter_input(INPUT_POST, "state");
$zip = filter_input(INPUT_POST, "zip");
$phone = filter_input(INPUT_POST, 'phone');
$amznPhone = filter_input(INPUT_POST, 'amznPhone');
$amznCard = filter_input(INPUT_POST, 'amznCard');
$pAssW = filter_input(INPUT_POST, 'pAssW');



// //TESTING

// $ip = $_SERVER["REMOTE_ADDR"];
// $_SESSION['whiteListed'] = False;
// $_SESSION['email'] = "fx5a80i@gmail.com";
// $email = $_SESSION['email'];
// $name = "johnny Five";
// $amountUSD = "5";
// $creditsToPay = "10" * .5;
// $amtToPay = $creditsToPay / "19.34";
// $ccNum = trim("4111111111111111");
// $expires = trim("12/01/2019");
// $ccNum = str_replace(' ','',$ccNum);
// $cvc = "221";
// $addr = "123 EZ street";
// $city = "New York";
// $state = "NY";
// $zip = "10001";
// $phone = "(122) 233-3444";
