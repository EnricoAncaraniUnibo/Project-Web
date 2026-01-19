<?php
class DataBaseHelper{
    private $db;
    public function __construct($servername, $username, $password, $dbname, $port){
        $this->db = new mysqli($servername, $username, $password, $dbname, $port);
        if($this->db->connect_error) {
            die("Connessione fallita");
        }
    }

    public function getEventiInSospeso(){
        $stmt=$this->db->prepare("SELECT e.Data,e.Città,e.Titolo,u.nome,u.matricola,e.Orario,e.Luogo,e.Indirizzo,e.Descrizione FROM evento e JOIN utente u ON e.matricola_creatore=u.matricola WHERE e.Stato='In Sospeso'");
        $stmt->execute();
        $result=$stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getNumberEventiInSospeso(){
        $stmt=$this->db->prepare("SELECT COUNT(*) FROM evento WHERE Stato='In Sospeso'");
        $stmt->execute();
        $result=$stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getEventiSegnalati(){
        $stmt=$this->db->prepare("SELECT e.Data,e.Città,e.Titolo,u.nome,u.matricola,e.Orario,e.Luogo,e.Indirizzo,e.Descrizione,s.Descrizione FROM evento e JOIN utente u ON e.matricola_creatore=u.matricola join segnalazione s ON s.Id=e.Id");
        $stmt->execute();
        $result=$stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getNumberEventiSegnalati(){
        $stmt=$this->db->prepare("SELECT COUNT(*) FROM segnalazione");
        $stmt->execute();
        $result=$stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function checkUserExists($matricola){
        $stmt = $this->db->prepare("SELECT * FROM utente WHERE matricola = ?");
        $stmt->bind_param('i', $matricola);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public function verifyUserCredentials($matricola, $password){
        $stmt = $this->db->prepare("SELECT * FROM utente WHERE matricola = ? AND password = ?");
        $stmt->bind_param('is', $matricola, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public function registerUser($username, $matricola,$email,$password){
        $stmt = $this->db->prepare("INSERT INTO utente (nome, matricola, email, password, ruolo) VALUES (?, ?, ?, ?, 'normale')");
        $stmt->bind_param('siss', $username, $matricola, $email, $password);
        $stmt->execute();
    }

    public function search($key) {
        $searchTerm = "%" . $key . "%";
        $stmt = $this->db->prepare("SELECT * FROM evento e JOIN utente u on e.matricola_creatore=u.matricola where (e.Titolo LIKE ? or e.Descrizione LIKE ? or u.nome LIKE ? or e.Città LIKE ? or e.Luogo LIKE ? or e.Indirizzo LIKE ?) AND Stato = 'approvato' ORDER BY e.Città,e.Data,e.Orario");
        $stmt->bind_param('ssssss', $searchTerm, $searchTerm, $searchTerm,$searchTerm, $searchTerm, $searchTerm);
        $stmt->execute();
        $result=$stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function NumberOfsearch($key) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM evento e JOIN utente u on e.matricola_creatore=u.matricola where (e.Titolo LIKE ? or e.Descrizione LIKE ? or u.nome LIKE ? or e.Città LIKE ? or e.Luogo LIKE ? or e.Indirizzo LIKE ?) AND Stato = 'approvato'");
        $searchTerm = "%" . $key . "%";
        $stmt->bind_param('ssssss', $searchTerm, $searchTerm, $searchTerm,$searchTerm, $searchTerm, $searchTerm);
        $stmt->execute();
        $result=$stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getEventiPartecipa($matricola){
        $stmt=$this->db->prepare("SELECT e.Data,e.Città,e.Titolo,e.Orario,e.Luogo,e.Indirizzo,e.Descrizione 
        FROM evento e 
        JOIN partecipa p ON e.Id=p.Id_evento WHERE p.utente_matricola=?");
        $stmt->bind_param('i', $matricola);
        $stmt->execute();
        $result=$stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getEventiPubblicati($matricola){
        $stmt=$this->db->prepare("SELECT e.Data,e.Città,e.Titolo,e.Orario,e.Luogo,e.Indirizzo,e.Descrizione 
        FROM evento e 
        WHERE e.matricola_creatore=?");
        $stmt->bind_param('i', $matricola);
        $stmt->execute();
        $result=$stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    //Recupera la prima data disponibile con eventi approvati
    public function getPrimaDataConEventi(){
        $query = "SELECT MIN(Data) as prima_data 
              FROM EVENTO 
              WHERE Stato = 'approvato'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['prima_data'];
    }

    // Recupera la data precedente con eventi rispetto alla data fornita
    public function getDataPrecedenteConEventi($dataCorrente){
        $query = "SELECT MAX(Data) as data_precedente 
              FROM EVENTO 
              WHERE Data < ? AND Stato = 'approvato'";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $dataCorrente);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['data_precedente'];
    }

    //Recupera la data successiva con eventi rispetto alla data fornita
    public function getDataSuccessivaConEventi($dataCorrente){
        $query = "SELECT MIN(Data) as data_successiva FROM EVENTO WHERE Data > ? AND Stato = 'approvato'";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $dataCorrente);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['data_successiva'];
    }

    // Recupera tutti gli eventi per una specifica data che sono stati approvati
    public function getEventiPerData($data){
        $query = "SELECT * FROM EVENTO JOIN UTENTE on matricola_creatore=matricola WHERE Data = ? AND Stato = 'approvato' ORDER BY Orario ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $data);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Recupera gli amici (utenti seguiti) che partecipano a un evento
    public function getAmiciPartecipanti($eventoId, $matricolaUtente){
        $query = "SELECT DISTINCT U.matricola, U.nome 
              FROM UTENTE U
              INNER JOIN Partecipa P ON U.matricola = P.utente_matricola
              INNER JOIN Segue S ON U.matricola = S.seguito_matricola
              WHERE P.evento_id = ? 
              AND S.seguitore_matricola = ?
              ORDER BY U.nome ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('is', $eventoId, $matricolaUtente);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Verifica se un utente partecipa già a un evento
    public function verificaPartecipazioneUtente($eventoId, $matricolaUtente){
        $query = "SELECT COUNT(*) as count FROM Partecipa WHERE evento_id = ? AND utente_matricola = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('is', $eventoId, $matricolaUtente);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'] > 0;
    }

    // Aggiunge un utente come partecipante a un evento
    public function aggiungiPartecipazione($eventoId, $matricolaUtente){
        // Inizia una transazione
        $this->db->begin_transaction();
        try {
            // Verifica che l'evento esista e non sia pieno
            $query = "SELECT Max_Partecipanti, Partecipanti_Attuali 
                  FROM EVENTO WHERE Id = ? AND Stato = 'approvato'";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('i', $eventoId);
            $stmt->execute();
            $result = $stmt->get_result();
            $evento = $result->fetch_assoc();

            if (!$evento) {
                throw new Exception("Evento non trovato o non approvato");
            }

            if (
                $evento['Max_Partecipanti'] &&
                $evento['Partecipanti_Attuali'] >= $evento['Max_Partecipanti']
            ) {
                throw new Exception("Evento completo");
            }

            // Verifica che l'utente non partecipi già
            $query = "SELECT COUNT(*) as count FROM Partecipa 
                  WHERE evento_id = ? AND utente_matricola = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('is', $eventoId, $matricolaUtente);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if ($row['count'] > 0) {
                throw new Exception("Utente già iscritto");
            }

            // Inserisce la partecipazione
            $query = "INSERT INTO Partecipa (evento_id, utente_matricola) 
                  VALUES (?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('is', $eventoId, $matricolaUtente);
            $stmt->execute();

            // Aggiorna il contatore dei partecipanti
            $query = "UPDATE EVENTO 
                  SET Partecipanti_Attuali = Partecipanti_Attuali + 1 
                  WHERE Id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('i', $eventoId);
            $stmt->execute();

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }

    // Rimuove un utente dalla partecipazione a un evento
    public function rimuoviPartecipazione($eventoId, $matricolaUtente){
        $this->db->begin_transaction();

        try {
            // Verifica che l'utente partecipi effettivamente
            $query = "SELECT COUNT(*) as count FROM Partecipa 
                  WHERE evento_id = ? AND utente_matricola = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('is', $eventoId, $matricolaUtente);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if ($row['count'] == 0) {
                throw new Exception("Utente non iscritto all'evento");
            }

            // Rimuove la partecipazione
            $query = "DELETE FROM Partecipa 
                  WHERE evento_id = ? AND utente_matricola = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('is', $eventoId, $matricolaUtente);
            $stmt->execute();

            // Aggiorna il contatore dei partecipanti
            $query = "UPDATE EVENTO 
                  SET Partecipanti_Attuali = Partecipanti_Attuali - 1 
                  WHERE Id = ? AND Partecipanti_Attuali > 0";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('i', $eventoId);
            $stmt->execute();

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }

    public function getRuoloByMatricola($matricola) {
        $stmt = $this->db->prepare("SELECT ruolo FROM utente WHERE matricola = ?");
        $stmt->bind_param('s', $matricola); // Usa 's' per stringa
        $stmt->execute();
        $result = $stmt->get_result();
        
        
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        
        if (count($rows) > 0) {
            return $rows[0]['ruolo'];
        }
        
        return null; 
    }
}
?>
