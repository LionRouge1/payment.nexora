<?php
namespace App\Models;
use App\Models\ApplicationRecord;
use App\Models\Website;
use App\Models\Payment;

class Author extends ApplicationRecord {
  // private $id;
  // private $fullname;
  // private $email;
  // private $phone;
  // private $whatsapp;
  // private $address;
  // private $created_at;
  // private $updated_at;

  public function __construct($data) {
    parent::__construct();
    if (is_array($data)) {
      foreach ($data as $key => $value) {
        if (property_exists($this, $key)) {
          $this->$key = $value;
        }
      }
    } else {
      // If an ID is provided, load the author details
      $this->id = $data;
      $this->load($data);
    }
  }

  // Getters
  // public function getId() {
  //   return $this->id;
  // }
  // public function getFullname() {
  //   return $this->fullname;
  // }
  // public function getEmail() {
  //   return $this->email;
  // }
  // public function getPhone() {
  //   return $this->phone;
  // }
  // public function getWhatsapp() {
  //   return $this->whatsapp;
  // }
  // public function getCreatedAt() {
  //   return $this->created_at;
  // }

  // public function loadAuthor() {
  //   $stmt = $this->db->prepare("SELECT * FROM authors WHERE id = :id");
  //   $stmt->bindParam(':id', $id, PDO::PARAM_INT);
  //   $stmt->execute();
  //   $author = $stmt->fetch(PDO::FETCH_ASSOC);
  //   if ($author) {
  //     $this->id = $id;
  //     $this->fullname = $author['fullname'];
  //     $this->email = $author['email'];
  //     $this->phone = $author['phone'];
  //     $this->whatsapp = $author['whatsapp'];
  //     $this->address = $author['address'];
  //     $this->created_at = $author['created_at'];
  //     $this->updated_at = $author['updated_at'];
  //   } else {
  //     throw new Exception("Author not found");
  //   }
  // }

  public function websites() {
    $stmt = $this->db->prepare("SELECT * FROM websites WHERE author_id = :id");
    $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
    $stmt->execute();
    $websites = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $websiteObjects = [];
    foreach ($websites as $website) {
      $websiteObjects[] = new Website($website);
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
      $paymentObjects[] = new Payment($payment);
    }
    return $paymentObjects;
  }

  public function addWebsite($domain, $name) {
    $stmt = $this->db->prepare("INSERT INTO websites (author_id, name, domain) VALUES (:author_id, :name, :domain)");
    $stmt->bindParam(':author_id', $this->id, PDO::PARAM_INT);
    $stmt->bindParam(':domain', $domain);
    $stmt->execute();
    return new Website($this->db->lastInsertId());
  }

  public function addPayment($website_id, $amount, $payment_id, $currency, $payment_method, $reference, $status, $receipt_number, $ip_address) {
    $stmt = $this->db->prepare("INSERT INTO payments (payment_id, amount, currency, payment_method, reference, status, receipt_number, ip_address, author_id, website_id) VALUES (:payment_id, :amount, :currency, :payment_method, :reference, :status, :receipt_number, :ip_address, :author_id, :website_id)");
    $stmt->bindParam(':author_id', $this->id, PDO::PARAM_INT);
    $stmt->bindParam(':website_id', $website_id, PDO::PARAM_INT);
    $stmt->bindParam(':amount', $amount);
    $stmt->bindParam(':payment_id', $payment_id);
    $stmt->bindParam(':currency', $currency);
    $stmt->bindParam(':payment_method', $payment_method);
    $stmt->bindParam(':reference', $reference);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':receipt_number', $receipt_number);
    $stmt->bindParam(':ip_address', $ip_address);
    $stmt->execute();
    return new Payment($this->db->lastInsertId());
  }
}