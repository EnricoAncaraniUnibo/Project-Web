<?php require_once("../PHPUtilities/bootstrap.php"); ?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/stylesVariables.css">
    <link rel="stylesheet" href="../css/stylesEMME.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MappaEventi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
</head>
<body class="body font">

<header>
    <?php require 'navbar.php'; ?>
</header>

<main>
    <!-- MAPPA -->
    <div class="d-flex flex-column align-items-center justify-content-center">
        <div id="mappaEventi" class="backgroundGrey" style="height: 400px; width: 90%; max-width: 800px;"></div>
    </div>

    <div class="d-flex flex-column align-items-center justify-content-center w-100">
        <h4 class="mt-4">Luoghi con eventi</h4>
        <div id="listaWrapper" class="w-100 d-flex flex-column align-items-center">
            <!-- qui il JS metterà i blocchi -->
            <div id="listaLuoghi" class="w-100"></div>
        </div>
    </div>

</main>

<footer>
    <?php require 'footer.php'; ?>
</footer>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="../JS/navbar.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {

    const mapContainer = document.getElementById('mappaEventi');
    const lista = document.getElementById('listaLuoghi');

    if (!mapContainer || !lista) return;

    fetch("../PHPUtilities/getEventiMappa.php") // aggiorna il path se serve
        .then(r => r.json())
        .then(luoghi => {

            if (!luoghi || luoghi.length === 0) {
                lista.innerHTML = "<p>Nessun evento disponibile</p>";
                return;
            }

            // CENTRO MAPPA: primo luogo con coordinate valide
            let primoLuogo = luoghi.find(l => l.coordinate[0] !== 0 && l.coordinate[1] !== 0) || { coordinate: [44.4949, 11.3426] };
            const map = L.map("mappaEventi").setView(primoLuogo.coordinate, 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            // Funzione per generare HTML degli eventi di un solo luogo
            function renderEventiLuogo(luogo) {
                let eventiHTML = '';
                luogo.eventi.forEach(evento => {
                    eventiHTML += `
                        <div class="d-flex mt-2 mx-3 mb-3">
                            <span class="line"></span>
                        </div>
                        <h6 class="fs-6 ms-3 mb-1">${evento.titolo}</h6>
                        <div class="ms-3 d-flex align-items-center mb-2">
                            <img src="../img/BlackCalendar.png" alt="Calendario" class="imageForInformation">
                            <p class="SizeForInformation mb-0 ms-2">${evento.data}</p>
                            <img src="../img/blackClock.png" alt="Orologio" class="imageForInformation ms-3">
                            <p class="SizeForInformation mb-0 ms-2">${evento.ora}</p>
                        </div>
                    `;
                });

                const borderClass = luogo.eventi.length > 0 ? 'redBorder' : 'whiteBorder';

                // Blocchi centrati orizzontalmente
                lista.innerHTML = `
                    <div class="d-flex justify-content-center w-100">
                        <div class="${borderClass} maxWidthScaling mb-3 w-100 pb-2" style="max-width:800px;">
                            <div class="d-flex align-items-center pt-3">
                                <img src="../img/imagePosition.png" alt="immagine di una posizione" class="imageForForm ms-3">
                                <h5 class="mb-0 ms-2">${luogo.nome}</h5>
                            </div>
                            <p class="SizeForInformation ms-5 mt-1 mb-0">${luogo.indirizzo}</p>
                            <span class="SizeForInformation ms-5 mt-1 eventLabel px-2 py-2 d-inline-block mb-2">${luogo.eventi.length} eventi</span>
                            ${eventiHTML}
                        </div>
                    </div>
                `;
            }

            // Aggiungi marker sulla mappa e gestione click
            luoghi.forEach(luogo => {
                const lat = luogo.coordinate[0];
                const lng = luogo.coordinate[1];
                if (lat === 0 && lng === 0) return; // salta marker non geocodificati

                const marker = L.marker([lat, lng]).addTo(map);
                marker.bindPopup(`<strong>${luogo.nome}</strong><br>${luogo.indirizzo}`);

                marker.on("click", () => renderEventiLuogo(luogo));
            });

            // Mostra primo luogo valido all'apertura
            const primoValido = luoghi.find(l => l.coordinate[0] !== 0 && l.coordinate[1] !== 0) || luoghi[0];
            renderEventiLuogo(primoValido);

        })
        .catch(err => {
            console.error("Errore fetch:", err);
            lista.innerHTML = "<p>Errore nel caricamento degli eventi</p>";
        });

});

</script>


</body>
</html>
