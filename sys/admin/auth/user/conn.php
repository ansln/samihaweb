<?php

class connection{
    private $db;

    private function dbConnect(){
        $dbConnect = new mysqli("localhost","u362596482_samiha_shop","!_Samih@!db_Password_Shop_135790_TEMP!","u362596482_shop");
        $this->db = $dbConnect;
        return $this->db;
    }
    
    public function getDb(){
        return $this->dbConnect();
    }
}

class connV2{
    private function dbConnectV2(){
        $dbConnect = new mysqli("localhost","u362596482_samiha_shop","!_Samih@!db_Password_Shop_135790_TEMP!","u362596482_shop");
        $this->db = $dbConnect;
        return $this->db;
    }
    public function getDb(){
        return $this->dbConnectV2();
    }
}

?>