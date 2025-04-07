<?php
namespace otazkyodpovede;
error_reporting(E_ALL); // zapne všetky chyby
ini_set('display_errors', "On"); // zobrazí chyby na obrazovke
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/classes/Database.php');
use PDO;
use Exception;
use PDOException;
use otazkyodpovede\Database; // Import triedy Database

class QnA extends Database {
    protected $conn;
    public function __construct() {
        $this->connect();
        $this->conn = $this->getConnection();
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
            return [];
        }
    }
}
