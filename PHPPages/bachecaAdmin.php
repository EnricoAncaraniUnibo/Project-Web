<?php
require_once("../PHPUtilities/bootstrap.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $evento_id = $_POST['evento_id'] ?? null;
    $azione = $_POST['azione'] ?? null;
    $sezione = $_POST['sezione'] ?? 'accettazioni';
    
    if ($evento_id && $azione) {
        switch ($azione) {
            case 'approva':
                $dbh->approvaEvento($evento_id);
                break;
                
            case 'rifiuta':
                $dbh->rifiutaEvento($evento_id);
                break;
                
            case 'risolto':
                $dbh->risolviSegnalazione($evento_id);
                break;
                
            case 'elimina':
                $dbh->eliminaEvento($evento_id);
                break;
                
            case 'modifica':
                header("Location: modificaEvento.php?id=" . $evento_id);
                exit;
                break;
        }
        header("Location: bachecaAdmin.php?sezione=" . urlencode($sezione));
        exit;
    }
}

$sezione_attiva = $_GET['sezione'] ?? 'accettazioni';

$templateParams["EventiInSospeso"] = $dbh->getEventiInSospeso();
$templateParams["NumeroEventiInSospeso"] = $dbh->getNumberEventiInSospeso();
$templateParams["EventiSegnalati"] = $dbh->getEventiSegnalati();
$templateParams["NumeroEventiSegnalati"] = $dbh->getNumberEventiSegnalati();

$eventiInSospesoPerCitta = [];
foreach ($templateParams["EventiInSospeso"] as $evento) {
    $citta = $evento["Città"];
    if (!isset($eventiInSospesoPerCitta[$citta])) {
        $eventiInSospesoPerCitta[$citta] = [];
    }
    $eventiInSospesoPerCitta[$citta][] = $evento;
}

$eventiSegnalatiPerCitta = [];
foreach ($templateParams["EventiSegnalati"] as $evento) {
    $citta = $evento["Città"];
    if (!isset($eventiSegnalatiPerCitta[$citta])) {
        $eventiSegnalatiPerCitta[$citta] = [];
    }
    $eventiSegnalatiPerCitta[$citta][] = $evento;
}
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="utf-8">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Bacheca</title>
        <link rel="stylesheet" href="../css/stylesVariables.css">
        <link rel="stylesheet" href="../css/stylesEMME.css">
        <link rel="stylesheet" href="../css/navbar.css">
        
    </head>
    <body class="body font">
        <div class="page-content">
        <header>
            <?php require 'navbar.php'; ?>
        </header>
        <main class="marginx6">
            <div class="d-flex justify-content-center align-items-center mt-5 flex-wrap">
                <h3 class="textsecondary text-center mb-0 fs-4">Bacheca Amministratore</h3>
                <p class="mb-0 role py-1 px-3 fs-6 ms-2">Admin</p>
            </div>
            <div class="mt-5 d-flex flex-column flex-md-row justify-content-center gap-3 align-items-center">
                <button type="button" id="bottone-Accettazione" onclick="mostraEventiDaAccettare()" class="<?php echo $sezione_attiva === 'accettazioni' ? 'selected' : 'notSelected' ?> maxWidthScaling px-5 py-3 border-0">Accettazione eventi (<?php echo $templateParams["NumeroEventiInSospeso"][0]["COUNT(*)"] ?? 0 ?>)</button>
                <button type="button" id="bottone-Segnalazione" onclick="mostraEventiDaRisolvere()" class="<?php echo $sezione_attiva === 'segnalazioni' ? 'selected' : 'notSelected' ?> maxWidthScaling px-5 py-3 border-0">Segnalazione problemi (<?php echo $templateParams["NumeroEventiSegnalati"][0]["COUNT(*)"] ?? 0 ?>)</button>
            </div>
            
            <div class="d-flex flex-column align-items-center mt-5 <?php echo $sezione_attiva === 'accettazioni' ? '' : 'd-none' ?>" id="Div-Accettazioni">
                <?php 
                $eventiPerCitta = $eventiInSospesoPerCitta;
                include "Cards.php" 
                ?>
            </div>
            
            <div class="d-flex flex-column align-items-center mt-5 <?php echo $sezione_attiva === 'segnalazioni' ? '' : 'd-none' ?>" id="Div-Segnalazioni">
                <?php 
                $eventiPerCitta = $eventiSegnalatiPerCitta;
                include "CardsSegnalazioni.php" 
                ?>
            </div>
        </main>
            </div>
        <?php require 'footer.php'; ?>
        <script src="../JS/bachecaAdmin.js"></script>
        <script src="../JS/navbar.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>