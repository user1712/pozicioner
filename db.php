<?php
/* Класс для выполнения SQL запросов */
class base {
    private $conn;
    public function __construct(){
        try{
            $this->conn = new PDO('mysql:host=localhost;dbname=sgp_3', 'root', '', 
            array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage()."<br/>";
            die();
        }
    }
    public function sql($q){
        $query = $this->conn->prepare($q);
        $query->execute();
        $result = $query->fetchAll();
        return $result;
    }
}
class oc {
    private $conn;
    public function __construct(){
        try{
            $this->conn = new PDO('mysql:host=localhost;dbname=zala', 'root', '', 
            array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage()."<br/>";
            die();
        }
    }
    public function sql($q){
        $query = $this->conn->prepare($q);
        $query->execute();
        $result = $query->fetchAll();
        return $result;
    }
}
?>