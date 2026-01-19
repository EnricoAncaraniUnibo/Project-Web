<?php
require_once("../PHPUtilities/bootstrap.php");
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['key'])) {
    $termine = trim($_GET['key']);
    $templateParams["EventiRicercati"]=$dbh->search($termine);
    $templateParams["NumeroEventiRicercati"]=$dbh->NumberOfsearch($termine);
}
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>RicercaNoLogin</title>
        <link rel="stylesheet" href="../css/stylesVariables.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    </head>
    <body class="body mx-0 my-0 font">
        <header class="bg-white ">
            <div class="d-flex align-items-center py-3 justify-content-between MaxHeightScaling marginx4 ms-2">
                <h2 class="textsecondary ps-3 my-0 fw-bold">Uni Events</h2>
                <a href="index.php" class="backToAccedi buttonPrimary border-0 px-4 py-2">Accedi</a>
            </div>
        </header>
        <div class="body">
            <main class="d-flex flex-column align-items-center marginx6">
                <div class="w-100 maxWidthScaling d-flex justify-content-center">
                    <form action="" method="GET" class="searchBox">
                        <input type="text" value="<?php echo $termine ?>" name="key" placeholder="Cerca eventi..." required>
                        <button type="submit" name="search">
                            🔍
                        </button>
                    </form>
                </div>
                <div class="mt-4 d-flex align-items-center">
                    <h3 class="mb-0">Risultati per </h3>
                    <span class="textprimary fs-3 mx-1">"<?php echo $termine ?>"</span>
                </div>
                <p class="SizeForDescription"><?php echo $templateParams["NumeroEventiRicercati"][0]["COUNT(*)"] ?> elementi trovati</p>
                <?php foreach($templateParams["EventiRicercati"] as $evento): ?>
                <div class="event-card mb-4 maxWidthScaling w-100">
                    <div class="event-header d-flex justify-content-between">
                        <div class="event-header d-flex align-items-center">
                            <img src="../img/positionHeader.png" alt="luogo" class="imageForForm me-2">
                            <span class="fw-bold"><?php echo $evento["Città"] ?></span>
                        </div>
                    </div>
                    <div class="px-3 py-3">
                        <h4 class="textprimary fw-bold"><?php echo $evento["Titolo"] ?></h4>
                        <p class="SizeForDescription">Creato da: <?php echo $evento["nome"] ?> (Mat. <?php echo $evento["matricola"] ?>)</p>
                        <p class="SizeForInformation mb-1">🕓 <?php echo formattaOrario($evento["Orario"]) ?>, <?php echo formattaDataItaliana($evento["Data"]) ?></p>
                        <p class="SizeForInformation mb-1">📍 <?php echo $evento["Luogo"] ?>, <?php echo $evento["Indirizzo"] ?></p>
                        <p class="SizeForInformation mb-1">🎓 <?php echo $evento["Descrizione"] ?></p>
                        <p class="SizeForInformation mb-2">👥 <?php echo $evento["Partecipanti_Attuali"] ?> partecipanti</p>
                    </div>
                </div>
                <?php endforeach; ?>
            </main>
            <footer>
                <p class="text-center mt-4">© 2026 Università di Bologna - Tutti i diritti riservati</p>
            </footer>
        </div>
    </body>
</html>