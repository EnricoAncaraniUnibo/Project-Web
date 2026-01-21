<?php

require_once("../PHPUtilities/bootstrap.php");

if (!isset($_SESSION['matricola'])) {
    header('Location: index.php');
    exit();
}

$host = 'localhost';
$port = '3307';
$dbname = 'gestionale_eventi';
$username = 'root';
$password = '';

$messaggio = '';
$tipo_messaggio = ''; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $titolo = trim($_POST['titolo'] ?? '');
        $descrizione = trim($_POST['descrizione'] ?? '');
        $data = $_POST['data'] ?? '';
        $orario = $_POST['orario'] ?? '';
        $luogo = trim($_POST['luogo'] ?? '');
        $indirizzo = trim($_POST['indirizzo'] ?? '');
        $citta = trim($_POST['citta'] ?? '');
        $Nmax = trim($_POST['Nmax'] ?? '');
        $matricola_creatore = $_SESSION['matricola'];
        if (empty($titolo) || empty($descrizione) || empty($data) || empty($orario) || 
            empty($luogo) || empty($indirizzo) || empty($citta) || empty($Nmax)) {
            throw new Exception('Tutti i campi obbligatori devono essere compilati');
        }
        $Nmax_int = (int)$Nmax;
        if($Nmax_int<=0 || !is_int($Nmax_int)) {
            throw new Exception('Il numero di partecipanti non è valido');
        }
        
        $data_evento = new DateTime($data);
        $oggi = new DateTime();
        $oggi->setTime(0, 0, 0);
        
        if ($data_evento < $oggi) {
            throw new Exception('La data dell\'evento non può essere nel passato');
        }
        
        $sql = "INSERT INTO EVENTO (matricola_creatore, Titolo, Descrizione, Data, Orario, Luogo, Indirizzo, Città, Stato, Max_Partecipanti) VALUES (
                    :matricola_creatore, :titolo, :descrizione, :data, :orario, :luogo, :indirizzo, :citta, 'in sospeso', :Nmax)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':matricola_creatore' => $matricola_creatore,
            ':titolo' => $titolo,
            ':descrizione' => $descrizione,
            ':data' => $data,
            ':orario' => $orario,
            ':luogo' => $luogo,
            ':indirizzo' => $indirizzo,
            ':citta' => $citta,
            'Nmax' => $Nmax
        ]);
        
        $messaggio = 'Evento creato con successo! In attesa di approvazione.';
        $tipo_messaggio = 'success';

        header('Refresh: 4; URL=creaEvento.php');
        
    } catch (PDOException $e) {
        $messaggio = 'Errore database: ' . $e->getMessage();
        $tipo_messaggio = 'error';
    } catch (Exception $e) {
        $messaggio = $e->getMessage();
        $tipo_messaggio = 'error';
    }
}
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Crea il tuo evento</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" href="../css/stylesVariables.css">
        <link rel="stylesheet" href="../css/stylesEMME.css">
        <link rel="stylesheet" href="../css/navbar.css">
        
    </head>
    <body class="body font">
        <div class="page-content">
        <?php require 'navbar.php'; ?>
        <?php if (!empty($messaggio)): ?>
            <div class="container mt-3">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-8 col-lg-6">
                        <div class="alert <?php echo $tipo_messaggio === 'success' ? 'alert-success' : 'alert-danger'; ?> alert-compact alert-dismissible fade show text-center" role="alert">
                            <?php echo htmlspecialchars($messaggio); ?>
                            <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <header>
            <div>
                <h3 class="textsecondary mt-5 text-center fw-bold">Crea un nuovo evento</h3>
                <p class="text-center fs-7 mb-0  defaultTextColor">Compila il form per richiedere la pubblicazione di un evento sulla piattaforma</p>
                <p class="text-center fs-7  defaultTextColor">di Uni Events</p>
            </div>
        </header>
        <main class="d-flex justify-content-center">
            <form class="bg-white paddingx4 form mt-2" method="POST">
                <div class="d-flex pt-4 pb-2">
                    <img src="../img/title.png" alt="" class="imageForForm">
                    <h3 class="ms-2 fs-6 mb-0 defaultTextColor">Titolo dell'evento *</h3>
                </div>
                <label for="inputTitolo" class="visually-hidden">Input per titolo dell'evento</label>
                <input type="text" id="inputTitolo" name="titolo" placeholder="Es. Workshop su AI e Machine Learning" class="inputForForm w-100" required>
                
                <div class="d-flex pt-4 pb-2">
                    <h3 class="ms-3 fs-6 mb-0 defaultTextColor">Descrizione *</h3>
                </div>
                <label for="inputDescrizione" class="visually-hidden">Input per la descrizione dell'evento</label>
                <textarea name="descrizione" id="inputDescrizione" placeholder="Descrivi l'evento, cosa verrà trattato, chi può partecipare..." class="inputForForm w-100" rows="4" required></textarea>
                
                <div class="d-flex pt-4 pb-2">
                    <img src="../img/calendary.png" alt="" class="imageForForm">
                    <h3 class="ms-3 fs-6 mb-0 defaultTextColor">Data *</h3>
                </div>
                <label for="inputData" class="visually-hidden">Input per la data dell'evento</label>
                <input type="date" id="inputData" name="data" class="inputForForm w-100" required>
                
                <div class="d-flex pt-4 pb-2">
                    <img src="../img/time.png" alt="" class="imageForForm">
                    <h3 class="ms-3 fs-6 mb-0 defaultTextColor">Orario *</h3>
                </div>
                <label for="inputTime" class="visually-hidden">Input per l'orario dell'evento</label>
                <input type="time" id="inputTime" name="orario" class="inputForForm w-100" required>
                
                <div class="d-flex pt-4 pb-2">
                    <img src="../img/position.png" alt="" class="imageForForm">
                    <h3 class="ms-3 fs-6 mb-0 defaultTextColor">Nome del luogo *</h3>
                </div>
                <label for="inputLuogo" class="visually-hidden">Input per il luogo dell'evento</label>
                <input type="text" id="inputLuogo" name="luogo" placeholder="Es. Aula Magna" class="inputForForm w-100" required>
                
                <div class="d-flex pt-4 pb-2">
                    <h3 class="ms-3 fs-6 mb-0 defaultTextColor">Indirizzo *</h3>
                </div>
                <label for="inputIndirizzo" class="visually-hidden">Input per l'indirizzo dell'evento</label>
                <input type="text" id="inputIndirizzo" name="indirizzo" placeholder="Es. Via dell'Università, 10, Bologna" class="inputForForm w-100" required>
                
                <div class="d-flex pt-4 pb-2">
                    <h3 class="ms-3 fs-6 mb-0 defaultTextColor">Città *</h3>
                </div>
                <label for="inputCittà" class="visually-hidden">Input per la città dell'evento</label>
                <input type="text" id="inputCittà" name="citta" placeholder="Es. Bologna" class="inputForForm w-100" required>

                <div class="d-flex pt-4 pb-2">
                    <h3 class="ms-3 fs-6 mb-0 defaultTextColor">Numero massimo di partecipanti *</h3>
                </div>
                <label for="inputNMax" class="visually-hidden">Input per il numero massimo di partecipanti dell'evento</label>
                <input type="text" id="inputNMax" name="Nmax" placeholder="0" class="inputForForm w-100" required>
                
                <div class="d-flex gap-3 mt-4 w-100 mb-4">
                    <button type="submit" class="btn-publish flex-fill">
                        <span>Richiedi<br>Pubblicazione</span>
                    </button>
                    <button type="button" class="btn-cancel flex-fill" onclick="window.location.href='homepageUser.php'">
                        Annulla
                    </button>
                </div>
            </form>
        </main>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <?php require 'footer.php'; ?>
        <script src="../JS/navbar.js"></script>
    </body>
</html>