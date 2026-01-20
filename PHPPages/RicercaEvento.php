<?php
require_once("../PHPUtilities/bootstrap.php");
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
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>RicercaNoLogin</title>
        <link rel="stylesheet" href="../css/stylesVariables.css">
        <link rel="stylesheet" href="../css/stylesEMME.css">
        <link rel="stylesheet" href="../css/navbar.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    </head>
    <body class="body mx-0 my-0 font">
        <header class="bg-white ">
            <?php require 'navbar.php'; ?>
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