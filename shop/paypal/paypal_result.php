<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

include(G5_SHOP_PATH.'/paypal/incPaypalCommon.php');
include(G5_SHOP_PATH.'/paypal/paypal_conn.php');

require G5_SHOP_PATH.'/paypal/bootstrap.php';

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\ExecutePayment;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;

$kmFunc = new kmpayFunc($LogDir);
$kmFunc->setPhpVersion($phpVersion);

$resultMap = get_session('paypalResultMap');

if(strcmp('approved', $resultMap['state']) == 0) {

	$payment = Payment::get($resultMap['id'], $apiContext);
	
	$kmFunc->writeLog($payment);
	
	$tno             = $resultMap['id'];
    $amount     = $payment->transactions[0]->amount->total * 100;
    $app_time   = date('Y-m-d h:m:s', strtotime($payment->create_time));
    $pay_method = 'PAYPAL';
    // $pay_type   = $PAY_METHOD[$pay_method];
    // $depositor  = $resultMap['VACT_InputName'];
    $currency     = $payment->transactions[0]->amount->currency;
    // $mobile_no  = $resultMap['HPP_Num'];c
    $app_no     = $resultMap['id'];
	
	$kmFunc->writeLog(strtotime($payment->create_time));
	$kmFunc->writeLog($amount);
	$kmFunc->writeLog($app_time);
} else {
	alert('[RESULT_CODE] : ' . $result['state']);
}
	
    

//     $amount          = $amt;
//     $app_time        = '20'.$authDate;
//     $bank_name       = $cardName;
//     $depositor       = '';
//     $account         = '';
//     $commid          = $cardCode;
//     $mobile_no       = '';
//     $app_no          = $authCode;
//     $card_name       = $cardName;
//     $pay_type        = 'CARD';
//     $escw_yn         = '0';
// } else {
//    alert('[RESULT_CODE] : ' . $resultCode . '\\n[RESULT_MSG] : ' . $resultMsg);
// }

?>
