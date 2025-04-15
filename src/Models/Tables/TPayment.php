<?php
namespace App\Models\Tables;
use App\Config\Database;
use PDO;
use Exception;

class TPayment {
    // protected $id;
    // protected $website_id;
    // protected $author_id;
    // protected $amount;
    // protected $method;
    // protected $status;
    // protected $reference;
    // protected $currency;
    // protected $payment_id;
    // protected $created_at;
    protected $db;

    public function __construct() {
        // $this->id = $id;
        // $this->website_id = $website_id;
        // $this->author_id = $author_id;
        // $this->method = $method;
        // $this->amount = $amount;
        // $this->status = $status;
        // $this->reference = $reference;
        // $this->currency = $currency;
        // $this->payment_id = $payment_id;
        // $this->created_at = $created_at;
        $this->db = Database::getInstance();
    }

    // Getters
    // public function getId() {
    //     return $this->id;
    // }
    // public function getWebsiteId() {
    //     return $this->website_id;
    // }
    // public function getAmount() {
    //     return $this->amount;
    // }
    // public function getStatus() {
    //     return $this->status;
    // }
    // public function getCreatedAt() {
    //     return $this->created_at;
    // }

    public static function create($website_id, $author_id, $amount, $payment_id, $currency, $receipt_number, $ip_address, $status, $reference, $method) {
        $db = Database::getInstance();
        $stmt = $db->prepare("INSERT INTO payments (website_id, author_id, amount, currency, receipt_number, ip_address, payment_id, status, reference, method) VALUES (:website_id, :author_id, :amount, :currency, :receipt_number, :ip_address, :payment_id, :status, :reference, :method)");
        $stmt->bindParam(':website_id', $website_id, PDO::PARAM_INT);
        $stmt->bindParam(':author_id', $author_id, PDO::PARAM_INT);
        $amount = $amount / 100;
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
        return self::find($this->db->lastInsertId());
    }

    public static function all() {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM payments");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // public static function find($id) {
    //     $db = Database::getInstance();
    //     $stmt = $db->prepare("SELECT * FROM payments WHERE id = :id");
    //     $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    //     $stmt->execute();
    //     return $stmt->fetch(PDO::FETCH_ASSOC);
    // }

    public function update($id, $website_id, $amount, $status) {
        $stmt = $this->db->prepare("UPDATE payments SET website_id = :website_id, amount = :amount, status = :status WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':website_id', $website_id);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':status', $status);
        return $stmt->execute();
    }

    public static function findByPaymentId($payment_id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM payments WHERE payment_id = :payment_id");
        $stmt->bindParam(':payment_id', $payment_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function findByReference($reference) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM payments WHERE reference = :reference");
        $stmt->bindParam(':reference', $reference);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
