<?php
require '../models/Service.php';
require '../models/Product.php';

class HomeController {
    public function index() {
        $serviceModel = new Service();
        $services = $serviceModel->getAllServices();

        $productModel = new Product();
        $products = $productModel->getAllProducts();

        require '../views/header.php';
        require '../views/home.php';
        require '../views/footer.php';
    }
    public function voirProduit() {
        $produitModel = new Product();
        $produits = $produitModel->getAllProducts();
        include '../views/voirProduit.php';
    }

    public function voirService() {
        $serviceModel = new Service();
        $services = $serviceModel->getAllServices();
        include '../views/voirService.php';
    }

    public function showDevis()
    {
        $devis = new Devis();
        $data = $devis->getDevisData();

        // Passez les données à la vue
        require '../views/devis.php';
    }
}
?>
