<?php 
$pageTitle = 'Loki Salle - Gestion des salles';
$breadcrumb = [
    ['name' => '→ Acceuil', 'path' => '/index.php'],
    ['name' => '→ Admin', 'path' => '#'],
    ['name' => '→ Gestion des salles', 'path' => '/pages/gestion_salles.php']
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

if (!isset($_SESSION['gsMessage'])) {
    $_SESSION['gsMessage'] = '';
}

if (!isset($_GET['result'])) {
    $_SESSION['gsMessage'] = '';
}

if (isset($_GET['id']) && $_GET['action'] === 'delete') {
    $id = $_GET['id'];
    $tableName = 'salle';
    $salleQuery = 'DELETE FROM '.$tableName.' WHERE id_salle = '.$id;
    $produitQuery = 'SELECT * FROM produit WHERE id_salle = '.$id;
    $avisQuery = 'SELECT * FROM avis WHERE id_salle = '.$id;
    $produitCheck = mysqli_query($link, $produitQuery);
    if (mysqli_num_rows($produitCheck) > 0) {
        $delQuery = 'DELETE FROM produit WHERE id_salle = '.$id;
        mysqli_query($link, $delQuery);
    }
    $avisCheck = mysqli_query($link, $avisQuery);
    if (mysqli_num_rows($avisCheck) > 0 ) {
        $delQuery = 'DELETE FROM avis WHERE id_salle ='.$id;
        mysqli_query($link, $delQuery);
    }
    $deleteSalle = mysqli_query($link, $salleQuery);
    if ($deleteSalle) {
        $_SESSION['gsMessage'] = '<span style="color:green;">Salle deleted successfuly</span>';
        header('Location: '.$_SERVER['PHP_SELF'].'?result=success');
        exit();
    } else {
        $_SESSION['gsMessage'] = '<span style="color:red;">Something went wrong, please try again</span>';
        header('Location: '.$_SERVER['PHP_SELF'].'?result=fail');
        exit();
    }
}


$gsFields = ['gsTitre','gsDesc', 'gsAdd', 'gsPays','gsVille', 'gsPhoto', 'gsCap', 'gsCat'];
foreach ($gsFields as $field) {
    if (!isset($_SESSION[$field])) {
        $_SESSION[$field] = '';
    }
}

if (isset($_POST['gs-s-ajouter'])) {
    $emptyField = false;
    foreach ($gsFields as $field) {
        $$field = $_POST[$field];
        if ($field == 'gsPhoto' && empty($_FILES['gsPhoto']['name'])) {
            $emptyField = true;
        }
        if ($field !== 'gsPhoto' && empty($$field)) {
            $emptyField = true;
        }
        if ($field == 'gsCat' && $$field == '0') {
            $emptyField = true;
        }


        $_SESSION[$field] = $$field;
    }

    if ($emptyField || $_SESSION['gsCat'] == '0') {
        $_SESSION['gsMessage'] = '<span style="color:red;">Veuillez remplir tous les champs</span>';
        header('Location: '.$_SERVER['PHP_SELF'].'?result=fail');
        exit();
    } else {
        $fileName = $fileName = isset($_FILES['gsPhoto']['name']) ? $_FILES['gsPhoto']['name'] : '';
        $fileParts = explode('.', $fileName);
        $fileExt = strtolower(end($fileParts));
        $fileNewName = uniqid('', true).'.'.$fileExt;
        $targetDirectory = '../images/uploads/';
        $targetFile = $targetDirectory . $fileNewName;
        if (!move_uploaded_file($_FILES['gsPhoto']['tmp_name'], $targetFile)) {
            $_SESSION['gsMessage'] = '<span style="color:red;">Une erreur s\'est produite. Veuillez réessayer</span>';
            header('Location: '.$_SERVER['PHP_SELF'].'?result=fail');

            exit();
        }
        $_SESSION['gsMessage'] = '<span style="color:green;">Creating Salle</span>';
        $tableName = 'salle';
        $query = 'INSERT INTO '.$tableName.' VALUES (NULL, "'.$gsTitre.'", "'.$gsDesc.'", "'.$gsAdd.'", "'.$gsPays.'", "'.$gsVille.'", "'.$fileNewName.'", "'.$gsCap.'", "'.$gsCat.'",NOW())';
        $createSalle = mysqli_query($link, $query);
        if ($createSalle) {
            $_SESSION['gsMessage'] = '<span style="color:green;">Salle créée avec succès</span>';
            header('Location: '.$_SERVER['PHP_SELF'].'?result=success');
            $_SESSION['gsTitre'] = '';
            $_SESSION['gsDesc'] = '';
            $_SESSION['gsAdd'] = '';
            $_SESSION['gsPays'] = '';
            $_SESSION['gsVille'] = '';
            $_SESSION['gsPhoto'] = '';
            $_SESSION['gsCap'] = '';
            $_SESSION['gsCat'] = '0';
            exit();
        } else {
            $_SESSION['gsMessage'] = '<span style="color:red;">Une erreur s\'est produite. Veuillez réessayer</span>';
            header('Location: '.$_SERVER['PHP_SELF'].'?result=fail');
            exit();
        }
        header('Location: '.$_SERVER['PHP_SELF'].'?result=success');
        exit();
    }
}


?>
<section id="gs-container">
    <div class="gs-addSalle">
        <h3 class="gs-title">Ajouter une salle </h3>
        <div class="msgBox">
            <?php echo $_SESSION['gsMessage']; ?>
        </div>
        <form action="#" method="post" class="gs-form" enctype="multipart/form-data">
            <label for="gsTitre">Titre</label>
            <input type="text" id="gsTitre" name="gsTitre" value="<?php echo $_SESSION['gsTitre'] ?>">
            <label for="gsDesc">Description</label>
            <input type="text" id="gsDesc" name="gsDesc" value="<?php echo $_SESSION['gsDesc'] ?>">
            <label for="gsAdd">Address</label>
            <input type="text" id="gsAdd" name="gsAdd" value="<?php echo $_SESSION['gsAdd'] ?>">
            <div class="gs-form-group">
                <div class="gs-form-group-item">
                    <label for="gsPays">Pays</label>
                    <input type="text" name="gsPays" id="gsPays" value="<?php echo $_SESSION['gsPays'] ?>">
                </div>
                <div class="gs-form-group-item">
                    <label for="gsVille">Ville</label>
                    <input type="text" name="gsVille" id="gsVille" value="<?php echo $_SESSION['gsVille'] ?>">
                </div>
            </div>
            <div class="gs-form-group">
                <div class="gs-form-group-item">
                    <label for="gsCap">Capacité</label>
                    <input type="text" name="gsCap" id="gsCap" value="<?php echo $_SESSION['gsCap'] ?>">
                </div>
                <div class="gs-form-group-item">
                    <label for="gsCat">Categorie</label>
                    <select id="gsCat" name="gsCat">
                        <option value="0" selected>Sélectionner</option>
                        <option value="reunion">Réunion</option>
                        <option value="fete">Fete</option>
                        <option value="anniversaire">Anniversaire</option>
                    <select>
                </div>
            </div>
            <div class="gs-form-group">
                <div class="gs-form-group-item">
                    <label for="gsPhoto">Photo</label>
                    <input type="file" name="gsPhoto" id="gsPhoto">
                </div>
            </div>
            <input type="submit" name="gs-s-ajouter" class="s-addBtn" id="gs-s-ajouter" value="Ajouter">
        </form>
    </div>
    <div class="gs-viewSalle">
        <h3 class="gs-title">Consulter les salles</h3>
        <?php
            $tableName = 'salle'; 
            $query = 'SELECT * FROM '.$tableName;
            $getSalles = mysqli_query($link, $query);
            echo '<table class="gs-table" border="1">';
            echo '<tr>';
            echo '<th>#ID</th>';
            echo '<th>Titre</th>';
            echo '<th>Ville</th>';
            echo '<th>Capacité</th>';
            echo '<th>Catégorie</th>';
            echo '<th>Actions</th>';
            echo '</tr>';
            if (mysqli_num_rows($getSalles) > 0 ) {
                while ($row = mysqli_fetch_assoc($getSalles)) {
                    echo '<tr>';
                    echo '<td>'.$row['id_salle'].'</td>';
                    echo '<td>'.$row['titre'].'</td>';
                    echo '<td>'.$row['ville'].'</td>';
                    echo '<td>'.$row['capacite'].'</td>';
                    echo '<td>'.$row['categorie'].'</td>';
                    echo '<td class="gs-actions">';
                    echo '<a href="manage_salle.php?id='.$row['id_salle'].'&action=view" class="gs-view"><img src="../images/view.png" class="gs-a-icon"></a>';
                    echo '<a href="manage_salle.php?id='.$row['id_salle'].'&action=edit" class="gs-edit"><img src="../images/edit.png" class="gs-a-icon"></a>';
                    echo '<a href="#" class="gs-delete" onclick="confirmDelete( '. $row['id_salle'] .')"><img src="../images/delete.png" class="gs-a-icon"></a>';
                    echo '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="6"><p class="gs-noSalle">Aucune salles trouvée</td></tr></p>';
            }
            echo '</table>';

        ?>
    </div>
</section>
<script>

function confirmDelete(id) {
    if (confirm("Voulez-vous vraiment supprimer cette salle ?")) {
        window.location.href = "gestion_salles.php?id=" + id + "&action=delete";
    }
}
</script>

<?php include_once('./templates/footer.php'); ?>
