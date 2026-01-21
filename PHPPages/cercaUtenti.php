<?php
require_once("../PHPUtilities/bootstrap.php");

if (!isset($_SESSION['matricola'])) {
    header('Location: login.php');
    exit();
}

$host = 'localhost';
$port = '3307';
$dbname = 'gestionale_eventi';
$username = 'root';
$password = '';

$messaggio = '';
$tipo_messaggio = '';
$utente_corrente = null;
$utenti = [];
$search_query = '';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $matricola_corrente = $_SESSION['matricola'];
    $utente_corrente = $dbh->getUserByMatricola($matricola_corrente);
    
    // Usa la matricola dal database per essere sicuri
    $matricola_corrente = $utente_corrente['matricola'];
    
    if (!$utente_corrente) {
        session_destroy();
        header('Location: index.php');
        exit();
    }
    
    // Gestione follow/unfollow
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $azione = $_POST['azione'] ?? '';
        $matricola_target = $_POST['matricola_target'] ?? '';
        
        if ($azione === 'follow' && !empty($matricola_target)) {
            // Previeni di seguire se stessi
            if ($matricola_target != $matricola_corrente) {
                $sql_follow = "INSERT IGNORE INTO Segue (seguitore_matricola, seguito_matricola) VALUES (:seguitore, :seguito)";
                $stmt_follow = $pdo->prepare($sql_follow);
                $stmt_follow->execute([':seguitore' => $matricola_corrente, ':seguito' => $matricola_target]);
                $messaggio = 'Ora segui questo utente!';
                $tipo_messaggio = 'success';
            }
        } elseif ($azione === 'unfollow' && !empty($matricola_target)) {
            $sql_unfollow = "DELETE FROM Segue WHERE seguitore_matricola = :seguitore AND seguito_matricola = :seguito";
            $stmt_unfollow = $pdo->prepare($sql_unfollow);
            $stmt_unfollow->execute([':seguitore' => $matricola_corrente, ':seguito' => $matricola_target]);
            $messaggio = 'Non segui più questo utente';
            $tipo_messaggio = 'success';
        }
    }
    
    // Gestione ricerca per nome o matricola
    $search_query = trim($_GET['inputNomeRicerca'] ?? '');

    if (!empty($search_query)) {
        $sql_search = "SELECT U.matricola, U.nome, U.email,
                       EXISTS(
                           SELECT 1 FROM Segue 
                           WHERE seguitore_matricola = :matricola_corrente 
                           AND seguito_matricola = U.matricola
                       ) as is_following
                       FROM UTENTE U
                       WHERE U.matricola != :matricola_corrente
                       AND (U.nome LIKE :inputNomeRicerca OR U.matricola LIKE :inputNomeRicerca)
                       ORDER BY is_following DESC, U.nome ASC
                       LIMIT 50";
        
        $stmt_search = $pdo->prepare($sql_search);
        $search_param = '%' . $search_query . '%';
        $stmt_search->execute([
            ':matricola_corrente' => $matricola_corrente,
            ':inputNomeRicerca' => $search_param
        ]);
        $utenti = $stmt_search->fetchAll(PDO::FETCH_ASSOC);
    } else {
        // Mostra tutti gli utenti (escluso l'utente corrente)
        $sql_all = "SELECT U.matricola, U.nome, U.email,
                    EXISTS(
                        SELECT 1 FROM Segue 
                        WHERE seguitore_matricola = :matricola_corrente 
                        AND seguito_matricola = U.matricola
                    ) as is_following
                    FROM UTENTE U
                    WHERE U.matricola != :matricola_corrente
                    ORDER BY is_following DESC, U.nome ASC
                    LIMIT 50";
        
        $stmt_all = $pdo->prepare($sql_all);
        $stmt_all->execute([':matricola_corrente' => $matricola_corrente]);
        $utenti = $stmt_all->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Filtra ulteriormente per rimuovere l'utente corrente (per sicurezza)
    $utenti = array_filter($utenti, function($utente) use ($matricola_corrente) {
        return $utente['matricola'] != $matricola_corrente;
    });
    
} catch (PDOException $e) {
    $messaggio = 'Errore database: ' . $e->getMessage();
    $tipo_messaggio = 'error';
    $utente_corrente = ['matricola' => 'N/A', 'nome' => '', 'email' => 'N/A'];
} catch (Exception $e) {
    $messaggio = $e->getMessage();
    $tipo_messaggio = 'error';
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cerca utenti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/stylesVariables.css">
    <link rel="stylesheet" href="../css/stylesEMME.css">
    <link rel="stylesheet" href="../css/navbar.css">
</head>

<body class="body font">
    <div class="page-content">
    <?php require 'navbar.php'; ?>

    <div class="d-flex flex-column container py-4 maxWidthScaling">
        <?php if (!empty($messaggio)): ?>
        <div class="alert <?php echo $tipo_messaggio === 'success' ? 'alert-success' : 'alert-danger'; ?> alert-dismissible fade show text-center mt-1" role="alert">
            <?php echo htmlspecialchars($messaggio); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <div class="profile-header mb-1">
            <h2 class="textsecondary fw-bold fs-1">Il mio profilo</h2>
            <p class="SizeForDescription mb-0">Username: <?php echo htmlspecialchars($utente_corrente['nome']); ?></p>
            <p class="SizeForDescription mb-0">Matricola: <?php echo htmlspecialchars($utente_corrente['matricola']); ?></p>
            <p class="SizeForDescription mb-0">Email: <?php echo htmlspecialchars($utente_corrente['email']); ?></p>
            <?php if ($utente_corrente['ruolo'] === 'admin'): ?>
                <p class="SizeForDescription mb-0"><span class="badge bg-primary">Amministratore</span></p>
            <?php endif; ?>
        </div>

        <div class="d-flex gap-2 mb-4">
            <a href="modificaProfilo.php" class="btn-active btn-disabled flex-fill border-0 text-decoration-none d-flex align-items-center justify-content-center " style="cursor: pointer;">Modifica profilo</a>
            <span class="buttonSelected flex-fill border-0 text-decoration-none d-flex align-items-center justify-content-center">Cerca utenti</span>
        </div>

        <form method="GET" class="search-container mb-4">
            <label for="inputPersona" class="visually-hidden">Input per cercare le persone</label>
            <input type="text" id="inputPersona" name="inputNomeRicerca" class="form-control inputForForm backgroundGrey border-0" 
                   placeholder="Cerca per nome o matricola..." 
                   value="<?php echo htmlspecialchars($search_query); ?>">
            <strong class="bi bi-search search-icon"></strong>
        </form>
        
        <div class="users-list">
            <?php if (empty($utenti)): ?>
                <div class="event-card p-4 text-center">
                    <p class="defaultTextColor mb-0">
                        <?php echo !empty($search_query) ? 'Nessun utente trovato per la ricerca' : 'Nessun utente disponibile'; ?>
                    </p>
                </div>
            <?php else: ?>
                <?php foreach ($utenti as $utente): ?>
                    <div class="user-card d-flex align-items-center justify-content-between mb-3 p-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="user-avatar">
                                <?php echo strtoupper(substr($utente['nome'], 0, 1)); ?>
                            </div>
                            <div>
                                <p class="user-name mb-0 fw-bold"><?php echo htmlspecialchars($utente['nome']); ?></p>
                                <p class="user-matricola mb-0">Mat. <?php echo htmlspecialchars($utente['matricola']); ?></p>
                            </div>
                        </div>
                        
                        <form method="POST" style="margin: 0;">
                            <input type="hidden" name="matricola_target" value="<?php echo htmlspecialchars($utente['matricola']); ?>">
                            
                            <?php if ($utente['is_following']): ?>
                                <input type="hidden" name="azione" value="unfollow">
                                <button type="submit" class="btn-follow-light">
                                    <strong class="bi bi-person-check-fill"></strong> Seguito
                                </button>
                            <?php else: ?>
                                <input type="hidden" name="azione" value="follow">
                                <button type="submit" class="btn-unfollow">
                                    <strong class="bi bi-person-plus-fill"></strong> Segui
                                </button>
                            <?php endif; ?>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php require 'footer.php'; ?>
    <script src="cercaUtenti.js"></script>
    <script src="../JS/navbar.js"></script>
    <script>
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alertEl => {
            const alert = bootstrap.Alert.getOrCreateInstance(alertEl);
            alert.close();
        });
    }, 4000);
</script>
</body>
</html>