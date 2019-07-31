<?php
// curl taken from postman
$curl = curl_init();
$data = array(
    "name" => $_REQUEST['name'],
    "hashKey" => $_REQUEST['hashKey'],
    "type" => $_REQUEST['type'],
    "amount" => $_REQUEST['amount'],
    "reference" => $_REQUEST['reference'],
    "timeStamp" => $_REQUEST['timeStamp']
);
$data_string = json_encode($data);
curl_setopt_array($curl, array(
    CURLOPT_PORT => "8080",
    CURLOPT_URL => "http://localhost:8080/gimo_master/transaction/create.php",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => $data_string,
    CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "Content-Type: application/json",
    ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    echo $response;
}