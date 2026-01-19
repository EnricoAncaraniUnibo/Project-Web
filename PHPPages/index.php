<?php
require_once("../PHPUtilities/login.php");
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
            <?php if (!empty($error_message)): ?>
                <div class="error">
                    <?php echo $error_message; ?>
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
                <button class="Registrati" type="submit" name="register">Registrati ora</button>
                <a href="#">Password dimenticata?</a>
            </form>
        </main>
        <footer>
            <p>© 2026 Università di Bologna - Tutti i diritti riservati</p>
        </footer>
    </body>
</html>