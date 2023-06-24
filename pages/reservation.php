<?php 
$pageTitle = 'Loki Salle - Réservations';
$breadcrumb = [
    ['name' => '→ Acceuil', 'path' => '/index.php'],
    ['name' => '→ Reservations', 'path' => '/pages/reservation.php']
];
require_once('../config/config.php');
?>

<?php include_once('./templates/header.php'); ?>

<div class="res-ptitle">
        <h2 class="res-title">Nos dérniers offres</h2>
</div>
<section id="res-container">
    <?php 
        $resultsPerPage = 9;
        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
        $startIndex = ($currentPage - 1) * $resultsPerPage;

        $totalRowsQuery = "SELECT COUNT(*) AS total FROM produit WHERE etat = 0";
        $totalRowsResult = mysqli_query($link, $totalRowsQuery);
        $totalRows = mysqli_fetch_assoc($totalRowsResult)['total'];

        $totalPages = ceil($totalRows / $resultsPerPage);

        $query = "SELECT p.*, s.photo, s.titre, s.ville, s.capacite FROM produit p INNER JOIN salle s ON p.id_salle = s.id_salle WHERE p.etat = 0 LIMIT $startIndex, $resultsPerPage";
        $result = mysqli_query($link, $query);

        if (mysqli_num_rows($result) > 0) {
            for ($i = 0; $i < mysqli_num_rows($result); $i++) {
                $row = mysqli_fetch_assoc($result);
                echo '<div class="resOffre">';
                echo '<img src="../images/uploads/'.$row['photo'].'" class="resOffre-img" alt="">';
                echo '<div class="resOffre-info">';
                echo '<h4 class="res-titre">'.$row['titre'].' - <span>'.$row['ville'].'</span></h4>';
                echo '<h5 class="res-date">Du '.date("d M Y",strtotime($row['date_arrive'])).' au '.date("d M Y",strtotime($row['date_depart'])).'</span></h5>';
                echo '<p class="res-price">'.$row['prix'].' Euros * pour '.$row['capacite'].' personnes</p>';
                echo '<a href="reservation_details.php?id='.$row['id_produit'].'" class="res-link">→ Voir l\'offre</a>';
                echo ($loggedIn ? '<a href="#" class="of-link" onclick="addToCart('.$row['id_produit'].')">→ Ajouter au Panier</a>' : '<a href="#" class="of-link">→ Connectez-vous pour l\'ajouter au panier') . '</a>';
                echo '</div>';
                echo '</div>';
            }
        }
    ?>
</section>
<?php 
    echo '<div class="pagination">';
    if ($currentPage > 1) {
        echo '<a href="?page=' . ($currentPage - 1) . '">Précédent</a>';
    }
    for ($i = 1; $i <= $totalPages; $i++) {
        if ($i == $currentPage) {
            echo '<span class="active">' . $i . '</span>';
        } else {
            echo '<a href="?page=' . $i . '">' . $i . '</a>';
        }
    }
    if ($currentPage < $totalPages) {
        echo '<a href="?page=' . ($currentPage + 1) . '">Suivant</a>';
    }
    echo '</div>';
?>

<?php include_once('./templates/footer.php'); ?>