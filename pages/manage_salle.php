<?php 
require_once('../config/config.php');

if (isset($_GET['id'])) {

    $query = 'SELECT * FROM salle WHERE id_salle = '.$_GET['id'];
    $getSalle = mysqli_query($link, $query);
    $salle = mysqli_fetch_assoc($getSalle);
    if ($_GET['action'] === 'view') {
        $pageTitle = 'Loki Salle - Consulter une Salle';
        $editing = false;
        $breadcrumb = [
            ['name' => '→ Acceuil', 'path' => '/index.php'],
            ['name' => '→ Admin', 'path' => '#'],
            ['name' => '→ Gestion des salles', 'path' => '/pages/gestion_salles.php'],
            ['name' => '→ '.$salle['titre'].'', 'path' => '/pages/manage_salle.php?id='.$_GET['id'].'&action=view']
        ];
    } else if ($_GET['action'] === 'edit') {
        $pageTitle = 'Loki Salle - Modifier une Salle';
        $editing = true;
        $breadcrumb = [
            ['name' => '→ Acceuil', 'path' => '/index.php'],
            ['name' => '→ Admin', 'path' => '#'],
            ['name' => '→ Gestion des salles', 'path' => '/pages/gestion_salles.php'],
            ['name' => '→ '.$salle['titre'].'', 'path' => '/pages/manage_salle.php?id='.$_GET['id'].'&action=view'],
            ['name' => '→ Modifier', 'path' => '/pages/manage_salle.php?id='.$_GET['id'].'&action=edit']
        ];
    }
}

include_once('./templates/header.php');
if (!isset($_SESSION['loggedIn'])) {
    header('Location: ../index.php');
    exit();
}
if (isset($_SESSION['loggedIn']) && $_SESSION['role'] !== '2') {
    header('Location: ../index.php');
    exit();
}
if (!isset($_SESSION['gs_viewMessage'])) {
    $_SESSION['gs_viewMessage'] = '';
}
if (!isset($_GET['result'])) {
    $_SESSION['gs_viewMessage'] = '';
}

if (isset($_POST['edit_salle'])) {
    $newTitre = $_POST['titre'];
    $newDes = $_POST['description'];
    $newAdd = $_POST['adresse'];
    $newPays = $_POST['pays'];
    $newVille = $_POST['ville'];
    $newCap = $_POST['capacite'];
    $newCat = $_POST['categorie'];
    $newPhoto = $_FILES['photo'];
    $id = $_GET['id'];

    // Check if a new photo is selected
    if (!empty($newPhoto['name'])) {
        // Delete the old photo
        $oldFilePath = '../images/uploads/' . $salle['photo'];
        if (file_exists($oldFilePath)) {
            unlink($oldFilePath);
        }

        // Upload the new photo
        $newPhotoName = uniqid('', true) . '.' . pathinfo($newPhoto['name'], PATHINFO_EXTENSION);
        $targetDirectory = '../images/uploads/';
        $targetFile = $targetDirectory . $newPhotoName;
        if (move_uploaded_file($newPhoto['tmp_name'], $targetFile)) {
            // Update the query with the new photo
            $query = 'UPDATE salle SET titre = "' . $newTitre . '", description = "' . $newDes . '", adresse = "' . $newAdd . '", pays = "' . $newPays . '", ville = "' . $newVille . '", capacite = "' . $newCap . '", categorie = "' . $newCat . '", photo = "' . $newPhotoName . '" WHERE id_salle = ' . $id;
        } else {
            $_SESSION['gs_viewMessage'] = '<span style="color:red;">Something went wrong while uploading the new photo. Please try again.</span>';
            header('Location: ' . $_SERVER['PHP_SELF'] . '?id=' . $id . '&action=edit&result=fail');
            exit();
        }
    } else {
        // Update the query without changing the photo
        $query = 'UPDATE salle SET titre = "' . $newTitre . '", description = "' . $newDes . '", adresse = "' . $newAdd . '", pays = "' . $newPays . '", ville = "' . $newVille . '", capacite = "' . $newCap . '", categorie = "' . $newCat . '" WHERE id_salle = ' . $id;
    }

    $updateSalle = mysqli_query($link, $query);
    if ($updateSalle) {
        $_SESSION['gs_viewMessage'] = '<span style="color:green;">Salle modifiée avec succès</span>';
        header('Location: ' . $_SERVER['PHP_SELF'] . '?id=' . $id . '&action=edit&result=success');
        exit();
    } else {
        $_SESSION['gs_viewMessage'] = '<span style="color:red;">Une erreur s\'est produite lors de la mise à jour du salle. Veuillez réessayer..</span>';
        header('Location: ' . $_SERVER['PHP_SELF'] . '?id=' . $id . '&action=edit&result=fail');
        exit();
    }
}
?>

    <section id="view-container">
        <div class="view-details">
        <div class="msgBox">
            <?php echo $_SESSION['gs_viewMessage']; ?>
        </div>
        <?php if (!$editing) { ?>
            <div class="details-container">
                <div class="details-column">
                    <h3>Titre</h3>
                    <p><?php echo $salle['titre']; ?></p>
                    <h3>Description</h3>
                    <p><?php echo $salle['description']; ?></p>
                    <h3>Adresse</h3>
                    <p><?php echo $salle['adresse']; ?></p>
                    <h3>Pays</h3>
                    <p><?php echo $salle['pays']; ?></p>

                </div>
                <div class="details-column">
                    <h3>Ville</h3>
                    <p><?php echo $salle['ville']; ?></p>
                    <h3>Capacité</h3>
                    <p><?php echo $salle['capacite']; ?></p>
                    <h3>Catégorie</h3>
                    <p><?php echo $salle['categorie']; ?></p>
                </div>
            </div>
            <a href="gestion_salles.php" class="view-Btn">Retour</a>
            <?php } else { ?> 
            <form action="#" method="POST" class="details-container edit-form" enctype="multipart/form-data">
                <div class="details-column">
                    <h3>Titre</h3>
                    <input type="text" name="titre" value="<?php echo $salle['titre']; ?>">
                    <h3>Description</h3>
                    <input type="text" name="description" value="<?php echo $salle['description']; ?>">
                    <h3>Adresse</h3>
                    <input type="text" name="adresse" value="<?php echo $salle['adresse']; ?>">
                    <h3>Pays</h3>
                    <input type="text" name="pays" value="<?php echo $salle['pays']; ?>">
                </div>
                <div class="details-column">
                    <h3>Ville</h3>
                    <input type="text" name="ville" value="<?php echo $salle['ville']; ?>">
                    <h3>Capacité</h3>
                    <input type="number" name="capacite" value="<?php echo $salle['capacite']; ?>">
                    <h3>Catégorie</h3>
                    <select name="categorie">
                        <option value="reunion" <?php if ($salle['categorie'] === 'reunion') { echo 'selected'; } ?>>Réunion</option>
                        <option value="fete" <?php if ($salle['categorie'] === 'fete') { echo 'selected'; } ?>>Fete</option>
                        <option value="anniversaire" <?php if ($salle['categorie'] === 'anniversaire') { echo 'selected'; } ?>>Anniversaire</option>
                    </select>
                    <h3>Photo</h3>
                    <input type="file" name="photo">

                </div>
                <div class="submit-container">
                    <input type="submit" value="Modifier" name="edit_salle" class="view-Btn">
                </div>
            </form>
            <?php } ?>
        </div>
        <div class="view-image">
            <img class="view-img" src="../images/uploads/<?php echo $salle['photo']; ?>" alt="<?php echo $salle['titre']; ?>">
        </div>
    </section>

<?php include_once('./templates/footer.php');?>