<?php 
$pageTitle = 'Loki Salle - S\'inscrire à newsletter';
$breadcrumb = [
    ['name' => '→ Acceuil', 'path' => '/index.php'],
    ['name' => '→ S\'inscrire à newsletter', 'path' => '/pages/inscrit-newsletter.php']
];
require_once('../config/config.php');

include_once('./templates/header.php');

if (!isset($_SESSION['in-l-Message'])) {
    $_SESSION['in-l-Message'] = '';
}

if (!isset($_GET['result'])) {
    $_SESSION['in-l-Message'] = '';
}

?>



<?php 
if (isset($_POST['inscritNewsLetter'])) {
    $email = $_POST['news-email'];
    $checkQuery = "SELECT id_membre FROM users WHERE email = '$email'";
    $checkResult = mysqli_query($link, $checkQuery);
    
    if (mysqli_num_rows($checkResult) > 0) {
        $idData = mysqli_fetch_assoc($checkResult);
        $id = $idData['id_membre'];
        
        $secondCheck = "SELECT id_membre FROM newsletter WHERE id_membre = $id";
        $secondCheckResult = mysqli_query($link, $secondCheck);
        
        if (mysqli_num_rows($secondCheckResult) > 0) {
            $_SESSION['in-l-Message'] = '<span style="color:red">Vous êtes déjà inscrit</span>';
            header('Location: ' . $_SERVER['PHP_SELF'] . '?result=fail');
            exit();
        } else {
            $query = "INSERT INTO newsletter (id_membre) VALUES ($id)";
            $result = mysqli_query($link, $query);
            
            if ($result) {
                $_SESSION['in-l-Message'] = '<span style="color:green">Vous êtes inscrit avec succès</span>';
                header('Location: ' . $_SERVER['PHP_SELF'] . '?result=success');
                exit();
            }
        }
    } else {
        $_SESSION['in-l-Message'] = '<span style="color:red">L\'adresse e-mail n\'est pas valide</span>';
        header('Location: ' . $_SERVER['PHP_SELF'] . '?result=fail');
        exit();
    }
}

?>
<div class="news-msgBox">
    <?php 
        if (isset($_SESSION['in-l-Message'])) {
            echo $_SESSION['in-l-Message'];
        }
    ?>
</div>
<main id="news-container">
<div class="news-sendLetter">
<h3 class="newsletter-title">Inscrit a la newsletter</h3>
<form action="#" method="post" class="newsletter-form">
    <div class="newsletter-form-group">
        <label for="news-email">Email:</label>
        <input type="text" name="news-email" id="news-email">
    </div>
    <input type="submit" name="inscritNewsLetter" value="Inscrit">
</form>
</div>
</main>
<?php include_once('./templates/footer.php'); ?>