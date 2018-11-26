
<?php

function loginForm(){

echo '<div class="container">
    <div class="section">
    <h3 class="hero header center text-darken-2">Login Form</h3>
<form action="#" method="POST">               
                    <div class="row">
                            <div class="input-field col s12 m8">
<i class="material-icons prefix">lock</i>                      
<input type="password" id="pAssW" name="pAssW" placeholder="Unlock Password" required />
<label for="pAssW">Password</label>
</div>
<div class="input-field col s12 m4">
<button type="submit">Login</button>
</div>
</form></center>';
}


function passIPCheck($db, $ipAddress){
 
$preparedStatement = $db->prepare(
    "SELECT count(ipAddress) from ipTable where ipAddress = :ip");

    $preparedStatement->execute([':ip' => $ipAddress]);
    $res = $preparedStatement->fetch();
    // print_r($res);


if ($res[0] > 0){


#echo " we found your IP address!";
return true;

} else {
#echo "We cannot find your IP address";
return false;
}
}



//AND `status` LIKE "NEW" OR `status` LIKE "COMPLETE"


function pullAllTX($db){
  $stmt = $db->query('SELECT * FROM webTx  where cast(`webTx`.`createdOn` as date) >= curdate() - interval 3 day  AND `status` LIKE "COMPLETE"  ORDER BY createdOn DESC');
echo '<table class="scroll"><thead>
  <tr>
    <th>Date And Time</th>
    <th>Backpage Email</th>
    <th>Amount Charged</th>
    <th>Status</th>
    <th>OrderNumber #</th>
    <th>Agent</th>
  </tr> </thead><tbody>';
  foreach ($stmt as $row)


  {


  $tmpData = "WEB".$row['orderNumber'];
$orderKey = $db->query("SELECT coinTxId FROM payouts where parentTxKey = '".$tmpData."';" );

$res = $orderKey->fetch(PDO::FETCH_ASSOC);
if (!empty($res['coinTxId'])){
  $res = "<span class='success'>Loaded</span>";
  $rowColor  = 'success';
}

else {

  $res = "<span class='fail'>Pending</span>";
  $rowColor  = 'fail';
}

      echo  "<tr>
      <td  class='".$rowColor."'>".$row['createdOn'] ." </td>
      <td  class='".$rowColor."'><a href='customerDetails.php?email=".$row['email']."' >". $row['email'] ."</a></td>
      <td  class='".$rowColor."'> ".$row['orderAmt']. " </td>
      <td>".$res. " </td>
      <td  class='".$rowColor."'> ". $row['orderNumber'] ." </td>
      <td  class='".$rowColor."'>  ".$row['employeeId']."</td>
      </tr>";
  }

  echo '</tbody></table>';

}




function pullpendingAMZN($db){
  $stmt = $db->query('SELECT * FROM webTx  where `txCode` IS NULL and `status` = "NEW" ORDER BY createdOn DESC');
echo '<table class="scroll" ><thead>
  <tr>
    <th>Date And Time</th>
    <th>Backpage Email</th>
    <th>Card #</th>
    <th>Approve</th>
    <th>Reject</th>
  </tr>
  </thead><tbody>';
  foreach ($stmt as $row){


      echo  "<tr>
      <td>".$row['createdOn'] ." </td>
      <td> ". $row['email'] ."</td>
      <td> ". $row['orderNumber'] ." </td>
      <td><a class='confirmAmazon' href='?confirm=".urlencode($row['orderNumber'])."'><i class='success material-icons '>check_circle</i></a></td>
      <td><a class='deny' href='?deny=".urlencode($row['orderNumber'])."'><i class='fail material-icons'>cancel</i></a></td>
      </tr>";
  }

  echo '<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
      </tr></tbody></table>';

}


function pullpendingCC($db){
  $stmt = $db->query('SELECT * FROM webTx  WHERE `status` = "NEW" AND `txCode` = "00" ORDER BY createdOn DESC');
echo '<table class="scroll"><thead>
  <tr>
    <th>Date And Time</th>
    <th>Backpage Email</th>
    <th>Amount Charged</th>
    <th>OrderNumber #</th>
    <th>Approve</th>
    <th>Reject</th>
  </tr></thead><tbody>';
  foreach ($stmt as $row){



      echo  "<tr>
      <td>".$row['createdOn'] ."</td>
      <td>". $row['email'] ."</td>
      <td>".$row['orderAmt']. "</td>
      <td>". $row['orderNumber'] ."</td>
      <td><a class='confirm' href='?confirm=".urlencode($row['orderNumber'])."'><i class='success material-icons '>check_circle</i></a></td>
      <td><a class='deny' href='?deny=".urlencode($row['orderNumber'])."'><i class='fail material-icons'>cancel</i></a></td>
      </tr>";
  }

  echo '<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
      </tr></tbody></table>';

}


function pullEmlWL($db){
  $stmt = $db->query('SELECT * FROM wlEmails');
echo '<table style="width:100%">
  <tr>
    <th>Date Added</th>
    <th>Email</th>
  </tr>';
  foreach ($stmt as $row){



      echo  "<tr><td>".$row['createdOn'] ." </td><td> ". $row['email'] ."</td></tr>";
  }

  echo '</table>';

}

function pullBinWL($db){
  $stmt = $db->query('SELECT * FROM wlBins');
echo '<table style="width:100%">
  <tr>
    <th>BIN #</th>
  </tr>';
  foreach ($stmt as $row){



      echo  "<tr><td> ". $row['bin'] ."</td></tr>";
  }

  echo '</table>';

}

function pullExpWL($db){
  $stmt = $db->query('SELECT `wlExpireDate` FROM configs');
echo '<table style="width:100%">
  <tr>
    <th>Date Limit</th>
  </tr>';
  foreach ($stmt as $row){
      echo  "<tr><td> ". $row['wlExpireDate'] ."</td></tr>";
  }
  echo '</table>';

}



function manageWL($db, $value, $type, $intent){

if ($intent == 'addThings') {
if ($type == 'email'){
$SQL = "INSERT into wlEmails (`email`) values(:value)";  }
else { $SQL = "INSERT into wlBins (`bin`) values(:value)"; } } 

else {//$intent is to remove things
if ($type == 'email'){ $SQL = "DELETE FROM wlEmails WHERE email = :value"; }
else { $SQL = "DELETE FROM wlBins WHERE bin = :value"; } }

$preparedStatement = $db->prepare($SQL);
$res = $preparedStatement->execute([':value' => $value]);

if ($res){

return true;

} else {
return false;
}



}



function updateExp($db, $newValue){

//Return False if Date is OLDER than NOW.
if  (time() > strtotime($newValue)){
  return false;
};

$preparedStatement = $db->prepare(
"UPDATE configs set `wlExpireDate` = :nv WHERE id = 1" );

$res = $preparedStatement->execute([':nv' => $newValue]);

if ($res){

return true;

} else {
return false;
}

}


function processAmzn($db, $transNum, $amount, $myDiscount, $yourDiscount, $myLTCAddress, $agent){

$pullEmail = $db->prepare(
    'SELECT `email` FROM `webTx` WHERE `orderNumber` = :orderNumber');
  $pullEmail->execute([  ':orderNumber' => $transNum  ]);
 $res = $pullEmail->fetch(PDO::FETCH_ASSOC);
// var_dump($res);
// var_dump($amount);
// echo $yourDiscount;
// echo $myDiscount;
  $bp = calculateBP($res['email'], $amount, $myDiscount, $yourDiscount);

  // var_dump($bp);
  sendToPayouts($db, $res['email'], $bp['coinAddress'], $amount, $bp['payBP'], "WEB".$transNum);
  sendToPayouts($db, $res['email'], $myLTCAddress, $bp['payUsF'], $bp['payUs']);
  $apiResponse = $db->prepare(
    'UPDATE `webTx` SET `status` = :status, `orderAmt` = :amt, `employeeId` = :eid WHERE `orderNumber` = :orderNumber');

  $apiResponse->execute([
    ':status' => 'COMPLETE',
    ':amt' => $amount,
    ':eid' => $agent,
    ':orderNumber' => $transNum
    ]);

}

