<?php
require_once("PHPUtilities/bootstrap.php");
$templateParams["EventiInSospeso"]=$dbh->getEventiInSospeso();
$templateParams["NumeroEventiInSospeso"]=$dbh->getNumberEventiInSospeso();
$templateParams["EventiSegnalati"]=$dbh->getEventiSegnalati();
//$templateParams["NumeroEventiSegnalati"]=$dbh->getNumberEventiSegnalati();
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Bacheca</title>
        <link rel="stylesheet" href="css/stylesVariables.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="body font">
        <header>
            Da fare importare da rossi navbar
        </header>
        <main class="marginx6">
            <div class="d-flex justify-content-center align-items-center mt-4 flex-wrap">
                <h3 class="textsecondary text-center mb-0 fs-4">Bacheca Amministratore</h3>
                <p class="mb-0 role py-1 px-3 fs-6 ms-2">Admin</p>
            </div>
            <div class="mt-5 d-flex flex-column flex-md-row justify-content-center gap-3 align-items-center">
                <button type="button" id="bottone-Segnalazione" onclick="mostraEventiDaRisolvere()" class="notSelected maxWidthScaling px-5 py-3 border-0">Segnalazione problemi (<?php echo $templateParams["NumeroEventiSegnalati"][0]["COUNT(*)"] ?>)</button>
                <button type="button" id="bottone-Accettazione" onclick="mostraEventiDaAccettare()" class="selected maxWidthScaling px-5 py-3 border-0">Accettazione eventi (<?php echo $templateParams["NumeroEventiInSospeso"][0]["COUNT(*)"] ?>)</button>
            </div>
            <div class="d-flex flex-column align-items-center gap-4 mt-5" id="Div-Accettazioni">
                <?php foreach($templateParams["EventiInSospeso"] as $eventoInSospeso): ?>
                <div class="event-card maxWidthScaling w-100" >
                    <div class="event-header d-flex justify-content-between">
                        <div class="event-header d-flex align-items-center">
                            <img src="img/positionHeader.png" alt="luogo" class="imageForForm me-2">
                            <span class="fw-bold"><?php echo $eventoInSospeso["Città"] ?></span>
                        </div>
                    </div>
                    <div class="px-3 py-3">
                        <h4 class="fw-bold"><?php echo $eventoInSospeso["Titolo"] ?></h4>
                        <p class="SizeForDescription">Creato da: <?php echo $eventoInSospeso["nome"] ?> (Mat. <?php echo $eventoInSospeso["matricola"] ?>)</p>
                        <p class="SizeForInformation mb-1">🕓 <?php echo formattaOrario($eventoInSospeso["Orario"]) ?>, <?php echo formattaDataItaliana($eventoInSospeso["Data"]) ?> </p>
                        <p class="SizeForInformation mb-1">📍 <?php echo $eventoInSospeso["Luogo"] ?>, <?php echo $eventoInSospeso["Indirizzo"] ?></p>
                        <p class="SizeForInformation mb-1">🎓 <?php echo $eventoInSospeso["Descrizione"] ?></p>
                        <button type="button" class="mt-2 buttonApproves border-0 px-3 py-2">✔ Approva</button>
                        <button type="button" class="buttonPrimary border-0 px-3 py-2">✕ Rifiuta</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="d-flex flex-column align-items-center gap-4 mt-5 d-none" id="Div-Segnalazioni">
                <?php foreach($templateParams["EventiSegnalati"] as $eventoSegnalato): ?>
                <div class="event-card maxWidthScaling w-100" >
                    <div class="event-header d-flex justify-content-between">
                        <div class="event-header d-flex align-items-center">
                            <img src="img/positionHeader.png" alt="luogo" class="imageForForm me-2">
                            <span class="fw-bold"><?php echo $eventoSegnalato["Città"] ?></span>
                        </div>
                    </div>
                    <div class="px-3 py-3">
                        <div class="d-flex align-items-center gap-2">
                            <img src="img/AlertImage.png" alt="immagine di allerta" class="imageForForm">
                            <p class="SizeForDescription mb-0">Segnalato da <?php echo $eventoSegnalato["nome"] ?> (Mat. <?php echo $eventoSegnalato["matricola"] ?>)</p>
                        </div>
                        <div class="warningBox px-2 py-2 my-3 ms-4">
                            <p class="mb-0"><?php echo $eventoSegnalato["Descrizione"] ?></p>
                        </div>
                        <div class="d-flex">
                            <span class="line"></span>
                        </div>
                        <h4 class="fw-bold mt-3"><?php echo $eventoSegnalato["Titolo"] ?></h4>
                        <p class="SizeForInformation mb-1">🕓 <?php echo formattaOrario($eventoSegnalato["Orario"]) ?>, <?php echo formattaDataItaliana($eventoSegnalato["Data"]) ?> </p>
                        <p class="SizeForInformation mb-1">📍 <?php echo $eventoSegnalato["Luogo"] ?>, <?php echo $eventoSegnalato["Indirizzo"] ?></p>
                        <p class="SizeForInformation mb-1">🎓 <?php echo $eventoInSospeso["Descrizione"] ?></p>
                        <button type="button" class="buttonModify border-0 px-3 py-2 mb-2">📝 Modifica evento</button>
                        <button type="button" class="mt-2 buttonApproves border-0 px-3 py-2">✔ Risolto</button>
                        <button type="button" class="buttonErase border-0 px-3 py-2">🗑️ Elimina Evento</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </main>
        <footer>FOOTER DA IMMETTERE</footer>
        <script src="bachecaAdmin.js"></script>
    </body>
</html>