<?php
session_start(); // Démarrer la session pour utiliser les variables de session
require '../controllers/HomeController.php';
require '../controllers/PanierController.php';

// Initialiser le contrôleur par défaut
$controller = new HomeController();

// Vérifiez les routes pour les actions spécifiques
if (isset($_GET['route'])) {
    switch ($_GET['route']) {
        case 'voirService':
            $controller->voirService();
            break;
        case 'voirProduit':
            $controller->voirProduit();
            break;
        case 'voirPanier': // Ajout de la route pour voir le panier
            $panierController = new PanierController();
            $panierController->vue(); // Appelle la méthode pour afficher le panier
            exit; // Sortie pour éviter de charger le contrôleur par défaut
        default:
            $controller->index();
            break;
    }
} else {
    $controller->index();
}

// Récupérer le contrôleur et l'action depuis l'URL
$controllerName = $_GET['controller'] ?? 'home';
$action = $_GET['action'] ?? 'index';

// Inclure le contrôleur approprié
switch ($controllerName) {
    case 'panier':
        include_once '../controllers/PanierController.php';
        $panierController = new PanierController();
        $panierController->$action(); // Appel à l'action spécifiée
        break;
    
    // Autres cas pour d'autres contrôleurs peuvent être ajoutés ici

    default:
        // Inclure le contrôleur par défaut si nécessaire
        break;
}
?>
