<?php
require_once("../PHPUtilities/bootstrap.php");

if (isset($_SESSION['user_id'])) {
    header('Location: homepageUser.php');
    exit();
}

$error_message = "";
$success_message = "";

// Gestione del login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $matricola = trim($_POST['matricola'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    if (empty($matricola) || empty($password)) {
        $error_message = "Inserisci sia matricola che password";
    } else {
        // Verifica se l'utente esiste
        if ($dbh->checkUserExists($matricola)) {
            // Verifica le credenziali
            if ($dbh->verifyUserCredentials($matricola, $password)) {
                // Login riuscito - imposta la sessione
                $_SESSION['user_id'] = $matricola;
                $_SESSION['user_matricola'] = $matricola;
                
                // Reindirizza alla homepage
                header('Location: homepageUser.php');
                exit();
            } else {
                $error_message = "Password errata";
            }
        } else {
            $error_message = "Utente non trovato";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>HomePage</title>
        <link rel="stylesheet" href="..\css\styles.css">
    </head>
    <body>
        <header class="alignHeader">
            <div class="LogoWrite">
                <img src="..\img\LogoUni.png" alt="Logo Unibo">
                <div class="WriteToPutDown">
                    <h1>Università di</h1>
                    <h1>Bologna</h1>
                </div>
            </div>
            <div class="searchBox">
                <input type="text" placeholder="Cerca eventi...">
                <button type="submit">
                    🔍
                </button>
            </div>
        </header>
        <main>
            <section>
                <div class="titolo">
                    <h2>Uni Events</h2>
                </div>
                <div class="paragrafo">
                     <p>La piattaforma ufficiale per scoprire e partecipare </p>
                    <p>a tutti gli eventi universitari!</p>
                </div>
            </section>
            <form method="POST" action="">
                <h2>Accedi al tuo account</h2>
                <h3>Numero Matricola</h3>
                <input type="text" name="matricola" placeholder="Inserisci il tuo numero di matricola">
                <h3>Password</h3>
                <input type="password" name="password" placeholder="Inserisci la tua password">
                <button type="submit" name="login" class="accedi">Accedi</button>
                <div class="separator">
                    <span class="linea"></span>
                    <span class="testo">oppure</span>
                    <span class="linea"></span>
                </div>
                <a class="Registrati" href="registrati.html">Registrati ora</a>
                <a href="#">Password dimenticata?</a>
            </form>
        </main>
        <footer>
            <p>© 2026 Università di Bologna - Tutti i diritti riservati</p>
        </footer>
    </body>
</html>