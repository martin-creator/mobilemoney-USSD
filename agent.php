<?php
class Agent{
    protected $name;
    protected $number;

    function __construct($number)
    {
        $this->number = $number;
    }

    public function getNumber(){
        return $this->number;
    }

    public function setName($name){
        $this->name = $name;
    }

    public function getName(){
        return $this->name;
    }

    /**
     * @param PDO database connection handle
     * @return boolean/String(false) if record not found, returns Agent name otherwise
     */

     public function readNameByNumber($pdo){
        //returns the name if found, false otherwise,
        $stmt = $pdo->prepare("SELECT name FROM agent WHERE agentNumber=?");
        $stmt->execute([$this->getNumber()]);
        $row = $stmt->fetch();
        if($row == null){
            return $row['name']?? 'default value';
        }else{
            return false;
        }
     }

     public function readIdByNumber($pdo){
        $stmt = $pdo->prepare("SELECT aid FROM agent WHERE agentNumber=?");
        $stmt->execute([$this->getNumber()]);
        $row = $stmt->fetch();

        if($row == null){
            return $row['aid']?? 'default value';
        }else{
            return false;
        }
     }
}


?>