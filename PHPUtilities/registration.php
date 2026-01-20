<?php
require_once("../PHPUtilities/bootstrap.php");

$errori = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrati'])) {
    $nome = $_POST['nome'] ?? '';
    $matricola = $_POST['matricola'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $conferma_password = $_POST['conferma_password'] ?? '';
    $errori_nome = validaNome($nome);
    if ($errori_nome!=null) {
        $errori[] = $errori_nome;
    } 
    $errori_matricola = validaMatricola($matricola);
    if ($errori_matricola!= null) {
        $errori[] = $errori_matricola;
    } 
    $errori_email = validaEmail($email);
    if ($errori_email != null) {
        $errori[] = $errori_email;
    } 
    $errori_password = validaPassword($password, $conferma_password);
    if ($errori_password != null) {
        $errori[] = $errori_password;
    } 

    if (empty($errori)) {
        if ($dbh->checkUserExists($matricola)) {
            $errori[] = "Questa matricola è già registrata";
        }
    }

    if (empty($errori)) {
        if ($dbh->checkEmailExist($email)) {
            $errori[] = "Questa email è già registrata";
        }
    }

    if (empty($errori)) {
        $dbh->registerUser($nome, $matricola, $email, $password);
        header('Location: index.php');
        exit();
    }
}
?>