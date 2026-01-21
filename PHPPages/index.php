<?php
require_once("../PHPUtilities/login.php");
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search'])) {
    $termine = $_GET['key'];
    header('Location: ricercaSenzaLogin.php?key=' . $termine);
    exit();
}
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>HomePage</title>
        <link rel="stylesheet" href="../css/styles.css">
    </head>
    <body>
        <header class="alignHeader">
            <div class="LogoWrite">
                <img src="../img/LogoUni.png" alt="Logo Unibo">
                <div class="WriteToPutDown">
                    <h1>Università di</h1>
                    <h1>Bologna</h1>
                </div>
            </div>
            <form method="GET" class="searchBox">
                <label for="searchKey" class="visually-hidden">Cerca eventi</label>
                <input  id="searchKey" type="text" name="key" placeholder="Cerca eventi..." required>
                <button type="submit" name="search">
                    🔍
                </button>
            </form>
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
            <?php if (!empty($error_message)): ?>
                <div class="error">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($_SESSION["registration_state"])): ?>
                <div class="success">
                    <?php echo $_SESSION["registration_state"]; ?>
                </div>
            <?php endif; ?>
            <form method="POST">
                <h2>Accedi al tuo account</h2>
                <h3>Numero Matricola</h3>
                <label for="matricola" class="visually-hidden">Numero Matricola</label>
                <input id="matricola" type="text" name="matricola" placeholder="Inserisci il tuo numero di matricola">
                <h3>Password</h3>
                <label for="password" class="visually-hidden">Password</label>
                <input id="password" type="password" name="password" placeholder="Inserisci la tua password">
                <button type="submit" name="login" class="accedi">Accedi</button>
                <div class="separator">
                    <span class="linea"></span>
                    <span class="testo">oppure</span>
                    <span class="linea"></span>
                </div>
                <button class="Registrati" type="submit" name="register">Registrati ora</button>
            </form>
        </main>
        <footer>
            <p>© 2026 Università di Bologna - Tutti i diritti riservati</p>
        </footer>
    </body>
</html>