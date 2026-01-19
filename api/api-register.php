<?php

require_once("../PHPUtilities/bootstrap.php");

if (isset($_POST['username']) && isset($_POST['matricola']) && isset($_POST['email']) && isset($_POST['password'])) {
    $user_exists = $dbh->checkUserExists($_POST['matricola']);

    if (!$user_exists) {
        if(!validatePassword($_POST['password'])) {
            http_response_code(400);
            header("Content-Type: application/json");
            echo json_encode(['error' => 'La password non rispetta i criteri di sicurezza']);
            exit();
        }
        $dbh->registerUser($_POST['username'], $_POST['matricola'], $_POST['email'], $_POST['password']);
        http_response_code(201);
        header("Location: ../pages/homepageUser.php");
    } else {
        http_response_code(409);
        header("Content-Type: application/json");
        echo json_encode(['error' => 'Utente già esistente']);
    }
} else {
    http_response_code(400);
    header("Content-Type: application/json");
    echo json_encode(['error' => 'Malformed post request']);
}
?>