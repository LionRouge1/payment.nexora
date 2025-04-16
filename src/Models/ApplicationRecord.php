<?php
namespace App\Models;

use Doctrine\Inflector\InflectorFactory;
use App\Database;
use PDO;
use Exception;
/**
 * ApplicationRecord class
 *
 * This class serves as a base model for all database records.
 * It provides methods for CRUD operations and handles database interactions.
 */

class ApplicationRecord {
  protected $db, $tableName, $className, $newRecord = false;
  private $inflector;

  public function __construct() {
    $this->db = Database::getInstance();
    $this->inflector = InflectorFactory::create()->build();
    $this->className = get_class($this);
    $this->tableName = strtolower($this->inflector->pluralize($this->className));
  }

  public function __get($property) {
    if (property_exists($this, $property)) {
      return $this->$property;
    }
    throw new Exception("Property $property does not exist in " . $this->className);
  }

  public function __set($property, $value) {
    if (property_exists($this, $property)) {
      $this->$property = $value;
      $this->newRecord = true;
    } else {
      throw new Exception("Property $property does not exist in " . $this->className);
    }
  }

  public static function find($id) {
    $className = get_called_class();
    return new $className($id);
  }

  public function load($id) {
    $stmt = $this->db->prepare("SELECT * FROM " . $this->tableName . "WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($record) {
      foreach ($record as $key => $value) {
        if (property_exists($this, $key)) {
          $this->$key = $value;
        }
      }
    } else {
      throw new Exception("Record not found");
    }
  }

  public static function findBy($column, $value) {
    $className = get_called_class();
    $db = Database::getInstance();
    $inflector = InflectorFactory::create()->build();
    $tableName = strtolower($inflector->pluralize($className));
    $stmt = $db->prepare("SELECT * FROM " . $tableName . "WHERE $column = :value");
    $stmt->bindParam(':value', $value);
    $stmt->execute();
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($record) {
      return new $className($record);
    }
    return null;
  }

  public static function all() {
    $className = get_called_class();
    $db = Database::getInstance();
    $inflector = InflectorFactory::create()->build();
    $tableName = strtolower($inflector->pluralize($className));
    $stmt = $db->prepare("SELECT * FROM " . $tableName);
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $recordObjects = [];
    foreach ($records as $record) {
      $recordObjects[] = new $className($record);
    }
    return $recordObjects;
  }

  public static function create($data) {
    $className = get_called_class();
    $db = Database::getInstance();
    $inflector = InflectorFactory::create()->build();
    $tableName = strtolower($inflector->pluralize($className));
    $columns = implode(", ", array_keys($data));
    $placeholders = ":" . implode(", :", array_keys($data));
    $stmt = $db->prepare("INSERT INTO " . $tableName . " ($columns) VALUES ($placeholders)");
    foreach ($data as $key => $value) {
        $stmt->bindValue(":$key", $value);
    }
        // Bind parameters and execute
    $stmt->execute();
    return new $className($db->lastInsertId());
  }

  public function save() {
    $properties = ['id', 'db', 'tableName', 'className', 'inflector', 'newRecord', 'created_at', 'updated_at'];

    if($this->newRecord && isset($this->id) && !empty($this->id)) {
      $data = [];
      foreach ($this as $key => $value) {
        if (!in_array($key, $properties)) {
          $data[] = "$key = :$value";
        }
      }
      $setString = implode(", ", $set);
      $stmt = $this->db->prepare("UPDATE " . $this->tableName . " SET $setString WHERE id = :id");
      foreach ($data as $key => $value) {
        $stmt->bindValue(":$key", $value);
      }
      $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
    } else {
      $data = [];
      foreach ($this as $key => $value) {
        if (!in_array($key, $properties)) {
          $data[$key] = $value;
        }
      }
      $columns = implode(", ", array_keys($data));
      $placeholders = ":" . implode(", :", array_keys($data));
        $stmt = $this->db->prepare("INSERT INTO " . $this->tableName . " ($columns) VALUES ($placeholders)");
        foreach ($data as $key => $value) {
          $stmt->bindValue(":$key", $value);
        }
    }

    $stmt->execute();
    return new $this->className($this->db->lastInsertId());
  }

  public function delete() {
    $stmt = $this->db->prepare("DELETE FROM " . $this->tableName . " WHERE id = :id");
    $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->rowCount() > 0;
  }
}