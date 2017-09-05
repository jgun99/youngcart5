<?php

include_once('./_common.php');
include_once(G5_LIB_PATH.'/json.lib.php');
include(G5_SHOP_PATH.'/paypal/incPaypalCommon.php');
include(G5_SHOP_PATH.'/paypal/paypal_conn.php');


require 'bootstrap.php';

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\ExecutePayment;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;

$oid = $_REQUEST["oid"];
$kmFunc = new kmpayFunc($LogDir);
$kmFunc->setPhpVersion($phpVersion);

$paymentId = $_REQUEST["paymentId"];
$token =  $_REQUEST["token"];
$payerId = $_REQUEST["PayerID"];

$paymentId = $_GET['paymentId'];
$payment = Payment::get($paymentId, $apiContext);

$kmFunc->writeLog($payment);
$execution = new PaymentExecution();
$execution->setPayerId($_GET['PayerID']);

try {
	// Execute the payment
	// (See bootstrap.php for more on `ApiContext`)
	$result = $payment->execute($execution, $apiContext);

	set_session('paypalResult', $result);

	$kmFunc->writeLog($result);
	$kmFunc->writeLog($result->getState());	

	$resultMap = json_decode($result, true);
	try {
		$payment = Payment::get($paymentId, $apiContext);

	} catch (Exception $ex) {

		$kmFunc->writeLog($ex);

		exit(1);
	}
} catch (Exception $ex) {

	$kmFunc->writeLog($ex);

	echo "<br/>";
	echo "####인증실패####";

	exit(1);
}


// 주문번호로 조회
$sql = " select * from {$g5['g5_shop_order_data_table']} where od_id = '$oid' ";
$row = sql_fetch($sql);

$data = unserialize(base64_decode($row['dt_data']));
// $kmFunc->writeLog($data);

if(isset($data['pp_id']) && $data['pp_id']) {
	$order_action_url = G5_HTTPS_SHOP_URL.'/personalpayformupdate.php';
	$page_return_url  = G5_SHOP_URL.'/personalpayform.php?pp_id='.$data['pp_id'];
} else {
	$order_action_url = G5_HTTPS_SHOP_URL.'/orderformupdate.php';
	$page_return_url  = G5_SHOP_URL.'/orderform.php';
	if($_SESSION['ss_direct'])
		$page_return_url .= '?sw_direct=1';
}

if($result->getState() == 'approved') {
	set_session('paypalResultMap', $resultMap);
                
	require G5_SHOP_PATH.'/paypal/paypal_result_inc.php';
}




?>