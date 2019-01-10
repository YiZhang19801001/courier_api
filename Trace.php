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
$myFile = "logger_track.json";

try
{
    $file_arr_data = array(); // create empty array

    $request_body = file_get_contents("php://input");
    //Get data
    $formdata = array('time' => date("Y-m-d H:i:s"), 'process' => 'before decode', 'request_body' => $request_body);

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

//get raw posted data
$data_raw = json_decode(file_get_contents("php://input"));

try
{
    $file_arr_data = array(); // create empty array
    //Get data
    $formdata = array('time' => date("Y-m-d H:i:s"), 'process' => 'after decode', 'request_body' => $data_raw);

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
//$branch_id = isset($data_raw->branchId) ? $Helper->cleanValue($data_raw->branchId) : null;
//$branch_key = isset($data_raw->branchKey) ? $Helper->cleanValue($data_raw->branchKey) : null;
// valide with DB data
//instantiate DB & connect
$database = new Database();
$db = $database->connect();
//call model method to validation
$user = new User($db);
////$validation_res = $user->find($branch_id, $branch_key);

$courior_name = isset($data_raw->strProviderCode) ? $data_raw->strProviderCode : '4PX';

$courier = new Courier($courior_name, 2, $db);

$response_arr = array();

if ($courior_name == '4PX') {
    //map values
    $data_arr = array(
        "Token" => $courier->getApiKey(),
        "Data" => ["ShipperOrderNo" => isset($data_raw->strOrderNo) ? $Helper->cleanValue($data_raw->strOrderNo) : null],
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
        "orderNumber" => isset($decoded_response->Data->ShipperOrderNo) ? $decoded_response->Data->ShipperOrderNo : null,
        "resMsg" => $res_arr['text'],
        "resCode" => $res_arr['code'],
        "TrackingList" => isset($decoded_response->Data->TrackingList) ? $Helper->getTrackingListHelper($decoded_response->Data->TrackingList) : null,
    );
} else if ($courior_name == 'CQCHS') {
    $wsdl = "http://www.zhonghuan.com.au:8085/API/cxf/common/logisticsservice?wsdl";
    $client = new SoapClient($wsdl, array('trace' => 1)); // The trace param will show you errors stack

    $request_param = array(
        "fydh" => $data_raw->strOrderNo,
        "countrytype" => "au",
    );

    try
    {
        $responce_param = $client->getLogisticsInformation($request_param);
        //$responce_param =  $client->call("webservice_methode_name", $request_param); // Alternative way to call soap method

        $index = strpos($responce_param->return, ">");

        $xml = simplexml_load_string(substr($responce_param->return, $index + 1));

        $json_string = json_encode($xml);
        $json_obj = json_decode($json_string);
			
        $response_arr = array(
            "orderNumber" => $json_obj->fydh,
            "resMsg" => !isset($json_obj->Logisticsback) ? "order not found" : "order found",
            "resCode" => !isset($json_obj->Logisticsback) ? "1" : "0",
            "TrackingList" => isset($json_obj->Logisticsback) ? $Helper->getTrackingListCQCHS($json_obj->Logisticsback, $json_obj->kdgsname) : "",
        );

    } catch (Exception $e) {
        $response_arr = array(
            "orderNumber" => "",
            "resMsg" => $e->getMessage(),
            "resCode" => "1",
            "TaxAmount" => "",
            "TaxCurrencyCode" => "",
        );

    }

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

$final_response = json_encode($response_arr);

try
{
    $file_arr_data = array(); // create empty array
    //Get data
    $formdata = array('time' => date("Y-m-d H:i:s"), 'response_data' => $response_arr);

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
