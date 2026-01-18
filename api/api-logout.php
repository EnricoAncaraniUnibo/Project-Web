<?php
require_once("../PHPUtilities/bootstrap.php");
header('Content-Type: application/json');

if (isUserLoggedIn()) {
    $_SESSION = [];
    session_destroy();
    echo json_encode(['response' => 'Logout avvenuto con successo']);
} else {
    echo json_encode(['error' => 'Nessun utente loggato']);
}
?>