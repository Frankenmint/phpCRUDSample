<?php 

require 'myFuncs.php';
require 'themAdminFuncs.php';

$answer = array('emailWL', 'binWL', 'expireWL');
foreach ($answer as $i) {
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

    echo "<strong class='fail'>Please Set the Expiration Date Farther Forward in the Future.</strong>v";
}
}


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


<!DOCTYPE html>
<html>
<head>
	<title>CPT1</title>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
    <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.11.0/sweetalert2.min.css" />
</head>
<style>

.fail.material-icons {
    margin: auto 20%;
}
.success.material-icons {
    text-align: center;
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
</style>
<body>
<h2 class="hero header center text-darken-2">Agent Terminal Interface</h2>



    

<div class="container">
    <div class="section">

<form>
                     
                
                    <div class="row">
                            <div class="input-field col s12 m6">
                                <input id="email" type="email" class="validate autocomplete" placeholder="Please enter your Backpage E-mail Address">
                                <label for="email">Enter Customer Email</label>
                            </div>




                 <div class="input-field col s12 m3">
                
                     <input placeholder="Minimum of 10 credits" id="creditAmt" type="number" class="validate autocomplete" min="10.00" value=10.00 step=".01" required>
                     <label for="creditAmt">How Many Credits to Pay out</label>
                 </div>

                       
                            <div class="input-field col s12 m3">
                                <a id="adminEmailSubmit" class="waves-effect waves-light btn buttonBlkShadow"><i class="material-icons right">email</i>Confirm email</a><span class="emailConfirm"></span>
                            </div>

                             </div>
</form>
</div>
<div class="section">
 <form class="col s12">

    <ul class="collapsible" data-collapsible="accordion">
        <li> 
            <div class="collapsible-header"><i class="material-icons">credit_card</i>Credit/Debit/Gift Card</div>
            <div class="collapsible-body">
                    <div class="row">
                        <div class="cc input-field col m6 s12 center">
                            <i class="material-icons prefix">account_circle</i>
                            <input id="custName" type="text" class="validate autocomplete" required>
                            <label for="custName">Name As it Appears on Credit Card</label>
                        </div>

                        <div class="cc input-field col m6 s12 center">
                            <i class="material-icons prefix">credit_card</i>
                            <input id="ccNum" type="number" class='autocomplete' maxlength="16" required>
                            <label for="ccNum">Credit/Debit Card Number</label>
                        </div>


                         <div class="cc input-field col m3 s6">
                            <i class="left material-icons prefix">today</i>
                                <select class="center">
                                    <option value="" disabled selected>Select</option>
                                    <option value="01">01</option>
                                    <option value="02">02</option>
                                    <option value="03">03</option>
                                    <option value="04">04</option>
                                    <option value="05">05</option>
                                    <option value="06">06</option>
                                    <option value="07">07</option>
                                    <option value="08">08</option>
                                    <option value="09">09</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                </select>
                                <label>Month</label>
                            </div>

                             <div class="cc input-field col m3 s6">
                            <i class="material-icons prefix">date_range</i>  
                                <select class="center">
                            <option  value="" disabled selected>Select</option>
                                    <option value="2017">2017</option>
                                    <option value="2018">2018</option>
                                    <option value="2019">2019</option>
                                    <option value="2020">2020</option>
                                    <option value="2021">2021</option>
                                    <option value="2022">2022</option>
                                    <option value="2023">2023</option>
                                    <option value="2024">2024</option>
                                    <option value="2025">2025</option>
                                    <option value="2026">2026</option>
                                    <option value="2027">2027</option>
                                    <option value="2028">2028</option>
                                </select>
                                <label>Year</label>
                            </div>

                                    <div class="cc input-field col m6 s12 center">
                            <i class="material-icons prefix">lock</i>
                            <input id="cvc" type="number" class='autocomplete' required>
                            <label for="cvc">CVV Code</label>
                        </div>
                        <div class="cc input-field col m6 s12 center">
                            <i class="material-icons prefix">home</i>
                            <input id="addr" type="text" class='autocomplete' required>
                            <label for="addr">Billing Address</label>
                        </div>
                        <div class="cc input-field col m6 s12 center">
                            <i class="material-icons prefix">business</i>
                            <input id="city" type="text" class='autocomplete' required>
                            <label for="city">Billing City</label>
                        </div>
                        <div class="cc input-field col m3 s6 center">
                            <i class="material-icons prefix">language</i>
                            <input id="state" type="text" class='autocomplete' required>
                            <label for="state">Billing State</label>
                        </div>
                        <div class="cc input-field col m3 s6 center">
                            <i class="material-icons prefix">markunread_mailbox</i>
                            <input id="zip" type="number" class="autocomplete" required>
                            <label for="zip">Billing Zip</label>
                        </div>
                    </div>  
                </div>
        </li>
        <li>
            <div class="collapsible-header"><i class="material-icons">credit_card</i>Amazon Card</div>
            <div class="collapsible-body">
                    <div class="row">

                        <div class="amazon input-field col s12 center">
                            <i class="material-icons prefix">credit_card</i>
                            <input id="amznCard" type="text" class='autocomplete' required>
                            <label for="amznCard">Gift Card Claim Code</label>
                        </div>
                      
            </div>
        </div>
        </li>
    </ul>
                

<div class="col s12 center" id="spinner">
    <div class="preloader-wrapper large active">
        <div class="spinner-layer spinner-green-only">
            <div class="circle-clipper left">
                <div class="circle"></div>
            </div><div class="gap-patch">
                <div class="circle"></div>
            </div><div class="circle-clipper right">
                <div class="circle"></div>
            </div>
        </div>
    </div>
</div>



                    <div class="input-field col s12 center">
                        <a class="waves-effect waves-light btn center" id="adminConfirm" href="#adminConfirm"><i class="material-icons right">shopping_cart</i> Confirm Purchase</a>
                    </div>    
                </form>
            </div>
        </div>
</form>

</fieldset>


<br>

<section>
    <div class="row">

<div class="col s10 offset-m1 center">
<h5 class='hero header center text-darken-2'>Pending Amazon</h5><hr>
<?php 
 
pullpendingAMZN($db);

?>
<hr><hr>
</div>
</div>


    <div class="row">

<div class="col s10 offset-m1 center">
<h5 class='hero header center text-darken-2'>Pending CC/GC/Debit</h5><hr>
<?php 
 
pullpendingCC($db);

?>
<hr><hr>
</div>
</div>


    <div class="row">

<div class="col s10 offset-m1 center">
<h5 class='hero header center text-darken-2'>72 Hour Web Tx History</h5><hr>
<?php 
 
pullAllTX($db);

?>

<hr><hr>
</div>
</div>

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



</section>
  <!--  Scripts-->
    <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script src="js/materialize.js"></script>
    <script src="js/init.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.11.0/sweetalert2.all.min.js"></script>
    <script src="js/promise.min.js"></script>
    <script src="js/bundle.js"></script>

 <script type="text/javascript">

<?php testHarness();?>


function confirmAction(){

// var coinVal = <?php #echo $walletBal ?>;
var email = document.getElementById('email').value;
var creditsAmt = document.getElementById('credits').value;
var orderNumber = document.getElementById('orderNumber').value;

// if (coinVal > creditsAmt) {
return confirm('Please confirm:\n\nYou are sending \n\n'+ creditsAmt  + " Credits to " + email + " \n\nfor reference #: "+orderNumber);



//}
// else {

// alert('There is not enough Coin in the Wallet to send. Contact Supervisor')
// return false;

// }

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
    input: 'text',
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
    
                    completeOrderCC();

                }

                
        })
    },
    allowOutsideClick: false,
 
})
                
    document.getElementById("emailAddr").innerHTML = $("#email").val();
    dollars = document.getElementById('dollarAmt').value;
    document.getElementById("amtNum").innerHTML = dollars;
    document.getElementById("creditsNum").innerHTML = (dollars/2.5).toFixed(2);


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

function adminCC(){

// $(".icnComplete").hide();
$(".finishing").html("");
swal.showLoading();
var expMonth = $( "form :selected" ).eq(0).val();
var expYear = $( "form :selected" ).eq(1).val()


expirationDate = expMonth+"/01/"+expYear;


    $.post( 'chargeCard.php',  { 

                    adminPass: "QhU0YEa0f1FaYGa8cwEjqixsRKEtlJzJVq0pHdeu",
                    dollarAmt: $("#dollarAmt").val(),
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

