<?php
$pageTitle = 'Loki Salle - Gestion des commandes';
$breadcrumb = [
    ['name' => '→ Acceuil', 'path' => '/index.php'],
    ['name' => '→ Admin', 'path' => '#'],
    ['name' => '→ Gestion des commandes', 'path' => '/pages/gestion_commandes.php']
];
require_once('../config/config.php');


?>

<?php include_once('./templates/header.php'); ?>

<?php

if (!isset($_SESSION['loggedIn'])) {
    header('Location: ../index.php');
    exit();
}

if (isset($_SESSION['loggedIn']) && $_SESSION['role'] !== '2') {
    header('Location: ../index.php');
    exit();
}




?>

<main id="gs-m-container">
    <table class="gs-commandes-table" border="1">
        <tr>
            <th>ID Commande</th>
            <th>ID Membre</th>
            <th>Montant</th>
        </tr>
        <?php 
            $tableName = 'commandes';
            $query = 'SELECT * FROM '.$tableName;
            $getCommandes = mysqli_query($link, $query);
            $chiffreAffaire = 0;
            while ($commande = mysqli_fetch_assoc($getCommandes)) {
                echo '<tr>';
                echo '<td>'.$commande['id_commande'].'</td>';
                echo '<td>'.$commande['id_membre'].'</td>';
                echo '<td>'.$commande['montant'].'€</td>';
                echo '</tr>';
                $chiffreAffaire =+ $commande['montant'];
            }
        ?>
    </table>
    <p class="gs-cm-chiffre">Le Chiffre d'affaires (CA) de notre société est de : <b><?php echo $chiffreAffaire; ?>€</b></p>
</main>

<?php include_once('./templates/footer.php'); ?>
