<?php
session_start();
$loggedIn = isset($_SESSION['loggedIn']);
$baseUrl = 'http://localhost/lokisalle';
$_SESSION['baseUrl'] = $baseUrl;
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = array();
}

?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="<?php echo $baseUrl; ?>/js/panier.js"></script>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="<?php echo $baseUrl ?>/css/style.css">
<head>
<body>
    <div class="header-logo-box">
<img src="<?php echo $baseUrl ?>/images/ls-logo.png" class="header-logo" alt="Loki Salle">

    </div>
    <header class="ls-header">
        <div class="header-menu">
            <ul class="nav-list">
                <li class="nav-item"><a class="nav-link" href="<?php echo $baseUrl ?>/index.php">Acceuil</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo $baseUrl ?>/pages/reservation.php">Réservation</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo $baseUrl ?>/pages/recherche.php">Recherche</a></li>
                <?php 
                    echo !$loggedIn 
                    ? '<li class="nav-item"><a class="nav-link" href="' . $baseUrl . '/pages/login.php">Se connecter | Inscrivez</a></li>' 
                    : '';
                    if ($loggedIn && $_SESSION['role'] === '2') {
                        include_once('admin-m.php');
                    }
                ?>
            </ul>
        </div>
        <?php if ($loggedIn): ?>

        <div class="header-panier">
        <ul class="nav-list">
                <li class="nav-item panier">
                    <a class="nav-link" href="<?php echo $baseUrl ?>/pages/panier.php">
                        <img class="menu-icon" src="<?php echo $baseUrl ?>/images/panier.png" alt="Panier">
                        <?php
                            $cartItemCount = count($_SESSION['panier']);
                            echo '<span class="cart-item-count">'.$cartItemCount.'</span>';
                        ?>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $baseUrl ?>/pages/profile.php">
                        <img class="menu-icon" src="<?php echo $baseUrl ?>/images/user.png" alt="User">
                    </a>
                        <ul class="dropdown-menu-user">
                            <li><a href="<?php echo $baseUrl ?>/pages/profile.php">Profile</a></li>
                            <li><a href="<?php echo $baseUrl ?>/pages/logout.php">Logout</a></li>
                        </ul>
                </li>   

            </ul>
        </div>
        <?php endif; ?>
    </header>
    <div class="bc-container">
        <?php

            foreach ($breadcrumb as $index => $crumb) {
                echo '<a class="breadcrumb" href="' . $baseUrl . $crumb['path'] . '">' . $crumb['name'] . '</a>';
            }
        ?>
    </div>







