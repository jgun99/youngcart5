<div id="pay_working">
    <span style="display:block; text-align:center;margin-top:120px"><img src="<?php echo G5_SHOP_URL; ?>/img/loading.gif" alt=""></span>
    <span style="display:block; text-align:center;margin-top:10px; font-size:14px">주문처리 중입니다. 잠시만 기다려 주십시오.</span>
</div>


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

$od_id = $_POST["merchantTxnNum"];
// 환율 변경전 금액 
$amount = $_POST["Amt"];
// 설정에 있는 환율
$rate = $default['de_paypal_exchange_rate'];
$goods_name = $_POST["GoodsName"];
$goods_count = $_POST["GoodsCnt"];

$kmFunc = new kmpayFunc($LogDir);
$kmFunc->setPhpVersion($phpVersion);

$kmFunc->writeLog("exchange rate = ".$rate);
$kmFunc->writeLog("charged amount = ".$amount);
$kmFunc->writeLog("charged amount = ".round($amount / $rate, 2));

// 설정에 있는 환율값으로 결제 요청금액 변환
$changed_amount = round($amount / $rate, 2);

$item1 = new Item();
$item1->setName($goods_name)
    ->setCurrency('USD')
    ->setQuantity(1)
    ->setPrice($changed_amount);

$itemList = new ItemList();
$itemList->setItems(array($item1));
$payer = new Payer();
$payer->setPaymentMethod("paypal");

// Set redirect urls
$redirectUrls = new RedirectUrls();
$redirectUrls->setReturnUrl(G5_SHOP_URL.'/paypal/result.php?oid='.$od_id)
  ->setCancelUrl(G5_SHOP_URL.'/paypal/result.php?oid='.$od_id);

// Set payment amount
$amount = new Amount();
$amount->setCurrency("USD")
  ->setTotal($changed_amount);

// Set transaction object
$transaction = new Transaction();
$transaction->setAmount($amount)
	->setItemList($itemList)
	->setDescription("");

// // Create the full payment object
$payment = new Payment();
$payment->setIntent('sale')
  ->setPayer($payer)
  ->setRedirectUrls($redirectUrls)
  ->setTransactions(array($transaction));

// Create payment with valid API context
try {
	$payment->create($apiContext);
  	$approvalUrl = $payment->getApprovalLink();
	
} catch (PayPal\Exception\PayPalConnectionException $ex) {
  echo $ex->getCode();
  echo $ex->getData();
  die($ex);
} catch (Exception $ex) {
  die($ex);
}


echo "<script>document.location.href='".$approvalUrl."'</script>";

?>
