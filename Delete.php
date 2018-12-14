<?php
//headers
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once './config/Database.php';
include_once './models/User.php';
include_once './models/Courier.php';
include_once './models/Helper.php';

$Helper = new Helper;

//get raw posted data
$data_raw = json_decode(file_get_contents("php://input"));

//validate user
$branch_id = isset($data_raw->branchId) ? $Helper->cleanValue($data_raw->branchId) : null;
$branch_key = isset($data_raw->branchKey) ? $Helper->cleanValue($data_raw->branchKey) : null;
// valide with DB data
//instantiate DB & connect
$database = new Database();
$db = $database->connect();
//call model method to validation
$user = new User($db);
$validation_res = $user->find($branch_id, $branch_key);

$courior_name = isset($data_raw->strProviderCode) ? $data_raw->strProviderCode : '4PX';

$courier = new Courier($courior_name, 3, $db);

$response_arr = array();

//map values
$data_arr = array(
    "Token" => $courier->getApiKey(),
    "Data" => ["ReferenceNumber" => isset($data_raw->strOrderNo) ? $Helper->cleanValue($data_raw->strOrderNo) : null],
);

//call api to get data?
$data_string = json_encode($data_arr);

$url = $courier->getUrl();
$curl = curl_init($url);

curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($data_string)));

$curl_response = curl_exec($curl);

if ($curl_response === false) {
    $info = curl_getinfo($curl);
    curl_close($curl);
    die('error occured during curl exec. Additioanl info: ' . var_export($info));
}

curl_close($curl);

$decoded_response = json_decode($curl_response);

if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
    die('error occured: ' . $decoded->response->errormessage);
}

$res_arr = $courier->makeResponseMsg($decoded_response->ResponseCode);

$response_arr = array(
    "orderNumber" => isset($data_raw->strOrderNo) ? $Helper->cleanValue($data_raw->strOrderNo) : null,
    "resMsg" => $res_arr['text'],
    "resCode" => $res_arr['code'],
);

// if($validation_res==1)
// {

// }
// else if($validation_res == 2){
//     $response_arr=array(
//         "resCode" => "2",
//         "resMsg" => "your account is not authorized, please contact XXX-XXXX-XXX"
//     );
// }
// else if($validation_res ==3){
//     $response_arr=array(
//         "resCode" => "3",
//         "resMsg" => "your account is inactived, please contact XXX-XXXX-XXX"
//     );
// }

$final_response = json_encode($response_arr);

echo $final_response;
