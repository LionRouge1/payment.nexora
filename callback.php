<?php
require __DIR__ . '/vendor/autoload.php';
require_once './modules/website.php';
require_once './modules/tables/TPayment.php';
if(!isset($_GET['reference'])) {
    echo 'No reference provided.';
    exit;
}
$reference = $_GET['reference'];

$curl = curl_init();
  
curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.paystack.co/transaction/verify/$reference",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
      "Authorization: Bearer ".$_ENV['PAYSTACK_SECRET_KEY'],
      "Cache-Control: no-cache",
    ),
));
  
$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);
  
if ($err) {
    echo "cURL Error #:" . $err;
} else {
    $response = json_decode($response, true);
    $website_id = $response['data']['metadata']['website_id'];
    $payment_id = $response['data']['id'];
    $payment_table = new TPayment();
    $payment = $payment_table->findByPaymentId($payment_id);
    if(!$payment) {
        $website = new Website($website_id);
        $currency = $response['data']['currency'];
        $amount = $response['data']['amount'];
        $method = $response['data']['channel'];
        $receipt_number = $response['data']['receipt_number'];
        $ip_address = $response['data']['ip_address'];
        $status = $response['data']['status'];
        $reference = $response['data']['reference'];
        $website->addPayment($amount, $payment_id, $currency, $receipt_number, $ip_address, $status, $reference, $method);
    }
    header("location: http://localhost/payment.nexora/thanks.php");
    die();
}
?>