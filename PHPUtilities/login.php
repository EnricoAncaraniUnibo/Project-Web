<?php
require_once("../PHPUtilities/bootstrap.php");

if (isUserLoggedIn()) {
    header('Location: /Project-Web/PHPPages/homepageUser.php');
    exit();
}

$error_message = $_SESSION['error_message'] ?? '';

unset($_SESSION['error_message']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $matricola = trim($_POST['matricola'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    if (empty($matricola) || empty($password)) {
        $_SESSION['error_message'] = "Inserisci sia matricola che password";
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    } else {
        if ($dbh->checkUserExists($matricola)) {
            if ($dbh->verifyUserCredentials($matricola, $password)) {
                $_SESSION['matricola'] = $matricola;
                header('Location: homepageUser.php');
                exit();
            } else {
                $_SESSION['error_message'] = "Password errata";
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit();
            }
        } else {
            $_SESSION['error_message'] = "Utente non trovato";
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        }
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    header('Location: registrati.php');
    exit();
}
?>