<?php
require_once __DIR__ . '/config.php';


class Modules {
  public $db;

  public function __construct() {
    try {
      $this->db = new PDO($dsn, $user, $pass, $options);;
    } catch (Exception $e) {
      echo "Error: " . $e->getMessage();
    }
  }

}