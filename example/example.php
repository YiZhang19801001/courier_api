<?php
//headers
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');

//include_once '../../config/Database.php';
//include_once '/models/Post.php' ->this is model to generate values

//call api to get data?
    $url = "http://sandbox.transrush.com.au/agent/createPickupItem";
    $curl = curl_init($url);
    $data = array(
	"Token"=> "TESTC78C-7923-404C-82CF-CD881539123c",
    "Data"=> [
        "ShipperOrderNo"=> "tesdfs922011",
        "ServiceTypeCode"=> "EC",
        "TerminalCode"=> " MPX01",
        "ConsignerName"=> "陶伟良", 
        "ConsignerMobile"=> "16987654321",
        "ConsigneeName"=> "陶伟良",
        "Province"=> "广东省",
        "City"=> "深圳市",
        "District"=> "宝安区",
        "ConsigneeStreetDoorNo"=> "宝城6区新安二路57号",
        "ConsigneeMobile"=> "13245671234",
        "OrderWeight"=> "1.000",
        "WeightUnit"=> "KG",
        "ItemDeclareCurrency"=> "CNY",
        "InsuranceTypeCode"=> "",
        "EndDeliveryType"=> "",
       "TraceSourceNumber"=> "",
        "ITEMS"=> [
            [
                "ItemSKU"=> "test123456",
                "ItemDeclareType"=> "01010700002",
                "ItemBrand"=> "A2",
                "ItemName"=> "婴幼儿奶粉",
                "ItemQuantity"=> "1",
                "Specifications"=> "900g二段",
                "ItemUnitPrice"=> "1000",
                "PreferentialSign"=> ""
            ]
        ]
    ]
    
    );
    $data_string = json_encode($data);

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
    $decoded = json_decode($curl_response);
    if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
        die('error occured: ' . $decoded->response->errormessage);
    }

     echo $decoded->ResponseCode;