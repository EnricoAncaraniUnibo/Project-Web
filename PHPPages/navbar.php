<nav class="navbar navbar-expand navbar-layout">
        <div class="container-lg d-flex">
            <a class="navbar-brand navbar-title" href="PHPPages/homepageUser.php">Uni Events</a>
            <ul class="navbar-nav flex-row justify-content-around w-75">
                <li class="nav-item">
                    <a href="PHPPages/homepageUser.php" class="navbar-button navbar-button-active">
                        <img src="../img/home_icon.svg" alt="Home" class="small-icon"/>
                        <span class="d-none d-sm-inline ms-2">Home</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="PHPPages/mappa.php" class="navbar-button">
                        <img src="../img/map_icon.svg" alt="Map" class="small-icon"/>
                        <span class="d-none d-sm-inline ms-2">Mappa</span>
                    </a>
                </li>
                <li class="nav-item d-flex">
                    <a href="#" class="navbar-button">
                        <img src="../img/search_icon.svg" alt="Search" class="small-icon"/>
                        <span class="d-none d-sm-inline ms-2">Cerca</span>
                    </a>
                    <input type="text" class="d-none d-lg-inline ms-2 searchbar" placeholder="Cerca eventi..."/>
                </li>
                <li class="nav-item dropdown">
                    <button href="#" class="navbar-button dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="../img/user_icon.svg" alt="User" class="small-icon"/>
                        <span class="d-none d-sm-inline ms-2">Profilo</span>
                    </button>
                    <div class="dropdown-menu">
                        <div class="dropdown-header">    
                            <span>Matricola</span>
                            <!--Check if h6 is the right tag-->
                            <h6>1122</h6>
                        </div>
                        <hr class="dropdown-divider">
                        <ul class="list-unstyled">
                            <li class="d-flex dropdown-item align align-items-center">
                                <img src="../img/activity_icon.svg" alt="Le mie attività" class="small-icon"/>
                                <a class="ms-2 dropdown-link" href="PHPPages/mieAttivita.php">Le mie attività</a>
                            </li>
                            <li class="d-flex dropdown-item align align-items-center">
                                <img src="../img/profile_icon.svg" alt="Mio profilo" class="small-icon"/>
                                <a class="ms-2 dropdown-link" href="#">Mio profilo</a>
                            </li>
                            <li class="d-flex dropdown-item align align-items-center">
                                <img src="../img/create_icon.svg" alt="Crea evento" class="small-icon"/>
<<<<<<< HEAD
                                <a class="ms-2 dropdown-link" href="PHPPages/creaEvento.php">Crea evento</a>
=======
                                <a class="ms-2 dropdown-link" href="creaEvento.php">Crea evento</a>
>>>>>>> ed0b23afa93641260a0cf99710fb3c6baaab3a71
                            </li>
                            <li class="d-flex dropdown-item align align-items-center">
                                <img src="../img/dashboard_icon.svg" alt="Bacheca" class="small-icon"/>
                                <a class="ms-2 dropdown-link" href="PHPPages/bachecaAdmin.php">Bacheca</a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li class="d-flex dropdown-item align align-items-center">
                                <img src="../img/logout_icon.svg" alt="Logout" class="small-icon"/>
                                <button class="ms-2 dropdown-link text-danger" id="logout-button">Logout</button>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </nav>