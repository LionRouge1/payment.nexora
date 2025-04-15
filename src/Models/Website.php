<?php
namespace App\Models;
use App\Config\Database;
use PDO;
use Exception;
use App\Models\Tables\TWebsite;
use App\Models\Author;

class Website extends TWebsite {
  private $id;
  private $author_id;
  private $domain;
  private $name;
  private $created_at;

  public function __construct($id) {
    parent::__construct();
    if (is_array($id)) {
      $this->id = $id['id'];
      $this->author_id = $id['author_id'];
      $this->domain = $id['domain'];
      $this->name = $id['name'];
      $this->created_at = $id['created_at'];
    } else {
      $this->id = $id;
      $this->loadWebsite();
    }
  }

  // Getters
  public function getId() {
    return $this->id;
  }
  public function getAuthorId() {
    return $this->author_id;
  }
  public function getDomain() {
    return $this->domain;
  }
  public function getName() {
    return $this->name;
  }
  public function getCreatedAt() {
    return $this->created_at;
  }

  public function loadWebsite() {
    $stmt = $this->db->prepare("SELECT * FROM websites WHERE id = :id");
    $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
    $stmt->execute();
    $website = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($website) {
      $this->author_id = $website['author_id'];
      $this->domain = $website['domain'];
      $this->created_at = $website['created_at'];
      $this->name = $website['name'];
    } else {
      throw new Exception("Website not found");
    }
  }

  public static function find($id) {
    return new Website($id);
  }

  public static function findByDomain($domain) {
    $db = Database::getInstance();
    $stmt = $db->prepare("SELECT * FROM websites WHERE domain = :domain");
    $stmt->bindParam(':domain', $domain);
    $stmt->execute();
    return new Website($stmt->fetch(PDO::FETCH_ASSOC));
  }

  public function author() {
    $stmt = $this->db->prepare("SELECT * FROM authors WHERE id = :author_id");
    $stmt->bindParam(':author_id', $this->author_id, PDO::PARAM_INT);
    $stmt->execute();
    $author = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($author) {
      return new Author($author);
    }
    throw new Exception("Author not found");
  }

  public function payments() {
    $stmt = $this->db->prepare("SELECT * FROM payments WHERE website_id = :website_id");
    $stmt->bindParam(':website_id', $this->id, PDO::PARAM_INT);
    $stmt->execute();
    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $paymentObjects = [];
    foreach ($payments as $payment) {
      $paymentObjects[] = new Payment($payment);
    }
    return $paymentObjects;
  }

  public function addPayment($amount, $payment_id, $currency, $receipt_number, $ip_address, $status, $reference, $method) {
    $stmt = $this->db->prepare("INSERT INTO payments (website_id, author_id, amount, currency, receipt_number, ip_address, payment_id, status, reference, method) VALUES (:website_id, :author_id, :amount, :currency, :receipt_number, :ip_address, :payment_id, :status, :reference, :method)");
    $stmt->bindParam(':website_id', $this->id, PDO::PARAM_INT);
    $stmt->bindParam(':author_id', $this->author_id, PDO::PARAM_INT);
    $amount = $amount/100;
    $stmt->bindParam(':amount', $amount);
    $status = ($status === 'success') ? 1 : 0;
    $stmt->bindParam(':status', $status, PDO::PARAM_INT);
    $stmt->bindParam(':reference', $reference);
    $stmt->bindParam(':method', $method);
    $stmt->bindParam(':currency', $currency);
    $stmt->bindParam(':receipt_number', $receipt_number);
    $stmt->bindParam(':ip_address', $ip_address);
    $stmt->bindParam(':payment_id', $payment_id);
    $stmt->execute();
    return $this->db->lastInsertId();
  }

  public function delete() {
    $stmt = $this->db->prepare("DELETE FROM websites WHERE id = :id");
    $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
    return $stmt->execute();
  }

  public function unpaid_months() {
    $currentMonth = date('m');
    $currentYear = date('Y');
    $createdMonth = date('m', strtotime($this->created_at));
    $createdYear = date('Y', strtotime($this->created_at));
    $months_payed = 0;
    $total_payments = $this->payments().count();
    if ($currentYear === $createdYear) {
      $months_payed = $currentMonth - $createdMonth;
    } else {
      $months_payed = 12 - $createdMonth + $currentMonth;
    }

    $unpaid_months = $months_payed - $total_payments;
    return $unpaid_months > 0 ? $unpaid_months : 0;
  }
  
  public function payed_this_month() {
    $stmt = $this->db->prepare("SELECT * FROM payments WHERE website_id = :website_id AND MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())");
    $stmt->bindParam(':website_id', $this->id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->rowCount() > 0;
  }

  public function is_renewed() {
    if($this->payed_this_month()) {
      return true;
    };
    return false;
  }
  
  public function next_renewal_date() {
    $stmt = $this->db->prepare("SELECT * FROM payments WHERE website_id = :website_id AND status = 1 ORDER BY created_at DESC LIMIT 1");
    $stmt->bindParam(':website_id', $this->id, PDO::PARAM_INT);
    $stmt->execute();
    $payment = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($payment) {
      return date('Y-m-d', strtotime($payment['created_at'] . ' + 1 month'));
    }
    return null;
  }
}