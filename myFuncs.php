<?php


require "parC0n5tant5.php";


function testHarness(){

echo "$('#email').val('fx5a80i@gmail.com');
$('#custName').val('ohhnnfkldj');
$('#amountUSD').val('25');
$('#ccNum').val('4111111111111111');
$('#cvc').val('455');
$('#addr').val('ohhnnfkldj 29JDFLKL');
$('#city').val('ohhnnfkldj');
$('#state').val('ohhnnfkldj'); 
$('#zip').val('24454');
$('#phone').val('(244) 242-3131');
$('#amznphoneNumber').val('(244) 242-3131');
// $('#amznCard').val('this-is-a-gift-card_');

";


}


function putHeader($title) {


echo '<!DOCTYPE html>
<html>
<head>
    <title>'.$title.'</title>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
    <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.11.0/sweetalert2.min.css" />

<style>

.fail.material-icons {
    margin: auto 20%;
}
.success.material-icons {
    margin: auto 29%;
}

.emailConfirm {
    position: relative;
    left: 10px;
    top: 8px;
}

#credits, #coinAmt {
    -moz-appearance: textfield;
    -webkit-appearance: textfield;
    appearance: textfield;
}


.success { color: green;
font-weight: 700; }
.fail { color: red;
font-weight: 700; }

button {
    width: 228px;
    padding: 10px;
    margin: 10px;
}
input{
    padding: 10px;
    margin: 10px;
    width: 200px;
}


table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}
th, td {
    padding: 5px;
}
th {
    text-align: left;
} 


.pad10 {

    margin-right: 10%;
}


table.scroll {
    width: 100%; /* Optional */
    border-collapse: collapse;
    border-spacing: 0;
    border: 1px double black;
}

table.scroll tbody,
table.scroll thead { display: block; }

thead tr th { 
    height: 30px;
    line-height: 30px;
    /*text-align: left;*/
}

table.scroll tbody {
    height: 300px;
    overflow-y: auto;
    overflow-x: hidden;
}

tbody { border-top: 2px solid black; }

tbody td, thead th {
    width: 1%;  /* Optional */
    border-right: 1px double black;
}

tbody td:last-child, thead th:last-child {
    border-right: none;
}

</style></head>';

}

function putJS(){

echo '<!--  Scripts-->
    <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script src="js/materialize.js"></script>
    <script src="js/init.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.11.0/sweetalert2.all.min.js"></script>
    <script src="js/promise.min.js"></script>
    <script src="js/bundle.js"></script>
    ';

}

function updateStatus($db, $transNum, $status, $agent){

$apiResponse = $db->prepare(
    'UPDATE `webTx` SET `status` = :status, `employeeId` = :eid WHERE `orderNumber` = :orderNumber');

  $apiResponse->execute([
    ':status' => $status,
    ':eid' => $agent,
    ':orderNumber' => $transNum
    ]);

}

function binLookup($db, $binLookup) {


 $pullBIN = "SELECT count(bin) FROM wlBins WHERE bin = :bin";
    $statement = $db->prepare($pullBIN);
    $parameters =  [ ":bin" => $binLookup];
    $statement->execute($parameters);

    $trxCount = $statement->fetch(PDO::FETCH_ASSOC);

    if ($trxCount['count(bin)'] > 0 )

    { 

        return true;

    } else

    {
        return false;

    }



}


function authorizeCC($db, $email, $card, $exp, $cvv, $amt, $name, $addr, $city, $state, $zip, $phone, $gwId, $gwRegKey, $url){


 $ch = curl_init($url);

  $gc = binLookup($db, substr($card, 0, 5));

  if($gc) {

        
      $jsonData = array(
        "Merc" => array (
        "id" => $gwId,
        "regKey" => $gwRegKey,
    "inType" =>1

    ),

       "Card" => array("sec"=> $cvv,
    "pan" => $card,
    "xprDt" => $exp ), 
       "reqAmt" => (int) $amt * 100,

       "tranCode" => 0 );
        
} else 

{

   $jsonData = array(
        "Merc" => array (
        "id" => $gwId,
        "regKey" => $gwRegKey,
    "inType" =>1

    ),

       "Card" => array("sec"=> $cvv,
    "pan" => $card,
    "xprDt" => $exp ), 
       "reqAmt" => (int) $amt * 100,
       array("AddressLine1" =>$addr,"City"=>$city,"State"=>$state,"Zip"=>$zip),

       "tranCode" => 0 );

}




    $jsonDataEncoded = json_encode($jsonData);

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
    $result = json_decode(curl_exec($ch));
    // var_dump($jsonDataEncoded);
    // var_dump($result);
    curl_close($ch);



  $apiResponse = $db->prepare(
    'INSERT INTO `webTx` (`email`, `status`, `orderAmt`, `orderNumber`, `txCode`, `txResult`, `ip`, `name`, `address`, `city`, `state`, `zip`,`phone`)
    VALUES (:email, :status, :orderAmt, :orderNumber, :txCode, :txResult, :ip, :name, :address, :city, :state, :zip, :phone)'
    );


if ($result->rspCode == '00'){
  $apiResponse->execute([

    ':email' => $_SESSION['email'],
    ':status' => "NEW",
    ':orderAmt' => $amt,
    ':orderNumber' => $result->tranData->tranNr,
    'txCode' => $result->rspCode,
    ':txResult' => $result->rspCodeMsg,
    ':ip' => $_SERVER["REMOTE_ADDR"],
    ':name' => $name,
    ':address' => $addr, 
    ':city' => $city, 
    ':state' => $state, 
    ':zip' => $zip,
    ':phone' => $phone
    ]);

  
}

else {

  $apiResponse->execute([

    ':email' => $_SESSION['email'],
    ':status' => "DECLINED",
    ':orderAmt' => $amt,
    ':orderNumber' => $result->tranData->tranNr,
    'txCode' => $result->rspCode,
    ':txResult' => $result->rspCodeMsg,
    ':ip' => $_SERVER["REMOTE_ADDR"],
    ':name' => $name,
    ':address' => $addr, 
    ':city' => $city, 
    ':state' => $state, 
    ':zip' => $zip,
    ':phone' => $phone
    ]);

  
}


    return $result;

}



function captureCC($db, $transNum, $gwId, $gwRegKey, $url){
    $ch = curl_init($url);
        $jsonData = array(
            "Merc" => array (
              "id" => $gwId,
              "regKey" => $gwRegKey,
              "inType" =>1),
          "tranCode" => 3,
          'origTranData' => array('tranNr' => $transNum));


          $jsonDataEncoded = json_encode($jsonData);

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
    $result = json_decode(curl_exec($ch));
    #var_dump($jsonDataEncoded);
    #var_dump($result);
    curl_close($ch);




return $result;

  }

//  function sendToPayouts($db, $email, $coinAddr, $dollarAmt, $coinAmt, $parent=NULL ){

// //FAKE FUNCTION TILL WE ACTUALLY GO LIVE
  

// }

function sendToPayouts($db, $email, $coinAddr, $dollarAmt, $coinAmt, $parent=NULL ){

    $stmt = $db->prepare(
        'INSERT INTO `payouts` (`coinTxId`, `email` ,`coinAddr`, `amtInFiat`, `amtInCoin`, `parentTxKey`)
         VALUES ( :ctx, :email, :coinAddr, :amt, :amtInCoin, :parentTxKey)');

    date_default_timezone_set("America/Los_Angeles"); 
    $now = date("Y-m-d H:i:s");

    $stmt->execute([

        ':ctx' => "COMPLETE:p",
        ':email' => $email,
        ':coinAddr' => $coinAddr,
        ':amt' => $dollarAmt,
        ':amtInCoin' => $coinAmt,
        ':parentTxKey' => $parent
    ]);

  
}


function calculateBP($email, $amount, $myDiscount, $yourDiscount){

$outputs = [];

$bpUrl = "https://secure.backpage.com/service/v2/bpi/generate_affiliate_address.php";
$ch = curl_init($bpUrl);
$jsonData = array(
  "code" => "ghAH2nq4bq2c9Ra2",
  "email" => $email,
  "currency" => "ltc"

);


//Encode the array into JSON.
$jsonDataEncoded = json_encode($jsonData);

curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
$result = json_decode(curl_exec($ch));
curl_close($ch);

if (!$result->error){

$priceRate = $result->price;

$youPay = round(($amount  * $yourDiscount) / $priceRate, 8);
$wePay = round(($amount  * $myDiscount) / $priceRate, 8);
$combined =  $youPay - $wePay;

$outputs['coinAddress'] = $result->address;
$outputs['payBP'] = round($wePay, 8);
$outputs['payUs'] = round($combined, 8);
$outputs['payUsF'] = round($combined * $priceRate, 2);
$outputs['result'] = $result;

}

else {
  $outputs['error'] = true;
}
return $outputs;

}


function pullEmployeeId($pass, $db){

$preparedStatement = $db->prepare(
    "SELECT employeeId from employees where pass = :pass");

    $preparedStatement->execute([':pass' => md5($pass)]);
    $res = $preparedStatement->fetch();


if (!empty($res['employeeId'])){


#echo " we found your IP address!";
return $res['employeeId'];

} else {
#echo "We cannot find your IP address";
return false;
}

#"<span class='fail' >Password Incorrect!</span>"


}


function pullAdmin($pass, $db){

$preparedStatement = $db->prepare(
    "SELECT adminUser from configs where adminPass = :pass");

    $preparedStatement->execute([':pass' => md5($pass)]);
    $res = $preparedStatement->fetch();


if (!empty($res['adminUser'])){


#echo " we found your IP address!";
return $res['adminUser'];

} else {
#echo "We cannot find your IP address";
return false;
}

#"<span class='fail' >Password Incorrect!</span>"


}


?>