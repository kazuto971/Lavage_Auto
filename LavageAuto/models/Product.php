<?php
require_once 'Database.php';

class Product {
    private $conn;
    private $table = 'services';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getAllProducts() {
        $query = 'SELECT * FROM ' . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // models/Product.php



    
}
?>
