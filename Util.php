<?php
class Util
{
    //DB variable
    static $DB_NAME = "ussdsms";
    static $DB_USER = "root";
    static $DB_USER_PASS = "";
    static $SERVER_NAME = "localhost";

    //About USSD MENU
    static $GO_BACK = "98";
    static $GO_TO_MAIN_MENU = "99";


    //user intial balance
    static $USER_BALANCE = 4000;

    //country code
    static $COUNTRY_CODE = "+256";

    //transaction fee
    static $TRANSACTION_FEE = 50;

    //static  $API_KEY = ""; //sandbox
    static  $API_KEY = "8b877138a29bfbd73b4b5dbc1372bf6980ae183a96ba2bfcc2baf463f6cd67fc
    "; //prod

    //static $API_USERNAME = ""; //sandbox
    static $API_USERNAME = "sandbox"; //prod

    //static $SMS_SHORTCODE = "";//sandbox
    static $SMS_SHORTCODE = "25625";//prod.
    static $SMS_SHORTCODE_KEYWORD = ""; //prod.
    static $COMPANY_NAME = "MartinM0202";
}

