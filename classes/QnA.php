<?php
namespace otazkyodpovede;
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/db/config.php');
use PDO;
use Exception;
use PDOException;

class QnA{
    private $conn;
    public function __construct() {
        $this->connect();
    }
    private function connect() {
        $config = DATABASE;
        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        );
        try {
            $this->conn = new PDO('mysql:host=' . $config['HOST'] . ';dbname=' . $config['DBNAME'] . ';port=' . $config['PORT'], $config['USER_NAME'], $config['PASSWORD'], $options);
        } catch (PDOException $e) {
                die("Chyba pripojenia: " . $e->getMessage());
            }
    }
    public function insertQnA(){
        try {
            // Načítanie JSON súboru
            $data = json_decode(file_get_contents(__ROOT__.'/data/data.json'), true);
            if ($data === null) {
                die("Chyba pri načítaní JSON súboru: " . json_last_error_msg());
            }
            if (!isset($data["otazky"]) || !isset($data["odpovede"])) {
                die("JSON súbor neobsahuje požadované kľúče 'otazky' a 'odpovede'.");
            }
            $otazky = $data["otazky"];
            $odpovede = $data["odpovede"];
            // Vloženie otázok a odpovedí v rámci transakcie
            $this->conn->beginTransaction();
            
            $sql = "INSERT INTO qna (otazka, odpoved) VALUES (:otazka, :odpoved)";
            $statement = $this->conn->prepare($sql);
            
            for ($i = 0; $i < count($otazky); $i++) {
                $statement->bindParam(':otazka', $otazky[$i]);
                $statement->bindParam(':odpoved', $odpovede[$i]);
                $statement->execute();
            }
            $this->conn->commit();
            echo "Dáta boli vložené";
        } catch (PDOException $e) {
            echo "Chyba pri vkladaní dát do databázy: " . $e->getMessage();
            if ($this->conn) {
                $this->conn->rollback();
            }
        } catch (Exception $e) {
            echo "Chyba: " . $e->getMessage();
        } finally {
            // Uzatvorenie spojenia
            $this->conn = null;
        }
    }
    // SELECT z databázy (DISTINCT rieši aby sa zobrazila položka len raz)
    public function getQnA() {
        try {
            $sql = "SELECT DISTINCT * FROM qna";
            $statement = $this->conn->prepare($sql);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Chyba pri načítaní otázok a odpovedí: " . $e->getMessage();
        }
    }
}
