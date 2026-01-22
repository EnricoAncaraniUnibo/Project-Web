<?php
require_once("../PHPUtilities/bootstrap.php");
redirectToLoginIfUserNotLoggedIn();
$matricolaUtente = $_SESSION['matricola'];
$ruolo = $dbh->getRuoloByMatricola($matricolaUtente);
$isAdmin = ($ruolo === 'admin');

if (!$isAdmin) {
    header("Location: index.php");
    exit;
}

$evento_id = $_GET['id'] ?? null;

if (!$evento_id) {
    header("Location: bachecaAdmin.php");
    exit;
}

$evento = $dbh->getEventoById($evento_id);

if (!$evento) {
    header("Location: bachecaAdmin.php");
    exit;
}

$numero_partecipanti_attuali = $dbh->getNumeroPartecipantiByEventoId($evento_id);

$errori = [];
$successo = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titolo = $_POST['titolo'] ?? '';
    $descrizione = $_POST['descrizione'] ?? '';
    $data = $_POST['data'] ?? '';
    $orario = $_POST['orario'] ?? '';
    $luogo = $_POST['luogo'] ?? '';
    $indirizzo = $_POST['indirizzo'] ?? '';
    $citta = $_POST['citta'] ?? '';
    $max_partecipanti = $_POST['max_partecipanti'] ?? null;
    
    try {
        if (empty($titolo) || empty($descrizione) || empty($data) || empty($orario) || 
            empty($luogo) || empty($indirizzo) || empty($citta)) {
            throw new Exception('Tutti i campi obbligatori devono essere compilati');
        }
        
        if (!empty($max_partecipanti)) {
            $max_partecipanti_int = (int)$max_partecipanti;
            
            if($max_partecipanti_int <= 0) {
                throw new Exception('Il numero di partecipanti deve essere maggiore di 0');
            }
            
            if($max_partecipanti_int < $numero_partecipanti_attuali) {
                throw new Exception('Il numero massimo di partecipanti (' . $max_partecipanti_int . 
                                  ') non può essere inferiore al numero attuale di partecipanti (' . 
                                  $numero_partecipanti_attuali . ')');
            }
        }
        
        $data_evento = new DateTime($data);
        $oggi = new DateTime();
        $oggi->setTime(0, 0, 0);
        
        if ($data_evento < $oggi) {
            throw new Exception('La data dell\'evento non può essere nel passato');
        }
        
        $dati = [
            'titolo' => $titolo,
            'descrizione' => $descrizione,
            'data' => $data,
            'orario' => $orario,
            'luogo' => $luogo,
            'indirizzo' => $indirizzo,
            'citta' => $citta,
            'max_partecipanti' => !empty($max_partecipanti) ? (int)$max_partecipanti : null
        ];
        
        $result = $dbh->updateEvento($evento_id, $dati);
        
        if ($result) {
            $successo = true;
            $evento = $dbh->getEventoById($evento_id);
        } else {
            $errori[] = 'Errore durante l\'aggiornamento dell\'evento';
        }
        
    } catch (Exception $e) {
        $errori[] = $e->getMessage();
    }

    header('Refresh: 1; URL=bachecaAdmin.php?sezione=segnalazioni');
}
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ModificaEvento</title>
        <link rel="stylesheet" href="../css/stylesVariables.css">
        <link rel="stylesheet" href="../css/stylesEMME.css">
        <link rel="stylesheet" href="../css/navbar.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="body font">
        <div class="page-content">
        <header>
            <?php require 'navbar.php'; ?>
        </header>
        <main class="d-flex flex-column justify-content-center align-items-center">
            <div>
                <h3 class="textsecondary mt-5 text-center fs-2 fw-bold">Modifica evento</h3>
                <p class="text-center fs-6 mb-0  defaultTextColor">Aggiorna i dati dell'evento in base</p>
                <p class="text-center fs-6  defaultTextColor">alla segnalazione</p>
            </div>
            
            <?php if ($successo): ?>
                <div class="alert alert-success alert-dismissible fade show mt-3" style="max-width: 500px;" role="alert">
                    Evento aggiornato con successo!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($errori)): ?>
                <div class="alert alert-danger alert-dismissible fade show mt-3" style="max-width: 500px;" role="alert">
                    <ul class="mb-0">
                        <?php foreach ($errori as $errore): ?>
                            <li><?php echo htmlspecialchars($errore); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <form method="POST" class="bg-white paddingx4 form mt-2">
                <div class="d-flex pt-4 pb-2">
                    <img src="../img/imageDocument.png" alt="immagine di un documento" class="imageForForm">
                    <h3 class="ms-3 fs-5 mb-0 defaultTextColor">Titolo dell'evento *</h3>
                </div>
                <input type="text" name="titolo" placeholder="Inserisci il titolo del tuo evento" 
                       class="inputForForm w-100" value="<?php echo htmlspecialchars($evento['Titolo']); ?>" required>
                
                <div class="d-flex pt-4 pb-2">
                    <h3 class="fs-5 mb-0 defaultTextColor">Descrizione *</h3>
                </div>
                <textarea name="descrizione" placeholder="Inserisci una descrizione" 
                          class="inputForForm w-100 pb-5" required><?php echo htmlspecialchars($evento['Descrizione']); ?></textarea>
                
                <div class="d-flex pt-4 pb-2">
                    <img src="../img/imageCalendar.png" alt="immagine di un calendario" class="imageForForm">
                    <h3 class="ms-3 fs-5 mb-0 defaultTextColor">Data *</h3>
                </div>
                <input type="date" name="data" placeholder="15/01/2026" 
                       class="inputForForm w-100" value="<?php echo htmlspecialchars($evento['Data']); ?>" required>
                
                <div class="d-flex pt-4 pb-2">
                    <img src="../img/imageClock.png" alt="immagine di un orologio" class="imageForForm">
                    <h3 class="ms-3 fs-5 mb-0 defaultTextColor">Orario *</h3>
                </div>
                <input type="time" name="orario" placeholder="15:00" 
                       class="inputForForm w-100" value="<?php echo htmlspecialchars($evento['Orario']); ?>" required>
                
                <div class="d-flex pt-4 pb-2">
                    <img src="../img/imagePosition.png" alt="immagine per indicare una posizione" class="imageForForm">
                    <h3 class="ms-3 fs-5 mb-0 defaultTextColor">Nome del luogo *</h3>
                </div>
                <input type="text" name="luogo" placeholder="Aula Magna" 
                       class="inputForForm w-100" value="<?php echo htmlspecialchars($evento['Luogo']); ?>" required>
                
                <div class="d-flex pt-4 pb-2">
                    <h3 class="fs-5 mb-0 defaultTextColor">Indirizzo *</h3>
                </div>
                <input type="text" name="indirizzo" placeholder="Via dell'università 20" 
                       class="inputForForm w-100" value="<?php echo htmlspecialchars($evento['Indirizzo']); ?>" required>
                
                <div class="d-flex pt-4 pb-2">
                    <h3 class="fs-5 mb-0 defaultTextColor">Città *</h3>
                </div>
                <input type="text" name="citta" placeholder="Bologna" 
                       class="inputForForm w-100" value="<?php echo htmlspecialchars($evento['Città']); ?>" required>
                
                <div class="d-flex pt-4 pb-2">
                    <h3 class="fs-5 mb-0 defaultTextColor">Numero massimo di partecipanti</h3>
                    <span class="ms-2 text-muted fs-6">(Attualmente: <?php echo $numero_partecipanti_attuali; ?> partecipanti)</span>
                </div>
                <input type="number" name="max_partecipanti" placeholder="Inserisci il numero massimo" 
                       class="inputForForm w-100" 
                       value="<?php echo htmlspecialchars($evento['Max_Partecipanti']); ?>" 
                       min="<?php echo max(1, $numero_partecipanti_attuali); ?>">
                
                <div class="d-flex mt-4">
                    <span class="line"></span>
                </div>
                <div class="d-flex flex-row gap-2">
                    <button type="submit" class="buttonPrimary mt-4 w-100 mb-4 border-0">📁 Salva modifiche</button>
                    <a href="bachecaAdmin.php" class="buttonErase mt-4 w-100 mb-4 border-0 text-decoration-none text-center d-flex align-items-center justify-content-center">Annulla</a>
                </div>
            </form>
        </main>
        </div>
        <footer><?php require 'footer.php'; ?></footer>
    </body>
    <script src="../JS/navbar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</html>