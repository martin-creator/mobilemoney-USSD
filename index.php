<?php

//   https://bb2b-197-239-4-111.in.ngrok.io/momo/index.php

include_once 'menu.php';
include_once 'db.php';
include_once 'user.php';

// Read the variables sent via POST from our API
$sessionId   = $_POST["sessionId"]; // id that identifies each users session * one user can have many sessions
$serviceCode = $_POST["serviceCode"];
$phoneNumber = $_POST["phoneNumber"];
$text        = $_POST["text"]; // this is what the user send back

$user = new User($phoneNumber); //$isRegistered = true;
$db = new DBConnector();
$pdo = $db->connectToDB();

$menu = new Menu();
$text = $menu->middleware($text, $user, $sessionId, $pdo);

if ($text == "" && $user->isUserRegistered($pdo)) {
    // user is registered and string is empty. Note how we start the response with CON
    echo "CON". $menu->mainMenuRegistered($user->readName($pdo));

} else if( $text == "" &&  !$user->isUserRegistered($pdo)){
  // user is not registered and string is empty
  $menu->mainMenuUnRegistered();

}else if( !$user->isUserRegistered($pdo) ){
// user is not registered and string is not empty
    $textArray = explode("*", $text);
    switch($textArray[0]){
        case 1:
            $menu->registerMenu($textArray, $phoneNumber, $pdo);
            break;
        default:
            "END Invalid choice. Please try again"; //handle invalid entry
    }
}else{
// user is registered and string is not empty
    $textArray = explode("*", $text);
    switch($textArray[0]){
        case 1:
            $menu->sendMoneyMenu($textArray, $user, $pdo, $sessionId);
            break;
        case 2:
            $menu->withdrawMoneyMenu($textArray, $sessionId, $user, $pdo);
            break;
        case 3:
           $menu->checkBalanceMenu($textArray); 
            break;
        default:
            $ussdLevel = count($textArray)- 1;
            $menu->persistInvalidEntry($sessionId, $user, $ussdLevel, $pdo);
            echo "CON Invalid Menu\n" . $menu->mainMenuRegistered($user->readName($pdo));
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
//header('Content-type: text/plain');
//echo $response;

?>
