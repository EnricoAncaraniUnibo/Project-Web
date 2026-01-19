<?php
require_once("../PHPUtilities/bootstrap.php");

redirectToLoginIfUserNotLoggedIn();

$matricolaUtente = $_SESSION['matricola'];

// GESTIONE AZIONI POST (Partecipa/Annulla)
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
    
    //Redirect per evitare re-submit del form
    $dataRitorno = isset($_POST['data_ritorno']) ? $_POST['data_ritorno'] : '';
    if ($dataRitorno && preg_match('/^\d{4}-\d{2}-\d{2}$/', $dataRitorno)) {
        header("Location: homepageUser.php?data=" . urlencode($dataRitorno));
    } else {
        header("Location: homepageUser.php");
    }
    exit;
}

// GESTIONE VISUALIZZAZIONE EVENTI
// Gestione della data da visualizzare
if (isset($_GET['data']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['data'])) {
    $dataSelezionata = $_GET['data'];
} else {
    $dataSelezionata = $dbh->getPrimaDataConEventi();
}

// Se non ci sono eventi, mostra messaggio
if ($dataSelezionata === null) {
    $templateParams["NoEventi"] = true;
} else {
    $templateParams["NoEventi"] = false;
    
    // Calcolo date precedente e successiva con eventi
    $dataPrecedente = $dbh->getDataPrecedenteConEventi($dataSelezionata);
    $dataSuccessiva = $dbh->getDataSuccessivaConEventi($dataSelezionata);
    
    // Recupero eventi per la data selezionata
    $templateParams["Eventi"] = $dbh->getEventiPerData($dataSelezionata);
    $templateParams["DataSelezionata"] = $dataSelezionata;
    $templateParams["DataFormattata"] = formattaDataItaliana($dataSelezionata);
    $templateParams["DataPrecedente"] = $dataPrecedente;
    $templateParams["DataSuccessiva"] = $dataSuccessiva;
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Eventi<?php echo !$templateParams["NoEventi"] ? " del " . $templateParams["DataFormattata"] : ""; ?></title>
    <link rel="stylesheet" href="../css/stylesVariables.css">
    <link rel="stylesheet" href="../css/stylesEMME.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="body font mb-0">

<div class="container maxWidthScaling">

    <?php if ($templateParams["NoEventi"]): ?>
        <div class="alert alert-info text-center mt-4">
            <p class="mb-0">Nessun evento disponibile al momento.</p>
        </div>
    <?php else: ?>
        
        <!-- Messaggi di feedback -->
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

        <div class="header d-flex justify-content-between align-items-center mb-3">
            <!-- Pulsante precedente -->
            <a href="<?php echo $templateParams["DataPrecedente"] ? '?data=' . $templateParams["DataPrecedente"] : '#'; ?>" 
                class="btn-primary-custom btn-arrow <?php echo !$templateParams["DataPrecedente"] ? 'disabled' : ''; ?>"
                <?php echo !$templateParams["DataPrecedente"] ? 'aria-disabled="true"' : ''; ?>>
                <span class="arrow-left"></span>
            </a>

            <!-- Data centrale -->
            <strong><?php echo $templateParams["DataFormattata"]; ?></strong>

            <!-- Pulsante successivo -->
            <a href="<?php echo $templateParams["DataSuccessiva"] ? '?data=' . $templateParams["DataSuccessiva"] : '#'; ?>" 
                class="btn-primary-custom btn-arrow <?php echo !$templateParams["DataSuccessiva"] ? 'disabled' : ''; ?>"
                <?php echo !$templateParams["DataSuccessiva"] ? 'aria-disabled="true"' : ''; ?>>
                <span class="arrow-right"></span>
            </a>
        </div>

        <!-- Lista eventi -->
        <?php foreach($templateParams["Eventi"] as $evento): ?>
            <div class="event-card mb-3">
                <div class="event-header d-flex justify-content-between">
                    <div class="event-header d-flex align-items-center">
                        <img src="../img/positionHeader.png" alt="luogo" class="event-icon">
                        <span class="event-city"><?php echo htmlspecialchars($evento["Città"]); ?></span>
                    </div>
                </div>
                <div class="px-3 py-3">
                    <h2 class="textprimary fw-bold fs-6"><?php echo htmlspecialchars($evento["Titolo"]); ?></h2>
                    <p class="SizeForDescription mb-1">📍 <?php echo htmlspecialchars($evento["Luogo"] . ", " . $evento["Indirizzo"]); ?></p>
                    <p class="SizeForDescription mb-1">🕓 <?php echo formattaOrario($evento["Orario"]); ?></p>
                    <p class="SizeForDescription mb-1">🎓 <?php echo htmlspecialchars($evento["Descrizione"]); ?></p>
                    <p class="SizeForDescription mb-2">
                        👥 <?php echo $evento["Partecipanti_Attuali"]; ?>
                        <?php if ($evento["Max_Partecipanti"]): ?>
                            / <?php echo $evento["Max_Partecipanti"]; ?>
                        <?php endif; ?>
                        partecipanti
                    </p>
                    
                    <?php 
                    // Recupero amici che partecipano all'evento
                    $amiciPartecipanti = $dbh->getAmiciPartecipanti($evento["Id"], $matricolaUtente);
                    if (count($amiciPartecipanti) > 0): 
                    ?>
                        <p class="SizeForDescription">
                            Amici che partecipano:
                            <strong>
                                <?php 
                                $nomiAmici = array_map(function($amico) {
                                    return htmlspecialchars($amico['nome']);
                                }, $amiciPartecipanti);
                                echo implode(', ', $nomiAmici);
                                ?>
                            </strong>
                        </p>
                    <?php endif; ?>
                    
                    <?php 
                    // Verifica se l'utente partecipa già
                    $utentePartecipa = $dbh->verificaPartecipazioneUtente($evento["Id"], $matricolaUtente);
                    $eventoCompleto = $evento["Max_Partecipanti"] && $evento["Partecipanti_Attuali"] >= $evento["Max_Partecipanti"];
                    ?>
                    
                    <div class="d-flex gap-2">
                        <?php if ($utentePartecipa): ?>
                            <!-- Utente già iscritto - può annullare -->
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="evento_id" value="<?php echo $evento["Id"]; ?>">
                                <input type="hidden" name="azione" value="annulla">
                                <input type="hidden" name="data_ritorno" value="<?php echo $templateParams["DataSelezionata"]; ?>">
                                <button type="submit" class="btn-secondary-custom">Annulla partecipazione</button>
                            </form>
                        <?php elseif ($eventoCompleto): ?>
                            <!-- Evento completo -->
                            <button class="btn-secondary-custom" disabled style="opacity: 0.5;">Evento completo</button>
                        <?php else: ?>
                            <!-- Utente non iscritto - può partecipare -->
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="evento_id" value="<?php echo $evento["Id"]; ?>">
                                <input type="hidden" name="azione" value="partecipa">
                                <input type="hidden" name="data_ritorno" value="<?php echo $templateParams["DataSelezionata"]; ?>">
                                <button type="submit" class="btn-primary-custom">Partecipa</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    
    <?php endif; ?>

</div>
<?php require 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>