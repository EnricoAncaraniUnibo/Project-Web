<?php
require_once("../PHPUtilities/registration.php");
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Registrazione</title>
        <link rel="stylesheet" href="../css/stylesVariables.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    </head>
    <body class="body font">
        <header>
            <div class="mt-5">
                <a href="index.php" class="text-decoration-none textprimary paddingleft8 fs-4">← Torna al login</a>
            </div>
            <div>
                <h3 class="textsecondary mt-5 text-center fs-1 fw-bold">Registrati</h3>
                <p class="text-center fs-6 mb-0  defaultTextColor">Crea il tuo account per accedere</p>
                <p class="text-center fs-6  defaultTextColor">a Uni Events</p>
            </div>
        </header>
        <main class="d-flex justify-content-center">
            <?php if (!empty($errori)): ?>
                <div class="error w-100">
                    <h4>Errore nella registrazione:</h4>
                    <ul>
                        <?php foreach ($errori as $errore): ?>
                            <li><?php echo $errore; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <form class="bg-white paddingx4 form mt-2" method="POST" action="">
                <div class="d-flex pt-4 pb-2">
                    <img src="../img/imageProfile.png" alt="immagine profilo utente" class="imageForForm">
                    <h3 class="ms-3 fs-5 mb-0 defaultTextColor">Nome Utente *</h3>
                </div>
                <input type="text" placeholder="Inserisci il tuo nome e cognome" name="nome" class="inputForForm w-100" required>
                <div class="d-flex pt-4 pb-2">
                    <img src="../img/imageGrid.png" alt="immagine di una griglia" class="imageForForm">
                    <h3 class="ms-3 fs-5 mb-0 defaultTextColor">Numero Matricola *</h3>
                </div>
                <input type="text" placeholder="0000123456" name="matricola" class="inputForForm w-100" required>
                <p class="SizeForDescription mt-2 mb-2">Deve essere composto da 10 cifre</p>
                <div class="d-flex pt-4 pb-2">
                    <img src="../img/imageEmail.png" alt="immagine di una email" class="imageForForm">
                    <h3 class="ms-3 fs-5 mb-0 defaultTextColor">Email Istituzionale *</h3>
                </div>
                <input type="text" placeholder="nome.cognome@studio.unibo.it" name="email" class="inputForForm w-100" required>
                <p class="SizeForDescription mt-2 mb-2">Utilizza la tua email @studio.unibo.it</p>
                <div class="d-flex pt-4 pb-2">
                    <img src="../img/imageLock.png" alt="immagine di un lucchetto" class="imageForForm">
                    <h3 class="ms-3 fs-5 mb-0 defaultTextColor">Password *</h3>
                </div>
                <input type="text" placeholder="Inserisci una password sicura" name="password" class="inputForForm w-100" required>
                <p class="SizeForDescription mt-2 mb-2">✔ Almeno 8 caratteri</p>
                <p class="SizeForDescription mt-2 mb-2">✔ Maiuscole e minuscole</p>
                <p class="SizeForDescription mt-2 mb-2">✔ Almeno un numero</p>
                <div class="d-flex pt-4 pb-2">
                    <img src="../img/imageLock.png" alt="immagine di un lucchetto" class="imageForForm">
                    <h3 class="ms-3 fs-5 mb-0 defaultTextColor">Conferma password *</h3>
                </div>
                <input type="text" placeholder="Reinserisci la password" name="conferma_password" class="inputForForm w-100" required>
                <div class="SizeForDescription backgroundGrey mt-3 d-flex flex-wrap align-items-center px-3 py-3 ">
                    <span>Registrandoti accetti i</span>
                    <a class="textprimary mx-1">Termini di Servizio</a>
                    <span>e la</span>
                    <a class="textprimary mx-1">Privacy Policy</a>
                    <span>di Uni Events</span>
                </div>
                <button type="submit" name="registrati" class="buttonPrimary mt-4 w-100 mb-4">Registrati</button>
            </form>
        </main>
        <footer>
            <p class="text-center mt-4">© 2026 Università di Bologna - Tutti i diritti riservati</p>
        </footer>
    </body>
</html>