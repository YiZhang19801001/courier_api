<?php
//headers
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');



function cleanValue($value){
    return htmlspecialchars(strip_tags($value));
}

function translateStatus($code){
    switch ($code) {
        case 'PU':
            return "The goods have been taken from the sender";
        case 'CL':
            return "Site collection";
        case 'AO':
            return "arrived oversea warehouse";
        case 'OC':
            return "operation complete";
        case 'LO':
            return "leave oversea warehouse";
        case 'FT':
            return "departure";
        case 'FL':
            return "arrived";
        case 'TRM':
            return "Being sent to customs clearance port";
        case 'CCE':
            return "clearance port complete";
        case 'OK':
            return "Delivery Complete";
        case 'CP':
            return "await";
        case 'CCMC':
            return "product lost";
        case 'CCSD':
            return "The goods have been destroyed";
        case 'HC':
            return "Customs fastener";
        case 'IDCS':
            return "ID card information collection";
        case 'IS':
            return "Handed over domestic delivery service provider";
        case 'PL':
            return "Internal operation of the operation center";
        case 'PO':
            return "Overseas warehouse made orders";
        case 'RT':
            return "The goods have been returned to the place of delivery";
        case 'SD':
            return "Damaged goods";
        case 'SH':
            return "Temporary deduction of goods";
        case 'PTW':
            return "The parcel is taken from the airport and transferred to the customs supervision warehouse.";
        case 'WA':
            return "Waiting to arrange a flight";
        case 'WT':
            return "Waiting for a transfer";
        case "WD":
            return "Waiting for customs clearance";
        default:
            return "unkown status";
    }
}

function getTrackingListHelper($trackingList){
    $formated_list = array();
    foreach ($trackingList as $list_item) {
        $new_node->location = $list_item->TrackLocation;
        $new_node->time = $list_item->TrackTime;
        $new_node->status = translateStatus($list_item->TrackStatusCode);
        array_push($formated_list,$new_node);
    }
    return $formated_list;
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
"Token"=> "TESTC78C-7923-404C-82CF-CD881539123C",
"Data"=> ["ShipperOrderNo"=> cleanValue($data_raw->strOrderNo)]


);

//call api to get data?
$data_string = json_encode($data_arr);

$url = "http://sandbox.transrush.com.au/Agent/getTrack";
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
    "orderNumber"=> $decoded_response->Data->ShipperOrderNo,
    // "message"=>translateResponseMsg($decoded_response->Message,$decoded_response->ResponseCode),
    "TrackingList"=> getTrackingListHelper($decoded_response->Data->TrackingList)
);

$final_response = json_encode($response_arr);

echo $final_response;

