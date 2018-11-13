<?php
class User{
    //DB stuff
    private $conn;
    private $table = 'users';

    //User Properties
    public $branchId;
    public $name;
    public $address;
    public $branchKey;
    public $status;

    //Constructor with DB
    public function __construct($db){
        $this->conn = $db;
    }

    //get User
    public function read() {
        //create query
        $query = 'SELECT *
                FROM 
                ' .$this->table;
        //Prepare statement
        $stmt = $this->conn->prepare($query);
    
        //Execute query
        $stmt->execute();

        return $stmt;
    }

    // Get Sing User, return searching result [2=>not found, 1=> found & active, 3=> inactive]
    public function find($branchId, $branchKey) {
        //create query
        $query = 'SELECT * FROM '.$this->table. ' WHERE branchId = ? && branchKey = ?';

        //Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind Param
        $stmt->bindParam(1,$branchId);
        $stmt->bindParam(2,$branchKey);
    
        //Execute query
        $stmt->execute();

        $num = $stmt->rowCount();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        //return searching result [2=>not found, 1=> found & active, 3=> inactive]
        if($num == 0)
        {
            return 2;
        }
        else if($user['status']==0)
        {
            return 3;
        }
        else
        {
            return 1;
        }

        return $stmt;
    }

    // Create new User and return branchId && branchKey for further using
    public function create() {
        //Create query
        $query = 'INSERT INTO '. $this->table .'
            SET
                branchId = :branchId,
                name = :name,
                address = :address,
                branchKey = :branchKey,
                status = 1
                ';

        //Prepare statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->address = htmlspecialchars(strip_tags($this->address));

        if($this->IsEmptyOrNull($this->name) || $this->IsEmptyOrNull($this->address))
        {
            return false;
        }

        if($this->findExist())
        {
            return true;
        }
        
        //Bind data
        $stmt->bindParam(':branchId',$this->branchId);
        $stmt->bindParam(':name',$this->name);
        $stmt->bindParam(':address',$this->address);
        $stmt->bindParam(':branchKey',$this->branchKey);

        //Execute query
        if($stmt->execute()){
            return true;
        }

        // //Print error if something goes wrong
        // printf("Error: %s. \n",$stmt->error);

        return false;
    }

    private function IsEmptyOrNull($str){
        return !isset($str) || empty($str) || trim($str)==='';
    }

    private function findExist(){
        //create query
        $query = 'SELECT * FROM '.$this->table. ' WHERE name = ? && address = ?';

        //Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind Param
        $stmt->bindParam(1,$this->name);
        $stmt->bindParam(2,$this->address);
    
        //Execute query
        $stmt->execute();

        $num = $stmt->rowCount();

        if($num == 0)
        {
            return false;
        }
        else
        {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->branchId = $user['branchId'];
            $this->branchKey = $user['branchKey'];
            return true;
        }
    }
}
