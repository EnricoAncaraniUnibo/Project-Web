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
    //Redirect per evitare re-submit del form
    $dataRitorno = $_POST['data_ritorno'] ?? $_GET['data'] ?? '';
    if ($dataRitorno && preg_match('/^\d{4}-\d{2}-\d{2}$/', $dataRitorno)) {
        header("Location: homepageUser.php?data=" . urlencode($dataRitorno));
    } else {
        header("Location: homepageUser.php");
    }
    exit;
}

if (isset($_GET['data']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['data'])) {
    $dataSelezionata = $_GET['data'];
} else {
    $dataSelezionata = $dbh->getPrimaDataConEventi();
}

if ($dataSelezionata === null) {
    $templateParams["NoEventi"] = true;
} else {
    $templateParams["NoEventi"] = false;
    $dataPrecedente = $dbh->getDataPrecedenteConEventi($dataSelezionata);
    $dataSuccessiva = $dbh->getDataSuccessivaConEventi($dataSelezionata);
    $templateParams["Eventi"] = $dbh->getEventiPerData($dataSelezionata);
    $templateParams["DataSelezionata"] = $dataSelezionata;
    $templateParams["DataFormattata"] = formattaDataItaliana($dataSelezionata);
    $templateParams["DataPrecedente"] = $dataPrecedente;
    $templateParams["DataSuccessiva"] = $dataSuccessiva;
    $eventiPerCitta = [];
    foreach($templateParams["Eventi"] as $evento) {
        $citta = $evento["Città"];
        if (!isset($eventiPerCitta[$citta])) {
            $eventiPerCitta[$citta] = [];
        }
        $eventiPerCitta[$citta][] = $evento;
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Eventi<?php echo !$templateParams["NoEventi"] ? " del " . $templateParams["DataFormattata"] : ""; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/stylesVariables.css">
    <link rel="stylesheet" href="../css/stylesEMME.css">
    <link rel="stylesheet" href="../css/navbar.css">
    
</head>
<script>
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alertEl => {
            const alert = bootstrap.Alert.getOrCreateInstance(alertEl);
            alert.close();
        });
    }, 4000);
</script>
<body class="body font mb-0">
<div class="page-content">
<?php require 'navbar.php'; ?>
<div class="container maxWidthScaling mt-4">

    <?php if ($templateParams["NoEventi"]): ?>
        <div class="alert alert-info text-center mt-4">
            <p class="mb-0">Nessun evento disponibile al momento.</p>
        </div>
    <?php else: ?>
        
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
            <a href="<?php echo $templateParams["DataPrecedente"] ? '?data=' . $templateParams["DataPrecedente"] : '#'; ?>" 
                class="btn-primary-custom btn-arrow <?php echo !$templateParams["DataPrecedente"] ? 'disabled' : ''; ?>"
                <?php echo !$templateParams["DataPrecedente"] ? 'aria-disabled="true"' : ''; ?>>
                <span class="arrow-left"></span>
            </a>
            <strong><?php echo $templateParams["DataFormattata"]; ?></strong>
            <a href="<?php echo $templateParams["DataSuccessiva"] ? '?data=' . $templateParams["DataSuccessiva"] : '#'; ?>" 
                class="btn-primary-custom btn-arrow <?php echo !$templateParams["DataSuccessiva"] ? 'disabled' : ''; ?>"
                <?php echo !$templateParams["DataSuccessiva"] ? 'aria-disabled="true"' : ''; ?>>
                <span class="arrow-right"></span>
            </a>
        </div>

        <?php include "Cards.php" ?>
    
    <?php endif; ?>
</div>
</div>
<?php require 'footer.php'; ?>
</body>
<script src="../JS/navbar.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</html>