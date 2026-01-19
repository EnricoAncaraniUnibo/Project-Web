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
        $stmt = $this->db->prepare("SELECT e.Data,e.Città,e.Titolo,u.nome,u.matricola,e.Orario,e.Luogo,e.Indirizzo,e.Descrizione,e.Partecipanti_Attuali FROM evento e JOIN utente u on e.matricola_creatore=u.matricola where e.Titolo LIKE ? or e.Descrizione LIKE ? or u.nome LIKE ? or e.Città LIKE ? or e.Luogo LIKE ? or e.Indirizzo LIKE ? ORDER BY e.Città,e.Data,e.Orario");
        $stmt->bind_param('ssssss', $searchTerm, $searchTerm, $searchTerm,$searchTerm, $searchTerm, $searchTerm);
        $stmt->execute();
        $result=$stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function NumberOfsearch($key) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM evento e JOIN utente u on e.matricola_creatore=u.matricola where e.Titolo LIKE ? or e.Descrizione LIKE ? or u.nome LIKE ? or e.Città LIKE ? or e.Luogo LIKE ? or e.Indirizzo LIKE ?");
        $searchTerm = "%" . $key . "%";
        $stmt->bind_param('ssssss', $searchTerm, $searchTerm, $searchTerm,$searchTerm, $searchTerm, $searchTerm);
        $stmt->execute();
        $result=$stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
