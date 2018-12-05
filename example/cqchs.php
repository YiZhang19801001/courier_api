<?php

$soap_client= new SoapClient("http://www.zhonghuan.com.au:8085/API/cxf/au/recordservice?wsdl");


        $stock = "<?xml version='1.0' encoding='UTF-8' standalone='no'?>";

        $stock.="<ydjbxx>";
        $stock.='<chrusername>0104</chrusername>';
        $stock.="<chrstockcode>au</chrstockcode>";
        $stock.="<chrpassword>123456</chrpassword>";
        // $stock.="<chryyrmc>0104</chryyrmc>";
        // $stock.="<chrzydhm>160-91239396</chrzydhm>";
        // $stock.="<chrhbh>CX110/CX052</chrhbh>";
        // $stock.="<chrjckrq>2015-06-25</chrjckrq>";       
        $stock.="<chrzl>4.6</chrzl>";
        $stock.="<chrsjr>魏小燕</chrsjr>";
        $stock.="<chrsjrdz>福建省宁德市古田县城东街道614中一支路</chrsjrdz>";
        $stock.="<chrsjrdh>13626994142</chrsjrdh>";
        $stock.="<chrjjr>luyuan</chrjjr>";
        $stock.="<chrjjrdh>0450494903</chrjjrdh>";       
        //$stock.="<chrsfzhm>352227198407180525</chrsfzhm>";
        $stock.="<ydhwxxlist>";
        $stock.="<ydhwxx>";
        $stock.="<chrpm>成人奶粉</chrpm>";
        $stock.="<chrpp>德运</chrpp>";
        $stock.="<chrggxh>900</chrggxh>";
        $stock.="<chrjz>50.00</chrjz>";
        $stock.="<chrjs>25</chrjs>";
        $stock.="</ydhwxx>";     
        $stock.="<ydhwxx>";
        $stock.="<chrpm>成人奶粉</chrpm>";
        $stock.="<chrpp>德运</chrpp>";
        $stock.="<chrggxh>900</chrggxh>";
        $stock.="<chrjz>50.00</chrjz>";
        $stock.="<chrjs>25</chrjs>";
        $stock.="</ydhwxx>";
        $stock.="</ydhwxxlist>";      
        $stock.="</ydjbxx>";

        $newStock = "<ydjbxx><chrusername>0104</chrusername><chrstockcode>au</chrstockcode><chrpassword>123456</chrpassword><chrzl>4.6</chrzl><chrsjr>魏小燕</chrsjr><chrsjrdz>福建省宁德市古田县城东街道614中一支路</chrsjrdz><chrsjrdh>13626994142</chrsjrdh><chrjjr>luyuan</chrjjr><chrjjrdh>0450494903</chrjjrdh><chrsfzhm>352227198407180525</chrsfzhm><ydhwxxlist><ydhwxx><chrpm>成人奶粉</chrpm><chrpp>德运</chrpp><chrggxh>900</chrggxh><chrjz>50.00</chrjz><chrjs>25</chrjs></ydhwxx><ydhwxx><chrpm>成人奶粉</chrpm><chrpp>德运</chrpp><chrggxh>900</chrggxh><chrjz>50.00</chrjz><chrjs>25</chrjs></ydhwxx></ydhwxxlist></ydjbxx>"; 


//$var = new \SoapVar($newStock,XSD_ANYXML);

echo json_encode($soap_client->getRecord($newStock));