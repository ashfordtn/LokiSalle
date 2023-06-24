<?php
$pageTitle = 'Loki Salle - Gestion des avis';
$breadcrumb = [
    ['name' => '→ Acceuil', 'path' => '/index.php'],
    ['name' => '→ Admin', 'path' => '#'],
    ['name' => '→ Gestion des avis', 'path' => '/pages/gestion_avis.php']
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

if (!isset($_SESSION['gs-av-Message'])) {
    $_SESSION['gs-av-Message'] = '';
}

if (isset($_SESSION['gs-av-Message']) && (!isset($_GET['result']))) {
    $_SESSION['gs-av-Message'] = '';
}

if (isset($_GET['id']) && $_GET['action'] == 'delete') {
    $id = $_GET['id'];
    $query = 'DELETE FROM avis WHERE id_avis = ' . $id;
    $deleteUser = mysqli_query($link, $query);
    if ($deleteUser) {
        $_SESSION['gs-av-Message'] = '<span style="color:green;">Avis supprimé avec succès</span>';
        header('Location: ' . $_SERVER['PHP_SELF'].'?result=success');
    } else {
        $_SESSION['gs-av-Message'] = '<span style="color:red;">Erreur lors de la suppression du avis</span>';
        header('Location: ' . $_SERVER['PHP_SELF'].'?result=success');
    }
} 

?>

<main id="gs-av-container">
    <div class="gs-av-msgBox">
        <?php
        if (isset($_SESSION['gs-av-Message'])) {
            echo $_SESSION['gs-av-Message'];
        }
        ?>
    </div>
    <table class="gs-avis-table" border="1">
        <tr>
            <th>ID_Avis</th>
            <th>ID_Membre</th>
            <th>ID_Salle</th>
            <th>Commentaire</th>
            <th>Note</th>
            <th>Date</th>
            <th>Supprimer</th>
        </tr>
        <?php 
            $tableName = 'avis';
            $query = 'SELECT * FROM '.$tableName;
            $getAvis = mysqli_query($link, $query);
            if (mysqli_num_rows($getAvis) > 0 ) {
                while ($avis = mysqli_fetch_assoc($getAvis)) {
                    echo '<tr>';
                    echo '<td>'.$avis['id_avis'].'</td>';
                    echo '<td>'.$avis['id_membre'].'</td>';
                    echo '<td>'.$avis['id_salle'].'</td>';
                    echo '<td>'.$avis['commentaire'].'</td>';
                    echo '<td>'.$avis['note'].'</td>';
                    echo '<td>'.$avis['date'].'</td>';
                    echo '<td><a href="'.$_SERVER['PHP_SELF'].'?id='.$avis['id_avis'].'&action=delete">Supprimer</a></td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr>';
                echo '<td colspan="7">Aucun avis trouvé</td>';
                echo '</tr>';
            }

        ?>
    </table>
</main>

<?php include_once('./templates/footer.php'); ?>