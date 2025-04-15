<?php
namespace App\Models\Tables;
use App\Config\Database;

class TWebsite{
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create($author_id, $name, $domain) {
        $db = Database::getInstance();
        $stmt = $db->prepare("INSERT INTO websites (author_id, name, domain) VALUES (:author_id, :name, :domain)");
        $stmt->bindParam(':author_id', $author_id);
        $stmt->bindParam(':domain', $domain);
        $stmt->execute();
        return self::find($db->lastInsertId());
    }

    public function all() {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM websites");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // public function find($id) {
    //     $db = Database::getInstance();
    //     $stmt = $db->prepare("SELECT * FROM websites WHERE id = :id");
    //     $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    //     $stmt->execute();
    //     return $stmt->fetch(PDO::FETCH_ASSOC);
    //     // if ($website) {
    //     //   $id = $website['id'];
    //     //   return new Website($id);
    //     // }
    // }
    public function update($id, $author_id, $domain) {
        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE websites SET author_id = :author_id, domain = :domain WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':author_id', $author_id);
        $stmt->bindParam(':domain', $domain);
        return $stmt->execute();
    }
    // public function findByDomain($domain) {
    //     $db = Database::getInstance();
    //     $stmt = $db->prepare("SELECT * FROM websites WHERE domain = :domain");
    //     $stmt->bindParam(':domain', $domain);
    //     $stmt->execute();
    //     return $stmt->fetch(PDO::FETCH_ASSOC);
    //     // if ($website) {
    //     //     $id = $website['id'];
    //     //     return new Website($id);
    //     // }
    // }
  }