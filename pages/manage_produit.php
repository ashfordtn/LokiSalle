<?php 
require_once('../config/config.php');

if (isset($_GET['id'])) {

    $query = 'SELECT p.*, s.titre, s.photo, pr.code_promo FROM produit p INNER JOIN salle s ON p.id_salle = s.id_salle LEFT JOIN promotion pr ON p.id_promo = pr.id_promo WHERE p.id_produit = '.$_GET['id'];
    $getProduit = mysqli_query($link, $query);
    $produit = mysqli_fetch_assoc($getProduit);
    if ($_GET['action'] === 'view') {
        $pageTitle = 'Loki Produit - Consulter une Produit';
        $editing = false;
        $breadcrumb = [
            ['name' => '→ Acceuil', 'path' => '/index.php'],
            ['name' => '→ Admin', 'path' => '#'],
            ['name' => '→ Gestion des produits', 'path' => '/pages/gestion_produits.php'],
            ['name' => '→ Produit #'.$produit['id_produit'].'', 'path' => '/pages/manage_produit.php?id='.$_GET['id'].'&action=view']
        ];
    } else if ($_GET['action'] === 'edit') {
        $pageTitle = 'Loki Produit - Modifier une Produit';
        $editing = true;
        $breadcrumb = [
            ['name' => '→ Acceuil', 'path' => '/index.php'],
            ['name' => '→ Admin', 'path' => '#'],
            ['name' => '→ Gestion des produits', 'path' => '/pages/gestion_produits.php'],
            ['name' => '→ Produit #'.$produit['id_produit'].'', 'path' => '/pages/manage_produit.php?id='.$_GET['id'].'&action=view'],
            ['name' => '→ Modifier', 'path' => '/pages/manage_produit.php?id='.$_GET['id'].'&action=edit']
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
if (!isset($_SESSION['gp_viewMessage'])) {
    $_SESSION['gp_viewMessage'] = '';
}
if (!isset($_GET['result'])) {
    $_SESSION['gp_viewMessage'] = '';
}

if (isset($_POST['edit_produit'])) {
    $newDateA = $_POST['gpDateA'];
    $newDateD = $_POST['gpDateD'];
    $newPromo = $_POST['gpCodePromo'];
    $newPrix = $_POST['gpPrix'];
    $newEtat = $_POST['gpEtat'];
    $id = $_GET['id'];

    $query = 'UPDATE produit SET date_arrive = "' . $newDateA . '", date_depart = "' . $newDateD . '", id_promo = "' . $newPromo . '", prix = "' . $newPrix . '", etat = "' . $newEtat .'" WHERE id_produit = ' . $id;
    $updateProduit = mysqli_query($link, $query);

    if ($updateProduit) {
        $_SESSION['gp_viewMessage'] = '<span style="color:green;">Produit modifiée avec succès</span>';
        header('Location: ' . $_SERVER['PHP_SELF'] . '?id=' . $id . '&action=edit&result=success');
        exit();
    } else {
        $_SESSION['gp_viewMessage'] = '<span style="color:red;">Une erreur s\'est produite lors de la mise à jour du produit. Veuillez réessayer.</span>';
        header('Location: ' . $_SERVER['PHP_SELF'] . '?id=' . $id . '&action=edit&result=fail');
        exit();
    }
}
?>

    <section id="view-container">
        <div class="view-details">
        <div class="msgBox">
            <?php echo $_SESSION['gp_viewMessage']; ?>
        </div>
        <?php if (!$editing) { ?>
            <div class="details-container">
                <div class="details-column">
                    <h3>Salle</h3>
                    <p><?php echo $produit['titre']; ?></p>
                    <h3>Date Arrivée</h3>
                    <p><?php echo $produit['date_arrive']; ?></p>
                    <h3>Date Depart</h3>
                    <p><?php echo $produit['date_depart']; ?></p>
                </div>
                <div class="details-column">
                    <h3>Prix</h3>
                    <p><?php echo $produit['prix']; ?></p>
                    <h3>Code Promo</h3>
                    <p><?php echo (!empty($produit['code_promo']) ? $produit['id_promo'] :  'No promo code') ?></p>
                    <h3>Etat</h3>
                    <p><?php echo ($produit['etat'] !== 1 ? "Libre" : "Reservé"); ?></p>
                </div>
            </div>
            <a href="gestion_produits.php" class="view-Btn">Retour</a>
            <?php } else { ?> 
            <form action="#" method="POST" class="details-container edit-form" enctype="multipart/form-data">
                <div class="details-column">
                    <h3>Salle</h3>
                    <input type="text" name="titre" value="<?php echo $produit['titre']; ?>" disabled>
                    <h3>Date Arrivée</h3>
                    <input type="date" name="gpDateA" value="<?php echo $produit['date_arrive']; ?>">
                    <h3>Date Depart</h3>
                    <input type="date" name="gpDateD" value="<?php echo $produit['date_depart']; ?>">
                </div>
                <div class="details-column">
                    <h3>Prix</h3>
                    <input type="text" name="gpPrix" value="<?php echo $produit['prix']; ?>">
                    <h3>Code Promo</h3>
                    <select name="gpCodePromo">
                        <option value="0" <?php echo (empty($produit['code_promo']) ? "selected" : ""); ?>>No promo code</option>
                        <?php foreach ($promos as $promo) { ?>
                            <option value="<?php echo $promo['id_promo']; ?>" <?php echo ($produit['id_promo'] === $promo['id_promo'] ? "selected" : ""); ?>><?php echo $promo['code_promo']; ?></option>
                        <?php } ?>
                    </select>
                    <h3>Etat</h3>
                    <select name="gpEtat">
                        <option value="0" <?php echo ($produit['etat'] == 0 ? "selected" : ""); ?>>Libre</option>
                        <option value="1" <?php echo ($produit['etat'] == 1 ? "selected" : ""); ?>>Reservé</option>
                    </select>
                </div>
                <div class="submit-container">
                    <input type="submit" value="Modifier" name="edit_produit" class="view-Btn">
                </div>
            </form>
            <?php } ?>
        </div>
        <div class="view-image">
            <img class="view-img" src="../images/uploads/<?php echo $produit['photo']; ?>" alt="<?php echo $produit['titre']; ?>">
        </div>
    </section>

<?php include_once('./templates/footer.php');?>