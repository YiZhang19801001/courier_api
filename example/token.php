<?php

header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
        $url = "http://auth.auexpress.com/api/token";
        // 登录ID：2742
        // 密码：A09062742
        $member_id = "2742";
        $password = "A09062742";


        $data_arr = array("MemberId"=>$member_id,"Password"=>$password);

        $data_string = json_encode($data_arr);
        
        $curl = curl_init($url);
        
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json', 'Content-Length: ' . strlen($data_string)));  
        
        $curl_response = curl_exec($curl);
        
        curl_close($curl);

        echo $curl_response;