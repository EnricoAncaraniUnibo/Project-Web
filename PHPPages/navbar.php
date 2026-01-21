<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search'])) {
    $termine = $_GET['key'];
    header('Location: RicercaEvento.php?key=' . $termine);
    exit();
}

$current_page = basename($_SERVER['PHP_SELF']);
function isActive($page_name, $current_page) {
    return $page_name === $current_page ? 'navbar-button-active' : '';
}

$matricolaUtente = $_SESSION['matricola'];
$ruolo = $dbh->getRuoloByMatricola($matricolaUtente);
$isAdmin = ($ruolo === 'admin');
?>

<nav class="navbar navbar-expand navbar-layout">
    <div class="container-lg d-flex">
        <a class="navbar-brand navbar-title" href="homepageUser.php">Uni Events</a>
        <ul class="navbar-nav flex-row justify-content-around w-75">
            <li class="nav-item">
                <a href="homepageUser.php" class="navbar-button <?php echo isActive('homepageUser.php', $current_page); ?>">
                    <img src="../img/home_icon.svg" alt="Home" class="small-icon"/>
                    <span class="d-none d-sm-inline ms-2">Home</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="MappaEventi.php" class="navbar-button <?php echo isActive('MappaEventi.php', $current_page); ?>">
                    <img src="../img/map_icon.svg" alt="Map" class="small-icon"/>
                    <span class="d-none d-sm-inline ms-2">Mappa</span>
                </a>
            </li>
            <li class="nav-item d-flex">
                <form action="" method="GET" class="d-flex ">
                    <button name="search" class="bg navbar-button border-0 <?php echo isActive('RicercaEvento.php', $current_page); ?>">
                        <img src="../img/search_icon.svg" alt="Search" class="small-icon"/>
                        <span class="d-none d-sm-inline ms-2">Cerca</span>
                    </button>
                    <label for="search-key" class="visually-hidden">Input per cercare gli eventi</label>
                    <input type="text" id="search-key" name="key" class="d-none d-lg-inline ms-2 searchbar" placeholder="Cerca eventi..."/>
                </form>
            </li>
            <li class="nav-item dropdown">
                <button type="button" class="navbar-button dropdown-toggle <?php echo in_array($current_page, ['mieAttivita.php', 'creaEvento.php', 'bachecaAdmin.php','modificaProfilo.php','cercaUtenti.php', 'modificaEvento.php']) ? 'navbar-button-active' : ''; ?>" 
                        data-bs-toggle="dropdown" 
                        aria-expanded="false"
                        aria-haspopup="true">
                    <img src="../img/user_icon.svg" alt="User" class="small-icon"/>
                    <span class="d-none d-sm-inline ms-2">Profilo</span>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <div class="dropdown-header">    
                        <span>Matricola</span>
                        <h6><?php echo $_SESSION['matricola']; ?></h6>
                    </div>
                    <hr class="dropdown-divider">
                    <ul class="list-unstyled">
                        <li class="d-flex dropdown-item align-items-center">
                            <img src="../img/activity_icon.svg" alt="Le mie attività" class="small-icon"/>
                            <a class="ms-2 dropdown-link <?php echo $current_page === 'mieAttivita.php' ? 'active' : ''; ?>" href="mieAttivita.php">Le mie attività</a>
                        </li>
                        <li class="d-flex dropdown-item align-items-center">
                            <img src="../img/profile_icon.svg" alt="Mio profilo" class="small-icon"/>
                            <a class="ms-2 dropdown-link <?php echo $current_page === 'modificaProfilo.php' ? 'active' : ''; ?>" href="modificaProfilo.php">Mio profilo</a>
                        </li>
                        <li class="d-flex dropdown-item align-items-center">
                            <img src="../img/create_icon.svg" alt="Crea evento" class="small-icon"/>
                            <a class="ms-2 dropdown-link <?php echo $current_page === 'creaEvento.php' ? 'active' : ''; ?>" href="creaEvento.php">Crea evento</a>
                        </li>
                        <?php if ($isAdmin): ?>
                        <li class="d-flex dropdown-item align-items-center">
                            <img src="../img/dashboard_icon.svg" alt="Bacheca" class="small-icon"/>
                            <a class="ms-2 dropdown-link <?php echo $current_page === 'bachecaAdmin.php' ? 'active' : ''; ?>" href="bachecaAdmin.php">Bacheca</a>
                        </li>
                        <?php endif; ?>
                        <li><hr class="dropdown-divider"></li>
                        <li class="d-flex dropdown-item align-items-center">
                            <img src="../img/logout_icon.svg" alt="Logout" class="small-icon"/>
                            <button class="ms-2 dropdown-link text-danger border-0 bg-transparent p-0" id="logout-button">Logout</button>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</nav>