<?php
require_once("../PHPUtilities/bootstrap.php");
if (isUserLoggedIn()) {
    $_SESSION = [];
    session_destroy();
    http_response_code(200);
    header("Location: ../pages/index.php");
} else {
    header('Content-Type: application/json');
    http_response_code(401);
    echo json_encode(['error' => 'Nessun utente loggato']);
}
?>