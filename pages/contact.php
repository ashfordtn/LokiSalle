<?php 
$pageTitle = 'Loki Salle - Contact';
$breadcrumb = [
    ['name' => '→ Acceuil', 'path' => '/index.php'],
    ['name' => '→ Admin', 'path' => '#'],
    ['name' => '→ Contact', 'path' => '/pages/contact.php']
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
        <h3 class="newsletter-title">Contactez nous</h3>
        <form action="#" method="post" class="newsletter-form">
            <div class="newsletter-form-group">
                <label for="news-sujet">Sujet:</label>
                <input type="text" name="news-sujet" id="news-sujet">
            </div>
            <div class="newsletter-form-group">
                <label for="news-msg">Message:</label>
                <textarea name="news-msg" id="news-msg" cols="30" rows="10"></textarea>
            </div>
            <input type="submit" name="envoiNewsletter" value="Envoyer">
        </form>
    </div>
</main>

<?php include_once('./templates/footer.php'); ?>