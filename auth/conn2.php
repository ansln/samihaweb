<?php

class connection{
    private $db;

    private function dbConnect(){
        $dbConnect = new mysqli("localhost","root","","shop");
        $this->db = $dbConnect;
        return $this->db;
    }
    
    public function getDb(){
        return $this->dbConnect();
    }
}

?>