<?php

function isUserLoggedIn() {
    return isset($_SESSION['matricola']);
}

function redirectToLoginIfUserNotLoggedIn() {
    if (!isUserLoggedIn()) {
        header("Location: index.php");
    }
}

function loginUser($matricola) {
    $_SESSION['matricola'] = $matricola;
    session_regenerate_id(true);
}

function validatePassword($password) {
    if (preg_match('/^(?:(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*)$/', $password)) {
        return true;
    }
    return false;
}
?>