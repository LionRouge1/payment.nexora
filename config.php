<?php
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS', 'DB_CHARSET']);

// Access variables
// $host = $_ENV['DB_HOST'];
// $db   = $_ENV['DB_NAME'];
// $user = $_ENV['DB_USER'];
// $pass = $_ENV['DB_PASS'];
// $charset = $_ENV['DB_CHARSET'];

// $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// $options = [
//     PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // throw exceptions
//     PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // return associative arrays
//     PDO::ATTR_EMULATE_PREPARES   => false,                  // use real prepared statements
// ];

// try {
//     $pdo = new PDO($dsn, $user, $pass, $options);
//     echo "✅ Connection successful!";
// } catch (\PDOException $e) {
//     throw new \PDOException($e->getMessage(), (int)$e->getCode());
// }
