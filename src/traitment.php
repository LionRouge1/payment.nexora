<?php
require __DIR__ . '/vendor/autoload.php';
require_once 'modules/tables/TWebsite.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo 'Invalid request method';
  exit;
}

if (!isset($_POST['domain'])) {
  echo 'Domain not set';
  exit;
}
$domain = $_POST['domain'];
$table = new TWebsite();
$website = $table->findByDomain($_POST['domain']);

if (!$website) {
  echo 'Website not found';
  exit;
}
$author = $website->author();

$url = "https://api.paystack.co/transaction/initialize";

$fields = [
  'email' => $author->email,
  'amount' => "22000",
  'metadata' => [
    'website_id' => $website->id,
  ],
];

$fields_string = http_build_query($fields);

// //open connection
$ch = curl_init();

curl_setopt_array($ch, [
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true, // ← CRITICAL: Makes curl_exec return the response
  CURLOPT_POST => true,
  CURLOPT_POSTFIELDS => json_encode($fields),
  CURLOPT_HTTPHEADER => [
      "Authorization: Bearer ".$_ENV['PAYSTACK_SECRET_KEY'],
      "Content-Type: application/json", // ← Required for JSON payloads
      "Cache-Control: no-cache",
  ],
]);

$response = curl_exec($ch); // Now returns the API response as a string
$error = curl_error($ch); // Check for cURL errors
curl_close($ch);

if ($error) {
    die("cURL Error: " . $error);
}

// Decode JSON response
$result = json_decode($response, true); // `true` converts to associative array

if (!$result || !isset($result['status'])) {
    die("Invalid Paystack response");
}

// Now safely access $result as an array
if ($result['status']) {
    $authorizationUrl = $result['data']['authorization_url'];
    header("Location: " . $authorizationUrl); // Redirect to Paystack checkout
    exit();
} else {
    die("Paystack Error: " . $result['message']);
}

?>