<?php
// Verifica se l'utente è loggato
$utenteLoggato = isset($_SESSION['matricola']) && !empty($_SESSION['matricola']);
$matricolaUtente = $utenteLoggato ? $_SESSION['matricola'] : null;
?>

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
                    <p class="SizeForDescription mb-2">
                        👥 <?php echo $evento["Partecipanti_Attuali"]; ?>
                        <?php if ($evento["Max_Partecipanti"]): ?>
                            / <?php echo $evento["Max_Partecipanti"]; ?>
                        <?php endif; ?>
                        partecipanti
                    </p>
                    
                    <?php if($utenteLoggato): ?>
                        <!-- Sezione per utenti loggati -->
                        <?php 
                        $amiciPartecipanti = $dbh->getAmiciPartecipanti($evento["Id"], $matricolaUtente);
                        if (count($amiciPartecipanti) > 0): 
                        ?>
                            <p class="SizeForDescription">
                                Amici che partecipano:
                                <strong>
                                    <?php 
                                    $nomiAmici = array_map(function($amico) {
                                        return htmlspecialchars($amico['nome']);
                                    }, $amiciPartecipanti);
                                    echo implode(', ', $nomiAmici);
                                    ?>
                                </strong>
                            </p>
                        <?php endif; ?>
                        
                        <?php 
                        $utentePartecipa = $dbh->verificaPartecipazioneUtente($evento["Id"], $matricolaUtente);
                        $eventoCompleto = $evento["Max_Partecipanti"] && $evento["Partecipanti_Attuali"] >= $evento["Max_Partecipanti"];
                        ?>
                        
                        <div class="d-flex gap-2">
                            <?php if (isset($activitiesPage) && $vistaAttiva === 'published'): ?>
                                <button type="button" class="report-button mt-2 border-0 px-3 py-2" 
                                        data-bs-toggle="modal" data-bs-target="#exampleModal"
                                        data-evento-id="<?php echo $evento['Id']; ?>">
                                    ⚠️ Segnala un problema
                                </button>
                            <?php else: ?>
                                <?php if ($utentePartecipa): ?>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="evento_id" value="<?php echo $evento["Id"]; ?>">
                                        <input type="hidden" name="azione" value="annulla">
                                        <button type="submit" class="btn-secondary-custom">Annulla partecipazione</button>
                                    </form>
                                <?php elseif ($eventoCompleto): ?>
                                    <button class="btn-secondary-custom" disabled style="opacity: 0.5;">Evento completo</button>
                                <?php else: ?>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="evento_id" value="<?php echo $evento["Id"]; ?>">
                                        <input type="hidden" name="azione" value="partecipa">
                                        <button type="submit" class="btn-primary-custom">Partecipa</button>
                                    </form>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
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