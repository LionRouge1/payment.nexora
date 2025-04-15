<?php
// require_once '../modules.php';
namespace App\Models\Tables;
use App\Config\Database;

class TAuthor {
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance();
        // parent::__construct();
    }

    public function create($fullname, $email, $phone, $whatsapp) {
        $db = Database::getInstance();
        $stmt = $db->prepare("INSERT INTO authors (fullname, email, phone, whatsapp) VALUES (:fullname, :email, :phone, :whatsapp)");
        $stmt->bindParam(':fullname', $fullname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':whatsapp', $whatsapp);
        $stmt->execute();
        return self::find($db->lastInsertId());

    }

    public function all() {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM authors");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function find($id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM authors WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function update($id, $fullname, $email, $phone, $whatsapp) {
        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE authors SET fullname = :fullname, email = :email, phone = :phone, whatsapp = :whatsapp WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':fullname', $fullname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':whatsapp', $whatsapp);
        return $stmt->execute();
    }

    public function findByEmail($email) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM authors WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}