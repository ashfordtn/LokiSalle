<?php 
$pageTitle = 'Loki Salle - Statistiques';
$breadcrumb = [
    ['name' => '→ Acceuil', 'path' => '/index.php'],
    ['name' => '→ Admin', 'path' => '#'],
    ['name' => '→ Statistiques', 'path' => '/pages/statistiques.php']
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

if (!isset($_SESSION['stat-Message'])) {
    $_SESSION['stat-Message'] = '';
}

if (!isset($_GET['result'])) {
    $_SESSION['stat-Message'] = '';
}

?>

<main id="stat-container">
    <div class="stat-info">
        <p class="top-note">→ Top 5 des salles les mieux notés</p>
        <p class="top-vente">→ Top 5 des salles les plus vendues</p>
        <p class="top-m-qte">→ Top 5 des membres qui achéte le plus (en termes des quantité)</p>
        <p class="top-m-prix">→ Top 5 des membres qui achéte le plus cher (en termes de prix)</p>
    </div>
</main>

<?php include_once('./templates/footer.php'); ?>