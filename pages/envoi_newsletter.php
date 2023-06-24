<?php 
$pageTitle = 'Loki Salle - Envoi Newsletter';
$breadcrumb = [
    ['name' => '→ Acceuil', 'path' => '/index.php'],
    ['name' => '→ Admin', 'path' => '#'],
    ['name' => '→ Envoi Newsletter', 'path' => '/pages/envoi_newsletter.php']
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

if (!isset($_SESSION['news-Message'])) {
    $_SESSION['news-Message'] = '';
}

if (!isset($_GET['result'])) {
    $_SESSION['news-Message'] = '';
}

?>

<div class="news-msgBox">
            <?php
            if (isset($_SESSION['news-Message'])) {
                echo $_SESSION['news-Message'];
            }
            ?>
        </div>
<main id="news-container">

    <div class="news-sendLetter">
        <?php 
            $query = "SELECT COUNT(*) AS total FROM newsletter";
            $result = mysqli_query($link, $query);
            $count = mysqli_fetch_assoc($result);
        ?>
        <h3 class="newsletter-title">Envoyer un Newsletter</h3>
        <h4 class="newsletter-count">Nombre d'abonné à la newsletter : <?php echo $count['total'] ?> </h4>
        <form action="#" method="post" class="newsletter-form">
            <div class="newsletter-form-group">
                <label for="news-exp">Expéditeur:</label>
                <input type="text" name="news-exp" id="news-exp">
            </div>
            <div class="newsletter-form-group">
                <label for="news-sujet">Sujet:</label>
                <input type="text" name="news-sujet" id="news-sujet">
            </div>
            <div class="newsletter-form-group">
                <label for="news-msg">Message:</label>
                <textarea name="news-msg" id="news-msg" cols="30" rows="10"></textarea>
            </div>
            <input type="submit" name="envoiNewsletter" value="Envoi de la newsletter au membres">
        </form>
    </div>
</main>

<?php include_once('./templates/footer.php'); ?>