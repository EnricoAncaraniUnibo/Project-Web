<footer class="footer">
    <div class="footer-section">
        <h3 class="footer-title">Contatti Admin</h3>
        <p class="footer-subtitle">Il team di amministrazione di Uni Events</p>
        
        <div class="admin-contact" onclick="toggleAdmin(this)">
            <img src="../img/imageProfileFooter.png" alt="user" class="admin-icon">
            <div class="admin-info">
                <div class="admin-name">Enrico Ancarani</div>
                <div class="admin-role">Amministratore</div>
            </div>
            <span class="arrow">▼</span>
        </div>

        <div class="admin-expand" id="adminExpand">
            <p>Amministratore del sistema, responsabile della gestione dei contenuti e del supporto agli utenti.</p>
        </div>

        <div class="admin-contact" onclick="toggleAdmin(this)">
            <img src="../img/imageProfileFooter.png" alt="user" class="admin-icon">
            <div class="admin-info">
                <div class="admin-name">Andrea Monti</div>
                <div class="admin-role">Amministratore</div>
            </div>
            <span class="arrow">▼</span>
        </div>

        <div class="admin-expand" id="adminExpand">
            <p>Amministratore del sistema, responsabile della gestione dei contenuti e del supporto agli utenti.</p>
        </div>

        <div class="admin-contact" onclick="toggleAdmin(this)">
            <img src="../img/imageProfileFooter.png" alt="user" class="admin-icon">
            <div class="admin-info">
                <div class="admin-name">Davide Rossi</div>
                <div class="admin-role">Amministratore</div>
            </div>
            <span class="arrow">▼</span>
        </div>

        <div class="admin-expand" id="adminExpand">
            <p>Amministratore del sistema, responsabile della gestione dei contenuti e del supporto agli utenti.</p>
        </div>
    </div>
    
    <div class="footer-section">
        <h3 class="footer-title">Naviga il Sito</h3>
        <ul class="footer-links">
            <li><a href="homepageUser.php">Home / Eventi</a></li>
            <li><a href="mieAttivita.php">Le mie attività</a></li>
            <li><a href="Mappa.php">Mappa</a></li>
            <li><a href="modificaProfilo.php">Mio profilo</a></li>
            <li><a href="creaEvento.php">Crea evento</a></li>
        </ul>
    </div>
    
    <div class="footer-section">
        <h3 class="footer-title">Uni Events</h3>
        <p class="footer-description">
            La piattaforma ufficiale per scoprire e partecipare agli eventi universitari dell'Università di Bologna.
        </p>
    </div>
    
    <div class="footer-bottom">
        © 2026 Università di Bologna - Alma Mater Studiorum.<br>
        Tutti i diritti riservati.
    </div>
    <script>
        function toggleAdmin(el) {
            const expand = el.nextElementSibling;

            // sicurezza: controlla che sia quello giusto
            if (!expand || !expand.classList.contains("admin-expand")) return;

                expand.classList.toggle("open");

                const arrow = el.querySelector(".arrow");
                arrow.classList.toggle("rotate");
        }
    </script>

</footer>
