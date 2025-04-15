<?php
namespace App\Models\Tables;
use App\Config\Database;
use PDO;
use Exception;

class TPayment {
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance();
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
