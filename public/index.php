<?php
require_once __DIR__ . '/../vendor/autoload.php';

session_start();

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Handle PUT/DELETE methods
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_method'])) {
    $_SERVER['REQUEST_METHOD'] = strtoupper($_POST['_method']);
}

// Route configuration
$routes = [
    'GET' => [
        '/payment.nexora/public/' => ['WebsiteController', 'index'],
        '/payment.nexora/public/payment/thanks' => ['PaymentController', 'thanks'],
        // '/payment.nexora/public/payments' => ['PaymentController', 'index'],
        // '/payments/create' => ['UserController', 'create'],
        // '/payments/(\d+)' => ['UserController', 'show'],
        // '/payments/(\d+)/edit' => ['UserController', 'edit']
    ],
    'POST' => [
        '/payment.nexora/public/' => ['WebsiteController', 'search'],
        '/payment.nexora/public/initialize' => ['PaymentController', 'initialize'],
        '/payment.nexora/public/verify' => ['PaymentController', 'verify'],
        // '/payments' => ['UserController', 'store']
    ],
    // 'PUT' => [
    //     '/payments/(\d+)' => ['UserController', 'update']
    // ],
    // 'DELETE' => [
    //     '/payments/(\d+)' => ['UserController', 'delete']
    // ]
];

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];
// var_dump($requestUri, $requestMethod); // Debugging line

// Check for asset requests first
if (preg_match('#^/assets/#', $requestUri)) {

  // Serve static assets directly
    return false; // Let Apache serve the asset directly
}

// Route matching
$matched = false;

foreach ($routes[$requestMethod] ?? [] as $pattern => [$controllerName, $action]) {
    if (preg_match('#^' . $pattern . '$#', $requestUri, $matches)) {
        $matched = true;
        $controllerClass = "App\\Controllers\\{$controllerName}";
        $controller = new $controllerClass();
        
        array_shift($matches); // Remove full match
        call_user_func_array([$controller, $action], $matches);
        break;
    }
}

if (!$matched) {
    $controller = new App\Controllers\ApplicationController();
    $controller->notFound();
}
?>

