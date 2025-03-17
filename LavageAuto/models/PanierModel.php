<?php
class PanierModel {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function ajouterAuPanier($produit_id, $quantite, $prix) {
        try {
            // Appel de la procédure stockée pour ajouter seulement un produit
            $stmt = $this->conn->prepare("CALL ajouter_au_panier(?, NULL, ?, ?)");
            $stmt->bindParam(1, $produit_id, PDO::PARAM_INT);
            $stmt->bindParam(2, $quantite, PDO::PARAM_INT);
            $stmt->bindParam(3, $prix, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false; // En cas d'erreur
        }
    }
}
