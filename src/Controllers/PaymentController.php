<?php
namespace App\Controllers;
use App\Controllers\ApplicationController;
use App\Models\Payment;

class PaymentController extends ApplicationController
{
  public function initialize()
  {
    if (!isset($_SESSION['website_id']) || !isset($_SESSION['email'])) {
      http_response_code(400);
      $_SESSION['error'] = 'Website ID or email not found in session';
      header('Location: /payment.nexora/public/');
      die();
      return;
    }

    $data = [
      "email" => $_SESSION['email'],
      "website_id" => $_SESSION['website_id'],
      "amount" =>  250 * 100,
    ];

    http_response_code(200);
    echo json_encode($data);

  }

  public function verify()
  {
    if (isset($_POST['error'])) {
      $this->handleError($_POST['error']);
      return;
    }

    $reference = $_POST['reference'] ?? null;
    if (!$reference) {
      http_response_code(400);
      $_SESSION['error'] = 'Reference not provided';
      echo json_encode(['error' => 'Reference not provided']);
      return;
    }

    $url = "https://api.paystack.co/transaction/verify/" . $reference;

    //open connection
    $ch = curl_init();

    curl_setopt_array($ch, [
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_HTTPHEADER => [
        "Authorization: Bearer ".$_ENV['PAYSTACK_SECRET_KEY'],
        "Cache-Control: no-cache",
      ],
    ]);

    $response = curl_exec($ch);
    curl_close($ch);
    
    // save data to database
    $response = json_decode($response, true);
    if (!$response || !isset($response['status'])) {
      http_response_code(500);
      $_SESSION['error'] = 'Invalid Paystack response';
      echo json_encode(['error' => 'Invalid Paystack response']);
      return;
    }

    if ($response['status'] === true && $response['data']['status'] === 'success') {
      $data = $response['data'];
      $website_id = $data['metadata']['website_id'] ?? null;

      if (!$website_id) {
        http_response_code(400);
        $_SESSION['error'] = 'Website ID not found in metadata';
        echo json_encode(['error' => 'Website ID not found in metadata']);
        return;
      }

      $author_id = $_SESSION['author_id'] ?? null;
      if (!$author_id) {
        http_response_code(400);
        $_SESSION['error'] = 'Author ID not found in session';
        echo json_encode(['error' => 'Author ID not found in session']);
        return;
      }

      try {
        $payment = [
          'website_id' => $website_id,
          'author_id' => $author_id,
          'amount' => $data['amount'],
          'payment_id' => $data['id'],
          'currency' => $data['currency'],
          'receipt_number' => $data['receipt_number'],
          'reference' => $data['reference'],
          'ip_address' => $data['ip_address'],
          'status' => $data['status'],
          'payment_method' => $data['channel']
        ];

        $payment = Payment::create($payment);
      } catch (Exception $e) {
        http_response_code(500);
        $_SESSION['error'] = 'Failed to save payment';
        echo json_encode(['error' => 'Failed to save payment']);
        return;
      }

    } else {
      http_response_code(500);
      $_SESSION['error'] = 'Paystack Error: ' . $response['message'];
      echo json_encode(['error' => 'Paystack Error: ' . $response['message']]);
      return;
    }

    // clean up session
    unset($_SESSION['website_id']);
    unset($_SESSION['email']);
    unset($_SESSION['fullname']);
    unset($_SESSION['domain']);
    unset($_SESSION['name']);
    unset($_SESSION['author_id']);
    unset($_SESSION['website_id']);

    // set success session
    $_SESSION['success'] = true;
    http_response_code(200);
    echo json_encode(['status' => 'success', 'message' => 'Payment verified successfully']);
  }

  public function thanks()
  {
    if (!isset($_SESSION['success']) || $_SESSION['success'] !== true) {
      http_response_code(400);
      header('Location: /payment.nexora/public/');
      die();
      return;
    }
    $this->render('payments/thanks');
  }

  private function handleError($error)
  {
    http_response_code(500);
    $_SESSION['error'] = $error;
    echo json_encode(['error' => 'error']);
    return;
  }
}