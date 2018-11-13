<?php
//headers
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once './config/Database.php';
include_once './models/User.php';

//get raw posted data
$data_raw = json_decode(file_get_contents("php://input"));

//instantiate DB & connect
$database = new Database();
$db = $database->connect();
//call model method to validation
$user = new User($db);
//validate user
$user->name = isset($data_raw->name)?$data_raw->name:null;
$user->address = isset($data_raw->address)?$data_raw->address:null;
$user->branchId = uniqid($name,false);
$user->branchKey = md5(uniqid($address,true));

// $branchId = uniqid($name,false);
// $branchKey = md5(uniqid($address,true));
// valide with DB data


// create token
if($user->create()){
    $res = array('resCode'=>0,'resMsg'=>'your token created','branchId'=>$user->branchId,'branchKey'=>$user->branchKey);
    echo json_encode($res);
}
else{
    echo json_encode(array('resCode'=>1,'resMsg'=>'creating token failed, please contact XXXX-XXX-XXX'));
}
