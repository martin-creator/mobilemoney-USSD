<?php 
include_once 'util.php';
include_once 'user.php';
//include_once 'util.php';
include_once 'transactions.php';
include_once 'agent.php';
include_once 'sms.php';

class Menu{
    protected $text;
    protected $sessionId;
    
    function __construct(){}

    public function mainMenuRegistered($name){
        $response = "Welcome " . $name.  "\n";
        $response .= "1. Send money\n";
        $response .= "2. Withdraw\n";
        $response .= "3. Check balance\n";
        return $response;
    }

    public function mainMenuUnRegistered(){
        $response = "CON Welcome to this app. Reply with\n";
        $response .= "1. Register\n";
        echo $response;
    }

    public function registerMenu($textArray,$phoneNumber, $pdo){
        $level = count($textArray); // This counts number of items in array
        if($level == 1){
            echo " CON Please enter your full name";
        }else if($level == 2){
            echo " CON Please set your PIN";
        }else if($level == 3){
            echo " CON Please re-enter your PIN";
        }else if($level == 4){
            $name = $textArray[1];
            $pin = $textArray[2];
            $confirmPin = $textArray[3];

            if($pin != $confirmPin){
                echo " END Your pins dont match please try again";
            }else{
                // register the user
                // send sms
                $user = new User($phoneNumber);
                $user->setName($name);
                $user->setPin($pin);
                $user->setBalance(Util::$USER_BALANCE);
                $user->register($pdo);
                echo "END  You have been registered";
            }
        }
    }

    public function sendMoneyMenu($textArray, $sender, $pdo, $sessionId){
        $level = count($textArray); 
        $receiver = null;
        $nameOfReceiver = null;
        $response = "";
        if($level == 1){
            echo "CON Enter mobile number of the receiver";
        }else if($level ==2 ){
            echo "CON Enter amount";
        }else if($level == 3){
            echo "CON Enter your PIN";
        }else if ($level == 4){
            $receiverMobile = $textArray[1];
            $receiverMobileWithCountryCode = $this->addCountryCodeToPhoneNumber($receiverMobile);
            $receiver = new User($receiverMobileWithCountryCode);
            $nameOfReceiver = $receiver->readName($pdo);
            $response .= "Send". " " . $textArray[2] . " to " . $nameOfReceiver . " - ". $receiverMobile . "\n";
            $response .= "1.Confirm\n";
            $response .= "2. Cancel \n";
            $response .= Util::$GO_BACK . "Back\n";
            $response .= Util::$GO_TO_MAIN_MENU . "Main Menu\n";
            echo "CON " .$response;
        }else if($level == 5 && $textArray[4] == 1){
            // confirm
            //send the money
            //check if pin is correct
            // If you have enough funds including charges
            $pin = $textArray[3];
            $amount = $textArray[2];
            $ttype = "send";
            $receiver = new User($this->addCountryCodeToPhoneNumber($textArray[1]));
            $sender->setPin($pin);
            $newSenderBalance = $sender->checkBalance($pdo) - $amount - Util::$TRANSACTION_FEE;
            $newReceiverBalance = $receiver->checkBalance($pdo) + $amount;

            if($sender->correctPin($pdo) == false){
                echo "END Wrong PIN";
                //send sms as well
            }else{
                $txn = new Transaction($amount, $ttype);
                $result = $txn->sendMoney($pdo, $sender->readUserId($pdo), $receiver->readUserId($pdo), $newSenderBalance, $newReceiverBalance);

                if($receiver == true){
                    echo " END We are processing your request. You will receive a confirmation message";
                    //send SMS as well
                }else{
                    echo "CON " . $result;
                }
            }
        }else if($level == 5 && $textArray[4] == 2){
            //cancel
            echo "END Thank you for using our service";
        }else if($level == 5 && $textArray[4] == Util::$GO_BACK){
            echo "END You have requested to go back one step - PIN ";
        }else if($level == 5 && $textArray[4] == Util::$GO_TO_MAIN_MENU){
            echo "END You have requested to go back to main menu ";
        }else{
            echo "END Invalid Entry";
        }

    }

    public function withdrawMoneyMenu($textArray, $user, $pdo, $sessionId){
        $level = count($textArray);
        if($level == 1){
            echo "CON Enter agent number";
        }else if($level == 2){
            echo "CON Enter amount";
        }else if($level == 3){
            echo "CON Enter yout PIN";
        }else if($level == 4){
            $agent = new Agent($textArray[1]); // not good pratice
            $agentName = $agent->readNameByNumber($pdo);
            echo "CON Withdraw" . $textArray[2].  "from agent ". $agentName. "\n 1. Confirm\n 2. Cancel\n";
        }else if($level == 5 && $textArray[4] == 1){
            // confirm
            $user->setPin($textArray[3]);
            if($user->correctPin($pdo) == false){
                echo " END Wrong PIN"; // or send an SMS
                return;
            }

            if ($user->checkBalance($pdo) < $textArray[2] + Util::$TRANSACTION_FEE){
                echo "END You have insufficient funds";
                return;
            }

            $agent = new Agent($textArray[1]);
            $agentName = $agent->readNameByNumber($pdo);
            $ttype = "withdraw";
            $txn =  new Transaction($textArray[2], $ttype);
            $newBalance = $user->checkBalance($pdo) - $textArray[2] - Util::$TRANSACTION_FEE;
            $result = $txn->withDrawCash($pdo, $user->readUserId($pdo), $agent->readIdByNumber($pdo), $newBalance);
            if ($result == true){
                echo "END Your request is being processed";
            }else{
                echo "END ". $result;
            }
           
        }else if($level == 5 && $textArray[4] == 2){
            echo "END Thank you!";
        }else{
            //echo "END Invalid Entry";
            $ussdLevel = count($textArray)- 1;
            $this->persistInvalidEntry($sessionId, $user, $ussdLevel, $pdo);
            echo "CON Invalid Menu\n" . $this->withdrawMoneyMenu($textArray, $sessionId,$user,$pdo);
        }
    }

    public function checkBalanceMenu($textArray, $user, $pdo){
        $level = count($textArray);
        if($level == 1){
            echo "CON Enter PIN";
        }else if($level == 2){
            //logic
            //check PIN validity
            //$pin = $textArray[1]
            $user->setPin($textArray[1]);

            if($user->correctPin($pdo) == true){
                $msg = "END Your wallet balance is ". $user->checkBalance($pdo). "  Thank you for using this service"; //send sms
                $sms = new Sms($user->getPhone());
                $result = $sms->sendSMS($msg);

                if($result['status' == "Success"]){
                    echo "END You will receive an SMS shortly" ; 
                }else{
                    echo "END There was an error please try again"; 
                }
            }else{
                echo " END Wrong PIN"; //send sms
            }

        }else{
            echo "END Invalid entry";
        }
    }

    public function middleware($text, $user, $sessionId, $pdo){
        // remove entries for going back and going to the main menu
        return $this->invalidEntry($this->goBack($this->goToMainMenu($text)), $user,$sessionId, $pdo);
    }

    public function goBack($text){
        //1*2*5*8*98*5*6*5*
        $explodedText = explode("*", $text);
        while(array_search(Util::$GO_BACK, $explodedText) != false){
            $firstIndex = array_search(Util::$GO_BACK, $explodedText);
            array_splice($explodedText, $firstIndex - 1, 2);
        }

        return join("*", $explodedText);

    }

    public function goToMainMenu($text){
        //1*2*5*8*99*5*6*5*99
        $explodedText = explode("*", $text);
        while(array_search(Util::$GO_TO_MAIN_MENU, $explodedText) != false){
            $firstIndex = array_search(Util::$GO_TO_MAIN_MENU, $explodedText);
            $explodedText = array_slice($explodedText, $firstIndex + 1);
        }
        return join("*", $explodedText);
    }

    public function persistInvalidEntry($sessionId, $user, $ussdLevel, $pdo){
        //ussdsession table in the database holds all invalid entries
        $stmt = $pdo->prepare("INSERT INTO ussdsession (sessionID, ussdLevel) values (?,?)");
        $stmt->execute([$sessionId, $ussdLevel]);
        $stmt = null;
    }

    public function invalidEntry($ussdStr, $user, $sessionId, $pdo){
        $stmt = $pdo->prepare("SELECT ussdLevel FROM ussdsession WHERE sessionId=?  ");
        $stmt->execute([$sessionId]);
        $result = $stmt->fetchAll();

        if(count($result) == 0 ){
            return $ussdStr;
        }

        $strArray = explode("*", $ussdStr);

        foreach($result as $value){
            unset($strArray[$value['ussdLevel']]);
        }

        $strArray = array_values($strArray);

        return join("*", $strArray);
    }

    public function addCountryCodeToPhoneNumber($phone){
        return Util::$COUNTRY_CODE . substr($phone, 1);
    }
}
