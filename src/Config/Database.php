<?php
  namespace App\Config;

  use Dotenv\Dotenv;
  use PDO;
  use PDOException;

  class Database
  {
    private static $instance = null;
    private $connection;

    private function __construct()
    {
      $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
      $dotenv->load();
      $dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS', 'DB_CHARSET']);

      $host = $_ENV['DB_HOST'];
      $db = $_ENV['DB_NAME'];
      $user = $_ENV['DB_USER'];
      $pass = $_ENV['DB_PASS'];

      try {
        $this->connection = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
      }
    }

    public static function getInstance()
    {
      if (self::$instance == null) {
        self::$instance = new Database();
      }
      return self::$instance->connection;
    }
  }