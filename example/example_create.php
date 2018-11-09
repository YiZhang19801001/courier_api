<?php
//headers
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

function getItemsHelper($arr_item){
    $list_items = array();
    foreach ($arr_item as $item) {
        $list_item = array(
            "ItemSKU"=> isset($item->strItemSKU)?cleanValue($item->strItemSKU):null,
            "ItemDeclareType"=> cleanValue($item->strItemDeclareType),
            "ItemName"=> isset($item->strItemName)?cleanValue($item->strItemName):null,
            "Specifications"=> isset($item->strItemSpecifications)?cleanValue($item->strItemSpecifications):null,
            "ItemQuantity"=> cleanValue($item->numItemQuantity),
            "ItemBrand"=> cleanValue($item->strItemBrand),
            "ItemUnitPrice"=> cleanValue($item->numItemUnitPrice),
            "PreferentialSign"=> isset($item->strIsDiscounted)?cleanValue($item->strIsDiscounted):null
        );

        array_push($list_items,$list_item);   
    }
    return $list_items;
}

function cleanValue($value){
    return htmlspecialchars(strip_tags($value));
}

function translateResponseMsg($msg,$code){
    switch ($code) {
        case 10000:
            return "Order Successfully Created, Please Save your order number for further service refferences";
        case 12001:
            return "order has not been created, please try again or contact xxx-xxx-xxxx";
        case 11004:
            return "you have to provide valid ship order number";
        case 12004:
            return "your ship order number has been used, please check and try again";
        case 12032:    
            return "you have to provide valid ship order number";
        case 11005:
            return "you have to provide valid service type code";
        case 12005:
            return "you have to provide valid service type code";
        case 11006:
            return "sender name can not be empty";
        case 11007:
            return "sender mobile can not be empty";
        case 11009:
            return "receiver name can not be empty";
        case 11010:
            return "receiver province can not be empty";
        case 12010:
            return "invalid receiver province";
        case 11011:
            return "receiver city name can not be empty";
        case 12011:
            return "receiver city name is invalid";
        case 11012:
            return "receiver district can not be empty";
        case 12012:
            return "receiver district is invalid";
        case 11013:
            return "receiver street door number can not be empty";
        case 11014:
            return "receiver mobile can not be empty";
        case 11015:
            return "receiver ID number can not be empty";
        case 12015:
            return "ID number is invalid";
        case 11016:
            return "receiver ID front copy can not be empty";
        case 11017:
            return "receiver ID back copy can not be empty";
        case 11018:
            return "order weight can not be 0";
        case 11019:
            return "order is overweight";
        case 11020:
            return "order unit weight is invalid";
        case 11008:
            return "item declare currency can not be empty";
        case 12008:
            return "item declare currency is invalid";
        case 11019:
            return "can not find any items in this order";
        case 11020:
            return "item name can not be empty";
        case 11021:
            return "item quantity can not be empty";
        case 11022:
            return "item specifications can not be empty";
        case 11024:
            return "item declare type can not be empty";
        case 12024:
            return "item declare type is invalid";
        case 12025:
            return "item is not promised for delivery";
        case 12026:
            return "order cost is over the limitation";
        case 12027:
            return "receiver name has to be Chinese, as receiver's country is China";
        case 12028:
            return "product, service, end delivery and product type is not matching";
        case 12029:
            return "item information is invalid";
        case 12030:
            return "insurance type is invalid";
        case 12031:
            return "item brand can not be empty";
        case 12033:
            return "shop code can not be empty, as your company has multiple shops";
        case 12034:
            return "trace source number is invalid";
        case 12035:
            return "trace source number can only be used in Australlian";
        case 11025:
            return "insurance expense can not be empty";
        case 12036:
            return "insurance expense is not valid";
        case 12037:
            return "country code is invalid";
        case 13001:
            return "fail to create order, try again or contanct XXXX-XXX-XXX";
        case 14000:
            return "fail to find the destination, please check your input";
        case A0001:
            return "ItemSKU is missing";
        case A0002:
            return "ItemSKU is not found";
        case A0003:
            return "ItemSKU is valid yet";

        default:
            return "error! please contact XXXX-XXX-XXX";
    }
}

//get raw posted data
$data_raw = json_decode(file_get_contents("php://input"));


//map values
$data_arr = array(
"Token"=> "TESTC78C-7923-404C-82CF-CD881539123c",
"Data"=> [
    "ShipperOrderNo"=> cleanValue($data_raw->strOrderNo),
    "ServiceTypeCode"=> cleanValue($data_raw->strServiceTypeCode),
    "TerminalCode"=> cleanValue($data_raw->strShopCode),
    "ConsignerName"=> cleanValue($data_raw->strSenderName), 
    "ConsignerMobile"=> cleanValue($data_raw->strSenderMobile),
    "ConsignerProvinceName"=>isset($data_raw->strSenderProvinceName)?cleanValue($data_raw->strSenderProvinceName):null,
    "ConsignerCityName"=>isset($data_raw->strSenderCityName)?cleanValue($data_raw->strSenderCityName):null,
    "ConsignerAddress"=>isset($data_raw->strSenderAddress)?cleanValue($data_raw->strSenderAddress):null,
    "ConsignerPostCode"=> isset($data_raw->strSenderPostCode)?cleanValue($data_raw->strSenderPostCode):null,
    "ItemDeclareCurrency"=> cleanValue($data_raw->strItemCurrency),
    "ConsigneeName"=> cleanValue($data_raw->strReceiverName),
    "CountryISO2"=>isset($data_raw->strCountryISO2)?cleanValue($data_raw->strCountryISO2):null,
    "Province"=> isset($data_raw->strReceiverProvince)?cleanValue($data_raw->strReceiverProvince):null,
    "City"=> isset($data_raw->strReceiverCity)?cleanValue($data_raw->strReceiverCity):null,
    "District"=> isset($data_raw->strReceiverDistrict)?cleanValue($data_raw->strReceiverDistrict):null,
    "ConsigneeStreetDoorNo"=> cleanValue($data_raw->strReceiverDoorNo),
    "ConsigneeMobile"=> cleanValue($data_raw->strReceiverMobile),
    "ConsigneeIDNumber"=>isset($data_raw->strReceiverIDNumber)?cleanValue($data_raw->strReceiverIDNumber):null,
    "ConsigneeIDFrontCopy"=>isset($data_raw->strReceiverIDFrontCopy)?cleanValue($data_raw->strReceiverIDFrontCopy):null,
    "ConsigneeIDBackCopy"=>isset($data_raw->strReceiverIDBackCopy)?cleanValue($data_raw->strReceiverIDBackCopy):null,
    "OrderWeight"=> cleanValue($data_raw->strOrderWeight),
    "WeightUnit"=> isset($data_raw->strWeightUnit)?cleanValue($data_raw->strWeightUnit):null,
    "EndDeliveryType"=> isset($data_raw->strEndDelivertyType)?cleanValue($data_raw->strEndDelivertyType):null,
    "InsuranceTypeCode"=> isset($data_raw->strInsuranceTypeCode)?cleanValue($data_raw->strInsuranceTypeCode):null,
    "InsuranceExpense"=>isset($data_raw->numInsuranceExpense)?cleanValue($data_raw->numInsuranceExpense):null,
    "TraceSourceNumber"=> isset($data_raw->strTraceNumber)?cleanValue($data_raw->strTraceNumber):null,
    "Remarks"=>isset($data_raw->strRemarks)?cleanValue($data_raw->strRemarks):null,
    "ITEMS"=> getItemsHelper($data_raw->items)
]

);

//call api to get data?
$data_string = json_encode($data_arr);

$url = "http://sandbox.transrush.com.au/agent/createPickupItem";
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

$response_arr=array(
    "orderNumber"=> $decoded_response->UnionOrderNumber,
    "message"=>translateResponseMsg($decoded_response->Message,$decoded_response->ResponseCode),
    "TaxAmount"=>$decoded_response->TaxAmount,
    "TaxCurrencyCode"=>$decoded_response->CurrencyCodeTax
);

$final_response = json_encode($response_arr);

echo $final_response;

