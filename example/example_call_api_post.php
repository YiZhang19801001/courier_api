<?php

    $url = "http://192.168.1.5:5000/value/getbycategory";
    $curl = curl_init($url);
    $data = array('shopId'=>'6','dateFrom'=>'1/1/1900','dateTo'=>'1/1/3000');
    $data_string = json_encode($data);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                     
        'Content-Type: application/json',                                                                             
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
    echo 'response ok!';
    echo $curl_response;
    var_dump($curl_response);
    

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>geogram</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="script.js"></script>
</head>

<body>
    <form action="" method="get">
        <input type="text" name="location" />
        <button type="submit">Submit</button>
    </form>
    <br />
    <!-- <div id="results" data-url="<?php if (!empty($url)) echo $url ?>"> -->
    <div>
        <?php
    // if (!empty($array)) {
    //     foreach ($array['data'] as $key => $item) {
    //         echo '<img id="' . $item['id'] . '" src="' . $item['images']['low_resolution']['url'] . '" alt=""/><br/>';
    //     }
    // }
    if(!empty($maps_array )){
        echo $maps_array["name"];
    }
    ?>
    </div>
</body>

</html>