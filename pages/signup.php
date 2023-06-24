<?php 
session_start();
if (!isset($_SESSION['status'])) {
    $_SESSION['status'] = '';
}
if (!isset($_SESSION['user_email'])) {
    $_SESSION['user_email'] = '';
}
if (!isset($_SESSION['user_name'])) {
    $_SESSION['user_name'] = '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<main class="auth-container">
    <section id="sg-section">
        <form action="inscription.php" method="POST" class="authForm">
            <div class="msgBox"><?php echo $_SESSION['status'] ?></div>
            <label>Nom et Prénom</label>
            <input type="text" class="nameInput auth" name="nameInput" value="<?php echo $_SESSION['user_name']?>">
            <label>Email</label>
            <input type="email" name="emailInput" class="emailInput auth" value="<?php echo $_SESSION['user_email']?>">
            <label>Mot de passe</label>
            <input type="password" name="pwdInput" class="pwdInput auth">
            <label>Vérifier le mot de passe</label>
            <input type="password" name="pwdVerify" class="pwdVerify auth">
            <input type="submit" name="submit" class="submitBtn" value="Inscrivez">
            <p>Vous-avez déja un compte? <span><a href="login.php">Connectez-vous</a></span></p>
        </form>
    </section>
</main>
</body>
</html>