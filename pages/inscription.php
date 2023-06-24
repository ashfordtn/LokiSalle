<?php
require_once('../config/config.php');

ob_start();
session_start();
$_SESSION['user_email'] = '';
$_SESSION['status'] = '';
$_SESSION['user_name'] = '';

if (isset($_POST['submit'])) {
    $_SESSION['status'] = '';
    $fields = ['nameInput','emailInput','pwdInput','pwdVerify'];
    foreach ($fields as $field) {
        $$field = $_POST[$field];
        if (empty($$field)) {
            $_SESSION['status'] = '<span style="color:red;">Please fill out all the blanks</span>';
            $_SESSION['user_email'] = $emailInput;
            $_SESSION['user_name'] = $nameInput;
            header('Location: signup.php');
            exit();
        }
    }
    if ($pwdInput !== $pwdVerify) {
        $_SESSION['status'] = '<span style="color:red;">Passwords do not match</span>';
        $_SESSION['user_email'] = $emailInput;
        $_SESSION['user_name'] = $nameInput;
        header('Location: signup.php');
        exit();
    }
    $tableName = 'users';
    $checkTable = 'SHOW TABLES LIKE "'. $tableName .'"';
    $checkTableResult = mysqli_query($link, $checkTable);
    if (mysqli_num_rows($checkTableResult) < 1) {
        $tableQuery = 'CREATE TABLE '.$tableName.' (
            id_membre INT(6) AUTO_INCREMENT PRIMARY KEY NOT NULL,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL,
            pays VARCHAR(200) DEFAULT "None",
            addresse VARCHAR(200) DEFAULT "None",
            photo VARCHAR(200) DEFAULT "avatar_default.jpg",
            role INT(1) NOT NULL DEFAULT 1);';
        $createTable = mysqli_query($link, $tableQuery);
    }
    $checkEmail = 'SELECT * FROM '.$tableName.' WHERE email = "'.$emailInput.'"';
    if (mysqli_num_rows(mysqli_query($link, $checkEmail)) > 0 ) {
        $_SESSION['status'] = '<span style="color:red">L\'Email existe déjà</span>';
        $_SESSION['user_email'] = $emailInput;
        $_SESSION['user_name'] = $nameInput;
        header('Location: signup.php');
        exit();
    } else {
        $query = 'INSERT INTO '.$tableName.' (id_membre, name, email, password) VALUES (NULL, "'.$nameInput.'", "'.$emailInput.'", "'.$pwdInput.'")';
        try {
            $createUser = mysqli_query($link, $query);
            if ($createUser) {
                $_SESSION['user_email'] = '';
                $_SESSION['user_name'] = '';
                $_SESSION['status'] = '<p style="color:green">Compte créé avec succès, veuillez vous connecter</p>';
                header('Location: signup.php');
                exit();
            } else {
                throw new Exception('Une erreur s\'est produite. Veuillez réessayer');
            }
        } catch (mysqli_sql_exception | Exception $e) {
            $_SESSION['status'] = '<p style="color:red">Une erreur s\'est produite. Veuillez réessayer</p>';
            $_SESSION['user_email'] = $emailInput;
            $_SESSION['user_name'] = $nameInput;
            header('Location: signup.php');
            exit();
        }
    }
}
?>
