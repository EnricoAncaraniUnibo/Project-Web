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
    $sql_user = "SELECT matricola, nome, email FROM UTENTE WHERE matricola = :matricola";
    $stmt_user = $pdo->prepare($sql_user);
    $stmt_user->execute([':matricola' => $matricola_corrente]);
    $utente_corrente = $stmt_user->fetch(PDO::FETCH_ASSOC);
    
    if (!$utente_corrente) {
        session_destroy();
        header('Location: login.php');
        exit();
    }
    
    // Gestione follow/unfollow
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $azione = $_POST['azione'] ?? '';
        $matricola_target = $_POST['matricola_target'] ?? '';
        
        if ($azione === 'follow' && !empty($matricola_target)) {
            $sql_follow = "INSERT IGNORE INTO Segue (seguitore_matricola, seguito_matricola) VALUES (:seguitore, :seguito)";
            $stmt_follow = $pdo->prepare($sql_follow);
            $stmt_follow->execute([':seguitore' => $matricola_corrente, ':seguito' => $matricola_target]);
            $messaggio = 'Ora segui questo utente!';
            $tipo_messaggio = 'success';
        } elseif ($azione === 'unfollow' && !empty($matricola_target)) {
            $sql_unfollow = "DELETE FROM Segue WHERE seguitore_matricola = :seguitore AND seguito_matricola = :seguito";
            $stmt_unfollow = $pdo->prepare($sql_unfollow);
            $stmt_unfollow->execute([':seguitore' => $matricola_corrente, ':seguito' => $matricola_target]);
            $messaggio = 'Non segui più questo utente';
            $tipo_messaggio = 'success';
        }
    }
    
    // Gestione ricerca per nome o matricola
    $search_query = trim($_GET['search'] ?? '');

    if (!empty($search_query)) {
        $sql_search = "SELECT U.matricola, U.nome, U.email,
                       EXISTS(
                           SELECT 1 FROM Segue 
                           WHERE seguitore_matricola = :matricola_corrente 
                           AND seguito_matricola = U.matricola
                       ) as is_following
                       FROM UTENTE U
                       WHERE U.matricola != :matricola_corrente
                       AND (U.nome LIKE :search OR U.matricola LIKE :search)
                       ORDER BY U.nome ASC
                       LIMIT 50";
        
        $stmt_search = $pdo->prepare($sql_search);
        $search_param = '%' . $search_query . '%';
        $stmt_search->execute([
            ':matricola_corrente' => $matricola_corrente,
            ':search' => $search_param
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
                    ORDER BY U.nome ASC
                    LIMIT 50";
        
        $stmt_all = $pdo->prepare($sql_all);
        $stmt_all->execute([':matricola_corrente' => $matricola_corrente]);
        $utenti = $stmt_all->fetchAll(PDO::FETCH_ASSOC);
    }
    
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
    <link rel="stylesheet" href="../css/stylesVariables.css">
    <link rel="stylesheet" href="../css/stylesEMME.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

</head>
<body class="body font">
    <?php require 'navbar.php'; ?>
    <?php if (!empty($messaggio)): ?>
        <div class="alert <?php echo $tipo_messaggio === 'success' ? 'alert-success' : 'alert-danger'; ?> alert-custom alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($messaggio); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="container py-4 maxWidthScaling">
        <div class="profile-header mb-4">
            <h1 class="textsecondary fw-bold">Il mio profilo</h1>
            <p class="SizeForDescription mb-0">Username: <?php echo htmlspecialchars($utente_corrente['nome']); ?></p>
            <p class="SizeForDescription mb-0">Matricola: <?php echo htmlspecialchars($utente_corrente['matricola']); ?></p>
            <p class="SizeForDescription">Email: <?php echo htmlspecialchars($utente_corrente['email']); ?></p>
        </div>

        <div class="d-flex gap-2 mb-4">
            <a href="modificaProfilo.php" class="buttonPrimary flex-fill border-0 text-decoration-none d-flex align-items-center justify-content-center">Modifica profilo</a>
            <span class="btn-active btn-disabled flex-fill border-0 text-decoration-none d-flex align-items-center justify-content-center">Cerca utenti</span>
        </div>

        <form method="GET" action="" class="search-container mb-4">
            <input type="text" name="search" class="form-control inputForForm backgroundGrey border-0" 
                   placeholder="Cerca per nome o matricola..." 
                   value="<?php echo htmlspecialchars($search_query); ?>">
            <i class="bi bi-search search-icon"></i>
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
                        
                        <form method="POST" action="" style="margin: 0;">
                            <input type="hidden" name="matricola_target" value="<?php echo htmlspecialchars($utente['matricola']); ?>">
                            
                            <?php if ($utente['is_following']): ?>
                                <input type="hidden" name="azione" value="unfollow">
                                <button type="submit" class="btn-follow-light">
                                    <i class="bi bi-person-check-fill"></i> Seguito
                                </button>
                            <?php else: ?>
                                <input type="hidden" name="azione" value="follow">
                                <button type="submit" class="btn-unfollow">
                                    <i class="bi bi-person-plus-fill"></i> Segui
                                </button>
                            <?php endif; ?>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="cercaUtenti.js"></script>
    <script src="../JS/navbar.js"></script>
</body>
</html>