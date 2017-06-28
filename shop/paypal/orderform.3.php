<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가


?>

	<div id="paypal-button"></div>

    <script>
		var data = $("#paypal_request input").serialize().split("&");
		var obj={};
		for(var key in data)
		{
			obj[data[key].split("=")[0]] = data[key].split("=")[1];
		}
	
		console.log(obj);
		
		var CREATE_PAYMENT_URL  = '/test/shop/paypal/create-payment.php';
		var EXECUTE_PAYMENT_URL = '/test/shop/paypal/execute-payment.php';

		
        paypal.Button.render({
			
            env: 'sandbox', // Or 'sandbox',

            commit: true, // Show a 'Pay Now' button

            payment: function() {
                return paypal.request.post(CREATE_PAYMENT_URL, obj).then(function(data) {	
                	
					alert(data.GoodsName);
					console.log(data);
					
					return data.id;
            	});
            },

            onAuthorize: function(data, actions) {
                return paypal.request.post(EXECUTE_PAYMENT_URL, {
					paymentID: data.paymentID,
					payerID:   data.payerID
				}).then(function() {

					f.submit();
				});
           }

        }, '#paypal-button');
    </script>
