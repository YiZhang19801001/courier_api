<?php

$wsdl   = "http://www.zhonghuan.com.au:8085/API/cxf/common/logisticsservice?wsdl";
$client = new SoapClient($wsdl, array('trace'=>1));  // The trace param will show you errors stack

$request_param = array(
    "fydh" => "970000718135",
    //"fydh"=>"9700007181",
    "countrytype"=>"au"
);


try
{
    $responce_param = $client->getLogisticsInformation($request_param);
   //$responce_param =  $client->call("webservice_methode_name", $request_param); // Alternative way to call soap method

    // $result = json_decode(json_encode($request_param));
    $index = strpos($responce_param->return,">");
    
    $xml = simplexml_load_string(substr($responce_param->return,$index+1));
    
    $json = json_encode($xml);
    $array = json_decode($json);


    // $formated_list = array();
    // foreach ($array->Logisticsback as $list_item) {
    //     $new_node=array();
    //     $new_node['location'] = "";
    //     $new_node['time'] = $list_item->time;
    //     $new_node['status'] = $list_item->ztai;
    //     array_push($formated_list,$new_node);
    // }


    // var_dump($formated_list);

var_dump($array);
echo "<br/>";
} 
catch (Exception $e) 
{ 
    echo "<h2>Exception Error!</h2>"; 
    echo $e->getMessage(); 
}

echo "<b>-------end</b>";
?>