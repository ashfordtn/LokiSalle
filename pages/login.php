<?php 
session_start();

if (!isset($_SESSION['status'])) {
    $_SESSION['status'] = '';
}
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = '';
    $_SESSION['user_name'] = '';
    $_SESSION['user_email'] = '';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Se connecter</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<main class="auth-container">
    <section id="lg-section">
        <form action="auth.php" method="POST" class="authForm">
            <div class="msgBox"><?php echo $_SESSION['status'] ?></div>
            <label>Email</label>
            <input type="email" name="emailInput" class="emailInput auth" value="<?php echo $_SESSION['user_email']?>">
            <label>Mot de passe</label>
            <input type="password" name="pwdInput" class="pwdInput auth">
            <input type="submit" name="login" class="submitBtn" value="Se connecter">
            <p>Vous n'avez pas un compte? <span><a href="signup.php">Inscrivez-vous</a></span></p>
        </form>
    </section>
</main>
</body>
</html>