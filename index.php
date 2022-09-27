<?php

// https://4816-197-239-4-211.eu.ngrok.io/momo/index.php

include_once 'menu.php';

// Read the variables sent via POST from our API
$sessionId   = $_POST["sessionId"]; // id that identifies each users session * one user can have many sessions
$serviceCode = $_POST["serviceCode"];
$phoneNumber = $_POST["phoneNumber"];
$text        = $_POST["text"]; // this is what the user send back

$isRegistered = true;
$menu = new Menu($text, $sessionId);

if ($text == "" && !$isRegistered) {
    // user is registered and string is empty. Note how we start the response with CON
    $menu->mainMenuRegistered();

} else if( $text == "" &&  $isRegistered){
  // user is not registered and string is empty
  $menu->mainMenuUnRegistered();

}else if( $isRegistered ){
// user is not registered and string is not empty
    $textArray = explode("*", $text);
    switch($textArray[0]){
        case 1:
            $menu->registerMenu($textArray);
            break;
        default:
            "END Invalid choice. Please try again";
    }
}else{
// user is registered and string is not empty
    $textArray = explode("*", $text);
    switch($textArray[0]){
        case 1:
            $menu->sendMoneyMenu($textArray);
            break;
        case 2:
            $menu->withdrawMoneyMenu($textArray);
            break;
        case 3:
           $menu->checkBalanceMenu($textArray); 
            break;
        default:
            "END Invalid choice. Please try again";
    }
}

/*else if ($text == "1") {
    // Business logic for first level response
    $response = "CON Choose account information you want to view \n";
    $response .= "1. Account number \n";

} else if ($text == "2") {
    // Business logic for first level response
    // This is a terminal request. Note how we start the response with END
    $response = "END Your phone number is ".$phoneNumber;

} else if($text == "1*1") { 
    // This is a second level response where the user selected 1 in the first instance
    $accountNumber  = "ACC1001";

    // This is a terminal request. Note how we start the response with END
    $response = "END Your account number is ".$accountNumber;

}*/

// Echo the response back to the API
header('Content-type: text/plain');
//echo $response;

?>
