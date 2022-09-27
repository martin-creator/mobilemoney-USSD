<?php 
class Menu{
    protected $text;
    protected $sessionId;
    
    function __construct($text, $sessionId)
    {
        $this->text = $text;
        $this->sessionId = $sessionId;
    }

    public function mainMenuRegistered(){
        $response = "CON Reply with \n";
        $response .= "1. Send money\n";
        $response .= "2. Withdraw\n";
        $response .= "3. Check balance\n";
        echo $response;
    }

    public function mainMenuUnRegistered(){
        $response = "CON Welcome to this app. Reply with\n";
        $response .= "1. Register\n";
        echo $response;
    }

    public function registerMenu($textArray){}

    public function sendMoneyMenu($textArray){}

    public function withdrawMoneyMenu($textArray){}

    public function checkBalanceMenu($textArray){}
}


?>