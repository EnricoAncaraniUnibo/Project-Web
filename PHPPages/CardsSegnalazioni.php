<?php
// Verifica se l'utente è loggato
$utenteLoggato = isset($_SESSION['matricola']) && !empty($_SESSION['matricola']);
$matricolaUtente = $utenteLoggato ? $_SESSION['matricola'] : null;
$current_page = basename($_SERVER['PHP_SELF']);

$sezione_attiva = $_GET['sezione'] ?? 'segnalazioni';
?>

<?php foreach($eventiPerCitta as $citta => $eventi): ?>
<div class="mb-3 w-100 maxWidthScaling">
    <div class="event-container">
        <?php foreach($eventi as $index => $evento): ?>
        <div class="<?php echo $index === 0 ? 'active' : 'd-none' ?>" data-index="<?php echo $index ?>">
            <div class="event-card">
                <div class="event-header d-flex justify-content-between">
                    <div class="event-header d-flex align-items-center">
                        <img src="../img/positionHeader.png" alt="luogo" class="imageForForm me-2">
                        <span class="fw-bold"><?php echo $evento["Città"] ?></span>
                        <?php if(count($eventi) > 1): ?>
                        <div class="badge bg-secondary ms-3">
                            <span class="current-index">1</span> di <span class="total-count"><?php echo count($eventi) ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="px-3 py-3">
                    <div class="d-flex align-items-center gap-2">
                        <img src="../img/AlertImage.png" alt="immagine di allerta" class="imageForForm">
                        <p class="SizeForDescription mb-0">Segnalato da <?php echo $evento["nome"] ?> (Mat. <?php echo $evento["matricola"] ?>)</p>
                    </div>
                    <div class="warningBox px-2 py-2 my-3 ms-4">
                        <p class="mb-0"><?php echo $evento["DescrizioneSegnalazione"] ?? $evento["Descrizione"] ?></p>
                    </div>
                    <div class="d-flex">
                        <span class="line"></span>
                    </div>
                    <h4 class="fw-bold mt-3"><?php echo $evento["Titolo"] ?></h4>
                    <p class="SizeForInformation mb-1">🕓 <?php echo formattaOrario($evento["Orario"]) ?>, <?php echo formattaDataItaliana($evento["Data"]) ?> </p>
                    <p class="SizeForInformation mb-1">📍 <?php echo $evento["Luogo"] ?>, <?php echo $evento["Indirizzo"] ?></p>
                    <p class="SizeForInformation mb-1">🎓 <?php echo $evento["Descrizione"] ?></p>
                    
                    <!-- Mostra il numero di partecipanti -->
                    <p class="SizeForDescription mb-2">
                        👥 <?php echo $evento["Partecipanti_Attuali"] ?? 0; ?>
                        <?php if (isset($evento["Max_Partecipanti"]) && $evento["Max_Partecipanti"]): ?>
                            / <?php echo $evento["Max_Partecipanti"]; ?>
                        <?php endif; ?>
                        partecipanti
                    </p>
                    
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="evento_id" value="<?php echo $evento["Id"]; ?>">
                        <input type="hidden" name="azione" value="modifica">
                        <input type="hidden" name="sezione" value="<?php echo $sezione_attiva; ?>">
                        <button type="submit" class="buttonModify border-0 px-3 py-2 mb-2">📝 Modifica evento</button>
                    </form>
                    
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="evento_id" value="<?php echo $evento["Id"]; ?>">
                        <input type="hidden" name="azione" value="risolto">
                        <input type="hidden" name="sezione" value="<?php echo $sezione_attiva; ?>">
                        <button type="submit" class="mt-2 buttonApproves border-0 px-3 py-2">✔ Risolto</button>
                    </form>
                    
                    <form method="POST" style="display: inline;" onsubmit="return confirm('Sei sicuro di voler eliminare questo evento?');">
                        <input type="hidden" name="evento_id" value="<?php echo $evento["Id"]; ?>">
                        <input type="hidden" name="azione" value="elimina">
                        <input type="hidden" name="sezione" value="<?php echo $sezione_attiva; ?>">
                        <button type="submit" class="buttonErase border-0 px-3 py-2">🗑️ Elimina Evento</button>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        
        <?php if(count($eventi) > 1): ?>
        <button class="carousel-btn prev" data-citta="<?php echo $citta ?>" disabled>‹</button>
        <button class="carousel-btn next" data-citta="<?php echo $citta ?>">›</button>
        <?php endif; ?>
    </div>
</div>
<?php endforeach; ?>

<script src="../JS/showCards.js"></script>