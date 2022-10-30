<?php

class homeManagement{
    private $db;

    private function callDb(){
        require_once "../auth/conn2.php";
        $get = new connection;
        $this->db = $get->getDb();
        return $this->db;
    }

    private function fetchElement($element){
        $db = $this->callDb();
        $elementFetch = $db->query("SELECT * FROM dashboard WHERE element_name = '$element'");
        return $elementFetch;
    }

    public function getElement($element){
        $getDb = $this->fetchElement($element);
        return $getDb;
    }

}

?>