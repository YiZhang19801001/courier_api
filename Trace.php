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
            return array("code"=>"0","text"=>"Order Successfully Created, Please Save your order number for further service refferences");
        case 13005:
            return array("code"=>"1","text"=>"Order can not find!");
        case 12001:
            return array("code"=>"ERR12001","text"=>"order has not been created, please try again or contact xxx-xxx-xxxx");
        case 11004:
            return array("code"=>"ERR11004","text"=>"you have to provide valid ship order number");
        case 12004:
            return array("code"=>"ERR12004","text"=>"your ship order number has been used, please check and try again");
        case 12032:    
            return array("code"=>"ERR12032","text"=>"you have to provide valid ship order number");
        case 11005:
            return array("code"=>"ERR11005","text"=>"you have to provide valid service type code");
        case 12005:
            return array("code"=>"ERR12005","text"=>"you have to provide valid service type code");
        case 11006:
            return array("code"=>"ERR11006","text"=>"sender name can not be empty");
        case 11007:
            return array("code"=>"ERR11007","text"=>"sender mobile can not be empty");
        case 11009:
            return array("code"=>"ERR11009","text"=>"receiver name can not be empty");
        case 11010:
            return array("code"=>"ERR11010","text"=>"receiver province can not be empty");
        case 12010:
            return array("code"=>"ERR12010","text"=>"invalid receiver province");
        case 11011:
            return array("code"=>"ERR11011","text"=>"receiver city name can not be empty");
        case 12011:
            return array("code"=>"ERR12011","text"=>"receiver city name is invalid");
        case 11012:
            return array("code"=>"ERR11012","text"=>"receiver district can not be empty");
        case 12012:
            return array("code"=>"ERR12012","text"=>"receiver district is invalid");
        case 11013:
            return array("code"=>"ERR11013","text"=>"receiver street door number can not be empty");
        case 11014:
            return array("code"=>"ERR11014","text"=>"receiver mobile can not be empty");
        case 11015:
            return array("code"=>"ERR11015","text"=>"receiver ID number can not be empty");
        case 12015:
            return array("code"=>"ERR12015","text"=>"ID number is invalid");
        case 11016:
            return array("code"=>"ERR11016","text"=>"receiver ID front copy can not be empty");
        case 11017:
            return array("code"=>"ERR11017","text"=>"receiver ID back copy can not be empty");
        case 11018:
            return array("code"=>"ERR11018","text"=>"order weight can not be 0");
        case 11019:
            return array("code"=>"ERR11019","text"=>"order is overweight");
        case 11020:
            return array("code"=>"ERR11020","text"=>"order unit weight is invalid");
        case 11008:
            return array("code"=>"ERR11008","text"=>"item declare currency can not be empty");
        case 12008:
            return array("code"=>"ERR12008","text"=>"item declare currency is invalid");
        case 11019:
            return array("code"=>"ERR11019","text"=>"can not find any items in this order");
        case 11020:
            return array("code"=>"ERR11020","text"=>"item name can not be empty");
        case 11021:
            return array("code"=>"ERR11021","text"=>"item quantity can not be empty");
        case 11022:
            return array("code"=>"ERR11022","text"=>"item specifications can not be empty");
        case 11024:
            return array("code"=>"ERR11024","text"=>"item declare type can not be empty");
        case 12024:
            return array("code"=>"ERR12024","text"=>"item declare type is invalid");
        case 12025:
            return array("code"=>"ERR12025","text"=>"item is not promised for delivery");
        case 12026:
            return array("code"=>"ERR12026","text"=>"order cost is over the limitation");
        case 12027:
            return array("code"=>"ERR12027","text"=>"receiver name has to be Chinese, as receiver's country is China");
        case 12028:
            return array("code"=>"ERR12028","text"=>"product, service, end delivery and product type is not matching");
        case 12029:
            return array("code"=>"ERR12029","text"=>"item information is invalid");
        case 12030:
            return array("code"=>"ERR12030","text"=>"insurance type is invalid");
        case 12031:
            return array("code"=>"ERR12031","text"=>"item brand can not be empty");
        case 12033:
            return array("code"=>"ERR12033","text"=>"shop code can not be empty, as your company has multiple shops");
        case 12034:
            return array("code"=>"ERR12034","text"=>"trace source number is invalid");
        case 12035:
            return array("code"=>"ERR12035","text"=>"trace source number can only be used in Australlian");
        case 11025:
            return array("code"=>"ERR11025","text"=>"insurance expense can not be empty");
        case 12036:
            return array("code"=>"ERR12036","text"=>"insurance expense is not valid");
        case 12037:
            return array("code"=>"ERR12037","text"=>"country code is invalid");
        case 13001:
            return array("code"=>"ERR13001","text"=>"fail to create order, try again or contanct XXXX-XXX-XXX");
        case 13004:
            return array("code"=>"ERR13004","text"=>"havenot received the products");
        case 14000:
            return array("code"=>"ERR14000","text"=>"fail to find the destination, please check your input");
        case A0001:
            return array("code"=>"ERRA0001","text"=>"ItemSKU is missing");
        case A0002:
            return array("code"=>"ERRA0002","text"=>"ItemSKU is not found");
        case A0003:
            return array("code"=>"ERRA0003","text"=>"ItemSKU is valid yet");

        default:
            return array("code"=>"ERR99999","text"=>"error! please contact XXXX-XXX-XXX");
    }
}

//get raw posted data
$data_raw = json_decode(file_get_contents("php://input"));

$shop_id = $data_raw->branchId;
$shop_key = $data_raw->branchKey;
// todo:: valide with DB data
$validation_res = 2;
if($shop_id == "SHOP111" && $shop_key = "123456")
{
    $validation_res = 1;
}
else if($shop_id == "SHOP333" && $shop_key = "123456")
{
    $validation_res = 3;
}
else{
    $validation_res =2;
}


if($validation_res==1)
{
    //map values
    $data_arr = array(
    "Token"=> "TESTC78C-7923-404C-82CF-CD881539123C",
    "Data"=> ["ShipperOrderNo"=>isset($data_raw->strOrderNo)?cleanValue($data_raw->strOrderNo):null]
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

    $res_arr=translateResponseMsg($decoded_response->Message,$decoded_response->ResponseCode);

    $response_arr=array(
        "orderNumber"=> isset($decoded_response->Data->ShipperOrderNo)?$decoded_response->Data->ShipperOrderNo:null,
        "resMsg"=>$res_arr['text'],
        "resCode"=>$res_arr['code'],
        "TrackingList"=>isset($decoded_response->Data->TrackingList)?getTrackingListHelper($decoded_response->Data->TrackingList):null
    );

    $final_response = json_encode($response_arr);

    echo $final_response;
}
else if($validation_res == 2){
    $response_arr=array(
        "resCode" => "2",
        "resMsg" => "your account is not authorized, please contact XXX-XXXX-XXX"
    );

    $final_response = json_encode($response_arr);
    
    echo $final_response;
}
else if($validation_res ==3){
    $response_arr=array(
        "resCode" => "3",
        "resMsg" => "your account is inactived, please contact XXX-XXXX-XXX"
    );

    $final_response = json_encode($response_arr);
    
    echo $final_response;
}
