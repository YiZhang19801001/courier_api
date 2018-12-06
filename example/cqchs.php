<?php

$soap_client= new SoapClient("http://www.zhonghuan.com.au:8085/API/cxf/au/recordservice?wsdl");

$wsdl   = "http://www.zhonghuan.com.au:8085/API/cxf/au/recordservice?wsdl";
$client = new SoapClient($wsdl, array('trace'=>1));  // The trace param will show you errors stack

$stock = "<ydjbxx><chrusername>0104</chrusername><chrstockcode>au</chrstockcode><chrpassword>123456</chrpassword><chrzl>4.6</chrzl><chrsjr>魏小燕</chrsjr><chrsjrdz>福建省宁德市古田县城东街道614中一支路</chrsjrdz><chrsjrdh>13626994142</chrsjrdh><chrjjr>luyuan</chrjjr><chrjjrdh>0450494903</chrjjrdh><chrsfzhm>352227198407180525</chrsfzhm><ydhwxxlist><ydhwxx><chrpm>成人奶粉</chrpm><chrpp>德运</chrpp><chrggxh>900</chrggxh><chrjz>50.00</chrjz><chrjs>25</chrjs></ydhwxx><ydhwxx><chrpm>成人奶粉</chrpm><chrpp>德运</chrpp><chrggxh>900</chrggxh><chrjz>50.00</chrjz><chrjs>25</chrjs></ydhwxx></ydhwxxlist></ydjbxx>"; 


//$var = new \SoapVar($newStock,XSD_ANYXML);

$request_param = array(
    "stock" => $stock
);

echo "<b>-------begin</b><br/>";

try
{
    $responce_param = $client->getRecord($request_param);
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