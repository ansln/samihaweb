<?php
//BELAJAR CLASS DAN FUNCTION BERSAMA SAYA!
class greet{
    protected function hello(){
        echo "Selamat Datang ";
    }
    function getHello($nama){
        $this->hello();
        return $nama;
    }
}

class home{
    public function homeMenu(){
        $hello = new greet;
        $nameList = array("Leo", "Ican", "Kemal", "Jupri", "Moti", "Bapong", "Fansa", "Marvin", "Andre", "Mufid", "Jaya", "Fadil");
        $randName = $nameList[array_rand($nameList)];
        $x = $hello->getHello($randName);
        echo $x;
    }
}

$callHome = new home;
$callHome->homeMenu();

?>