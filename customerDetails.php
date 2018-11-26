<?php 

require 'myFuncs.php';

/**
* 
*/
class Customer
{


	public function history($db, $email){

  $stmt = $db->prepare('SELECT * FROM webTx where email = :eml ORDER BY createdOn DESC');

 $stmt->execute([':eml' => $email]);
 $parentRes = $stmt->fetchAll(PDO::FETCH_ASSOC);
	echo '<div class="col s12 m6"><table><thead>
	    <tr>
	    <th>Date And Time</th>
	    <th>Amount Charged</th>
	    <th>OrderNumber #</th>
	    <th>Agent</th>
	  </tr> </thead><tbody>';
  foreach ($parentRes as $row)


  {

      echo  "<tr>
      <td>".$row['createdOn'] ." </td>
      <td> ".$row['orderAmt']. " </td>
      <td> ". $row['orderNumber'] ." </td>
      <td>  ".$row['employeeId']."</td>
      </tr>";
  }

  echo '</tbody></table></div>'; }


	public function pullStats($db, $email){
		$attr = ['count(email)', 'sum(orderAmt)', 'max(orderAmt)', 'min(orderAmt)', 'max(createdOn)', 'min(createdOn)']; 


	foreach ($attr as $key => $value) {
		$sql = sprintf("SELECT %s FROM webTx where email = :eml AND `status` LIKE 'NEW' OR `status` LIKE 'COMPLETE' ORDER BY createdOn DESC", $value);
		// echo $sql;
		$stmt = $db->prepare($sql);

  	$stmt->execute([':eml' => $email]);
  	$res = $stmt->fetch(PDO::FETCH_ASSOC);
	$retVal[$value] = $res[$value];
	}
 	return $retVal;
	}


}

putHeader("Customer Details");

if(isset($_REQUEST['email'])){
$email = $_REQUEST['email'];
 $info = new Customer();


$stats = $info->pullStats($db, $email);


?>
<a class="text-darken2 right pad10"  href="awebAdmin.php">Go Back</a>
<h3 class="hero header center text-darken-2"> <?php echo $email;?> Details </h3>


<br>
<hr><br><br>

<div class="container">
    <div class="section">
 <div class="row">
<div class="col s12 m6">
<table><thead>
	    <tr>
	    <th># of Purchases</th>
	    <th>Lifetime Value</th>
	    <th>Largest Purchase</th>
	    <th>Lowest Purchase</th>
	    <th>Latest Order</th>
	    <th>First Order</th>
	  </tr> </thead><tbody><tr>
	  	


<?php  foreach ($stats as $row)


  {

      echo  "<td>".$row ."</td>";
  }

  echo '</tr></tbody></table></div>';

$info->history($db, $email);

?>
</div></div></div>

<?php putJS();


}

else {

	echo '<span class="fail">No Email Was Loaded!</span>';
}


 ?>
</body></html>

