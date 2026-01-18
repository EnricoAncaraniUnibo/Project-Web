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

    public function checkUserExists($matricola){
        $stmt = $this->db->prepare("SELECT * FROM utente WHERE matricola = ?");
        $stmt->bind_param('i', $matricola);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }
}
?>
