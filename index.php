<?php 

include 'myFuncs.php';


if(isset($_SESSION)){

	session_destroy();
	session_start();

}




?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no"/>
	<title>BuyBPC.com</title>
	<link rel="shortcut icon" href="#">
	<!-- CSS  -->
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
	<link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.11.0/sweetalert2.min.css" />
</head>
<body>
	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

		ga('create', '', 'auto');
		ga('send', 'pageview');

	</script>

	<header></header>
	<main>
		<div id="index-banner" class="parallax-container">
			<div class="section no-pad-bot">
				<div class="container">
					 <h5 class="hero header center text-darken-2">Get BackPage Credits 24x7 at BuyBPC.com
					<br><br>
				 </h5> 
<form>
					 
				
					<div class="row center">
							<div class="input-field col m8 offset-m2 s12">
								<i class="material-icons prefix">email</i>
								<input id="email" type="email" class="validate autocomplete" data-error="Please enter a valid email Address." placeholder="Please enter your Backpage E-mail Address">
								<label for="email">Buy Backpage Credits / Fund Account </label>
							</div>
						</div>
							<div class="input-field col s12 center">
								<a id="emailSubmit" class="waves-effect waves-light btn buttonBlkShadow"><i class="material-icons right">trending_up</i>Next</a>
							</div>
</form>
						</div>

						<br><br>

				</div>

	 <div class="parallax makeitBlk"><!-- <img src="background2.jpg" alt="Unsplashed background img 2"> --></div>
		</div>

		

<div class="container">
	<div class="section">
 <form class="col s12">

	<ul class="collapsible" data-collapsible="accordion">
		<li> 
			<div class="collapsible-header"><i class="material-icons">credit_card</i>Credit/Debit/Gift Card</div>
			<div class="collapsible-body">
					<div class="row">

					<div class="input-field col s12 m5">
					 <br>
					 <input placeholder="Minimum Purchase is $25" id="dollarAmt" type="number" class="validate autocomplete" min="25" step=".01" max="300" value=25 data-error="You must ordrer between $25 - $300 USD worth of Credits" required>
					 <label for="dollarAmt">Pay This Amount</label>
				 </div>


				 <div class="input-field col s12 m2">
					 <br>
					 <h5 class="center">FOR</h5>
				 </div>

				 <div class="input-field col s12 m5">
					 <br>
					 <input placeholder="Minimum of 10 credits" id="creditAmt" type="number" data-error="You must ordrer between 10 - 120 Credits" class="validate autocomplete" min="10.00" value=10.00 step=".01" required>
					 <label for="creditAmt">This Many Credits</label>
				 </div>
</div>
<div class="row">
						<div class="cc input-field col m6 s12 center">
							<i class="material-icons prefix">account_circle</i>
							<input id="custName" type="text" class="validate autocomplete" data-error="Please Enter the Name On your Card" required>
							<label for="custName">Name As it Appears on Credit Card</label>
						</div>

						<div class="cc input-field col m6 s12 center">
							<i class="material-icons prefix">credit_card</i>
							<input id="ccNum" type="number" class='autocomplete' maxlength="16" data-error="Please Enter your Credit Card" required>
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
						<div class="input-field col m6 s12 center">
							<i class="material-icons prefix">phone</i>
							<input id="phoneNumber" type="tel" class="validate autocomplete" required>
							<label for="phoneNumber">Phone Number</label>
						</div>              
					</div>  
				</div>
		</li>
		<li>
			<div class="collapsible-header"><i class="material-icons">credit_card</i>Amazon Card</div>
			<div class="collapsible-body">

						<div class="amazon input-field col m6 s12 center">
							<i class="material-icons prefix">credit_card</i>
							<input id="amznCard" type="text" class='autocomplete' required>
							<label for="amznCard">Gift Card Claim Code</label>
						</div>
						 <div class="input-field col m6 s12 center">
							<i class="material-icons prefix">phone</i>
							<input id="amznphoneNumber" type="tel" class="validate autocomplete" required>
							<label for="amznphoneNumber">Phone Number</label>
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
						<a class="waves-effect waves-light btn center" id="confirm" href="#confirm"><i class="material-icons right">shopping_cart</i> Confirm Purchase</a>
					</div>    
				</form>
			</div>
		</div>

<!-- 	</div>
</div>
 <div class="parallax-container valign-wrapper">
	<div class="section no-pad-bot">
		<div class="container">
			<div class="row center"><i class="material-icons medium hide-on-large-only minIcon">warning</i></div>
			<div class="row center">
				<h5 class="header bottomHClass col s12 light"> <i class="material-icons medium hide-on-med-and-down left valign-wrapper">warning</i> Please Note: No Refunds for Successful Deliveries of Credits. Read Our Terms And Conditions or FAQ Below for More Information.</h5>
			</div>
		</div>
	</div>

</div>
 -->


	</main>
	<footer class="page-footer">
		<div class="footer-copyright">
			<div class="container">
				<a class="grey-text text-lighten-4 left" onClick="faq()" href="#faq">FAQ</a>

			 <a class="grey-text terms text-lighten-4 left" onClick="terms()" href="#terms">T&C</a>
				
				<span class="grey-text text-lighten-4 right">©2017 BuyBPC.com</span>
			</div>
		</div>
	</footer>





	<!--  Scripts-->
	<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
	<script src="js/materialize.js"></script>
	<script src="js/init.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.11.0/sweetalert2.all.min.js"></script>
	<script src="js/promise.min.js"></script>
	<script src="js/phonevalidation.js"></script>
	<script src="js/bundle.js"></script>

	<script type="text/javascript">


	$("#creditAmt").keyup( function(){
		var credits = document.getElementById('creditAmt').value;
		document.getElementById('dollarAmt').value = (credits * 2.50).toFixed(2);  
		if (credits == 0 ) { $('#dollarAmt').val(""); }
		if (credits > (maxAvail/2)){ updateTotal(maxAvail); }
	});

	$("#dollarAmt").keyup( function(){
		var dollars = document.getElementById('dollarAmt').value;
		document.getElementById('creditAmt').value = (dollars / 2.50).toFixed(2);  
		if (dollars == 0 ) { $('#creditAmt').val(""); }
		if (dollars > maxAvail){ updateTotal(maxAvail); } 
	});

<?php testHarness();?>

</script>
</body>


</html>
