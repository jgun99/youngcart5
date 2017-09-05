<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

?>

<script src="https://www.paypalobjects.com/api/checkout.js"></script>

<script>
	function getPayPalTxn(frm) {
		
		alert("test");
		
			// if(makeHashData(frm)) {
		frm.Amt.value = frm.good_mny.value;
		frm.BuyerEmail.value = frm.od_email.value;
		frm.BuyerName.value = frm.od_name.value;

		$.ajax({
			url: g5_url+"/shop/paypal/getTxnId.php",
			type: "POST",
			data: $("#paypal_request input").serialize(),
			dataType: "json",
			async: false,
			cache: false,
			success: function(data) {
				
				// frm.resultCode.value = data.resultCode;
				// frm.resultMsg.value = data.resultMsg;
				// frm.txnId.value = data.txnId;
				// frm.prDt.value = data.prDt;

				// cnspay(frm);
				
				console.log(data.approvalUrl);
				
				
				location.href=data.approvalUrl;
				
			},
			error: function(data) {
				console.log(data);
			}
		});
	}
	
</script>
