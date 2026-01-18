<?php

function isUserLoggedIn() {
    return isset($_SESSION['matricola']);
}

function redirectToLoginIfUserNotLoggedIn() {
    if (!isUserLoggedIn()) {
        header("Location: index.php");
    }
}
?>