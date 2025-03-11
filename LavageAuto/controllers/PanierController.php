<?php
include_once '../models/PanierModel.php';

class PanierController {
    public function ajouter() {
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $produit_id = filter_input(INPUT_GET, 'produit_id', FILTER_VALIDATE_INT);
            $prix = filter_input(INPUT_GET, 'prix', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $quantite = 1; // Quantité par défaut

            // Initialiser le panier dans la session si ce n'est pas déjà fait
            if (!isset($_SESSION['panier'])) {
                $_SESSION['panier'] = [];
            }

            // Ajouter uniquement les produits au panier
            if ($produit_id) {
                $_SESSION['panier'][] = [
                    'produit_id' => $produit_id,
                    'prix' => $prix, // Formater le prix avec 2 décimales
                    'quantite' => $quantite
                ];
            }

            // Ajouter une notification
            $_SESSION['notification'] = "Le produit a été ajouté à votre panier.";
            header('Location: index.php?controller=panier&action=vue'); // Rediriger vers la vue du panier
            exit();
        }
    }

    public function vue() {
        // Récupérer les produits du panier depuis la session
        $panier = $_SESSION['panier'] ?? [];

        // Inclure la vue du panier
        include '../views/voirPanier.php';
    }

    public function supprimer() {
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $produit_id = filter_input(INPUT_GET, 'produit_id', FILTER_VALIDATE_INT);

            // Vérifiez si le panier existe
            if (isset($_SESSION['panier'])) {
                // Parcourir le panier pour trouver et supprimer le produit
                foreach ($_SESSION['panier'] as $key => $item) {
                    if ($item['produit_id'] == $produit_id) {
                        unset($_SESSION['panier'][$key]); // Supprimer l'élément du panier
                        $_SESSION['notification'] = "Le produit a été supprimé de votre panier.";
                        break; // Sortir de la boucle après avoir supprimé l'élément
                    }
                }
            }

            exit();
        }
    }
}
