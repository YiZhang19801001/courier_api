<?php

$wsdl   = "http://www.zhonghuan.com.au:8085/API/cxf/common/logisticsservice?wsdl";
$client = new SoapClient($wsdl, array('trace'=>1));  // The trace param will show you errors stack

$request_param = array(
    "fydh" => "970000717343",
    "countrytype"=>"au"
);


try
{
    $responce_param = $client->getLogisticsInformation($request_param);
   //$responce_param =  $client->call("webservice_methode_name", $request_param); // Alternative way to call soap method

   var_dump( $responce_param);
   echo "<br/>";
} 
catch (Exception $e) 
{ 
    echo "<h2>Exception Error!</h2>"; 
    echo $e->getMessage(); 
}

echo "<b>-------end</b>";
?>