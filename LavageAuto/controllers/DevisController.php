<?php
class DevisController {
    public function generateDevis() {
        $produit = $_POST['produit'] ?? '';
        $service = $_POST['service'] ?? '';
        $quantite = $_POST['quantite'] ?? 1;

        // Traitement du devis sans enregistrement
        include '../views/devis.php';
    }
}
?>