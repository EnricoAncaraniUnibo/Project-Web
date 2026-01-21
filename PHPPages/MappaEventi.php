<?php require_once("../PHPUtilities/bootstrap.php"); ?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/stylesVariables.css">
    <link rel="stylesheet" href="../css/stylesEMME.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MappaEventi</title>
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
</head>
<body class="body font mb-0">
<div class="page-content">
<header>
    <?php require 'navbar.php'; ?>
</header>

<main>

    <div class="d-flex flex-column align-items-center justify-content-center mt-4">
        <div id="mappaEventi" class="backgroundGrey" style="height: 400px; width: 90%; max-width: 800px;"></div>
    </div>

    <div class="d-flex flex-column align-items-center justify-content-center w-100">
        <h4 class="mt-4">Luoghi con eventi</h4>
        <div id="listaWrapper" class="w-100 d-flex flex-column align-items-center">
            <div id="listaLuoghi" class="w-100"></div>
        </div>
    </div>

</main>
</div>
<?php require 'footer.php'; ?>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="../JS/navbar.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../JS/Mappa.js"></script>
</body>
</html>
