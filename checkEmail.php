<?php
session_start();

require "myFuncs.php";



//TEST HARNESS
//$ran  = ['429ovd+cplfw0qpl6a2c@sharklasers.com', 'barbie050372@gmail.com', 'barbieprincess051@gmail.com', 'BrookQueen.70@gmail.com', 'notreal@gmail.com', 's.silhan@yahoo.com', 'getstacks.2013@gmail.com' ];

//$randomElement = $ran[array_rand($ran, 1)];

//$email = $randomElement;

$email = strtolower($email);

$response = [];

// $response['emailpicked'] = $email;

//whitelisted? 


$trxLimiter = "SELECT count(email) FROM wlEmails WHERE email = :email";
$statement = $db->prepare($trxLimiter);
$parameters =  [ ":email" => $email];
$statement->execute($parameters);

$trxCount = $statement->fetch(PDO::FETCH_ASSOC);

if ($trxCount['count(email)'] > 0 )

{
    
    $_SESSION['whiteListed'] = true;

}



$bp = calculateBP($email, "1.00", $myDiscount, $yourDiscount);

if (!isset($bp['error'])){

$response['emailFound'] = 'success';
$_SESSION['email'] = $email;

} 



echo json_encode($response);
die();
    ?>