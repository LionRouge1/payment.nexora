<?php
namespace App\Models;
use App\Config\Database;
use PDO;
use Exception;
use App\Models\ApplicationRecord;
use App\Models\Author;

class Website extends ApplicationRecord {
  protected $id, $author_id, $domain, $name, $updated_at, $created_at;

  public function __construct($data) {
    parent::__construct();
    if (is_array($data)) {
      foreach ($data as $key => $value) {
        if (property_exists($this, $key)) {
          $this->$key = $value;
        }
      }
    } else {
      $this->id = $data;
      $this->load($data);
    }
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

  public function addPayment($amount, $payment_id, $currency, $payment_method, $reference, $status, $receipt_number, $ip_address) {
    $stmt = $this->db->prepare("INSERT INTO payments (payment_id, amount, currency, payment_method, reference, status, receipt_number, ip_address, author_id, website_id) VALUES (:payment_id, :amount, :currency, :payment_method, :reference, :status, :receipt_number, :ip_address, :author_id, :website_id)");
    $stmt->bindParam(':website_id', $this->id, PDO::PARAM_INT);
    $stmt->bindParam(':author_id', $this->author_id, PDO::PARAM_INT);
    $amount = $amount/100;
    $stmt->bindParam(':amount', $amount);
    $stmt->bindParam(':status', $status, PDO::PARAM_INT);
    $stmt->bindParam(':reference', $reference);
    $stmt->bindParam(':payment_method', $method);
    $stmt->bindParam(':currency', $currency);
    $stmt->bindParam(':receipt_number', $receipt_number);
    $stmt->bindParam(':ip_address', $ip_address);
    $stmt->bindParam(':payment_id', $payment_id);
    $stmt->execute();
    return $this->db->lastInsertId();
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