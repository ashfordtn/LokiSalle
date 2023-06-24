<?php 
$pageTitle = 'Loki Salle - Acceuil';
$breadcrumb = [['name' => '→ Acceuil', 'path' => '/index.php']];
require_once('./config/config.php');
?>

<?php 
    include_once('pages/templates/header.php'); 
    if (!isset($_SESSION['role'])) {
        $_SESSION['role'] = '0';
    }
    if (!isset($_SESSION['baseUrl'])) {
        $_SESSION['baseUrl'] = 'http://localhost/lokisalle';
    }
    $baseUrl = $_SESSION['baseUrl'];
?>
    <main class="content-container">
        <div class="content-area">
            <p class="content">
            <span>Bienvenue chez Loky Salle, votre destination de choix pour la location de salles de fêtes extraordinaires.</span>

            <span>Chez Loky Salle, nous comprenons l'importance de créer des moments mémorables pour vos événements spéciaux. Que ce soit pour un mariage, un anniversaire, une réception d'entreprise ou toute autre occasion spéciale, nous mettons à votre disposition des salles spacieuses et élégamment décorées qui répondent à tous vos besoins.</span>

            <span>Nos salles sont conçues pour offrir le cadre idéal pour célébrer vos moments précieux. Dotées d'une infrastructure moderne, d'un éclairage ambiant et d'un aménagement flexible, elles peuvent être personnalisées selon vos préférences pour créer une atmosphère unique et magique.</span>

            <span>Notre équipe dévouée est là pour vous accompagner à chaque étape de la planification de votre événement. De la sélection de la salle à la coordination des services supplémentaires tels que la restauration, la décoration et le divertissement, nous veillons à ce que chaque détail soit pris en compte pour créer une expérience inoubliable.</span>

            <span>En choisissant Loky Salle, vous bénéficiez également d'un emplacement pratique, d'un stationnement facile d'accès et d'un personnel chaleureux et professionnel qui est là pour répondre à tous vos besoins.</span>

            <span>Découvrez dès maintenant notre sélection de salles et réservez celle qui correspond à vos besoins. Nous sommes impatients de vous aider à réaliser l'événement de vos rêves.</span>

            <span>N'hésitez pas à nous contacter pour plus d'informations ou pour planifier une visite de nos installations. L'équipe Loky Salle est là pour faire de votre événement un moment extraordinaire.</span>

            <span>Nous vous remercions de votre confiance et sommes ravis de vous accueillir chez Loky Salle !</span>
            <br>
            Cordialement,<br>
            L'équipe Loki Salle
            </p>
        </div>
        <div class="of-sidebar">
            <h2 class="of-title">Nos dérniers offres</h2>

                <?php 
                    $query = 'SELECT p.*, s.photo, s.titre, s.ville, s.capacite FROM produit p INNER JOIN salle s ON p.id_salle = s.id_salle WHERE p.etat = 0 ORDER BY date_added DESC';
                    $result = mysqli_query($link, $query);
                    $rowCount = 0;

                    if (mysqli_num_rows($result) > 0) {
                        for ($i = 0; $i < mysqli_num_rows($result); $i++) {
                            $row = mysqli_fetch_assoc($result);
                            echo '<div class="offreBox">';
                            echo '<img src="images/uploads/'.$row['photo'].'" class="offreBox-img" alt="">';
                            echo '<div class="offreBox-info">';
                            echo '<h4 class="of-titre">'.$row['titre'].' - <span>'.$row['ville'].'</span></h4>';
                            echo '<h5 class="of-date">Du '.date("d M Y",strtotime($row['date_arrive'])).' au '.date("d M Y",strtotime($row['date_depart'])).'</span></h5>';
                            echo '<p class="of-price">'.$row['prix'].' Euros * pour '.$row['capacite'].' personnes</p>';
                            echo '<a href="pages/reservation_details.php?id='.$row['id_produit'].'" class="of-link">→ Voir l\'offre</a>';
                            echo ($loggedIn ? '<a href="#" class="of-link" onclick="addToCart('.$row['id_produit'].')">→ Ajouter au Panier</a>' : '<a href="'.$baseUrl.'/pages/login.php" class="of-link">→ Connectez-vous pour l\'ajouter au panier') . '</a>';
                            echo '</div>';
                            echo '</div>';
                            $rowCount++;
                            if ($rowCount >= 3) {
                                break;
                            }
                        } 
                    } else {
                        echo '<p>Aucune offres trouvées</p>';
                    }
                ?>
            </div>
    </main>
<?php include_once('pages/templates/footer.php'); ?>            

