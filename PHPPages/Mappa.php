<?php 
require_once("../PHPUtilities/bootstrap.php");
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../css/stylesVariables.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../css/stylesEMME.css">
        <link rel="stylesheet" href="../css/navbar.css">
        <title>MappaEventi</title>
    </head>
    <body class="body font">
        <header>
            <?php require 'navbar.php'; ?>
        </header>
        <main>
            <div class="d-flex flex-column align-items-center justify-content-center">
                <div class="map backgroundGrey d-flex justify-content-center align-items-center">
                    Mappa
                </div>
            </div>
            <div class="my-4 d-flex flex-column align-items-center">
                <h4>Luoghi con eventi</h4>
                <div class="redBorder maxWidthScaling mb-3 w-100">
                    <div class="d-flex align-items-center pt-3">
                        <img src="../img/imagePosition.png" alt="immagine di una posizione" class="imageForForm ms-3">
                        <h5 class="mb-0 ms-2">Aula Magna</h5>
                    </div>
                    <p class="SizeForInformation ms-5 mt-1 mb-0">Via Zamboni, 33 - Bologna</p>
                    <span class="SizeForInformation ms-5 mt-1 eventLabel px-2 py-2 d-inline-block mb-2">3 eventi</span>
                    <div class="d-flex mt-2 mx-3 mb-3">
                        <span class="line"></span>
                    </div>
                    <h6 class="fs-6 ms-3 mb-1">WorkShop su AI e Machine Learning</h6>
                    <div class="ms-3 d-flex align-items-center mb-2">
                        <img src="../img/BlackCalendar.png" alt="Immagine di un calendario" class="imageForInformation">
                        <p class="SizeForInformation mb-0 ms-2">15 gennaio</p>
                        <img src="../img/blackClock.png" alt="Immagine di un orologio" class="imageForInformation ms-3">
                        <p class="SizeForInformation mb-0 ms-2">15:00</p>
                    </div>
                    <h6 class="fs-6 ms-3 mb-1">Concerto di fine anno</h6>
                    <div class="ms-3 d-flex align-items-center mb-2">
                        <img src="../img/BlackCalendar.png" alt="Immagine di un calendario" class="imageForInformation">
                        <p class="SizeForInformation mb-0 ms-2">15 gennaio</p>
                        <img src="../img/blackClock.png" alt="Immagine di un orologio" class="imageForInformation ms-3">
                        <p class="SizeForInformation mb-0 ms-2">20:30</p>
                    </div>
                    <h6 class="fs-6 ms-3 mb-1">Carrer Day 2025</h6>
                    <div class="ms-3 d-flex align-items-center mb-2">
                        <img src="../img/BlackCalendar.png" alt="Immagine di un calendario" class="imageForInformation">
                        <p class="SizeForInformation mb-0 ms-2">18 gennaio</p>
                        <img src="../img/blackClock.png" alt="Immagine di un orologio" class="imageForInformation ms-3">
                        <p class="SizeForInformation mb-0 ms-2">9:00</p>
                    </div>
                </div>
                <div class="whiteBorder maxWidthScaling w-100 pb-2">
                    <div class="d-flex align-items-center pt-3">
                        <img src="../img/imagePosition.png" alt="immagine di una posizione" class="imageForForm ms-3">
                        <h5 class="mb-0 ms-2">Biblioteca universitaria</h5>
                    </div>
                    <p class="SizeForInformation ms-5 mt-1 mb-0">Via Zamboni, 35 - Bologna</p>
                    <span class="SizeForInformation ms-5 mt-1 eventLabel px-2 py-2 d-inline-block mb-2">2 eventi</span>
                </div>
            </div>
        </main>
        <footer>
            <?php require 'footer.php'; ?>
        </footer>
        <script src="../JS/MieAttivita.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    </body>
</html>