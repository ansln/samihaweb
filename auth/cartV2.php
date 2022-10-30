<?php

class cartManagement{

    private function getUserEmail(){
        $get = new userSession;
        $userEmail = $get->generateEmail();
        return $userEmail;
    }

    public function generateUserEmail(){
        return $this->getUserEmail();
    }
}

?>