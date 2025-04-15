<?php
// require_once 'modules.php';
namespace App\Models;
use App\Config\Database;
use PDO;
use Exception;
use App\Models\Tables\TPayment;
use App\Models\Website;
use App\Models\Author;

class Payment extends TPayment {
  private $id;
  private $website_id;
  private $author_id;
  private $amount;
  private $method;
  private $status;
  private $reference;
  private $currency;
  private $payment_id;
  private $created_at;
  // protected $db;

  public function __construct($id) {
    parent::__construct();
    if (is_array($id)) {
      $this->id = $id['id'];
      $this->website_id = $id['website_id'];
      $this->author_id = $id['author_id'];
      $this->amount = $id['amount'];
      $this->method = $id['method'];
      $this->status = $id['status'];
      $this->reference = $id['reference'];
      $this->currency = $id['currency'];
      $this->payment_id = $id['payment_id'];
      $this->created_at = $id['created_at'];
    }  else {
      // If an ID is provided, load the payment details
      $this->id = $id;
      $this->loadPayment();
    }
  }

  // Getters
  public function getId() {
    return $this->id;
  }
  public function getWebsiteId() {
    return $this->website_id;
  }
  public function getAmount() {
    return $this->amount;
  }
  public function getStatus() {
    return $this->status;
  }
  public function getCreatedAt() {
    return $this->created_at;
  }
  public function getMethod() {
    return $this->method;
  }
  public function getReference() {
    return $this->reference;
  }
  public function getCurrency() {
    return $this->currency;
  }
  public function getPaymentId() {
    return $this->payment_id;
  }

  public static function find($id) {
    return new Payment($id);
  }

  public static function all() {
    $db = Database::getInstance();
    $stmt = $db->prepare("SELECT * FROM payments");
    $stmt->execute();
    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $paymentObjects = [];
    foreach ($payments as $payment) {
      // var_dump($payment);
      $paymentObjects[] = new Payment($payment['id']);
    }
    return $paymentObjects;
  }

  public static function create($website_id, $author_id, $amount, $payment_id, $currency, $receipt_number, $ip_address, $status, $reference, $method) {
    $db = Database::getInstance();
    $stmt = $db->prepare("INSERT INTO payments (website_id, author_id, amount, currency, receipt_number, ip_address, payment_id, status, reference, method) VALUES (:website_id, :author_id, :amount, :currency, :receipt_number, :ip_address, :payment_id, :status, :reference, :method)");
    $stmt->bindParam(':website_id', $website_id, PDO::PARAM_INT);
    $stmt->bindParam(':author_id', $author_id, PDO::PARAM_INT);
    $amount = $amount / 100;
    $stmt->bindParam(':amount', $amount);
    $status = ($status === 'success' && $amount >= 220) ? 1 : 0;
    $stmt->bindParam(':status', $status, PDO::PARAM_INT);
    $stmt->bindParam(':reference', $reference);
    $stmt->bindParam(':method', $method);
    $stmt->bindParam(':currency', $currency);
    $stmt->bindParam(':receipt_number', $receipt_number);
    $stmt->bindParam(':ip_address', $ip_address);
    $stmt->bindParam(':payment_id', $payment_id);
    $stmt->execute();
    return self::find($db->lastInsertId());
  }

  public function loadPayment() {
    $stmt = $this->db->prepare("SELECT * FROM payments WHERE id = :id");
    $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
    $stmt->execute();
    $payment = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($payment) {
      // $this->id = $id;
      $this->website_id = $payment['website_id'];
      $this->amount = $payment['amount'];
      $this->status = $payment['status'];
      $this->author_id = $payment['author_id'];
      $this->reference = $payment['reference'];
      $this->currency = $payment['currency'];
      $this->payment_id = $payment['payment_id'];
      $this->method = $payment['method'];
      $this->created_at = $payment['created_at'];
    }
  }

  public function author() {
    $stmt = $this->db->prepare("SELECT * FROM authors WHERE id = :author_id");
    $stmt->bindParam(':author_id', $this->author_id, PDO::PARAM_INT);
    $stmt->execute();
    $author = $stmt->fetch(PDO::FETCH_ASSOC);
    if($author) {
      return new Author($author['id']);
    }
    throw new Exception("Author not found");
  }

  public function website() {
    $stmt = $this->db->prepare("SELECT * FROM websites WHERE id = :website_id");
    $stmt->bindParam(':website_id', $this->website_id, PDO::PARAM_INT);
    $stmt->execute();
    $website = $stmt->fetch(PDO::FETCH_ASSOC);
    if($website) {
      return new Website($website['id']);
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