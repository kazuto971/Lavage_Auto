<?php include 'header.php'; ?> 

<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";  
$password = "";  
$dbname = "lavage_auto";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

// Récupérer les services
$services = $conn->query("SELECT id, nom, prix FROM prestations");

// Récupérer les produits
$produits = $conn->query("SELECT id, nom, prix FROM services");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Générer un devis</title>
    <!-- Inclusion de jsPDF et autoTable -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>
</head>
<body>

    <form id="devisForm">
        <label for="firstName">Prénom :</label>
        <input type="text" id="firstName" name="firstName" required><br>

        <label for="lastName">Nom :</label>
        <input type="text" id="lastName" name="lastName" required><br>

        <label for="email">Adresse mail :</label>
        <input type="email" id="email" name="email" required><br>
        <p id="emailError" style="color:red; display:none;">Veuillez entrer une adresse email valide.</p>

        <label for="phone">Numéro de téléphone :</label>
        <input type="tel" id="phone" name="phone" required><br>
        <p id="phoneError" style="color:red; display:none;">Veuillez entrer un numéro de téléphone valide.</p>

        <label for="address">Adresse postale :</label>
        <input type="text" id="address" name="address" required><br>
        <p id="addressError" style="color:red; display:none;">Veuillez entrer une adresse postale valide.</p>

        <label for="service">Choisissez un service :</label>
        <select id="service" name="service">
            <option value="">Aucun service</option>
            <?php while ($service = $services->fetch_assoc()) { ?>
                <option value="<?php echo $service['id']; ?>" data-prix="<?php echo $service['prix']; ?>">
                    <?php echo $service['nom']; ?>
                </option>
            <?php } ?>
        </select><br>


        <label for="serviceQuantity">Quantité :</label>
        <input type="number" id="serviceQuantity" name="serviceQuantity" min="1" value="1"><br>

        <label for="product">Choisissez un produit :</label>
        <select id="product" name="product">
            <option value="">Aucun produit</option>
            <?php while ($produit = $produits->fetch_assoc()) { ?>
                <option value="<?php echo $produit['id']; ?>" data-prix="<?php echo $produit['prix']; ?>">
                    <?php echo $produit['nom']; ?>
                </option>
            <?php } ?>
        </select><br>


        <label for="productQuantity">Quantité :</label>
        <input type="number" id="productQuantity" name="productQuantity" min="1" value="1"><br>

        <button type="button" onclick="validateAndGeneratePDF()">Visualiser le PDF</button>
    </form>
    
</body>



    
    <script>
        
        async function validateAndGeneratePDF() {
            const firstName = document.getElementById('firstName').value;
            const lastName = document.getElementById('lastName').value;
            const address = document.getElementById('address').value;
            const phone = document.getElementById('phone').value;
            const email = document.getElementById('email').value;

            const telRegex = /^0[1-68]([-. ]?[0-9]{2}){4}$/;
            const emailRegex = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/i;

            let isValid = true;

            async function fetchBadDomains() {
                try {
                    const response = await fetch('https://dl.red.flag.domains/red.flag.domains.txt');
                    const text = await response.text();
                    return text.split('\n').map(domain => domain.trim()).filter(domain => domain);
                } catch (error) {
                    console.error("Erreur lors du chargement des domaines malveillants", error);
                    return [];
                }
            }

            // Vérification de l'e-mail
            if (!emailRegex.test(email)) {
                document.getElementById('emailError').style.display = 'block';
                console.log("Email invalide");
                isValid = false;
            } else {
                console.log("malveillant");
                document.getElementById('emailError').style.display = 'none';

                // Vérification des noms de domaine malveillants
                const domain = email.split('@')[1];
                const badDomains = await fetchBadDomains();
                const cleanedBadDomains = badDomains.map(d => d.trim().toLowerCase());

                console.log("Domaine extrait :", domain); // Affiche le domaine extrait
                console.log("Domaines malveillants :", cleanedBadDomains); // Affiche les domaines malveillants

                if (cleanedBadDomains.includes(domain.toLowerCase())) {
                    alert("L'adresse email provient d'un domaine malveillant.");
                    isValid = false;
                }
            }

            // Validation du prénom
            if (!firstName) {
                alert("Veuillez entrer un prénom.");
                isValid = false;
            }

            // Validation du nom
            if (!lastName) {
                alert("Veuillez entrer un nom.");
                isValid = false;
            }

            // Validation de l'adresse postale
            if (!address) {
                alert("Veuillez entrer une adresse postale.");
                isValid = false;
            }

            // Validation du numéro de téléphone
            if (!telRegex.test(phone)) {
                document.getElementById('phoneError').style.display = 'block';
                console.log("Numéro invalide");
                isValid = false;
            } else {
                document.getElementById('phoneError').style.display = 'none';
            }

            // Si toutes les validations sont correctes, générer le PDF
            if (isValid) {
                generatePDF();
            }
        }


        function generatePDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        // Ajouter le logo
        const imgData = 'logo.jpeg';  // Remplacer par le chemin correct vers l'image
        doc.addImage(imgData, 'JPEG', 150, 10, 30, 30);

        // Informations de l'entreprise
        const companyName = "Lavage Auto";
        const companyAddress = "Rue de l’Automobile, 97111, MORNE A L'EAU";
        const companyPhone = "Téléphone : +590 690 12 34 56";
        const companyEmail = "Email : contact@lavageauto.fr";
        const companySIRET = "SIRET : 123 456 789 00000";
        
        // Ajout des informations de l'entreprise au début du devis
        doc.setFontSize(12);
        doc.text(companyName, 120, 110);
        doc.text(companyAddress, 120, 120);
        doc.text(companyPhone, 120, 130);
        doc.text(companyEmail, 120, 140);
        doc.text(companySIRET, 120, 150);

        // Récupération des informations du client
        const firstName = document.getElementById('firstName').value;
        const lastName = document.getElementById('lastName').value;
        const email = document.getElementById('email').value;
        const phone = document.getElementById('phone').value;
        const address = document.getElementById('address').value;

        const serviceSelect = document.getElementById('service');
        const serviceText = serviceSelect.options[serviceSelect.selectedIndex].text;
        const servicePrice = parseFloat(serviceSelect.options[serviceSelect.selectedIndex].getAttribute('data-prix')) || 0;
        const serviceQuantity = parseInt(document.getElementById('serviceQuantity').value) || 1;

        const productSelect = document.getElementById('product');
        const productText = productSelect.options[productSelect.selectedIndex].text;
        const productPrice = parseFloat(productSelect.options[productSelect.selectedIndex].getAttribute('data-prix')) || 0;
        const productQuantity = parseInt(document.getElementById('productQuantity').value) || 1;

        // Vérification des valeurs et conversion pour éviter NaN
        if (isNaN(servicePrice) || isNaN(productPrice)) {
            alert("Erreur lors de la récupération des prix.");
            return;
        }

        // Calculer le total HT
        const serviceTotal = servicePrice * serviceQuantity;
        const productTotal = productPrice * productQuantity;
        const totalHT = serviceTotal + productTotal;
        const tvaAmount = totalHT * 0.20;  // TVA à 20%
        const totalTTC = totalHT + tvaAmount;

        // Ajout du titre du devis
        doc.setFontSize(18);
        doc.text("Votre devis", 105, 60, { align: "center" });

        // Informations du client
        doc.setFontSize(12);
        doc.text(`Nom : ${firstName} ${lastName}`, 20, 80);
        doc.text(`Email : ${email}`, 20, 90);
        doc.text(`Téléphone : ${phone}`, 20, 100);
        doc.text(`Adresse : ${address}`, 20, 110);

        // Tableau avec service et produit
        const tableBody = [];
        if (serviceText !== "Aucun service") {
            tableBody.push([serviceText, serviceQuantity, servicePrice.toFixed(2), serviceTotal.toFixed(2)]);
        }
        if (productText !== "Aucun produit") {
            tableBody.push([productText, productQuantity, productPrice.toFixed(2), productTotal.toFixed(2)]);
        }

        if (tableBody.length > 0) {
            doc.autoTable({
                startY: 160,
                head: [['Désignation', 'Quantité', 'Prix Unitaire (EUR)', 'Total (EUR)']],
                body: tableBody,
            });
            doc.autoTable({
                startY: doc.lastAutoTable.finalY + 10,
                head: [['Total HT', 'TVA (20%)', 'Total TTC']],
                body: [[totalHT.toFixed(2), tvaAmount.toFixed(2), totalTTC.toFixed(2)]],
            });
        } else {
            doc.text("Aucun service ou produit sélectionné.", 20, 160);
        }

        // Générer le PDF
        const pdfBlob = doc.output('blob');
        const pdfWindow = window.open("");
        pdfWindow.document.write("<iframe width='100%' height='100%' src='" + URL.createObjectURL(pdfBlob) + "'></iframe>");
    }


    </script>
</html>
<?php include 'footer.php'; ?>
