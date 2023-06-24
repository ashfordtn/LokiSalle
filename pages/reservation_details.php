<?php 
require_once('../config/config.php');
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = 'SELECT p.*, s.titre, s.photo, s.adresse, s.capacite, s.categorie, s.description, s.pays, s.ville FROM produit p INNER JOIN salle s ON p.id_salle = s.id_salle WHERE p.id_produit = '.$id;
    $getProduit = mysqli_query($link, $query);
    if ($getProduit) {
        $produit = mysqli_fetch_assoc($getProduit);
    }
}
$pageTitle = 'Loki Salle - Réservations Details';
$breadcrumb = [
    ['name' => '→ Acceuil', 'path' => '/index.php'],
    ['name' => '→ Reservations', 'path' => '/pages/reservation.php'],
    ['name' => '→ Details', 'path' => '/pages/reservation_details.php?id='.$id]
];
?>
<?php include_once('./templates/header.php'); ?>

<?php

if (!isset($_SESSION['res-avis-add-Message'])) {
    $_SESSION['res-avis-add-Message'] = '';
}

if (isset($_SESSION['res-avis-add-Message']) && !isset($_GET['result'])) {
    $_SESSION['res-avis-add-Message'] = '';
}

if (isset($_POST['avisAdd'])) {
    $avisCommentaire = $_POST['avis-com'];
    $avisNote = $_POST['avis-note'];
    if (empty($avisCommentaire)) {
        $_SESSION['res-avis-add-Message'] = '<span style="color:red;">Veuillez saisir un commentaire</span>';
        header('Location: '. $baseUrl .'/pages/reservation_details.php?id='.$id.'&result=fail');
        exit();
    } else if (empty($avisNote)) {
        $_SESSION['res-avis-add-Message'] = '<span style="color:red;">Veuillez saisir une note</span>';
        header('Location: '. $baseUrl .'/pages/reservation_details.php?id='.$id.'&result=fail');
        exit();
    } else {
        $query = 'INSERT INTO avis (id_membre, id_salle, commentaire, note, date) VALUES ('.$_SESSION['user_id'].', '.$produit['id_salle'].', "'.$avisCommentaire.'", '.$avisNote.', NOW())';
        $result = mysqli_query($link, $query);
        if ($result) {
            $_SESSION['res-avis-add-Message'] = '<span style="color:green;">Votre avis a été enregistré avec succès</span>';
            header('Location: '. $baseUrl .'/pages/reservation_details.php?id='.$id.'&result=success');
            exit();
        } else {
            $_SESSION['res-avis-add-Message'] = '<span style="color:red;">Une erreur est survenue lors de l\'enregistrement de votre avis</span>';
            header('Location: '. $baseUrl .'/pages/reservation_details.php?id='.$id.'&result=fail');
            exit();
        }
    }
}

?>


<main id="res-details-container">
    <section id="res-details-header">
        <h4 class="res-details-title"><?php echo $produit['titre'] ?></h4>
        <div class="res-details-preview">
            <img src="../images/uploads/<?php echo $produit['photo'] ?>" alt="" class="res-details-img">
            <div class="res-details-header-info">
                <p class="res-details-desc"><b>Description: </b><?php echo $produit['description'] ?></p>
                <span class="res-details-group">
                    <p class="res-details-capacity"><b>Capacité:</b> <?php echo $produit['capacite'] ?> personnes</p>
                    <p> - </p>
                    <p class="res-details-category"><b>Catégorie:</b> <?php echo $produit['categorie'] ?></p>
                </span>
            </div>
        </div>
    </section>
    <section id="res-details-content">
        <div class="res-details-comp">
            <h4 class="res-details-title">Informations complémentaires</h4>
            <p class="res-details-country"><b>Pays:</b> <?php echo $produit['pays'] ?></p>
            <p class="res-details-city"><b>Ville:</b> <?php echo $produit['ville'] ?></p>
            <p class="res-details-address"><b>Adresse:</b> <?php echo $produit['adresse'] ?></p>
            <p class="res-details-dateA"><b>Date d'arrivée: </b> <?php echo $produit['date_arrive'] ?></p>
            <p class="res-details-dateD"><b>Date de départ: </b> <?php echo $produit['date_depart'] ?></p>
            <p class="res-details-prix"><b>Prix: </b><?php echo $produit['prix'] ?>€<span style="color:red">*</span></p>
            <p class="res-details-taxe"><span style="color:red">*</span>Ce prix est hors taxes</p>
            <p class="res-details-access"><b>Accés :</b></p>
            <img src="../images/res-image.jpg" alt="Reservation details access" class="res-details-access-img">
        </div>
        <div class="res-details-sep"></div>
        <div class="res-details-avis">
            <h4 class="res-details-title">Avis</h4>
            <div class="res-details-overflow-container">
                <?php 
                    $query = "SELECT * FROM avis WHERE id_salle = ".$produit['id_salle'];
                    $result = mysqli_query($link, $query);
                    if ($result) {
                        if (mysqli_num_rows($result) > 0 ) {
                           while($row = mysqli_fetch_assoc($result)) {
                                $membreQuery = "SELECT name FROM users WHERE id_membre = ".$row['id_membre'];
                                $membreResult = mysqli_query($link, $membreQuery);
                                if ($membreResult) {
                                    while ($membreRow = mysqli_fetch_assoc($membreResult)) {
                                        echo '<div class="res-details-avis-box">';
                                        echo '<p class="avis-name-title">'.$membreRow['name'].', le '.$row['date'].' ('.$row['note'].'/10)</p>';
                                        echo '<p class="comment">'.$row['commentaire'].'</p>';
                                        echo '</div>';
                                    }
                                }
                            } 
                        } else {
                            echo '<p>Aucun avis pour cette salle</p>';}
                    }
                ?>
            </div>
            <div class="res-details-avis-add">
                <div class="res-add-avis-msgBox">
                    <?php 
                        if (isset($_SESSION['res-add-avis-Message'])) {
                            echo $_SESSION['res-add-avis-Message'];
                        }
                    ?>
                </div>
                <form action="#" method="post" class="res-details-avis-form">
                    <div class="res-details-avis-form-box">
                        <div class="res-details-avis-form-box-item">
                            <label for="avis-com">Ajouter un avis</label>
                            <textarea name="avis-com" id="avis-com" cols="30" rows="8" placeholder="Votre avis..."></textarea>
                        </div>
                        <div class="res-details-avis-form-box-item">
                            <label for="avis-note">Note</label>
                            <select name="avis-note" id="avis-note">
                                <?php for ($i=1; $i <= 10; $i++) { ?>
                                    <option value="<?php echo $i ?>"><?php echo $i ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <input type="submit" value="Soumettre" name="avisAdd" class="res-details-avis-btn">
                </form>
            </div>
        </div>
    </section>
    <?php echo '<a href="#" class="res-details-panier-btn" onclick="addToCart('.$_GET['id'].')">Ajouter au panier</a>' ?>

    <section id="res-autre-sugg">
    <h4 class="res-details-title">Autre Suggestions</h4>
    <div class="res-details-container">
        <?php 
        $query = "SELECT p.*, s.photo, s.titre, s.ville, s.capacite FROM produit p INNER JOIN salle s ON p.id_salle = s.id_salle WHERE p.etat = 0 AND p.id_produit <> $id ORDER BY RAND() LIMIT 3";
        $result = mysqli_query($link, $query);

        if (mysqli_num_rows($result) > 0) {
            for ($i = 0; $i < mysqli_num_rows($result); $i++) {
                $row = mysqli_fetch_assoc($result);
                echo '<div class="res-details-as">';
                echo '<img src="../images/uploads/'.$row['photo'].'" class="res-details-img" alt="">';
                echo '<div class="res-details-info">';
                echo '<h4 class="res-titre">'.$row['titre'].' - <span>'.$row['ville'].'</span></h4>';
                echo '<h5 class="res-details-date">Du '.date("d M Y",strtotime($row['date_arrive'])).' au '.date("d M Y",strtotime($row['date_depart'])).'</span></h5>';
                echo '<p class="res-price">'.$row['prix'].' Euros * pour '.$row['capacite'].' personnes</p>';
                echo '<a href="reservation_details.php?id='.$row['id_produit'].'" class="res-link">→ Voir l\'offre</a>';
                echo ($loggedIn ? '<a href="#" class="res-link" onclick="addToCart('.$row['id_produit'].')">→ Ajouter au Panier</a>' : '<a href="'.$baseUrl.'/pages/login.php" class="res-link">→ Connectez-vous pour l\'ajouter au panier') . '</a>';
                echo '</div>';
                echo '</div>';
            }
        }
        ?>
        </div>
    </section>
</main>

<?php include_once('./templates/footer.php'); ?>
