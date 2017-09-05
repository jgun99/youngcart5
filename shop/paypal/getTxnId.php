<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/json.lib.php');
include(G5_SHOP_PATH.'/paypal/incPaypalCommon.php');
include(G5_SHOP_PATH.'/paypal/paypal_conn.php');

require 'bootstrap.php';

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;


$kmFunc = new kmpayFunc($LogDir);
$kmFunc->setPhpVersion($phpVersion);

$payer = new Payer();
$payer->setPaymentMethod("paypal");

// Set redirect urls
$redirectUrls = new RedirectUrls();
$redirectUrls->setReturnUrl('http://localhost:3000/process.php')
  ->setCancelUrl('http://localhost:3000/cancel.php');

// Set payment amount
$amount = new Amount();
$amount->setCurrency("USD")
  ->setTotal(10);

// Set transaction object
$transaction = new Transaction();
$transaction->setAmount($amount)
  ->setDescription("Payment description");

// Create the full payment object
$payment = new Payment();
$payment->setIntent('sale')
  ->setPayer($payer)
  ->setRedirectUrls($redirectUrls)
  ->setTransactions(array($transaction));



// Create payment with valid API context
try {
  $payment->create($apiContext);

	$kmFunc->writeLog($payment);
	
  // Get PayPal redirect URL and redirect user
  $approvalUrl = $payment->getApprovalLink();
	$paymentId = $payment->getId();
	
	
	

  // REDIRECT USER TO $approvalUrl
} catch (PayPal\Exception\PayPalConnectionException $ex) {
  echo $ex->getCode();
  echo $ex->getData();
  die($ex);
} catch (Exception $ex) {
  die($ex);
}


$result = array();

$result = array(
	'paymentID' => $paymentId,
	'approvalUrl' => $approvalUrl,
    'resultCode' => $resultCode,
    'resultMsg'  => $resultMsg,
    'txnId'      => $txnId,
    'prDt'       => $prDt
);

die(json_encode($result));
?>