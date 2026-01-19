<?php
require_once("../PHPUtilities/bootstrap.php");

if (isset($_SESSION['user_id'])) {
    header('Location: homepageUser.php');
    exit();
}

// Inizializza i messaggi dalla sessione, se presenti
$error_message = $_SESSION['error_message'] ?? '';
$success_message = $_SESSION['success_message'] ?? '';

// Pulisci i messaggi dalla sessione dopo averli presi
unset($_SESSION['error_message'], $_SESSION['success_message']);

// Gestione del login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $matricola = trim($_POST['matricola'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    if (empty($matricola) || empty($password)) {
        $_SESSION['error_message'] = "Inserisci sia matricola che password";
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
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
                $_SESSION['error_message'] = "Password errata";
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit();
            }
        } else {
            $_SESSION['error_message'] = "Utente non trovato";
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
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
        <style>
            /* Aggiungi questo stile per il messaggio di errore */
            .error-message {
                background-color: #f8d7da;
                color: #721c24;
                padding: 12px;
                border-radius: 5px;
                border: 1px solid #f5c6cb;
                margin: 15px 0;
                text-align: center;
                font-weight: bold;
            }
            
            .success-message {
                background-color: #d4edda;
                color: #155724;
                padding: 12px;
                border-radius: 5px;
                border: 1px solid #c3e6cb;
                margin: 15px 0;
                text-align: center;
                font-weight: bold;
            }
        </style>
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
            <!-- Mostra messaggio di errore se presente -->
            <?php if (!empty($error_message)): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            
            <!-- Mostra messaggio di successo se presente -->
            <?php if (!empty($success_message)): ?>
                <div class="success-message">
                    <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>
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