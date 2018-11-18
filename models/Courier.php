<?php
class Courier{
    //DB stuff
    private $conn;
    private $courier_table = 'couriers';
    
    private $courier_code;
    private $request_type;
    private $API_KEY = "TESTC78C-7923-404C-82CF-CD881539123c";

    //Constructor with DB
    public function __construct($courier_name,$request_type,$db){
        $this->courier_code = $courier_name;
        $this->request_type = $request_type;
        $this->conn = $db;
    }
    
    public function getApiKey(){
        //create query
        $query = 'SELECT * FROM couriers WHERE code = :courier_code';

        //Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind Param
        $stmt->bindParam(':courier_code',$this->courier_code);
    
        //Execute query
        $stmt->execute();

        //$num = $stmt->rowCount();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['api_key'];
    }

    public function getUrl(){
        //create query
        $query = 'SELECT * FROM api_urls WHERE courier_code = :courier_code && request_type = :request_type';

        //Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind Param
        $stmt->bindParam(':courier_code',$this->courier_code);
        $stmt->bindParam(':request_type',$this->request_type);
    
        //Execute query
        $stmt->execute();

        //$num = $stmt->rowCount();

        $api_url = $stmt->fetch(PDO::FETCH_ASSOC);

        return $api_url['request_url'];
    }

    public function makeResponseMsg($code){

        //create query
        $query = 'SELECT * FROM error_messages WHERE courier_code = :courier_code && request_type = :request_type && code = :code';

        //Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind Param
        $stmt->bindParam(':courier_code',$this->courier_code);
        $stmt->bindParam(':request_type',$this->request_type);
        $stmt->bindParam(':code',$code);
    
        //Execute query
        $stmt->execute();

        $num = $stmt->rowCount();

        if($num>0)
        {

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
            //create returning oject
            $res_arr = array(
                'text'=>$row['res_msg'],
                'code'=>$row['res_code']
            );
        }
        else
        {
            //create returning oject
            $res_arr = array(
                'text'=>'error! contact XXXX-XXX-XXX',
                'code'=>'ERR99999'
            );
        }

        return $res_arr;
    }
}