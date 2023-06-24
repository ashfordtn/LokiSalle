<?php
$pageTitle = 'Loki Salle - Réservations Details';
$breadcrumb = [
    ['name' => '→ Acceuil', 'path' => '/index.php'],
    ['name' => '→ Profile', 'path' => '/pages/profile.php'],
    ['name' => '→ Panier', 'path' => '/pages/panier.php']
];
require_once('../config/config.php');


?>

<?php include_once('./templates/header.php'); ?>

<?php

if (!isset($_SESSION['loggedIn'])) {
    header('Location: ../index.php');
    exit();
}

if (isset($_SESSION['panier'])) {
    $panier = $_SESSION['panier'];
} else {
    $panier = [];
}

if (!isset($_SESSION['panier_msg'])) {
    $_SESSION['panier_msg'] = '';
}

if (!isset($_SESSION['panier_total'])) {
    $_SESSION['panier_total'] = 0;
}

if (!isset($_GET['result']) && isset($_SESSION['panier_msg'])) {
    $_SESSION['panier_msg'] = '';
}

if (isset($_GET['action']) && $_GET['action'] == 'commander') {
    if (count($panier) > 0) {
        $query = 'INSERT INTO commandes (id_membre, montant, date_commande) VALUES (' . $_SESSION['user_id'] . ', ' . $_SESSION['panier_total'] . ', NOW())';
        $result = mysqli_query($link, $query);
        if ($result) {
            $commandeId = mysqli_insert_id($link);
            foreach ($panier as $productId) {
                $query = 'INSERT INTO details_commande (id_commande, id_produit) VALUES (' . $commandeId . ', ' . $productId . ')';
                $result = mysqli_query($link, $query);
            }
            $_SESSION['panier_msg'] = '<span style="color:green;">Votre commande a été enregistrée avec succès</span>';
            $_SESSION['panier'] = [];
            $_SESSION['panier_total'] = 0;
            header('Location: '. $baseUrl .'/pages/panier.php?result=success');
            exit();
        } else {
            $_SESSION['panier_msg'] = '<span style="color:red;">Une erreur est survenue lors de l\'enregistrement de votre commande</span>';
            header('Location: '. $baseUrl .'/pages/panier.php?result=error');
            exit();
        }
    } else {
        $_SESSION['panier_msg'] = '<span style="color:red;">Votre panier est vide</span>';
        header('Location:  '. $baseUrl .'/pages/panier.php?result=error');
        exit();
    }
}


?>

<main id="panier-container">
    <div class="p_msgBox">
        <?php echo $_SESSION['panier_msg']; ?>
    </div>
    <table class="panier-table" border="1">
        <tr>
            <th class="bg" colspan="10">Votre panier</th>
        </tr>
        <tr>
            <th>Produit</th>
            <th>Salle</th>
            <th>Photo</th>
            <th>Ville</th>
            <th>Capacité</th>
            <th>Date Arrivée</th>
            <th>Date Départ</th>
            <th>Retirer</th>
            <th>Prix HT</th>
            <th>TVA</th>
        </tr>
        <?php
        $totalTTC = 0;
        if (count($panier) > 0) {
            foreach ($panier as $productId) {
                // Fetch product details from the database based on the product ID
                $query = 'SELECT p.*, s.titre, s.photo, s.ville, s.capacite FROM produit p INNER JOIN salle s ON p.id_salle = s.id_salle WHERE p.id_produit = ' . $productId;
                $result = mysqli_query($link, $query);
                $row = mysqli_fetch_assoc($result);
            
                // Display the table row for the product
                echo '<tr>';
                echo '<td>' . $row['id_produit'] . '</td>';
                echo '<td>' . $row['titre'] . '</td>';
                echo '<td><img class="panier-img" src="../images/uploads/' . $row['photo'] . '" alt=""></td>';
                echo '<td>' . $row['ville'] . '</td>';
                echo '<td>' . $row['capacite'] . '</td>';
                echo '<td>' . $row['date_arrive'] . '</td>';
                echo '<td>' . $row['date_depart'] . '</td>';
                echo '<td><a href="#" onclick="removeFromCart('.$row['id_produit'].')">X</a></td>';
                echo '<td>' . $row['prix'] . '€</td>';
                echo '<td>19.6%</td>';
                echo '</tr>';
                $totalTTC+= $row['prix'];
            }
            } else {
                echo '<tr><td colspan="10">Votre panier est vide</td></tr>';
            }
            $_SESSION['panier_total'] = $totalTTC + ((19.6/100) * $totalTTC);
        ?>
        <tr>
            <th class="bg" colspan="8">Prix Total TTC :</th>
            <th class="bg" colspan="2"><?php echo $_SESSION['panier_total'] ?> €</th>
        </tr>
    </table>
    <div class="panierCon">
        <div class="panierCon-group">
            <span>
                <label for="p_condition">J'accepte les conditions générales de vente</label>
                <input type="checkbox" name="p_condition" id="p_condition">
            </span>
            <span>
                <label for="panier_CodePromo">Utiliser un code promo: </label>
                <input type="text" name="panier_CodePromo" id="panier_CodePromo">
            </span>
            <a class="viderPanier" href="#" onclick="viderPanier()">Vider mon panier</a>
        </div>
        <div class="panierCon-group">
            <a class="payerPanier" href="panier.php?action=commander">Commander</a>
        </div>
    </div>

    <section id="panier_cheque">
        <p>Tous nos articles sont calculés avec la taux de TVA à 20%</p>
        <p>Réglement Par Chéque uniquement</p>
        <p>Nous attendons votre réglement par chéque à l'address suivante</p>
        <p>Notre Boutique - Cité Ibn Kholdoun Rue 6623 - Tunis - Tunisie</p>
    </section>
</main>


<?php include_once('./templates/footer.php'); ?>