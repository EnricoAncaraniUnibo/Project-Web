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

function validaNome($nome) {
    $nome = trim($nome);
    if (empty($nome)) {
        return "Il nome è obbligatorio";
    }
    return null;
}

function validaMatricola($matricola) {
    $matricola = trim($matricola);
    if (empty($matricola)) {
        return "La matricola è obbligatoria";
    } elseif (!preg_match('/^\d{10}$/', $matricola)) {
        return "La matricola deve essere composta da 10 cifre";
    }
    return null;
}

function validaEmail($email) {
    $email = trim($email);
    if (empty($email)) {
        return "L'email è obbligatoria";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Formato email non valido";
    } elseif (!preg_match('/@studio\.unibo\.it$/', $email)) {
        return "Devi usare un'email @studio.unibo.it";
    }
    return null;
}

function validaPassword($password, $conferma_password) {
    if (empty($password)) {
        return "La password è obbligatoria";
    } elseif (strlen($password) < 8) {
        return "La password deve essere almeno di 8 caratteri";
    } elseif (!preg_match('/[A-Z]/', $password)) {
        return "La password deve contenere almeno una lettera maiuscola";
    } elseif (!preg_match('/[a-z]/', $password)) {
        return "La password deve contenere almeno una lettera minuscola";
    } elseif (!preg_match('/\d/', $password)) {
        return "La password deve contenere almeno un numero";
    } elseif ($password !== $conferma_password) {
        return "Le password non coincidono";
    }
    return null;
}
?>