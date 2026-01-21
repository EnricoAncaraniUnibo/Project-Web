<?php
require_once("../PHPUtilities/bootstrap.php");
redirectToLoginIfUserNotLoggedIn();
if (!isset($_SESSION['matricola'])) {
    header('Location: index.php');
    exit();
}

$messaggio = '';
$tipo_messaggio = '';
$utente = null;

try {
    $matricola = $_SESSION['matricola'];

    $utente = $dbh->getUserByMatricola($matricola);

    if (!$utente) {
        session_destroy();
        header('Location: index.php?error=utente_non_trovato');
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nuovo_nome = trim($_POST['nome'] ?? '');
        $vecchia_password = $_POST['vecchia_password'] ?? '';
        $nuova_password = $_POST['nuova_password'] ?? '';

        if (empty($nuovo_nome)) {
            throw new Exception('Il nome non può essere vuoto');
        }

        $cambio_password = !empty($vecchia_password) && !empty($nuova_password);

        if ($cambio_password) {
            $currentPwd = $dbh->getPasswordByMatricola($matricola);
            if ($vecchia_password !== $currentPwd) {
                throw new Exception('La vecchia password non è corretta');
            }
            $errore = validaPassword($nuova_password, $nuova_password);
            if ($errore !== null) {
                throw new Exception($errore);
            }
        }
        $success = $dbh->updateUser(
            $matricola,
            $nuovo_nome !== $utente['nome'] ? $nuovo_nome : $utente['nome'],
            $cambio_password ? $nuova_password : null
        );

        if (!$success) {
            throw new Exception('Nessuna modifica effettuata. Assicurati di cambiare il nome o la password.');
        }

        $messaggio = 'Profilo aggiornato con successo!';
        $tipo_messaggio = 'success';

        // Ricarica i dati aggiornati
        $utente = $dbh->getUserByMatricola($matricola);

        header('Refresh: 2; URL=modificaProfilo.php');
    }

} catch (Exception $e) {
    $messaggio = $e->getMessage();
    $tipo_messaggio = 'error';
    header('Refresh: 2; URL=modificaProfilo.php');
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Il mio profilo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/stylesVariables.css">
    <link rel="stylesheet" href="../css/stylesEMME.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <style>
        .alert-custom {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
        }
    </style>
</head>
<body class="body font">
    <div class="page-content">
    <?php require 'navbar.php'; ?>
    

    <div class="container py-4 maxWidthScaling">
        <?php if (!empty($messaggio)): ?>
            <div class="container mt-3">
                <div class="row justify-content-center">
                    <div class="">
                        <div class="alert <?php echo $tipo_messaggio === 'success' ? 'alert-success' : 'alert-danger'; ?> alert-compact alert-dismissible fade show text-center" role="alert">
                            <?php echo htmlspecialchars($messaggio); ?>
                            <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div class="profile-header mb-1">
            <h1 class="textsecondary fw-bold">Il mio profilo</h1>
            <p class="SizeForDescription mb-0">Username: <?php echo htmlspecialchars($utente['nome']); ?></p>
            <p class="SizeForDescription mb-0">Matricola: <?php echo htmlspecialchars($utente['matricola']); ?></p>
            <p class="SizeForDescription mb-0">Email: <?php echo htmlspecialchars($utente['email']); ?></p>
            <?php if ($utente['ruolo'] === 'admin'): ?>
                <p class="SizeForDescription mb-0"><span class="badge bg-primary">Amministratore</span></p>
            <?php endif; ?>
        </div>

        <div class="d-flex gap-2 mb-4">
            <span class="buttonSelected flex-fill border-0 text-decoration-none d-flex align-items-center justify-content-center">Modifica profilo</span>
            <a href="cercaUtenti.php" class="btn-active btn-disabled flex-fill border-0 text-decoration-none d-flex align-items-center justify-content-center" style="cursor: pointer;">Cerca utenti</a> 
        </div>

        <div class="event-card">
            <div class="p-4">
                <h2 class="defaultTextColor text-center mb-4 fw-semibold">I miei dati</h2>
                
                <form method="POST">
                    <div class="mb-3">
                        <label for="nome" class="form-label defaultTextColor fw-semibold SizeForDescription">Nome</label>
                        <input type="text" class="inputForForm w-100" id="nome" name="nome" value="<?php echo htmlspecialchars($utente['nome']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="vecchia_password" class="form-label defaultTextColor fw-semibold SizeForDescription">Cambia Password (opzionale)</label>
                        <input type="password" placeholder="Vecchia password" class="inputForForm w-100" id="vecchia_password" name="vecchia_password">
                        <small class="text-muted">Lascia vuoto se non vuoi cambiare la password</small>
                    </div>

                    <div class="mb-3">
                        <label for="nuova_password" class="form-label defaultTextColor fw-semibold SizeForDescription">Nuova Password</label>
                        <input type="password" placeholder="Nuova password (min. 8 caratteri)" class="inputForForm w-100" id="nuova_password" name="nuova_password">
                        <p class="SizeForDescription mt-2 mb-2">✔ Almeno 8 caratteri</p>
                        <p class="SizeForDescription mt-2 mb-2">✔ Maiuscole e minuscole</p>
                        <p class="SizeForDescription mt-2 mb-2">✔ Almeno un numero</p>
                    </div>

                    <button type="submit" class="buttonPrimary border-0 d-flex align-items-center gap-2">
                        ✏️ Modifica
                    </button>
                </form>
            </div>
        </div>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php require 'footer.php'; ?>
    <script src="../JS/navbar.js"></script>
</body>
</html>