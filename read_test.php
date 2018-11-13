
<?php
//headers
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');
// header('Access-Control-Allow-Methods: POST');
// header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once './config/Database.php';
include_once './models/User.php';

//instantiate DB & connect
$database = new Database();
$db = $database->connect();

//
$user = new User($db);

$result = $user->read();

$num = $result->rowCount();

if($num >0){
    $users_arr = array();
    $users_arr['data']=array();

    while($row = $result->fetch(PDO::FETCH_ASSOC)){
        extract($row);

        $user_item = array(
            'id'=>$branchId,
            'name'=>$name,
            'address'=>$address,
            'key'=>$branchKey,
            'status'=>$status==1?'active':'inactive'
        );

        array_push($users_arr['data'],$user_item);
    }

    echo json_encode($users_arr);

}
else{
    echo "no results";
}