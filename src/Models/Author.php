<?php
namespace App\Models;
use App\Models\Tables\TAuthor;
use App\Models\Website;
use App\Models\Payment;

class Author extends TAuthor {
  private $id;
  private $fullname;
  private $email;
  private $phone;
  private $whatsapp;
  private $created_at;
  private $updated_at;

  public function __construct($id = null) {
    parent::__construct();
    if (is_array($id)) {
      $this->id = $id['id'];
      $this->fullname = $id['fullname'];
      $this->email = $id['email'];
      $this->phone = $id['number'];
      $this->whatsapp = $id['whatsapp'];
      $this->created_at = $id['created_at'];
      $this->updated_at = $id['updated_at'];
    } else {
      // If an ID is provided, load the author details
      $this->id = $id;
      $this->loadAuthor();
    }
  }

  // Getters
  public function getId() {
    return $this->id;
  }
  public function getFullname() {
    return $this->fullname;
  }
  public function getEmail() {
    return $this->email;
  }
  public function getPhone() {
    return $this->phone;
  }
  public function getWhatsapp() {
    return $this->whatsapp;
  }
  public function getCreatedAt() {
    return $this->created_at;
  }

  public function loadAuthor() {
    $stmt = $this->db->prepare("SELECT * FROM authors WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $author = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($author) {
      $this->id = $id;
      $this->fullname = $author['fullname'];
      $this->email = $author['email'];
      $this->phone = $author['number'];
      $this->whatsapp = $author['whatsapp'];
      $this->created_at = $author['created_at'];
      $this->updated_at = $author['updated_at'];
    } else {
      throw new Exception("Author not found");
    }
  }

  public function websites() {
    $stmt = $this->db->prepare("SELECT * FROM websites WHERE author_id = :id");
    $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
    $stmt->execute();
    $websites = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $websiteObjects = [];
    foreach ($websites as $website) {
      $websiteObjects[] = new Website($website['id']);
    }
    return $websiteObjects;
  }

  public function payments() {
    $stmt = $this->db->prepare("SELECT * FROM payments WHERE author_id = :id");
    $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
    $stmt->execute();
    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $paymentObjects = [];
    foreach ($payments as $payment) {
      $paymentObjects[] = new Payment($payment['id']);
    }
    return $paymentObjects;
  }

  public function addWebsite($domain, $name) {
    $stmt = $this->db->prepare("INSERT INTO websites (author_id, name, domain) VALUES (:author_id, :name, :domain)");
    $stmt->bindParam(':author_id', $this->id, PDO::PARAM_INT);
    $stmt->bindParam(':domain', $domain);
    $stmt->execute();
    return $this->db->lastInsertId();
  }

  public function updateWebsite($id, $domain, $name) {
    $stmt = $this->db->prepare("UPDATE websites SET domain = :domain, name = :name WHERE id = :id AND author_id = :author_id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':author_id', $this->id, PDO::PARAM_INT);
    $stmt->bindParam(':domain', $domain);
    $stmt->bindParam(':name', $name);
    return $stmt->execute();
  }

  public function deleteWebsite($id) {
    $stmt = $this->db->prepare("DELETE FROM websites WHERE id = :id AND author_id = :author_id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':author_id', $this->id, PDO::PARAM_INT);
    return $stmt->execute();
  }

  public function addPayment($website_id, $amount, $payment_id, $status, $reference, $method) {
    $stmt = $this->db->prepare("INSERT INTO payments (author_id, amount, payment_id, status, reference, method, website_id) VALUES (:author_id, :amount, :payment_id, :status, :reference, :method, :website_id)");
    $stmt->bindParam(':author_id', $this->id, PDO::PARAM_INT);
    $stmt->bindParam(':amount', $amount);
    $stmt->bindParam(':payment_id', $payment_id);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':reference', $reference);
    $stmt->bindParam(':method', $method);
    $stmt->bindParam(':website_id', $website_id);
    return $stmt->execute();
  }

  public function updatePayment($id, $status) {
    $stmt = $this->db->prepare("UPDATE payments SET status = :status WHERE id = :id AND author_id = :author_id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':author_id', $this->id, PDO::PARAM_INT);
    $stmt->bindParam(':status', $status);
    return $stmt->execute();
  }

  public function deletePayment($id) {
    $stmt = $this->db->prepare("DELETE FROM payments WHERE id = :id AND author_id = :author_id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':author_id', $this->id, PDO::PARAM_INT);
    return $stmt->execute();
  }
}