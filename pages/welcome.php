<?php 
session_start();
if (!isset($_SESSION['status'])) {
    $_SESSION['status'] = '';
}
if (!isset($_SESSION['id'])) {
    $_SESSION['id'] = '';
    $_SESSION['name'] = '';
    $_SESSION['email'] = '';
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
<main class="welcome-container">
    <section id="welcome-section">
        <form action="logout.php" method="POST" class="welcomeForm">
            <div class="msgBox"><?php echo $_SESSION['status'] ?></div>
            <div class="welcomeBox">
                <p class="welcomeText">Welcome <span style="color:green;"><?php echo $_SESSION['name']?></span> You have successfully logged in</p>
                <p class="welcomeSubText">Click the button below to Logout.</p>
            </div>
            <input type="submit" name="logout" class="submitBtn" value="Logout">
        </form>
    </section>
</main>
</body>
</html>