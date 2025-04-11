<?php
require_once __DIR__ . '/modules/modules.php';

class Website extends Modules {
  
  public function __construct() {
    parent::__construct();
  }

  public function getWebsites() {
    $stmt = $this->db->prepare("SELECT * FROM websites");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getWebsite($id) {
    $stmt = $this->db->prepare("SELECT * FROM websites WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function getWebsiteByAuthor($author) {
    $stmt = $this->db->prepare("SELECT * FROM websites WHERE author = :author");
    $stmt->bindParam(':author', $author);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function addWebsite($author, $domain) {
    $stmt = $this->db->prepare("INSERT INTO websites (author, domain) VALUES (:author, :domain)");
    $stmt->bindParam(':author', $author);
    $stmt->bindParam(':domain', $domain);
    return $stmt->execute();
  }
  
  public function updateWebsite($id, $author, $domain) {
    $stmt = $this->db->prepare("UPDATE websites SET author = :author, domain = :domain WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':author', $author);
    $stmt->bindParam(':domain', $domain);
    return $stmt->execute();
  }

  public function deleteWebsite($id) {
    $stmt = $this->db->prepare("DELETE FROM websites WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    return $stmt->execute();
  }

  public function getWebsiteByDomain($domain) {
    $stmt = $this->db->prepare("SELECT * FROM websites WHERE domain = :domain");
    $stmt->bindParam(':domain', $domain);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function getWebsiteById($id) {
    $stmt = $this->db->prepare("SELECT * FROM websites WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
}