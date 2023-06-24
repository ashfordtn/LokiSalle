<?php 
$pageTitle = 'Loki Produit - Gestion des produits';
$breadcrumb = [
    ['name' => '→ Acceuil', 'path' => '/index.php'],
    ['name' => '→ Admin', 'path' => '#'],
    ['name' => '→ Gestion des produits', 'path' => '/pages/gestion_produits.php']
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

if (!isset($_SESSION['gpMessage'])) {
    $_SESSION['gpMessage'] = '';
}

if (!isset($_GET['result'])) {
    $_SESSION['gpMessage'] = '';
}


$gpFields = ['gpprSelect', 'prDateA', 'prDateD', 'prPrix','prCPSelect'];

if (isset($_POST['gp-p-ajouter'])) {
    $emptyField = false;
    foreach ($gpFields as $field) {
        $$field = $_POST[$field];
        if ($field !== 'prCPSelect' && empty($$field)) {
            $emptyField = true;
        }
        $_SESSION[$field] = $$field;
        $prCPSelect = $_POST['prCPSelect'];
    }

    if ($_POST['prDateA'] > $_POST['prDateD']) {
        $_SESSION['gpMessage'] = '<span style="color:red">Date d\'arrivée doit être inférieure à la date de départ</span>';;
        header('Location: '.$_SERVER['PHP_SELF'].'?result=fail');
        exit();
    }
    if ($emptyField) {
        $_SESSION['gpMessage'] = '<span style="color:red;">Veuillez remplir tous les champs</span>';
        header('Location: '.$_SERVER['PHP_SELF'].'?result=fail');
        exit();
    } else {
        $tableName = 'produit';
        $query = 'INSERT INTO '.$tableName.' (id_salle, date_arrive, date_depart, prix, id_promo, etat) VALUES ('.$gpprSelect.', "'.$prDateA.'", "'.$prDateD.'", '.$prPrix.', '.$prCPSelect.', 0)';
        $addProduit = mysqli_query($link, $query);
        if ($addProduit) {
            $_SESSION['gpMessage'] = '<span style="color:green;">Produit ajouté avec succès</span>';
            header('Location: '.$_SERVER['PHP_SELF'].'?result=success');
            exit();
        } else {
            $_SESSION['gpMessage'] = '<span style="color:red;">Une erreur s\'est produite. Veuillez réessayer</span>';
            header('Location: '.$_SERVER['PHP_SELF'].'?result=fail');
            exit();
        }
    }
}

if (isset($_GET['id']) && $_GET['action'] === 'delete') {
    $tableName = 'produit';
    $query = 'DELETE FROM '.$tableName.' WHERE id_produit = '.$_GET['id'];
    $deleteProduit = mysqli_query($link, $query);
    if ($deleteProduit) {
        $_SESSION['gpMessage'] = '<span style="color:green;">Produit supprimé avec succès</span>';
        header('Location: '.$_SERVER['PHP_SELF'].'?result=success');
        exit();
    } else {
        $_SESSION['gpMessage'] = '<span style="color:red;">Une erreur s\'est produite. Veuillez réessayer</span>';
        header('Location: '.$_SERVER['PHP_SELF'].'?result=fail');
        exit();
    }
}

?>

<section id="gp-container">
    <div class="gp-addProduit">
        <h3 class="gp-title">Ajouter une produit</h3>
        <div class="msgBox">
            <?php echo $_SESSION['gpMessage']; ?>
        </div>
        <form action="#" method="post" class="gp-form" enctype="multipart/form-data">
            <label for="gpprSelect">Choisir une salle parmi les salles existantes :</label>
            <select name="gpprSelect" id="gpprSelect" class="gp-pr-select">
                <?php
                    $tableName = 'salle'; 
                    $query = 'SELECT * FROM '.$tableName;
                    $getSalles = mysqli_query($link, $query);
                    if (mysqli_num_rows($getSalles) > 0 ) {
                        while ($row = mysqli_fetch_assoc($getSalles)) {
                            echo '<option value="'.$row['id_salle'].'">'.$row['id_salle'].' - '.$row['pays'].' - '.$row['ville'].' - '.$row['adresse'].' - '.$row['titre'].' - '.$row['capacite'].' - '.$row['categorie'].'</option>';
                        } 
                    } else {
                        echo '<option value="0">Aucune salles trouvée</option>';
                    }
                ?>
            </select>
            <div class="gp-form-group">
                <div class="gp-form-group-item box">
                    <label for="prDateA">Date d'arrivée: </label>
                    <input type="date" name="prDateA" class="gp-input" id="prDateA" required>
                </div>
                <div class="gp-form-group-item box">
                    <label for="prDateD">Date de départ: </label>
                    <input type="date" name="prDateD" class="gp-input" id="prDateD" required>
                </div>
                <div class="gp-form-group-item box">
                    <label for="prPrix">Prix: </label>
                    <input type="number" name="prPrix" class="gp-input" id="prPrix" required>
                </div>
                <div class="gp-form-group-item">
                    <label for="prCP">Attribution remise parmi les codes promo existant :</label>
                    <select name="prCPSelect" id="prCPSelect" class="gp-cp-select">
                    <?php
                        $tableName = 'promotion';
                        $query = 'SELECT * FROM '.$tableName;
                        $getCodesPromo = mysqli_query($link, $query);
                        if (mysqli_num_rows($getCodesPromo) > 0) {
                            while ($row = mysqli_fetch_assoc($getCodesPromo)) {
                                echo '<option value="'.$row['id_promo'].'">'.$row['id_promo'].' - '.$row['code_promo'].' - '.$row['reduction'].'%</option>';
                            }
                        } else {
                            echo '<option value="0" selected>Aucune promo codes</option>';
                        }
                    ?>
                </select>
                </div>
            </div>
            <input type="submit" name="gp-p-ajouter" class="gp-addBtn" id="gp-p-ajouter" value="Ajouter un produit">
        </form>
    </div>
    <div class="gp-viewProduit">
        <h3 class="gp-title">Consulter les produits</h3>
        <?php
            $tableName = 'produit'; 
            $prQuery = 'SELECT p.*, s.titre FROM '.$tableName.' p INNER JOIN salle s ON p.id_salle = s.id_salle';
            $getProduits = mysqli_query($link, $prQuery);
            echo '<table class="gp-table" border="1">';
            echo '<tr>';
            echo '<th>#ID</th>';
            echo '<th>Salle</th>';
            echo '<th>Date Arrivé</th>';
            echo '<th>Date Depart</th>';
            echo '<th>Prix</th>';
            echo '<th>Etat</th>';
            echo '<th>Actions</th>';
            echo '</tr>';
            if (mysqli_num_rows($getProduits) > 0 ) {
                while ($row = mysqli_fetch_assoc($getProduits)) {
                    echo '<tr>';
                    echo '<td>'.$row['id_produit'].'</td>';
                    echo '<td>'.$row['titre'].'</td>';
                    echo '<td>'.$row['date_arrive'].'</td>';
                    echo '<td>'.$row['date_depart'].'</td>';
                    echo '<td>'.$row['prix'].' Euros</td>';
                    echo '<td>'.($row['etat'] == 0 ? "Libre" : "Reservé").'</td>';
                    echo '<td class="gp-actions">';
                    echo '<a href="manage_produit.php?id='.$row['id_produit'].'&action=view" class="gp-view"><img src="../images/view.png" class="gp-a-icon"></a>';
                    echo '<a href="manage_produit.php?id='.$row['id_produit'].'&action=edit" class="gp-edit"><img src="../images/edit.png" class="gp-a-icon"></a>';
                    echo '<a href="#" class="gp-delete" onclick="confirmDelete( '. $row['id_produit'] .')"><img src="../images/delete.png" class="gp-a-icon"></a>';
                    echo '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="6"><p class="gp-noProduit">Aucune produits trouvée</td></tr></p>';
            }
            echo '</table>';

        ?>
    </div>
</section>
<script>

function confirmDelete(id) {
    if (confirm("Voulez-vous vraiment supprimer ce produit ?")) {
        // If the user confirms, redirect to the delete action with the ID
        window.location.href = "gestion_produits.php?id=" + id + "&action=delete";
    }
}
</script>

<?php include_once('./templates/footer.php'); ?>