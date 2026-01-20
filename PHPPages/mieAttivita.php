<?php
require_once("../PHPUtilities/bootstrap.php");
redirectToLoginIfUserNotLoggedIn();

$matricolaUtente = $_SESSION['matricola'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['evento_id']) && isset($_POST['azione']) && ($_POST['azione'] === 'partecipa' || $_POST['azione'] === 'annulla')) {
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
    header("Location: ".$_SERVER['REQUEST_URI']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['azione']) && $_POST['azione'] === 'segnala') {
    $eventoId = isset($_POST['evento_id']) ? intval($_POST['evento_id']) : 0;
    $descrizione = isset($_POST['descrizione_problema']) ? $_POST['descrizione_problema'] : '';
    $exist = $dbh->esisteSegnalazione($eventoId);
    if ($exist) {
        $_SESSION['erroreSegnalazione'] = "Hai già segnalato un problema per questo evento.";
        header("Location: ".$_SERVER['REQUEST_URI']);
        exit;
    }
    $risultato = $dbh->aggiungiSegnalazione($eventoId, $descrizione);
    $_SESSION['successoSegnalazione'] = "Segnalazione inviata con successo";
    header("Location: ".$_SERVER['REQUEST_URI']);
    exit;
}

if (!isset($_SESSION['vista_eventi'])) {
    $_SESSION['vista_eventi'] = 'joined';
}

// aggiorna vista se arriva POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vista_eventi'])) {
    $_SESSION['vista_eventi'] = $_POST['vista_eventi'];
    header("Location: ".$_SERVER['PHP_SELF']); // evita doppio submit
    exit;
}

$activitiesPage = true;
$vistaAttiva = $_SESSION['vista_eventi'];


$templateParams["EventiPartecipa"]=$dbh->getEventiPartecipa($_SESSION['matricola']);
$templateParams["EventiPubblicati"]=$dbh->getEventiPubblicati($_SESSION['matricola']);
$eventiPerCitta = [];

if($vistaAttiva === 'joined') {
    foreach($templateParams["EventiPartecipa"] as $evento) {
        $citta = $evento["Città"];
        if (!isset($eventiPerCitta[$citta])) {
            $eventiPerCitta[$citta] = [];
        }
        $eventiPerCitta[$citta][] = $evento;
    }
} else {
    foreach($templateParams["EventiPubblicati"] as $evento) {
        $citta = $evento["Città"];
        if (!isset($eventiPerCitta[$citta])) {
            $eventiPerCitta[$citta] = [];
        }
        $eventiPerCitta[$citta][] = $evento;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Le mie attività</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/stylesVariables.css">
    <link rel="stylesheet" href="../css/stylesActivities.css">
    <link rel="stylesheet" href="../css/stylesEMME.css">
    <link rel="stylesheet" href="../css/navbar.css">
</head>
<body class="body">
    <?php include "navbar.php"; ?>
    <?php if (isset($_SESSION['successoSegnalazione'])): ?>
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                <?php echo htmlspecialchars($_SESSION['successoSegnalazione']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['successoSegnalazione']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['erroreSegnalazione'])): ?>
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                <?php echo htmlspecialchars($_SESSION['erroreSegnalazione']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['erroreSegnalazione']); ?>
    <?php endif; ?>
    <main class="marginx6">
        <div class="d-flex justify-content-center align-items-center mt-4 flex-wrap">
                <h3 class="textsecondary text-center fs-4">Le mie attività</h3>
        </div>
        <div class="mt-5 d-flex justify-content-center gap-3 align-items-center flex-wrap">
            <form method="POST" class="d-inline">
                <input type="hidden" name="vista_eventi" value="joined">
                <button type="submit" class="<?php echo $vistaAttiva === 'joined' ? 'selected' : 'notSelected'; ?> maxWidthScaling px-5 py-3 border-0">Eventi a cui partecipo</button>
            </form>
            <form method="POST" class="d-inline">
                <input type="hidden" name="vista_eventi" value="published">
                <button type="submit" class="<?php echo $vistaAttiva === 'published' ? 'selected' : 'notSelected'; ?> maxWidthScaling px-5 py-3 border-0">Eventi che ho pubblicato</button>
            </form>
        </div>
        <div class="d-flex flex-column align-items-center gap-4 mt-5">
                <?php if (empty($eventiPerCitta)): ?>
                    <p class="text-center text-secondary fs-5">Nessun evento disponibile</p>
                <?php else: ?>
                    <?php include "Cards.php"; ?>
                <?php endif; ?>
        </div>
    </main>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">⚠️ Segnala un problema</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>
                        Descrivi il problema riscontrato con questo evento. Il team di amministrazione riceverà la tua
                        segnalazione.
                    </p>
                    <div class="mt-3">
                        <textarea class="form-control" name="descrizione_problema" rows="4" placeholder="Descrivi il problema nel dettaglio..." required></textarea>
                        <input type="hidden" name="evento_id" id="eventoIdModal" value="">
                        <input type="hidden" name="azione" value="segnala">
                        <span>Minimo 10 caratteri</span>
                    </div>
                </div>
                <div class="modal-footer p-3">
                    <div class="d-flex w-100">
                        <button type="button" class="btn btn-secondary w-50 m-2" data-bs-dismiss="modal">
                            Annulla
                        </button>
                        <button type="submit" class="btn btn-secondary w-50 m-2">
                            Segnala
                        </button>
                    </div>
                </div>
            </form>
            </div>
        </div>
    </div>
    <?php include "footer.php"; ?>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script src="../JS/MieAttivita.js"></script>
<script src="../JS/navbar.js"></script>
</html>