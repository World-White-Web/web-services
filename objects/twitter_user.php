<?php
class TwitterUser{
    // database connection and table name
    private $conn;
    private $table_name = "Twitter_User";
 
    // object properties
    public $id;
    public $id_twitter;
    public $joined_date;
    public $created_at;

 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    function read(){
 
        // select all query
        $query = "SELECT
                    *
                FROM
                    " . $this->table_name ;
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }



    // create product
    function create(){
        
        $query = "INSERT INTO
        " . $this->table_name . "
            SET
            id_twitter=:id_twitter,joined_date=:joined_date";
            
            // prepare query
        $stmt = $this->conn->prepare($query);
        


        //INSERT INTO employee(user_id,name,address,city) 
        //VALUES(:user_id,:name,:address,:city) RETURNING employee_id"   


        // sanitize
        //$this->created_at=htmlspecialchars(strip_tags($this->created_at));
    
        $this->id_twitter=htmlspecialchars(strip_tags($this->id_twitter));
        $this->joined_date=htmlspecialchars(strip_tags($this->joined_date));
    
        // bind values
        $stmt->bindParam(':id_twitter', $this->id_twitter);
        $stmt->bindParam(':joined_date', $this->joined_date);
        //$stmt->bindParam(':created_at', $this->created_at);

        // $stmt->execute();
       
        // $result = $stmt->fetch(PDO::FETCH_ASSOC);
        // echo 'console.log('. json_encode( $temp ) .')';
        // return $result["id"];


        //execute query
        if($stmt->execute()){   
            $this->id = $this->conn->lastInsertId();
            //echo 'console.log('. json_encode( $this->id ) .')';
            return true;
        }
    
        return false;
        
       


    }

    function exist(){

        $query = "SELECT
            *
            FROM
                " . $this->table_name . " 
            WHERE
                id_twitter = ?
            LIMIT
                0,1";
        
  
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
            
        $this->id_twitter=htmlspecialchars(strip_tags($this->id_twitter));

        // bind id of product to be updated
        $stmt->bindParam(1, $this->id_twitter);
    
        // execute query
        $stmt->execute();
    
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // set values to object properties



        if(count($row)>=4){
            $this->id = $row['id'];
            $this->id_twitter = $row['id_twitter'];
            $this->joined_date = $row['joined_date'];
            $this->created_at = $row['created_at'];
            return true;
        }
    
        return false;

    }   
}
?>