<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object file
include_once '../config/database.php';
include_once '../objects/wallet.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare wallet object
$wallet = new Wallet($db);

// get wallet id
$data = json_decode(file_get_contents("php://input"));

// set wallet id to be deleted


// delete the wallet
if($wallet->myList()){

    // set response code - 200 ok
    http_response_code(200);

    // tell the user
    echo json_encode(array("message" => "List of Wallets."));
}

// if unable to delete the wallet
else{

    // set response code - 403 service unavailable
    http_response_code(403);

    // tell the user
    echo json_encode(array("message" => "No Wallets are found."));
}
?>