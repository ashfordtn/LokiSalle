<?php
$pageTitle = 'Loki Salle - Gestion des membres';
$breadcrumb = [
    ['name' => '→ Acceuil', 'path' => '/index.php'],
    ['name' => '→ Admin', 'path' => '#'],
    ['name' => '→ Gestion des membres', 'path' => '/pages/gestion_membres.php']
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

if (!isset($_SESSION['gs-m-Message'])) {
    $_SESSION['gs-m-Message'] = '';
}

if (isset($_SESSION['gs-m-Message']) && (!isset($_GET['result']))) {
    $_SESSION['gs-m-Message'] = '';
}

if (isset($_GET['id']) && $_GET['action'] == 'delete') {
    $id = $_GET['id'];
    $query = 'DELETE FROM users WHERE id_membre = ' . $id;
    $deleteUser = mysqli_query($link, $query);
    if ($deleteUser) {
        $_SESSION['gs-m-Message'] = '<span style="color:green;">Membre supprimé avec succès</span>';
        header('Location: ' . $_SERVER['PHP_SELF'].'?result=success');
    } else {
        $_SESSION['gs-m-Message'] = '<span style="color:red;">Erreur lors de la suppression du membre</span>';
        header('Location: ' . $_SERVER['PHP_SELF'].'?result=success');
    }
} 

?>

<main id="gs-m-container">
    <div class="gs-m-msgBox">
        <?php
        if (isset($_SESSION['gs-m-Message'])) {
            echo $_SESSION['gs-m-Message'];
        }
        ?>
    </div>
    <table class="gs-membres-table" border="1">
        <tr>
            <th>ID</th>
            <th>Nom et Prenom</th>
            <th>Email</th>
            <th>Mdp</th>
            <th>Pays</th>
            <th>Addresse</th>
            <th>Role</th>
            <th>Supprimer</th>
        </tr>
        <?php 
            $tableName = 'users';
            $query = 'SELECT * FROM '.$tableName;
            $getUsers = mysqli_query($link, $query);
            while ($user = mysqli_fetch_assoc($getUsers)) {
                echo '<tr>';
                echo '<td>'.$user['id_membre'].'</td>';
                echo '<td>'.$user['name'].'</td>';
                echo '<td>'.$user['email'].'</td>';
                echo '<td>'.$user['password'].'</td>';
                echo '<td>'.$user['pays'].'</td>';
                echo '<td>'.$user['addresse'].'</td>';
                echo '<td>'.($user['role'] == 2 ? 'Admin' : 'Utilisateur').'</td>';
                echo '<td><a href="'.$_SERVER['PHP_SELF'].'?id='.$user['id_membre'].'&action=delete">Supprimer</a></td>';
                echo '</tr>';
            }
        ?>
    </table>
</main>

<?php include_once('./templates/footer.php'); ?>