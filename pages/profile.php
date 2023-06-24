<?php

require_once('../config/config.php');

if (isset($_GET['action']) && $_GET['action'] === 'edit') {
    $pageTitle = 'Loki Salle - Modifier Profile';
    $breadcrumb = [
        ['name' => '→ Acceuil', 'path' => '/index.php'],
        ['name' => '→ Profile', 'path' => '/pages/profile.php'],
        ['name' => '→ Modifier', 'path' => '/pages/profile.php?action=edit']
    ];
    $editing = true;
} else {
    $pageTitle = 'Loki Salle - Profile';
    $breadcrumb = [
        ['name' => '→ Acceuil', 'path' => '/index.php'],
        ['name' => '→ Profile', 'path' => '/pages/profile.php']
    ];
    $editing = false;
}
?>

<?php include_once('./templates/header.php'); ?>

<?php

    if (!isset($_SESSION['loggedIn'])) {
        header('Location: ../index.php');
        exit();
    }

if (!isset($_SESSION['profile_editMessage'])) {
    $_SESSION['profile_editMessage'] = '';
}

if (isset($_SESSION['profile_editMessage']) && (!isset($_GET['result']))) {
    $_SESSION['profile_editMessage'] = '';
}

if (isset($_POST['editProfile']) && $_GET['action'] == 'edit') {
    $newName = $_POST['name'];
    $newEmail = $_POST['email'];
    $newPays = $_POST['pays'];
    $newAddresse = $_POST['addresse'];
    $newPhoto = $_FILES['newAvatar'];
    $oldPassword = $_POST['oldPassword'];
    $newPassword = $_POST['newPassword'];
    $newPasswordCon = $_POST['newPasswordConfirm'];
    $id = $_SESSION['user_id'];


    $pwdQuery = "SELECT password FROM users WHERE id_membre = ".$id;
    $getPwd = mysqli_query($link, $pwdQuery);
    $pwd = mysqli_fetch_assoc($getPwd)['password'];

    if ($oldPassword != $pwd) {
        $_SESSION['profile_editMessage'] = '<span style="color:red;">Mot de passe incorrect</span>';
        header('Location: ' . $_SERVER['PHP_SELF'] . '?action=edit&result=fail');
        mysqli_free_result($pwd);
        exit();
    } 

    if (!empty($newPassword) && !empty($newPasswordCon)) {
        if ($newPassword != $newPasswordCon) {
            $_SESSION['profile_editMessage'] = '<span style="color:red;">Les mots de passe ne correspondent pas</span>';
            header('Location: ' . $_SERVER['PHP_SELF'] . '?action=edit&result=fail');
            exit();
        } else {
            $query = "UPDATE users SET password = '$newPassword' WHERE id_membre = $id";
            $pwdChange = mysqli_query($link, $query);
        }   
    } elseif (!empty($newPassword) || !empty($newPasswordCon)) {
        // Handle the case where only one of the password fields is set
        $_SESSION['profile_editMessage'] = '<span style="color:red;">Veuillez remplir les deux champs de mot de passe.</span>';
        header('Location: ' . $_SERVER['PHP_SELF'] . '?action=edit&result=fail');
        exit();
    }
    
    if (!empty($newPhoto['name'])) {
        $oldFilePath = '../images/uploads/profile/' . $_SESSION['user_photo'];
        if (file_exists($oldFilePath)) {
            unlink($oldFilePath);
        }
        $newPhotoName = uniqid('', true) . '.' . pathinfo($newPhoto['name'], PATHINFO_EXTENSION);
        $targetDirectory = '../images/uploads/profile/';
        $targetFile = $targetDirectory . $newPhotoName;
        if (move_uploaded_file($newPhoto['tmp_name'], $targetFile)) {
            $query = "UPDATE users SET name = '$newName', email = '$newEmail', pays = '$newPays', addresse = '$newAddresse', photo = '$newPhotoName' WHERE id_membre = $id";
        } else {
            $_SESSION['profile_editMessage'] = '<span style="color:red;">Une erreur s\'est produite lors de la mise à jour du profil. Veuillez réessayer.</span>';
            header('Location: ' . $_SERVER['PHP_SELF'] . '?action=edit&result=fail');
            exit();
        }
    } else {
        $query = "UPDATE users SET name = '$newName', email = '$newEmail', pays = '$newPays', addresse = '$newAddresse' WHERE id_membre = $id";
    }

    $updateProfile = mysqli_query($link, $query);

    if ($updateProfile) {
        $_SESSION['profile_editMessage'] = '<span style="color:green;">Profile modifiée avec succès</span>';
        $_SESSION['user_name'] = $newName;
        $_SESSION['user_email'] = $newEmail;
        $_SESSION['user_pays'] = $newPays;
        $_SESSION['user_addresse'] = $newAddresse;
        if (!empty($newPhoto['name'])) {
            $_SESSION['user_photo'] = $newPhotoName;
        }
        header('Location: ' . $_SERVER['PHP_SELF'] . '?result=success');
        exit();
    } else {
        $_SESSION['profile_editMessage'] = '<span style="color:red;">Une erreur s\'est produite lors de la mise à jour du profil. Veuillez réessayer.</span>';
        header('Location: ' . $_SERVER['PHP_SELF'] . '?action=edit&result=fail');
        exit();
    }
}





?>

<div class="profile_msgBox">
    <?php echo $_SESSION['profile_editMessage']; ?>
</div>
<main id="profile-container">
    <div class="profile-information">
        <h3 class="profile-title">Voici vos Informations</h3>
        <?php if (!$editing) { ?>
            <div class="profile-avatar-container">
                <?php if ($_SESSION['user_photo'] === 'avatar_default.jpg') {?>
                    <img src="<?php echo $baseUrl; ?>/images/uploads/<?php echo $_SESSION['user_photo'];?>" alt="" class="profile-avatar-img">
                <?php } else { ?>
                    <img src="<?php echo $baseUrl; ?>/images/uploads/profile/<?php echo $_SESSION['user_photo'];?>" alt="" class="profile-avatar-img">
                <?php } ?>
            </div>
            <div class="profile-info-details">
                <p class="profile-info-name"><b>Nom et Prénom :</b> <?php echo $_SESSION['user_name']; ?></p>
                <p class="profile-info-email"><b>Email :</b> <?php echo $_SESSION['user_email']; ?></p>
                <p class="profile-info-pays"><b>Pays :</b> <?php echo $_SESSION['user_pays']; ?></p>
                <p class="profile-info-addresse"><b>Addresse :</b> <?php echo $_SESSION['user_addresse']; ?></p>
            </div>
            <a href="<?php echo $baseUrl; ?>/pages/profile.php?action=edit" class="profile-edit-btn">Modifier</a>

            <?php } else { ?>
            <form action="#" method="post" class="profile-info-form" enctype="multipart/form-data">
                <div class="profile-avatar-container">
                <?php if ($_SESSION['user_photo'] === 'avatar_default.jpg') {?>
                    <img src="<?php echo $baseUrl; ?>/images/uploads/<?php echo $_SESSION['user_photo'];?>" alt="" class="profile-avatar-img">
                <?php } else { ?>
                    <img src="<?php echo $baseUrl; ?>/images/uploads/profile/<?php echo $_SESSION['user_photo'];?>" alt="" class="profile-avatar-img">
                <?php } ?>
                    <input type="file" name="newAvatar" id="newAvatar">
                </div>
                <div class="profile-info-details">
                    <div class="profile-info-form-group">
                        <label for="name"><b>Nom et Prénom</b></label>
                        <input type="text" name="name" id="name" value="<?php echo $_SESSION['user_name']; ?>">
                    </div>
                    <div class="profile-info-form-group">
                        <label for="email"><b>Email</b></label>
                        <input type="email" name="email" id="email" value="<?php echo $_SESSION['user_email']; ?>">
                    </div>
                    <div class="profile-info-form-group">
                        <label for="pays"><b>Pays</b></label>
                        <input type="text" name="pays" id="pays" value="<?php echo $_SESSION['user_pays']; ?>">
                    </div>
                    <div class="profile-info-form-group">
                        <label for="addresse"><b>Addresse</b></label>
                        <input type="text" name="addresse" id="addresse" value="<?php echo $_SESSION['user_addresse']; ?>">
                    </div>
                    <div class="profile-info-form-group">
                        <label for="oldPassword"><b>Ancien mot de passe</b></label>
                        <input type="password" name="oldPassword" id="oldPassword" required>
                    </div>
                    <div class="profile-info-form-group">
                        <label for="newPassword"><b>Mot de passe</b></label>
                        <input type="password" name="newPassword" id="newPassword">
                    </div>
                    <div class="profile-info-form-group">
                        <label for="newPasswordConfirm"><b>Confirmer le mot de passe</b></label>
                        <input type="password" name="newPasswordConfirm" id="newPasswordConfirm">
                    </div>
                    <div class="profile-info-btn-group">
                        <a href="profile.php" class="profile-info-return-btn">Retour</a>
                        <button type="submit" name="editProfile" class="profile-info-form-btn">Modifier</button>
                    </div>
                </div>
            </form>
        <?php } ?>
    </div>
    <div class="profile-orders">
        <h3 class="profile-title">Vos Commandes</h3>
        <div class="profile-orders-container">
            <table class="profile-orders-table" border="1">
                <tr>
                    <th>Numéro de Commande</th>
                    <th>Date de Commande</th>
                    <th>Montant</th>
                </tr>
                    <?php 
                        $user_id = $_SESSION['user_id'];
                        $sql = "SELECT * FROM commandes WHERE id_membre = '$user_id'";
                        $result = mysqli_query($link, $sql);
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $commande_id = $row['id_commande'];
                                $commande_date = $row['date_commande'];
                                $commande_montant = $row['montant'];
                                echo "<tr>
                                        <td>$commande_id</td>
                                        <td>$commande_date</td>
                                        <td>$commande_montant €</td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr>
                                    <td colspan='3'>Vous n'avez pas encore de commandes</td>
                                </tr>";
                        }
                    ?>
            </table>
    </div>
</main>

<?php include_once('./templates/footer.php'); ?>