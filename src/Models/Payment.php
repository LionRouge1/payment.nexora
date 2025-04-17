<?php
namespace App\Models;
use App\Config\Database;
use PDO;
use Exception;
use App\Models\ApplicationRecord;
use App\Models\Website;
use App\Models\Author;

class Payment extends ApplicationRecord {
  protected $id, $website_id, $author_id, $amount, $payment_method, $status, 
          $reference, $currency, $payment_id, $receipt_number, $ip_address, 
          $updated_at, $created_at;

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