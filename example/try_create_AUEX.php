<?php

    $url = "http://www.auexpress.com.au/MobileApps.asmx/GetOrderInfo";
    $curl = curl_init($url);
    $data = array('shopId'=>'6','dateFrom'=>'1/1/1900','dateTo'=>'1/1/3000');
    $data_string = "strOrderId=adb&strSecretKey=sdfa";
    //$data_string = json_encode($data_string);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                     
        'Content-Type: application/x-www-form-urlencoded',                                                                             
        'Content-Length: ' . strlen($data_string))                                                                    );  


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
    //echo 'response ok!';
    //var_dump($curl_response);
    //echo $curl_response;
   $json = simplexml_load_string($curl_response);
   $res = json_decode($json);
   echo $res->OrderId;