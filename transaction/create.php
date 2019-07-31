<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get database connection
include_once '../config/database.php';

// instantiate wallet object
include_once '../objects/transaction.php';

$database = new Database();
$db = $database->getConnection();

$transaction = new Transaction($db);

// get posted data
$data = json_decode(file_get_contents("php://input", true));

// make sure data is not empty
if (!empty($data->type) &&
    !empty($data->amount) &&
    !empty($data->reference) &&
    !empty($data->timeStamp) &&
    preg_match('/^[a-z]{3,255}$/', $data->reference)
) {
    $valid = 0;
    // set transaction property values
    $transaction->name = $data->name;
    $transaction->hashKey = $data->hashKey;
    $transaction->type = $data->type;
    $transaction->amount = $data->amount;
    $transaction->reference = "TR-" . $data->reference;
    $transaction->timeStamp = $data->timeStamp;

    // check if type is bet or win
    if ($data->type == "bet" && $data->amount <= 0) {
        $valid = 1;
    }
    if ($data->type == "win" && $data->amount >= 0) {
        $valid = 1;
    }

    if ($valid == 1) {
        // create the transaction
        if ($transaction->create()) {

            // set response code - 201 created
            http_response_code(200);

            // tell the user
            echo json_encode(array("message" => "Transaction was created."));
        } // if unable to create the transaction, tell the user
        else {

            // set response code - 403 service unavailable
            http_response_code(403);

            // tell the user
            echo json_encode(array("message" => "Unable to create transaction."));
        }
    } else {
        http_response_code(403);
    }
} // tell the user data is incomplete
else {

    // set response code - 400 bad request
    // http_response_code(400);
    http_response_code(403);

    // tell the user
    echo json_encode(array("message" => "Unable to create transaction. Data is incomplete."));
}
?>