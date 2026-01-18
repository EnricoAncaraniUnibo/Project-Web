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
?>