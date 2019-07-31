<?php
// required headers
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get database connection
include_once '../config/database.php';

// instantiate wallet object
include_once '../objects/wallet.php';

$database = new Database();
$db = $database->getConnection();

$wallet = new Wallet($db);

// get posted data
$data = json_decode(file_get_contents("php://input",true));

// make sure data is not empty
    if (
        !empty($data->name) &&
        !empty($data->hashKey) &&
        preg_match('/^[a-z0-9]{3,255}$/', $data->name) &&
        preg_match('/^[a-z]{3,255}$/', $data->hashKey)
    ) {

        // set wallet property values
        $wallet->name = $data->name;
        $wallet->hashKey = $data->hashKey;

        // create the wallet
        if ($wallet->create()) {

            // set response code - 200 created
            http_response_code(200);

            // tell the user
            echo json_encode(array("message" => "Wallet was created."));
        } // if unable to create the wallet, tell the user
        else {

            // set response code - 503 service unavailable
            http_response_code(503);

            // tell the user
            echo json_encode(array("message" => "Unable to create wallet."));
        }
    } // tell the user data is incomplete
    else {

        // set response code - 403 bad request
        // http_response_code(403);
        http_response_code(403);

        // tell the user
        echo json_encode(array("message" => "Unable to create wallet. Data is invalid."));
    }

?>