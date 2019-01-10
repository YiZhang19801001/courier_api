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
$dateTimeForLogger = $Helper->getDateTime();

$myFile = "./log/create/logger $dateTimeForLogger->date.json";

try
{

    $file_arr_data = array(); // create empty array

    $request_body = file_get_contents("php://input");
    //Get data
    $formdata = array('time' => $dateTimeForLogger->time, 'process' => 'before decode', 'request_body' => $request_body);

    //Get data from existing json file
    $jsondata = file_get_contents($myFile);

    // converts json data into array
    $file_arr_data = json_decode($jsondata, true) !== null ? json_decode($jsondata, true) : [];

    // Push user data to array
    array_push($file_arr_data, $formdata);

    //Convert updated array to JSON
    $jsondata = json_encode($file_arr_data, JSON_PRETTY_PRINT);

    //save data in log file

    file_put_contents($myFile, $jsondata);

} catch (Exception $e) {
    echo 'Caught exception: ', $e->getMessage(), "\n";
}

//get raw posted data
$data_raw = json_decode(file_get_contents("php://input"));

try
{
    $file_arr_data = array(); // create empty array
    //Get data
    $formdata = array('time' => $dateTimeForLogger->time, 'process' => 'after decode', 'request_body' => $data_raw);

    //Get data from existing json file
    $jsondata = file_get_contents($myFile);

    // converts json data into array
    $file_arr_data = json_decode($jsondata, true);

    // Push user data to array
    array_push($file_arr_data, $formdata);

    //Convert updated array to JSON
    $jsondata = json_encode($file_arr_data, JSON_PRETTY_PRINT);
    //save data in log file

    file_put_contents($myFile, $jsondata);

} catch (Exception $e) {
    echo 'Caught exception: ', $e->getMessage(), "\n";
}

//validate user
// $branch_id = isset($data_raw->branchId) ? $Helper->cleanValue($data_raw->branchId) : null;
// $branch_key = isset($data_raw->branchKey) ? $Helper->cleanValue($data_raw->branchKey) : null;
// valide with DB data
//instantiate DB & connect
$database = new Database();
$db = $database->connect();
//call model method to validation
$user = new User($db);
//$validation_res = $user->find($branch_id, $branch_key);

$courier_name = isset($data_raw->strProviderCode) ? $data_raw->strProviderCode : '4PX';

$response_arr = array();

if ($courier_name == '4PX') {

    $courier = new Courier($courier_name, 1, $db);
    $curl_response = $courier->callApi($data_raw);
    $decoded_response = json_decode($curl_response);

    if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
        die('error occured: ' . $decoded->response->errormessage);
    }

    $res_arr = $courier->makeResponseMsg($decoded_response->ResponseCode);

    $response_arr = array(
        "orderNumber" => isset($decoded_response->Data) ? $decoded_response->Data : null,
        "resMsg" => $res_arr['text'] . '  ( ' . $decoded_response->Message . ' )',
        "resCode" => $res_arr['code'],
        "TaxAmount" => isset($decoded_response->TaxAmount) ? $decoded_response->UnionOrderNumber : null,
        "TaxCurrencyCode" => isset($decoded_response->CurrencyCodeTax) ? $decoded_response->CurrencyCodeTax : null,
    );
} else if ($courier_name == 'CQCHS') {
    //$soap_client= new SoapClient("http://www.zhonghuan.com.au:8085/API/cxf/au/recordservice?wsdl");

    $stock = $Helper->CQCHSCreateString($data_raw);

    $wsdl = "http://www.zhonghuan.com.au:8085/API/cxf/au/recordservice?wsdl";
    $client = new SoapClient($wsdl, array('trace' => 1));

    $request_param = array(
        "stock" => $stock,
    );

    try
    {
        $response_string = json_encode($client->getRecord($request_param));
        $response_json = json_decode($response_string);

        //$responce_param =  $client->call("webservice_methode_name", $request_param); // Alternative way to call soap method

        $response_arr = array(
            "orderNumber" => $response_json->return->chrfydh,
            "resMsg" => $response_json->return->backmsg,
            "resCode" => $response_json->return->msgtype === "200" ? "0" : "1",
            "TaxAmount" => "",
            "TaxCurrencyCode" => "",
        );
    } catch (Exception $e) {
        $response_arr = array(
            "orderNumber" => "",
            "resMsg" => $e->getMessage(),
            "resCode" => "1",
            "TaxAmount" => "",
            "TaxCurrencyCode" => "",
        );

        // echo "<h2>Exception Error!</h2>";
        // echo $e->getMessage();
    }

} else {
    $response_arr = array(
        "orderNumber" => isset($data_raw->strOrderNo) ? $data_raw->strOrderNo : "",
        "resMsg" => "no courier matched, please check your courier name(运输公司名无法匹配，请检查您提交的运输公司名)",
        "resCode" => 'ERR99999',
        "TaxAmount" => "not availiable for this transaction",
        "TaxCurrencyCode" => "not availiable for this transaction",
    );

}

// if ($validation_res == 1) {

// } else if ($validation_res == 2) {

//     $response_arr = array(
//         "resCode" => "2",
//         "resMsg" => "your account is not authorized, please contact XXX-XXXX-XXX",
//     );
// } else if ($validation_res == 3) {
//     $response_arr = array(
//         "resCode" => "3",
//         "resMsg" => "your account is inactived, please contact XXX-XXXX-XXX",
//     );
// }

/** output api value */

$final_response = json_encode($response_arr);

try
{
    $file_arr_data = array(); // create empty array
    //Get data
    $formdata = array('time' => $dateTimeForLogger->time, 'response_data' => $response_arr);

    //Get data from existing json file
    $jsondata = file_get_contents($myFile);

    // converts json data into array
    $file_arr_data = json_decode($jsondata, true);

    // Push user data to array
    array_push($file_arr_data, $formdata);

    //Convert updated array to JSON
    $jsondata = json_encode($file_arr_data, JSON_PRETTY_PRINT);
    //save data in log file

    file_put_contents($myFile, $jsondata);

} catch (Exception $e) {
    echo 'Caught exception: ', $e->getMessage(), "\n";
}

echo $final_response;
