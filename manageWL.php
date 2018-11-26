<?php 

require 'myFuncs.php';
require 'themAdminFuncs.php';

putHeader("adminWebPanel");


session_start();

// Does My IP Match?
// Am I logged IN?
// Am I submitting Processing?
// Am I submitting Settings Changes?


// if(isset($_POST['agentLogin'])){

//$pass = '123puppies';

if($pAssW){
$res = pullEmployeeId($pAssW, $db);

if ($res){

    $_SESSION['loggedIn'] = true;
    $_SESSION['agent'] = $res;


}
else {
echo "<span class='fail'>Password Incorrect!</span>";
}

 }




if (isset($_SESSION['loggedIn'])){
    if (isset($_GET['logout'])){
session_unset();
session_destroy();
header("location: awebAdmin.php");
    }

//NON PASSWORD DB UPDATES (THIS MAY GO INTO A DIFFERENT PAGE!)

foreach (array('emailWL', 'binWL', 'expireWL') as $i) {
if (!empty($_POST["$i"])){

    $tmpVar = $_POST["$i"];

    // echo $tmpVar;
    // echo $i;

    if ($i == 'emailWL') {

$trxLimiter = "SELECT count(email) FROM wlEmails WHERE email = :email";
$statement = $db->prepare($trxLimiter);
$parameters =  [ ":email" => $tmpVar];
$statement->execute($parameters);

$trxCount = $statement->fetch(PDO::FETCH_ASSOC);

if ($trxCount['count(email)'] > 0 )

{
    manageWL($db, $tmpVar, 'email', 'removeThings');
    echo "<strong class='fail'>We have Removed $tmpVar from the Email WhiteList</strong><br>";

} else {

manageWL($db, $tmpVar, 'email', 'addThings');
echo "<strong class='success'>We have Added $tmpVar to the Email WhiteList</strong><br>";

}

} else if ($i == 'binWL') {

    $binRes = binLookup($db, $tmpVar);

    if ($binRes) {

     manageWL($db, $tmpVar, 'bin', 'removeThings');
    echo "<strong class='fail'>We have Removed $tmpVar from the BIN WhiteList</strong><br>";
    }
    else {
    manageWL($db, $tmpVar, 'bin', 'addThings');
    echo "<strong class='success'>We have added $tmpVar to the BIN WhiteList</strong><br>";
    }
} else {

$resExp = updateExp($db, $tmpVar);

if ($resExp){
echo "<strong class='success'>NEW WL Expiration Date: $tmpVar</strong><br>";
}else{

    echo "<strong class='fail'>Please Set the Expiration Date Farther Forward in the Future.</strong>";
}
}


}
}




if (isset($_POST['pwd'])){
// var_dump(get_defined_vars());

$pwdCorrect = pullEmployeeId($_POST['pwd'], $db);

if ($pwdCorrect){


if (isset($_POST['confirm'])){
        $ourId = $_POST["confirm"];
    if (isset($_POST['amtApproved'])){
        $amznAmt = $_POST['amtApproved'];
        processAmzn($db, $ourId, $amznAmt, $myDiscount, $yourDiscount, $myLTCAddress, $_SESSION['agent']);
        echo "<strong class='success'>Amazon Order ".$ourId." Marked As Complete!</strong><br>";
        }
    else {

        $pullEmail = $db->prepare(
    'SELECT `email` FROM `webTx` WHERE `orderNumber` = :orderNumber');
  $pullEmail->execute([  ':orderNumber' => $ourId  ]);
 $resEmail = $pullEmail->fetch(PDO::FETCH_ASSOC);

            $res = captureCC($db, $ourId, $gwId, $gwRegKey, $url);
            if (isset($res->rspCode)){
                if($res->rspCode == "00"){
                    $theResult["trxApproved"] = true;
                    $bp = calculateBP($resEmail['email'], (int)$res->tranData->amt/100, $myDiscount, $yourDiscount);
//Main payout
                    sendToPayouts($db, $resEmail['email'], $bp['coinAddress'], (int)$res->tranData->amt/100,  $bp['payBP'], "WEB".$res->tranData->tranNr);
//Our Commission
                    sendToPayouts($db, $resEmail['email'], $myLTCAddress, $bp['payUsF'], $bp['payUs']);

                    updateStatus($db, $ourId, "COMPLETE", $_SESSION['agent']);
                    echo "<strong class='success'>".$ourId." Marked As Complete!</strong><br>";
                }
                else {
                    #DEBUGGING VVVVV
                    echo "<strong class='Fail'>Rejected CC/GC/Debit ".$ourId."</strong><br>";
                    echo '<span class="fail">'.json_encode($res)."</span>";


                    updateStatus($db, $ourId, "FAILED", $_SESSION['agent']);

                }
            }
        }

}
if (isset($_POST["deny"])) {


$ourId = $_POST["deny"];
updateStatus($db, $ourId, "REJECTED", $_SESSION['agent']);
echo "<strong class='Fail'>Rejected ".$ourId."</strong><br>";
}


}


else {

    echo "<span class='fail'>Password Incorrect!</span>";
}

}
//tempPWD is: QhU0YEa0f1FaYGa8cwEjqixsRKEtlJzJVq0pHdeu


/*Password Generator:

echo "for exmample: 123puppies<br><br>";
echo md5("123puppies");
echo '<br><br>';

MAKES 70b55fb2b63f19df70b8f5b5592ae24a

*/


//Ratios (yours should be LOWER than customer)

// $walletBal = balanceCheck($db);
// $ipMatch = passIPCheck($db, $ipAddress);



?>


<body>
<a class="text-darken2 right pad10" href="awebAdmin.php">Go Back</a>
<h3 class="hero header center text-darken-2">WhiteListing</h3>



    

<div class="container">
    <div class="section">


<div class="row">

<div class="col s4">
<h5 class='hero header center text-darken-2'>Email Whitelist</h5><hr>
<?php 
 
pullEmlWL($db);

?>


<form action="#" id='adminActions' method="POST" >
                     
<input id="emailWL" name='emailWL' type="email" class="validate autocomplete" placeholder="E-mail Address">
<a id="emailWLSubmit" onclick="document.getElementById('adminActions').submit()" class="waves-effect waves-light btn buttonBlkShadow"><i class="material-icons right">email</i>Add/Remove from Whitelist</a>

<hr><hr>
</div>

<div class="col s4">
<h5 class='hero header center text-darken-2'>BIN Whitelist</h5><hr>
<?php 
 
pullBinWL($db);

?>

                     
<input id="binWL" name='binWL' type="text" class="validate autocomplete" maxlength=6 placeholder="6 Digit BIN Number">

<a id="binWL" onclick="document.getElementById('adminActions').submit()" class="waves-effect waves-light btn buttonBlkShadow"><i class="material-icons right">credit_card</i>Add/Remove BIN from Whitelist</a>


<hr><hr>
</div>


<div class="col s4">
<h5 class='hero header center text-darken-2'> Expiration Whitelist</h5><hr>
<?php 
 
pullExpWL($db);

?>
                     
<input id="expireWL" name='expireWL' type="Date" class="validate autocomplete" placeholder="Expiration Date">

<a id="expireWLSubmit" onclick="document.getElementById('adminActions').submit()" class="waves-effect waves-light btn buttonBlkShadow"><i class="material-icons right">date_range</i>Set New Expiration Date</a>

</form>

<hr><hr>
</div>

</div>



</div>
 <?php putJS(); ?>
 <script type="text/javascript">

var $table = $('table.scroll'),
    $bodyCells = $table.find('tbody tr:first').children(),
    colWidth;

// Adjust the width of thead cells when window resizes
$(window).resize(function() {
    // Get the tbody columns width array
    colWidth = $bodyCells.map(function() {
        return $(this).width();
    }).get();
    
    // Set the width of thead columns
    $table.find('thead tr').children().each(function(i, v) {
        $(v).width(colWidth[i]);
    });    
}).resize(); // Trigger resize handler




function post(path, params, method) {
    method = "post"; // Set method to post by default if not specified.

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    for(var key in params) {
        if(params.hasOwnProperty(key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);
        }
    }

    document.body.appendChild(form);
    form.submit();
}



$(".confirm").click(function(e){
    e.preventDefault()
    var myVal = $(this).attr('href').split("=")[1];
    takeAction('approve', myVal); 

});


$(".confirmAmazon").click(function(e){
    e.preventDefault()
    var myVal = $(this).attr('href').split("=")[1];
    takeAction('approve', myVal, 'amazon'); 

});


$(".deny").click(function(e){
    e.preventDefault()
    var myVal = $(this).attr('href').split("=")[1];
    takeAction('deny', myVal);

});


<?php testHarness();?>


function takeAction(type, orderId, orderType){
    var fieldString = '</p>Agent Password<br><input id="agentPwd" placeholder="Agent Password" type="password" class="swal2-input">';

    if (orderType == 'amazon'){

       fieldString = '</p>Amount To Pay<br><input id="amtApproved" name="amtApproved" placeholder="Amazon Amount" class="swal2-input">Agent Password<br><input id="agentPwd" type="password" class="swal2-input">'; 
    }

    swal({
    title: "<h4>Confirm</h4>",
    html: '<br>Please enter your Agent Password to '+ type +' '+orderId+fieldString,
    showCloseButton: true,
    confirmButtonClass: "waves-effect waves-light btn finishing",
    confirmButtonColor: "",
    confirmButtonText:'<i class="material-icons right icnComplete">local_atm</i>Complete Purchase',
        showLoaderOnConfirm: true,
    preConfirm: function (result) {
        return new Promise(function (resolve, reject) {
                if (!result) {
                    reject('Password Is Required!')
                } else {
                    resolve([
pwd =  $('#agentPwd').val(),
appAmt =  $('#amtApproved').val()

                        ])
                    // alert(type);
                    if (type == 'approve'){
                    if (appAmt !== undefined){
                      post('#', { confirm: orderId, amtApproved: appAmt, pwd: pwd});
                    // window.location.replace("?"+type+"="+orderId+"&amtApproved="+appAmt);
                }
                else {
                    post('#', {confirm: orderId, pwd: pwd});
                    // window.location.replace("?"+type+"="+orderId);

                }
                } else{

                     post('#', {deny: orderId, pwd: pwd});
                }
}
                
        })
    },
    allowOutsideClick: false,
 
})

}





 $( "#adminEmailSubmit" ).click( function(e){

    
    e.preventDefault();

    $.post( "checkEmail.php", { email: $("#email").val()})
    .then(

        function(json){
            var data_array = $.parseJSON(json);


            if (data_array['emailFound'] == "success"){
                $(".emailConfirm").html("<i class='material-icons'>done</i>");

            } 

            else {
              $(".emailConfirm").html("<i class='material-icons'>clear</i>");
         };

     });

});  




 $("#adminConfirm").click(function(e){

//Set checkbox back to clear
 $("#swal2-checkbox").prop("disabled", null);
            

    e.preventDefault();  


    if ($("#email").val() ==""){

        throwAlert("Missing Info", "error", "Please click 'Next' so we can verify your account exists");
    }

// else if ( $("#creditAmt").val() == ''){
//  throwAlert('Missing Info', 'warning', 'Please Enter How Many Credits You Wish to Buy');
//  }

// else if ( $("#dollarAmt").val() < 5 ){
//  throwAlert('Order Minimum', 'warning', 'Our Minimum Purchase is 2.5 Credits');
//  }


if ( $("#amznCard").val() != '') {



    swal({
    title: "<h4>Amazon Confirm</h4>",
    input: 'password',
    type: 'warning',
    inputPlaceholder:'Agent Password',
    html: '<p>We\'re Sendng '+$("#creditAmt").val() +' to ' + $("#email").val()+ '<br> Please enter your Agent Password to Confirm and Finalize</p> ',
    showCloseButton: true,
    confirmButtonClass: "waves-effect waves-light btn finishing",
    confirmButtonColor: "",
    confirmButtonText:'<i class="material-icons right icnComplete">local_atm</i>Complete Purchase',
        showLoaderOnConfirm: true,
    preConfirm: function (result) {
        return new Promise(function (resolve, reject) {
                if (!result) {
                    reject('Password Is Required!')
                } else {
                    adminAMZN(result);

                }

                
        })
    },
    allowOutsideClick: false,
 
})
                
    // document.getElementById("emailAddr").innerHTML = $("#email").val();


    } 


 
else if ( $("#custName").val() == ''){
 throwAlert('Missing Info', 'warning', 'Please Customer Name On Card');
 } 
else if ( $("#ccNum").val() == ''){
 throwAlert('Missing Info', 'warning', 'Please Enter Customer Card Number');
} 
else if ( $( "form :selected" ).eq(0).val() == ""){
 throwAlert('Missing Info', 'warning', 'Please Enter Customer Expiration Month');
}
else if ( $( "form :selected" ).eq(1).val() == ""){
 throwAlert('Missing Info', 'warning', 'Please Enter Customer Expiration Year');
} 
else if ( $("#cvc").val() == ''){
 throwAlert('Missing Info', 'warning', 'Please Enter Customer Verfication Code');
} 




else {

//Is this a gift card?

if (!giftCard) {

if ( $("#addr").val() == ''){
 throwAlert('Missing Info', 'warning', 'Please Enter Customer Billing Address');
} 
else if ( $("#city").val() == ''){
 throwAlert('Missing Info', 'warning', 'Please Enter Customer Billing City');
} 
else if ( $("#state").val() == ''){
    throwAlert('Missing Info', 'warning', "Please Enter Customer Billing State");
} 
else if ( $("#zip").val() == ''){
 throwAlert('Missing Info', 'warning', "Please Enter Your Customer Zip Code");
} 


}


    
    swal({
    title: "<h4>CC/GC/Debit Confirm</h4>",
    input: 'password',
    inputPlaceholder:'Agent Password',
    html: '<p>We\'re Sendng '+$("#creditAmt").val() +' to ' + $("#email").val()+ '<br> Please enter your Agent Password to Confirm and Finalize</p> ',
    showCloseButton: true,
    confirmButtonClass: "waves-effect waves-light btn finishing",
    confirmButtonColor: "",
    confirmButtonText:'<i class="material-icons right icnComplete">local_atm</i>Complete Purchase',
    showLoaderOnConfirm: true,
    preConfirm: function (result) {
        return new Promise(function (resolve, reject) {
                if (!result) {
                    reject('You need to agree with T&C')
                } else {
    
                    adminCC(result);

                }

                
        })
    },
    allowOutsideClick: false,
 
})
  


    } 
})


function adminAMZN(agentPass){

$(".finishing").html("");
swal.showLoading();


    $.post( 'chargeAMZN.php',  { 
                    adminPass: "QhU0YEa0f1FaYGa8cwEjqixsRKEtlJzJVq0pHdeu",
                    agentPass: agentPass,
                    amount: $("#creditAmt").val(),
                    amznCard: $("#amznCard").val(),
                    email: $('#email').val(),
                }).done(

                    function(json){ 

                        // Feel free to Debug if you're reading this ;)
                        //$('header').append(json);
                        
                        var data_array = $.parseJSON(json);

                        var moreDetails = '';
                        var resulting = 'error';
                        var details = "Complete";


                        if (data_array["trxApproved"] == true){
                            resulting = 'success';

                            moreDetails = "";

                        }


                        swal({

                            type: resulting,
                            timer: 5000,
                            title: details, 

                            html:  data_array["messageText"]+ moreDetails,  
                            confirmButtonColor: "#5682A3",
                            confirmButtonClass: "waves-effect waves-light btn"

                        }).then(  
                        function () {
                             window.location.reload();
                        },
                        // handling the promise rejection
                            function (dismiss) {
                                 window.location.reload(); }) 
                    });



}

function adminCC(agentPass){

// $(".icnComplete").hide();
$(".finishing").html("");
swal.showLoading();
var expMonth = $( "form :selected" ).eq(0).val();
var expYear = $( "form :selected" ).eq(1).val()


expirationDate = expMonth+"/01/"+expYear;


    $.post( 'chargeCard.php',  { 

                    agentPass: agentPass,
                    creditsAmt: $("#creditAmt").val(),
                    email: $('#email').val(),  
                    name: $("#custName").val(),
                    ccNum : $("#ccNum").val() ,
                    expires : expirationDate,
                    cvc: $("#cvc").val(),
                    addr : $("#addr").val(),
                    city : $("#city").val(),
                    state : $("#state").val(),
                    zip : $("#zip").val()
                }).done(

                    function(json){ 

                        // Feel free to Debug if you're reading this ;)
                        //$('header').append(json);
                        
                        var data_array = $.parseJSON(json);

                        var moreDetails = '';
                        var resulting = 'error';
                        var details = "Transaction Declined";


                        if (data_array["trxApproved"] == true){
                            resulting = 'success';
                            details = "Thank You, Your Transaction is Being Processed";
                            moreDetails = "<br><br>Thank you for your business!<br><br>Your card statement will be billed by us as:<br><span style='font-size:115%;    line-height: 35px;'><b>BUYBPC.COM</span></b><br><br>Authorization: <b>" + data_array["authID"]+"</b>";

                        }


                        swal({

                            type: resulting,

                            title: details, 

                            html:  data_array["messageText"]+ moreDetails,  
                            confirmButtonColor: "#5682A3",
                            confirmButtonClass: "waves-effect waves-light btn"

                        })
                    
            });


};


</script>
  
</body>


</html>

<?php

}

// var_dump(get_defined_vars());

if (!isset($_SESSION['loggedIn'])){
$ipWhitelisted = passIPCheck($db, $_SERVER['REMOTE_ADDR']);

if ($ipWhitelisted){

loginForm();

} else 
{   echo "<strong class='fail' style='margin: 2%;top: 8px;position: relative;'>Please Contact Site Administrator</strong>";}

}
?>

