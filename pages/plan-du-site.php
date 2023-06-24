<?php 
$pageTitle = 'Loki Salle - Plan du site';
$breadcrumb = [
    ['name' => '→ Acceuil', 'path' => '/index.php'],
    ['name' => '→ Plan du site', 'path' => '/pages/plan-du-site.php']
];
?>

<?php include_once('./templates/header.php'); ?>

<section id="static-wrapper">
    <div class="static-content">
    <h1>Plan du site</h1>
    <ul>
    <ul>
    <li><a href="<?php echo $baseUrl ?>/index.php">Acceuil</a></li>
    <li><a  href="<?php echo $baseUrl ?>/pages/reservation.php">Réservations</a></li>
    <li><a  href="<?php echo $baseUrl ?>/pages/recherche.php">Recherche</a></li>
    <li><a href="<?php echo $baseUrl ?>/pages/contact.php">Contact</a></li>
        <li><a href="<?php echo $baseUrl ?>/pages/mentions-legal.php">Mentions Légales</a></li>
        <li><a href="<?php echo $baseUrl ?>/pages/cgv.php">Conditions Générales de Vente</a></li>
        <li><a href="<?php echo $baseUrl ?>/pages/plan-du-site.php">Plan du site</a></li>
    </ul>

    </div>
</section>

<?php include_once('./templates/footer.php'); ?>