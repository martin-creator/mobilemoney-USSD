<?php
require 'vendor/autoload.php';

use AfricasTalking\SDK\AfricasTalking;

include_once 'util.php';
include_once 'db.php';

class Sms
{
    protected $phone;
    protected $AT;

    function __construct($phone)
    {
        $this->phone = $phone;
        $this->AT = new AfricasTalking(Util::$API_USERNAME, Util::$API_KEY);
    }

    public function getPhone(){
        return $this->phone;
    }

    public function sendSMS($message){
        //get the sms service
        $sms = $this->AT->sms();
        //use the service 
        $result = $sms->send([
            'to'      => $this->getPhone(),
            'message' => $message,
            'from'    => Util::$COMPANY_NAME,
            //'keyword' => Util::$SMS_SHORTCODE_KEYWORD
        ]);
        return $result;
    }
}
