<?php
class TwitterUserAux{
    // database connection and table name
    private $conn;
    private $twitter_user = "Twitter_User";
    private $twitter_user_history = "Twitter_User_History";

 
    // object properties
    public $id;
    public $id_twitter;
    public $joined_date;


    public $id_twitter_user;
    public $user_name;
    public $following;
    public $followers;
    public $link;
    public $location;
    public $verified;
    public $followers_impact;
    public $user_credibility;
    public $extraction_method;

 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }





    // create User 
    // Falta verificar que cuando se vaya a insertar un historial no sea reciente sino cada 24,12,6, o 3 horas aproximandamente 
    function create(){

        // sanitize
        //$this->created_at=htmlspecialchars(strip_tags($this->created_at));
    
        $this->id_twitter=htmlspecialchars(strip_tags($this->id_twitter));
        $this->joined_date=htmlspecialchars(strip_tags($this->joined_date));

        //$this->id_twitter_user=htmlspecialchars(strip_tags($this->id_twitter_user));
        $this->user_name=htmlspecialchars(strip_tags($this->user_name));
        $this->following=htmlspecialchars(strip_tags($this->following));
        $this->followers=htmlspecialchars(strip_tags($this->followers));
        $this->link=htmlspecialchars(strip_tags($this->link));
        $this->location=htmlspecialchars(strip_tags($this->location));
        $this->verified=htmlspecialchars(strip_tags($this->verified));
        $this->followers_impact=htmlspecialchars(strip_tags($this->followers_impact));
        $this->user_credibility=htmlspecialchars(strip_tags($this->user_credibility));
        $this->extraction_method=htmlspecialchars(strip_tags($this->extraction_method));

        $row = '';
        if($this->extraction_method =='0' ){

            $query = "SELECT
                c.*
            FROM
                " . $this->twitter_user . " p
                INNER JOIN
                    Twitter_User_History c
                        ON p.id = c.id_twitter_user
            WHERE
                c.user_name = ? 
            ORDER BY c.created_at DESC              
            LIMIT
                0,1";

            $stmtRow = $this->conn->prepare( $query );
        
            // bind id of product to be updated
            $stmtRow->bindParam(1, $this->user_name);
    
            $stmtRow->execute();
        
            // get retrieved row
            $row = $stmtRow->fetch(PDO::FETCH_ASSOC);

            $var= $row['created_at'];
            
            if((time()-(60*60*1)) - strtotime($var) < 1800){
                // echo 'console.log('. json_encode( "existe y la hora es cercana" ) .')';
                // echo 'console.log('. json_encode( $row ) .')';

                return false;
            }


        }else{

            $query = "SELECT
            c.*
            FROM
                " . $this->twitter_user . " p
                INNER JOIN
                    Twitter_User_History c
                        ON p.id = c.id_twitter_user
            WHERE
                p.id_twitter = ?
            ORDER BY c.created_at DESC             
            LIMIT
                0,1";

            // prepare query statement
            $stmtRow = $this->conn->prepare( $query );
                
            // bind id of product to be updated
            $stmtRow->bindParam(1, $this->id_twitter);

            $stmtRow->execute();
        
            // get retrieved row
            $row = $stmtRow->fetch(PDO::FETCH_ASSOC);
            //echo 'console.log('. json_encode( $this ) .')';

            $var= $row['created_at'];

            // echo 'console.log('. json_encode( (time()-(60*60*0.5) ))  .')';
            // echo 'console.log('. json_encode( (time()-(60*60*1) ))  .')';

            // $var=time() - strtotime($var);
            if((time()-(60*60*1)) - strtotime($var) < 1800){
                // echo 'console.log('. json_encode( "existe y la hora es cercana" ) .')';
                // echo 'console.log('. json_encode( $row ) .')';

                return false;
            }
            // echo 'console.log('. json_encode( "existe y la hora no es cercana" ) .')';
            //     echo 'console.log('. json_encode( $row ) .')';
        }

        
        if(count($row)>=4){
            $this->id_twitter_user = $row['id_twitter_user'];  
            

            $queryHistory = "INSERT INTO
            " . $this->twitter_user_history . "
                SET
                id_twitter_user=:id_twitter_user, user_name=:user_name,following=:following,
                followers=:followers, link=:link ,location=:location,verified=:verified,
                followers_impact=:followers_impact,user_credibility=:user_credibility,
                extraction_method=:extraction_method";
                
                // prepare query
            $stmtHistory = $this->conn->prepare($queryHistory);
                

            $stmtHistory->bindParam(":id_twitter_user", $this->id_twitter_user);
            $stmtHistory->bindParam(":user_name", $this->user_name);
            $stmtHistory->bindParam(":following", $this->following);
            $stmtHistory->bindParam(":followers", $this->followers);
            $stmtHistory->bindParam(":link", $this->link);
            $stmtHistory->bindParam(":location", $this->location);
            $stmtHistory->bindParam(":verified", $this->verified);
            $stmtHistory->bindParam(":followers_impact", $this->followers_impact);
            $stmtHistory->bindParam(":user_credibility", $this->user_credibility);
            $stmtHistory->bindParam(":extraction_method", $this->extraction_method);

            if($stmtHistory->execute()){
                return true;
            }
        
            return false;

            
        } else {
            
            $queryUser = "INSERT INTO
            " . $this->twitter_user . "
                SET
                id_twitter=:id_twitter,joined_date=:joined_date";
                
            // prepare query
            $stmt = $this->conn->prepare($queryUser);
            // bind values
            $stmt->bindParam(':id_twitter', $this->id_twitter);
            $stmt->bindParam(':joined_date', $this->joined_date);
            
            // echo 'console.log('. json_encode( "no existe" ) .')';
          
            
            if($stmt->execute()){   
                $this->id = $this->conn->lastInsertId();
                // echo 'console.log('. json_encode( $this ) .')';
                
                $queryHistory = "INSERT INTO
                " . $this->twitter_user_history . "
                    SET
                    id_twitter_user=:id_twitter_user, user_name=:user_name,following=:following,
                    followers=:followers, link=:link ,location=:location,verified=:verified,
                    followers_impact=:followers_impact,user_credibility=:user_credibility,
                    extraction_method=:extraction_method";
    
                // prepare query
                $stmtHistory = $this->conn->prepare($queryHistory);
    
                $stmtHistory->bindParam(":id_twitter_user", $this->id);
                $stmtHistory->bindParam(":user_name", $this->user_name);
                $stmtHistory->bindParam(":following", $this->following);
                $stmtHistory->bindParam(":followers", $this->followers);
                $stmtHistory->bindParam(":link", $this->link);
                $stmtHistory->bindParam(":location", $this->location);
                $stmtHistory->bindParam(":verified", $this->verified);
                $stmtHistory->bindParam(":followers_impact", $this->followers_impact);
                $stmtHistory->bindParam(":user_credibility", $this->user_credibility);
                $stmtHistory->bindParam(":extraction_method", $this->extraction_method);
    
    
                if($stmtHistory->execute()){
                    return true;
                }
            
                return false;
    
            }

            return false;
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