<?php
  // only a post with paystack signature header gets our attention
  require __DIR__ . '/vendor/autoload.php';
  require_once './modules/website.php';
  require_once './modules/payment.php';
  require_once 'functions.php';

  if ((strtoupper($_SERVER['REQUEST_METHOD']) != 'POST' ) || !array_key_exists('HTTP_X_PAYSTACK_SIGNATURE', $_SERVER) ) 
      exit();

  // Retrieve the request's body
  $input = @file_get_contents("php://input");
  define('PAYSTACK_SECRET_KEY', $_ENV['PAYSTACK_SECRET_KEY']);

  // validate event do all at once to avoid timing attack
  if($_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] !== hash_hmac('sha512', $input, PAYSTACK_SECRET_KEY))
      exit();
  
  echo "Signature is valid\n";
  http_response_code(200);

  $event = json_decode($input);
  echo "Get result";
  switch ($event->event) {
      case 'charge.success':
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
        if ($response['status'] != 'success') {
          $website_id = $event->data->metadata->website_id;
          $website = new Website($website_id);
          $payment_id = $event->data->id;
          $currency = $event->data->currency;
          $amount = $event->data->amount;
          $method = $event->data->channel;
          $receipt_number = $response['data']['receipt_number'];
          $ip_address = $event->data->ip_address;
          $status = $event->data->status;
          $reference = $event->data->reference;
          $pay_id = $website->addPayment($amount, $payment_id, $currency, $receipt_number, $ip_address, $status, $reference, $method);
          $payment = new Payment($pay_id);
          if($payment) {
            $author = $website->author();
            $template = receipt_mail_template($website, $payment, $author);
            mail($author->email, "✅ Payment Received – Your WebStarter Plan is Active", );
          }
          break;}
      case 'charge.failed':
          // Handle failed charge
          break;
      case 'charge.pending':
          // Handle pending charge
          break;
      default:
          // Handle other events if necessary
  }
?>