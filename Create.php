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
    //map values
    $data_arr = array(
    "Token"=> $courier->getApiKey(),
    "Data"=> [
        "ShipperOrderNo"=> isset($data_raw->strOrderNo)?$Helper->cleanValue($data_raw->strOrderNo):null,
        "ServiceTypeCode"=>isset($data_raw->strServiceTypeCode)?$Helper->cleanValue($data_raw->strServiceTypeCode):null,
        "TerminalCode"=> isset($data_raw->strShopCode)?$Helper->cleanValue($data_raw->strShopCode):null,
        "ConsignerName"=> isset($data_raw->strSenderName)?$Helper->cleanValue($data_raw->strSenderName):null, 
        "ConsignerMobile"=> isset($data_raw->strSenderMobile)?$Helper->cleanValue($data_raw->strSenderMobile):null,
        "ConsignerProvinceName"=>isset($data_raw->strSenderProvinceName)?$Helper->cleanValue($data_raw->strSenderProvinceName):null,
        "ConsignerCityName"=>isset($data_raw->strSenderCityName)?$Helper->cleanValue($data_raw->strSenderCityName):null,
        "ConsignerAddress"=>isset($data_raw->strSenderAddress)?$Helper->cleanValue($data_raw->strSenderAddress):null,
        "ConsignerPostCode"=> isset($data_raw->strSenderPostCode)?$Helper->cleanValue($data_raw->strSenderPostCode):null,
        "ItemDeclareCurrency"=> isset($data_raw->strItemCurrency)?$Helper->cleanValue($data_raw->strItemCurrency):null,
        "ConsigneeName"=> isset($data_raw->strReceiverName)?$Helper->cleanValue($data_raw->strReceiverName):null,
        "CountryISO2"=>isset($data_raw->strCountryISO2)?$Helper->cleanValue($data_raw->strCountryISO2):null,
        "Province"=> isset($data_raw->strReceiverProvince)?$Helper->cleanValue($data_raw->strReceiverProvince):null,
        "City"=> isset($data_raw->strReceiverCity)?$Helper->cleanValue($data_raw->strReceiverCity):null,
        "District"=> isset($data_raw->strReceiverDistrict)?$Helper->cleanValue($data_raw->strReceiverDistrict):null,
        "ConsigneeStreetDoorNo"=> isset($data_raw->strReceiverDoorNo)?$Helper->cleanValue($data_raw->strReceiverDoorNo):null,
        "ConsigneeMobile"=> isset($data_raw->strReceiverMobile)?$Helper->cleanValue($data_raw->strReceiverMobile):null,
        "ConsigneeIDNumber"=>isset($data_raw->strReceiverIDNumber)?$Helper->cleanValue($data_raw->strReceiverIDNumber):null,
        "ConsigneeIDFrontCopy"=>isset($data_raw->strReceiverIDFrontCopy)?$Helper->cleanValue($data_raw->strReceiverIDFrontCopy):null,
        "ConsigneeIDBackCopy"=>isset($data_raw->strReceiverIDBackCopy)?$Helper->cleanValue($data_raw->strReceiverIDBackCopy):null,
        "OrderWeight"=> isset($data_raw->strOrderWeight)?$Helper->cleanValue($data_raw->strOrderWeight):null,
        "WeightUnit"=> isset($data_raw->strWeightUnit)?$Helper->cleanValue($data_raw->strWeightUnit):null,
        "EndDeliveryType"=> isset($data_raw->strEndDelivertyType)?$Helper->cleanValue($data_raw->strEndDelivertyType):null,
        "InsuranceTypeCode"=> isset($data_raw->strInsuranceTypeCode)?$Helper->cleanValue($data_raw->strInsuranceTypeCode):null,
        "InsuranceExpense"=>isset($data_raw->numInsuranceExpense)?$Helper->cleanValue($data_raw->numInsuranceExpense):null,
        "TraceSourceNumber"=> isset($data_raw->strTraceNumber)?$Helper->cleanValue($data_raw->strTraceNumber):null,
        "Remarks"=>isset($data_raw->strRemarks)?$Helper->cleanValue($data_raw->strRemarks):null,
        "ITEMS"=> $Helper->getItemsHelper($data_raw->items)
    ]
    
    );
    
    //call api to get data?
    $data_string = json_encode($data_arr);
    
    $url = $courier->getUrl();
    $curl = curl_init($url);
    
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json', 'Content-Length: ' . strlen($data_string)));  
    
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
    
    $response_arr=array(
        "orderNumber"=> isset($decoded_response->UnionOrderNumber)?$decoded_response->UnionOrderNumber:null,
        "resMsg"=>$res_arr['text'],
        "resCode"=>$res_arr['code'],
        "TaxAmount"=>isset($decoded_response->TaxAmount)?$decoded_response->UnionOrderNumber:null,
        "TaxCurrencyCode"=>isset($decoded_response->CurrencyCodeTax)?$decoded_response->CurrencyCodeTax:null
    );
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
