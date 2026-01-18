<?php
require_once("../PHPUtilities/bootstrap.php");
header('Content-Type: application/json');

if (isset($_POST['matricola']) && isset($_POST['password'])) {

    $user_exists = $dbh->checkUserExists($_POST['matricola']);

    if ($user_exists) {
        $user_login_success = $dbh->verifyUserCredentials($_POST['matricola'], $_POST['password']);
        if ($user_login_success) {
            loginUser($_POST['matricola']);
            http_response_code(200);
            echo json_encode(['response' => 'Login avvenuto con successo']);
        } else {
            http_response_code(401);
            echo json_encode(['error' => 'Matricola o password errati']);
        }
    }
    else {
        http_response_code(401);
        echo json_encode(['error' => 'Matricola o password errati']);
    }
}else {
    http_response_code(400);
    echo json_encode(['error' => 'Malformed post request']);
}
?>