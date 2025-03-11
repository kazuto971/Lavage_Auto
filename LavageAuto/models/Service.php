<?php
require_once 'Database.php';

class Service {
    private $conn;
    private $table = 'prestations';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getAllServices() {
        $query = 'SELECT * FROM ' . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
