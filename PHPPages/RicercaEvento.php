<?php
require_once("../PHPUtilities/bootstrap.php");

$matricolaUtente = $_SESSION['matricola'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['azione']) && ($_POST['azione'] === 'partecipa' || $_POST['azione'] === 'annulla')) {
    $eventoId = isset($_POST['evento_id']) ? intval($_POST['evento_id']) : 0;
    $azione = isset($_POST['azione']) ? $_POST['azione'] : '';
    if ($eventoId > 0 && in_array($azione, ['partecipa', 'annulla'])) {
        
        if ($azione === 'partecipa') {
            if ($dbh->verificaPartecipazioneUtente($eventoId, $matricolaUtente)) {
                $_SESSION['errore'] = "Sei già iscritto a questo evento";
            } else {
                $risultato = $dbh->aggiungiPartecipazione($eventoId, $matricolaUtente);
                if ($risultato) {
                    $_SESSION['successo'] = "Ti sei iscritto con successo all'evento!";
                } else {
                    $_SESSION['errore'] = "Impossibile iscriversi all'evento. Potrebbe essere completo.";
                }
            }
        } elseif ($azione === 'annulla') {
            if (!$dbh->verificaPartecipazioneUtente($eventoId, $matricolaUtente)) {
                $_SESSION['errore'] = "Non risulti iscritto a questo evento";
            } else {
                $risultato = $dbh->rimuoviPartecipazione($eventoId, $matricolaUtente);
                if ($risultato) {
                    $_SESSION['successo'] = "Hai annullato la partecipazione all'evento";
                } else {
                    $_SESSION['errore'] = "Impossibile annullare la partecipazione.";
                }
            }
        }
    }
    
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $redirect = 'RicercaEvento.php';

    if (!empty($_POST['key'])) {
        $redirect .= '?key=' . urlencode($_POST['key']);
    }

    header("Location: $redirect");
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['key'])) {
    $termine = trim($_GET['key']);
    $templateParams["EventiRicercati"]=$dbh->search($termine);
    $templateParams["NumeroEventiRicercati"]=count($templateParams["EventiRicercati"]);
    $eventiPerCitta = [];
    foreach($templateParams["EventiRicercati"] as $evento) {
        $citta = $evento["Città"];
        if (!isset($eventiPerCitta[$citta])) {
            $eventiPerCitta[$citta] = [];
        }
        $eventiPerCitta[$citta][] = $evento;
    }
}
$termineRicerca = $termine;
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>RicercaNoLogin</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../css/stylesVariables.css">
        <link rel="stylesheet" href="../css/stylesEMME.css">
        <link rel="stylesheet" href="../css/navbar.css">
    </head>
    <body class="body font">
        
        <header >
            <?php require 'navbar.php'; ?>
        </header>
        
            <div class="page-content">
            <main class="d-flex flex-column align-items-center marginx6">
                <div class="w-100 maxWidthScaling d-flex justify-content-center">
                    <form action="" method="GET" class="searchBox">
                        <input type="text" value="<?php echo $termine ?>" name="key" placeholder="Cerca eventi..." required>
                        <button type="submit" name="search">
                            🔍
                        </button>
                    </form>
                </div>
                <?php if (isset($_SESSION['successo'])): ?>
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                <?php echo htmlspecialchars($_SESSION['successo']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php unset($_SESSION['successo']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['errore'])): ?>
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                <?php echo htmlspecialchars($_SESSION['errore']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['errore']); ?>
        <?php endif; ?>
                <div class="mt-4 d-flex align-items-center">
                    <h3 class="mb-0">Risultati per </h3>
                    <span class="textprimary fs-3 mx-1">"<?php echo $termine ?>"</span>
                </div>
                <p class="SizeForDescription"><?php echo $templateParams["NumeroEventiRicercati"]?> elementi trovati</p>
                <?php include "Cards.php" ?>
            </main>
            
            <footer>
                <?php require 'footer.php'; ?>
            </footer>
        </div>
    </body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../JS/navbar.js"></script>
</html>