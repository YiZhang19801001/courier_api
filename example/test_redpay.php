<?php
//headers
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

//map values
$data_arr = array(
    'amount'=>'3.60',
    'channel'=>'ALIPAY',
    'currency'=>'AUD',
    'item'=>'Clothes',
    'mchNo'=>'77902',
    'mchOrderNo'=>'20180909002',
    'params'=>"{\"buyerId\":285502587945850268}",
    'payWay'=>'SHOP_SCAN_QRCODE',
    'quantity'=>'1',
    'storeNo'=>'77911',
    'timestamp'=>'153613188',
    'version'=>'1.0'
);

$data_arr_str="";

foreach ($data_arr as $key => $value) {
    $data_arr_str .= $key.'-'.$value.'&';
}

$data_arr_str .="key-2c0ba056eafd47f681201ff022bf3130";

$sign = md5($data_arr_str);
$data_arr['sign']=$sign;

//call api to get data?
$data_string = json_encode($data_arr);

$url = "https://dev-service.redpayments.com.au/pay/gateway/create-order";
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

//echo $data_arr_str;
echo $curl_response;

// $response_arr=array(
//     "orderNumber"=> isset($decoded_response->UnionOrderNumber)?$decoded_response->UnionOrderNumber:null,
//     "resMsg"=>$res_arr['text'],
//     "resCode"=>$res_arr['code'],
//     "TaxAmount"=>isset($decoded_response->TaxAmount)?$decoded_response->UnionOrderNumber:null,
//     "TaxCurrencyCode"=>isset($decoded_response->CurrencyCodeTax)?$decoded_response->CurrencyCodeTax:null
// );



// /** output api value */

// $final_response = json_encode($response_arr);

// echo $final_response;
