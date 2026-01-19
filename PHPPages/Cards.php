<?php foreach($eventiPerCitta as $citta => $eventi): ?>
<div class="mb-3 w-100 maxWidthScaling">
    <div class="event-container">
        <?php foreach($eventi as $index => $evento): ?>
        <div class="<?php echo $index === 0 ? 'active' : 'd-none' ?>" data-index="<?php echo $index ?>">
            <div class="event-card mb-4">
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
                    <h4 class="textprimary fw-bold"><?php echo $evento["Titolo"] ?></h4>
                    <p class="SizeForDescription">Creato da: <?php echo $evento["nome"] ?> (Mat. <?php echo $evento["matricola"] ?>)</p>
                    <p class="SizeForInformation mb-1">🕓 <?php echo formattaOrario($evento["Orario"]) ?>, <?php echo formattaDataItaliana($evento["Data"]) ?></p>
                    <p class="SizeForInformation mb-1">📍 <?php echo $evento["Luogo"] ?>, <?php echo $evento["Indirizzo"] ?></p>
                    <p class="SizeForInformation mb-1">🎓 <?php echo $evento["Descrizione"] ?></p>
                    <p class="SizeForInformation mb-2">👥 <?php echo $evento["Partecipanti_Attuali"] ?> partecipanti</p>
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