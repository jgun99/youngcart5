<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

echo "teset";
echo $_REQUEST;


if($paySuccess) {
    $tno             = $tid;
    $amount          = $amt;
    $app_time        = '20'.$authDate;
    $bank_name       = $cardName;
    $depositor       = '';
    $account         = '';
    $commid          = $cardCode;
    $mobile_no       = '';
    $app_no          = $authCode;
    $card_name       = $cardName;
    $pay_type        = 'CARD';
    $escw_yn         = '0';
} else {
   alert('[RESULT_CODE] : ' . $resultCode . '\\n[RESULT_MSG] : ' . $resultMsg);
}

?>
