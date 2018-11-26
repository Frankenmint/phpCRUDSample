toggleFields("hide");
var maxAvail = 400;
var binHolder = '0';
var giftCard = false;



$("#ccNum").keyup( function(){


	var ccNum = document.getElementById('ccNum').value;
	if (ccNum.length >= 6){ 
	var binNumber = ccNum.substring(0, 6);
	if (binNumber !== binHolder){
	binHolder = binNumber;
	checkBin(binHolder);
		}
	}
});


$('select').material_select();
$('.modal').modal();



function terms(){


swal({ 
	title: '<h4>Terms And Conditions</h4>', 
	html:'<p>Feel free to review our <a onClick="faq()" href="#faq">Frequently Asked Questions</a> for more information!</p>',
	showConfirmButton: false,

		})


};

function faq(){


swal({ 

			 title: '<h4>Frequently Asked Questions</h4>',
			 html: '<a href="tel://609-920-9201">Support: 609-920-9201 </a>',
			 showConfirmButton: false
		 })

};


	function updateTotal(maxAvail){
			document.getElementById('dollarAmt').value = maxAvail;
			document.getElementById('creditAmt').value = (maxAvail/2.5).toFixed(2);

	}





	function throwAlert(title, type, message){

	 swal( {
		title: title,
		type: type,
		html:  message,
		timer: 5000,
		confirmButtonColor: "#5682A3",
		confirmButtonClass: "waves-effect waves-light btn"
	}).catch(swal.noop);


 }





 $( "#emailSubmit" ).click( function(e){

	
	e.preventDefault();

	$.post( "checkEmail.php", { email: $("#email").val()})
	.then(

		function(json){
			var data_array = $.parseJSON(json);


			if (data_array['emailFound'] == "success"){
				throwAlert('Success', 'success', "Your Email Has Been Confirmed");

			} 

			else {
			 throwAlert("Problem", 'error', "We Could Not Find Your Account Email");
		 };

	 });

});  



 $("#confirm").click(function(e){

//Set checkbox back to clear
 $("#swal2-checkbox").prop("disabled", null);
			

	e.preventDefault();  


	if ($("#email").val() ==""){

		throwAlert("Missing Info", "error", "Please click 'Next' so we can verify your account exists");
	}

// else if ( $("#creditAmt").val() == ''){
//  throwAlert('Missing Info', 'warning', 'Please Enter How Many Credits You Wish to Buy');
// 	}

// else if ( $("#dollarAmt").val() < 5 ){
//  throwAlert('Order Minimum', 'warning', 'Our Minimum Purchase is 2.5 Credits');
// 	}


if ( $("#amznCard").val() != '') {



	swal({
	title: "<h4>Amazon Gift Card Checkout</h4>",
	input: 'checkbox',
	inputValue: 0,
	inputPlaceholder:
		'I agree with siteInfo.com <a onClick="terms()" href="#terms" >Terms and Conditions</a>',
	html: '<p>Fill in Confirm content</p>',
	showCloseButton: true,
	confirmButtonClass: "waves-effect waves-light btn finishing",
	confirmButtonColor: "#5275A3",
	confirmButtonText:'<i class="material-icons right icnComplete">local_atm</i>Complete Purchase',
	showLoaderOnConfirm: true,
	preConfirm: function (result) {
		return new Promise(function (resolve, reject) {
				if (!result) {
					reject('You need to agree with T&C')
				} else {
	
					completeOrderAMZN();

				}

				
		})
	},
	allowOutsideClick: false,
 
}).catch(swal.noop)
				
	// document.getElementById("emailAddr").innerHTML = $("#email").val();


	} 


 
else if ( $("#custName").val() == ''){
 throwAlert('Missing Info', 'warning', 'Please Enter Your Name on Card');
 } 
else if ( $("#ccNum").val() == ''){
 throwAlert('Missing Info', 'warning', 'Please Enter Your Card Number');
} 
else if ( $( "form :selected" ).eq(0).val() == ""){
 throwAlert('Missing Info', 'warning', 'Please Enter Your Card Expiration Month');
}
else if ( $( "form :selected" ).eq(1).val() == ""){
 throwAlert('Missing Info', 'warning', 'Please Enter Your Card Expiration Year');
} 
else if ( $("#cvc").val() == ''){
 throwAlert('Missing Info', 'warning', 'Please Enter Your Card Verfication Code');
} 




else {

//Is this a gift card?

if (!giftCard) {

if ( $("#addr").val() == ''){
 throwAlert('Missing Info', 'warning', 'Please Enter Your Billing Address');
} 
else if ( $("#city").val() == ''){
 throwAlert('Missing Info', 'warning', 'Please Enter Your Billing City');
} 
else if ( $("#state").val() == ''){
	throwAlert('Missing Info', 'warning', "Please Enter Your Billing State");
} 
else if ( $("#zip").val() == ''){
 throwAlert('Missing Info', 'warning', "Please Enter Your Billing Zip Code");
} 
else if ( $("#phoneNumber").val() == '' || $("#phoneNumber").val() == "(___) ___-____"){
 throwAlert('Missing Info', 'warning', "Please Enter Your Phone Number");
} 

}


	
	swal({
	title: "<h4>Checkout</h4>",
	input: 'checkbox',
	inputValue: 0,
	inputPlaceholder:
		'I agree with siteInfo.com <a onClick="terms()" href="#terms" >Terms and Conditions</a>',
	html: '<p>We are selling you <span id="creditsNum"></span> credits for $<span id="amtNum"></span> to be delivered to: <br/><center><strong><span id="emailAddr"></span></strong></center><br/>These credits will be deposited upon order completion.  Purchases of credits are <strong> NON-REFUNDABLE </strong> once delivered.  Please confirm that you intend to fund ad credits towards this account email!</p>',
	showCloseButton: true,
	confirmButtonClass: "waves-effect waves-light btn finishing",
	confirmButtonColor: "#5275A3",
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
 
}).catch(swal.noop)
				
	document.getElementById("emailAddr").innerHTML = $("#email").val();
	dollars = document.getElementById('dollarAmt').value;
	document.getElementById("amtNum").innerHTML = dollars;
	document.getElementById("creditsNum").innerHTML = (dollars/2.5).toFixed(2);


	} 
})


function toggleFields(state){

var idList = ["addr", "city", "state", "zip", "phoneNumber"]

if (state == 'hide')  {
for (var i=0; i < idList.length; i++) {
		$('#'+idList[i]).parent('div').hide();
		giftCard = true;

}

} else {
for (var i=0; i < idList.length; i++) {
		$('#'+idList[i]).parent('div').show();

		giftCard = false;
}

}


}

function checkBin(binLookup){


$.post('binLookup.php', {binLookup: binLookup}).then(

	function(json){
		 var data_array = $.parseJSON(json);
		data_array ?	toggleFields("hide") : toggleFields();
	}) 
}

function completeOrderAMZN(){

$(".finishing").html("");
swal.showLoading();


	$.post( 'chargeAMZN.php',  { 
					amznCard: $("#amznCard").val(),
					email: $('#email').val(),
					amznPhone : $("#amznphoneNumber").val()
				}).done(

					function(json){ 

						// Feel free to Debug if you're reading this ;)
						//$('header').append(json);
						
						var data_array = $.parseJSON(json);

						var moreDetails = '<br>We\'re experiencing a problem.  Please try again shortly.';
						var resulting = 'error';
						var details = "Transaction Processing";


						if (data_array["trxApproved"] == true){
							resulting = 'success';

							moreDetails = "<br><br>Thank you for your business!<br><br>Our team is verifying your Payment info for accuracy.<br>";

						}


						swal({

							type: resulting,
							timer: 50000,
							title: details, 
							html:  data_array["messageText"]+ moreDetails,  
							confirmButtonColor: "#5682A3",
							confirmButtonClass: "waves-effect waves-light btn"

						}).catch(swal.noop)
					
			});



}

function completeOrderCC(){

// $(".icnComplete").hide();
$(".finishing").html("");
swal.showLoading();
var expMonth = $( "form :selected" ).eq(0).val();
var expYear = $( "form :selected" ).eq(1).val()


expirationDate = expMonth+"/01/"+expYear;


	$.post( 'chargeCard.php',  { dollarAmt: $("#dollarAmt").val(),
					email: $('#email').val(), 
					phone: $("#phoneNumber").val(), 
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
							timer:30000,
							title: details, 

							html:  data_array["messageText"]+ moreDetails,  
							confirmButtonColor: "#5682A3",
							confirmButtonClass: "waves-effect waves-light btn"

						}).catch(swal.noop);
					
			});


};