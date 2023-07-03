<?php

class cartManagement{

    private function getUserEmail(){
        $get = new userSession;
        $userEmail = $get->generateEmail();
        return $userEmail;
    }

    //setter getter
    public function generateUserEmail(){
        return $this->getUserEmail();
    }
}

?>