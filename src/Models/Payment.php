<?php
// require_once 'modules.php';
namespace App\Models;
use App\Config\Database;
use PDO;
use Exception;
use App\Models\ApplicationRecord;
use App\Models\Website;
use App\Models\Author;

class Payment extends ApplicationRecord {
  // private $id, $website_id, $author_id, $amount, $payment_method, $status, 
  //         $reference, $currency, $payment_id, $receipt_number, $ip_address, 
  //         $updated_at, $created_at;

  public function __construct($data) {
    parent::__construct();
    if (is_array($data)) {
      foreach ($data as $key => $value) {
        if (property_exists($this, $key)) {
          $this->$key = $value;
        }
      }
    } else {
      // If an ID is provided, load the payment details
      $this->id = $data;
      $this->load($data);
    }
  }

  // Getters
  // public function __call($name, $arguments) {
  //   $property = strtolower(preg_replace('/^get/', '', $name));
  //   if (property_exists($this, $property)) {
  //     return $this->$property;
  //   }
  //   throw new Exception("Method $name does not exist");
  // }

  // public static function find($id) {
  //   return new Payment($id);
  // }

  // public static function all() {
  //   $db = Database::getInstance();
  //   $stmt = $db->prepare("SELECT * FROM payments");
  //   $stmt->execute();
  //   $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
  //   $paymentObjects = [];
  //   foreach ($payments as $payment) {
  //     $paymentObjects[] = new Payment($payment);
  //   }
  //   return $paymentObjects;
  // }

  // public static function create($website_id, $author_id, $amount, $payment_id, $currency, $receipt_number, $ip_address, $status, $reference, $payment_method) {
  //   $db = Database::getInstance();
  //   $stmt = $db->prepare("INSERT INTO payments (payment_id, amount, currency, payment_method, reference, status, receipt_number, ip_address, website_id, author_id) VALUES ( :payment_id, :amount, :currency, :payment_method, :reference, :status, :receipt_number, :ip_address, :website_id, :author_id)");
  //   $stmt->bindParam(':website_id', $website_id, PDO::PARAM_INT);
  //   $stmt->bindParam(':author_id', $author_id, PDO::PARAM_INT);
  //   $amount = $amount / 100;
  //   $stmt->bindParam(':amount', $amount);
  //   $stmt->bindParam(':status', $status, PDO::PARAM_INT);
  //   $stmt->bindParam(':reference', $reference);
  //   $stmt->bindParam(':method', $payment_method);
  //   $stmt->bindParam(':currency', $currency);
  //   $stmt->bindParam(':receipt_number', $receipt_number);
  //   $stmt->bindParam(':ip_address', $ip_address);
  //   $stmt->bindParam(':payment_id', $payment_id);
  //   $stmt->execute();
  //   return self::find($db->lastInsertId());
  // }

  // public function loadPayment() {
  //   $stmt = $this->db->prepare("SELECT * FROM payments WHERE id = :id");
  //   $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
  //   $stmt->execute();
  //   $payment = $stmt->fetch(PDO::FETCH_ASSOC);
  //   if ($payment) {
  //     $this->website_id = $payment['website_id'];
  //     $this->amount = $payment['amount'];
  //     $this->status = $payment['status'];
  //     $this->author_id = $payment['author_id'];
  //     $this->reference = $payment['reference'];
  //     $this->currency = $payment['currency'];
  //     $this->payment_id = $payment['payment_id'];
  //     $this->payment_method = $payment['payment_method'];
  //     $this->receipt_number = $payment['receipt_number'];
  //     $this->ip_address = $payment['ip_address'];
  //     $this->updated_at = $payment['updated_at'];
  //     $this->created_at = $payment['created_at'];
  //   }
  // }

  public function author() {
    $stmt = $this->db->prepare("SELECT * FROM authors WHERE id = :author_id");
    $stmt->bindParam(':author_id', $this->author_id, PDO::PARAM_INT);
    $stmt->execute();
    $author = $stmt->fetch(PDO::FETCH_ASSOC);
    if($author) {
      return new Author($author);
    }
    throw new Exception("Author not found");
  }

  public function website() {
    $stmt = $this->db->prepare("SELECT * FROM websites WHERE id = :website_id");
    $stmt->bindParam(':website_id', $this->website_id, PDO::PARAM_INT);
    $stmt->execute();
    $website = $stmt->fetch(PDO::FETCH_ASSOC);
    if($website) {
      return new Website($website);
    }
    throw new Exception("Website not found");
  }

  public function next_renewal_date() {
    $stmt = $this->db->prepare("SELECT * FROM payments WHERE website_id = :website_id AND status = 1 ORDER BY created_at DESC LIMIT 1");
    $stmt->bindParam(':website_id', $this->website_id, PDO::PARAM_INT);
    $stmt->execute();
    $payment = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($payment) {
      return date('Y-m-d', strtotime($payment['created_at'] . ' + 1 month'));
    }
    return null;
  }

}