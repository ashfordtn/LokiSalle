<?php 
require_once('../config/config.php');
session_start();
$_SESSION['status'] = '';
$_SESSION['user_id'] = '';
$_SESSION['user_name'] = '';
$_SESSION['user_email'] = '';
$_SESSION['user_pays'] = '';
$_SESSION['user_addresse'] = '';
$_SESSION['user_photo'] = '';
$_SESSION['role'] = '';
$_SESSION['loggedIn'] = false;

if (isset($_POST['login'])) {
    $email = $_POST['emailInput'];
    $pwd = $_POST['pwdInput'];
    if (empty($email) || empty($pwd)) {
        $_SESSION['status'] = '<p style="color:red">Veuillez remplir tous les champs</p>';
        $_SESSION['user_email'] = $email;
        header('Location: login.php');
        exit();
    }
    $tableName = 'users';
    $query = 'SELECT * FROM '.$tableName.' WHERE email = "' . $email .'"';
    $result = mysqli_query($link, $query);
    if (mysqli_num_rows($result) < 1 ) {
        $_SESSION['status'] = '<p style="color:red">L\'e-mail n\'existe pas</p>';
        $_SESSION['user_email'] = $email;
        header('Location: login.php');
        exit();
    } else {
        $data = mysqli_fetch_assoc($result);
        if ($data['password'] !== $pwd ) {
            $_SESSION['status'] = '<p style="color:red">Mot de passe inccorect</p>';
            $_SESSION['user_email'] = $email;
            header('Location: login.php');
            exit();
        } else {
            $_SESSION['user_id'] = $data['id_membre'];
            $_SESSION['user_name'] = $data['name'];
            $_SESSION['user_email'] = $data['email'];
            $_SESSION['role'] = $data['role'];
            $_SESSION['user_pays'] = $data['pays'];
            $_SESSION['user_addresse'] = $data['addresse'];
            $_SESSION['user_photo'] = $data['photo'];
            $_SESSION['loggedIn'] = true;
            header('Location: '. $_SESSION['baseUrl'] .'/index.php');
            exit();
        }
    }
}
?>