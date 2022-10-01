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
            $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, FALSE); // we use this when updating more than one table at a time
            try{
                $pdo->beginTransaction();
                $stmtT = $pdo->prepare("INSERT INTO transaction (amount, uid, ruid, ttype) values(?,?,?,?)");
                $stmtU = $pdo->prepare("UPDATE user SET balance=? WHERE  uid=? ");

                $stmtT->execute([$this->getAmount(), $uid, $ruid, $this->getTType()]);
                $stmtU->execute([$newSenderBalance, $uid]); //update sender balance
                $stmtU->execute([$newReceiverBalance, $ruid]); //update reciever balance

                $pdo->commit();

            }catch(Exception $e){
                $pdo->rollback();
                return "An error was encountered";
            }

        }

        public function withDrawCash($pdo, $uid, $aid, $newBalance){

        }
    }


?>