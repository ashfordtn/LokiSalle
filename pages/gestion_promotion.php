<?php 
$pageTitle = 'Loki Salle - Gestion des promotion';
$breadcrumb = [
    ['name' => '→ Acceuil', 'path' => '/index.php'],
    ['name' => '→ Admin', 'path' => '#'],
    ['name' => '→ Gestion des promotion', 'path' => '/pages/promotion.php']
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

if (!isset($_SESSION['gs-pc-Message'])) {
    $_SESSION['gs-pc-Message'] = '';
}

if (!isset($_GET['result'])) {
    $_SESSION['gs-pc-Message'] = '';
}

if (isset($_POST['addPromoCode'])) {
    $codePromo = $_POST['gs-pc-codepromo'];
    $reduction = $_POST['gs-pc-reduction'];
    if (empty($codePromo) || empty($reduction)) {
        $_SESSION['gs-pc-Message'] = '<span style="color:red;">Veuillez remplir tous les champs</span>';
        header('Location: ' . $_SERVER['PHP_SELF'].'?result=fail');
        exit();
    }
    $checkQuery = "SELECT code_promo FROM promotion WHERE code_promo = '$codePromo'";
    $checkCode = mysqli_query($link, $checkQuery);
    if (mysqli_num_rows($checkCode) > 0) {
        $_SESSION['gs-pc-Message'] = '<span style="color:red;">Ce code promo existe déjà</span>';
        header('Location: ' . $_SERVER['PHP_SELF'].'?result=fail');
        exit();
    }
    $query = 'INSERT INTO promotion (code_promo, reduction) VALUES ("' . $codePromo . '", "' . $reduction . '")';
    $addPromoCode = mysqli_query($link, $query);
    if ($addPromoCode) {
        $_SESSION['gs-pc-Message'] = '<span style="color:green;">Code promo ajouté avec succès</span>';
        header('Location: ' . $_SERVER['PHP_SELF'].'?result=success');
    } else {
        $_SESSION['gs-pc-Message'] = '<span style="color:red;">Erreur lors de l\'ajout du code promo</span>';
        header('Location: ' . $_SERVER['PHP_SELF'].'?result=fail');
    }
}

if (isset($_GET['id']) && $_GET['action'] == 'delete') {
    $id = $_GET['id'];
    $query = 'DELETE FROM promotion WHERE id_promo = ' . $id;
    $deletePromoCode = mysqli_query($link, $query);
    if ($deletePromoCode) {
        $_SESSION['gs-pc-Message'] = '<span style="color:green;">Code promo supprimé avec succès</span>';
        header('Location: ' . $_SERVER['PHP_SELF'].'?result=success');
    } else {
        $_SESSION['gs-pc-Message'] = '<span style="color:red;">Erreur lors de la suppression du code promo</span>';
        header('Location: ' . $_SERVER['PHP_SELF'].'?result=fail');
    }
}

?>
<div class="gs-pc-msgBox">
            <?php
            if (isset($_SESSION['gs-pc-Message'])) {
                echo $_SESSION['gs-pc-Message'];
            }
            ?>
        </div>
<main id="gs-pc-container">

    <div class="gs-pc-addCode">

        <h3 class="gs-pc-title">Ajouter un code promo</h3>

        <form action="#" method="post" class="gs-pc-form">
            <label for="gs-pc-codepromo">Code Promo:</label>
            <input type="text" name="gs-pc-codepromo" id="gs-pc-codepromo">
            <label for="gs-pc-reduction">Réduction:</label>
            <input type="text" name="gs-pc-reduction" id="gs-pc-reduction">
            <input type="submit" name="addPromoCode" value="Ajouter">
        </form>
    </div>
    <div class="gs-pc-manageCode">
        <h3 class="gs-pc-title">Gestion des codes promo</h3>
        <table class="gs-pc-table" border="1">
            <tr>
                <th>ID</th>
                <th>Code Promo</th>
                <th>Réduction</th>
                <th>Supprimer</th>
            </tr>
            <?php
            $query = 'SELECT * FROM promotion';
            $result = mysqli_query($link, $query);
            if (mysqli_num_rows($result) > 0 ) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>';
                    echo '<td>' . $row['id_promo'] . '</td>';
                    echo '<td>' . $row['code_promo'] . '</td>';
                    echo '<td>' . $row['reduction'] . '%</td>';
                    echo '<td><a href="'.$_SERVER['PHP_SELF'].'?id=' . $row['id_promo'] . '&action=delete">Supprimer</a></td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr>';
                echo '<td colspan="4">Aucun code promo</td>';
                echo '</tr>';
            }
            ?>
        </table>
    </div>

</main>

<?php include_once('./templates/footer.php'); ?>