<?php 
session_start();
if (isset($_SESSION['loggedIn'])) {
    session_destroy();
    header('Location: ' . $_SESSION['baseUrl'] . '/index.php');
    exit();
}
?>