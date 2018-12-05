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

echo $Helper->getAuexToken();
//get raw posted data
$data_raw = json_decode(file_get_contents("php://input"));


//validate user
$branch_id = isset($data_raw->branchId)?$Helper->cleanValue($data_raw->branchId):null;
$branch_key = isset($data_raw->branchKey)?$Helper->cleanValue($data_raw->branchKey):null;
// valide with DB data
//instantiate DB & connect
$database = new Database();
$db = $database->connect();
//call model method to validation
$user = new User($db);
$validation_res = $user->find($branch_id,$branch_key);



$courior_name = isset($data_raw->strProviderCode)?$data_raw->strProviderCode:'4PX';

$courier = new Courier($courior_name,1,$db);

$response_arr=array();

if($validation_res==1){
    if($courier_name=='4PX')
    {
        $curl_response = $courier->callApi($data_raw);
        $decoded_response = json_decode($curl_response);

        if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
            die('error occured: ' . $decoded->response->errormessage);
        }
    
        $res_arr = $courier->makeResponseMsg($decoded_response->ResponseCode);
    
        $response_arr=array(
            "orderNumber"=> isset($decoded_response->Data)?$decoded_response->Data:null,
            "resMsg"=>$res_arr['text'],
            "resCode"=>$res_arr['code'],
            "TaxAmount"=>isset($decoded_response->TaxAmount)?$decoded_response->UnionOrderNumber:null,
            "TaxCurrencyCode"=>isset($decoded_response->CurrencyCodeTax)?$decoded_response->CurrencyCodeTax:null
        );
    }
    else if($courier_name=='CQCHS')
    {
        $soap_client= new SoapClient("http://www.zhonghuan.com.au:8085/API/cxf/au/recordservice?wsdl");

        $stock = $Helper->CQCHSCreateString($data_raw);
             
        $final_response = $soap_client->getRecord($stock);
    }
}
else if($validation_res == 2){
    
    $response_arr=array(
        "resCode" => "2",
        "resMsg" => "your account is not authorized, please contact XXX-XXXX-XXX"
    );
}
else if($validation_res ==3){
    $response_arr=array(
        "resCode" => "3",
        "resMsg" => "your account is inactived, please contact XXX-XXXX-XXX"
    );
}


/** output api value */

$final_response = json_encode($response_arr);

echo $final_response;