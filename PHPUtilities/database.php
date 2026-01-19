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
}
?>
