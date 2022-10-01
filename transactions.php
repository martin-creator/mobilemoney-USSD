<?php 
    class Transaction{
        protected $amount;
        protected $ttype;

        function __construct($amount, $ttype)
        {
            $this->amount = $amount;
            $this->ttype = $ttype;
        }

        public function getAmount(){
            return $this->amount;
        }

        public function getTType(){
            return $this->ttype;
        }

        public function sendMoney($pdo, $uid, $ruid, $newSenderBalance, $newReceiverBalance){

        }

        public function withDrawCash($pdo, $uid, $aid, $newBalance){
            
        }
    }


?>