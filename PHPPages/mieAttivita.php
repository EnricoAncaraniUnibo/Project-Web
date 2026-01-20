<?php
require_once("../PHPUtilities/bootstrap.php");
redirectToLoginIfUserNotLoggedIn();

$matricolaUtente = $_SESSION['matricola'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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


$templateParams["EventiPartecipa"]=$dbh->getEventiPartecipa($_SESSION['matricola']);
$templateParams["EventiPubblicati"]=$dbh->getEventiPubblicati($_SESSION['matricola']);
$eventiPerCitta = [];
foreach($templateParams["EventiPartecipa"] as $evento) {
    $citta = $evento["Città"];
    if (!isset($eventiPerCitta[$citta])) {
        $eventiPerCitta[$citta] = [];
    }
    $eventiPerCitta[$citta][] = $evento;
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
</head>
<body class="body">
    <main class="marginx6">
        <div class="d-flex justify-content-center align-items-center mt-4 flex-wrap">
                <h3 class="textsecondary text-center fs-4">Le mie attività</h3>
        </div>
        <div class="mt-5 d-flex justify-content-center gap-3 align-items-center flex-wrap">
            <button type="button" class="selected maxWidthScaling px-5 py-3 border-0" id="joinedEvents">Eventi a cui partecipo</button>
            <button type="button" class="notSelected maxWidthScaling px-5 py-3 border-0" id="publishedEvents">Eventi che ho pubblicato</button>
        </div>
        <div class="d-flex flex-column align-items-center gap-4 mt-5" id="joinedEventsList">
            <?php include "Cards.php" ?>
        </div>
        <div id="publishedEventsList" class="d-flex flex-column align-items-center gap-4 mt-5 d-none">
                <?php foreach ($templateParams["EventiPubblicati"] as $evento): ?>
                    <div class="event-card maxWidthScaling w-100">
                        <div class="event-header d-flex justify-content-between">
                            <div class="event-header d-flex align-items-center">
                                <img src="../img/positionHeader.png" alt="luogo" class="imageForForm me-2">
                                <span class="fw-bold"><?php echo $evento["Città"]; ?></span>
                            </div>
                        </div>
                        <div class="px-3 py-3">
                            <h4 class="textprimary fw-bold"><?php echo $evento["Titolo"]; ?></h4>
                            <p class="SizeForDescription">
                                Creato da: <?php echo $evento["nome"]; ?> (Mat. <?php echo $evento["matricola_creatore"]; ?>)
                            </p>
                            <p class="SizeForInformation mb-1">
                                🕓 <?php echo formattaOrario($evento["Orario"]); ?>, <?php echo formattaDataItaliana($evento["Data"]); ?>
                            </p>
                            <p class="SizeForInformation mb-1">
                                📍 <?php echo $evento["Luogo"]; ?>, <?php echo $evento["Indirizzo"]; ?>
                            </p>
                            <p class="SizeForInformation mb-1">
                                🎓 <?php echo $evento["Descrizione"]; ?>
                            </p>
                            <p class="SizeForDescription mb-2">
                                👥 <?php echo $evento["Partecipanti_Attuali"]; ?>
                                <?php if ($evento["Max_Partecipanti"]): ?>
                                    / <?php echo $evento["Max_Partecipanti"]; ?>
                                <?php endif; ?>
                                partecipanti
                            </p>
                            <button type="button" class="report-button mt-2 border-0 px-3 py-2" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                ⚠️ Segnala un problema
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
        </div>
    </main>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

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
                <textarea class="form-control" rows="4" placeholder="Descrivi il problema nel dettaglio..."></textarea>
                <span>Minimo 10 caratteri</span>
                </div>
            </div>
            <div class="modal-footer p-3">
                <div class="d-flex w-100">
                    <button type="button" class="btn btn-secondary w-50 m-2" data-bs-dismiss="modal">
                    Annulla
                    </button>
                    <button type="button" class="btn btn-secondary w-50 m-2">
                    Segnala
                    </button>
                </div>
            </div>

            </div>
        </div>
    </div>
</body>
<script src="../JS/MieAttivita.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</html>