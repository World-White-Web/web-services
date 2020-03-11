<?php
class TwitterUserHistory{
    // database connection and table name
    private $conn;
    private $table_name = "Twitter_User_History";
 
    // object properties
    public $id;
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



    function create(){
    
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                id_twitter_user=:id_twitter_user, user_name=:user_name,following=:following,
                followers=:followers, link=:link ,location=:location,verified=:verified,
                followers_impact=:followers_impact,user_credibility=:user_credibility,
                extraction_method=:extraction_method";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->id_twitter_user=htmlspecialchars(strip_tags($this->id_twitter_user));
        $this->user_name=htmlspecialchars(strip_tags($this->user_name));
        $this->following=htmlspecialchars(strip_tags($this->following));
        $this->followers=htmlspecialchars(strip_tags($this->followers));
        $this->link=htmlspecialchars(strip_tags($this->link));
        $this->location=htmlspecialchars(strip_tags($this->location));
        $this->verified=htmlspecialchars(strip_tags($this->verified));
        $this->followers_impact=htmlspecialchars(strip_tags($this->followers_impact));
        $this->user_credibility=htmlspecialchars(strip_tags($this->user_credibility));
        $this->extraction_method=htmlspecialchars(strip_tags($this->extraction_method));
    
        // bind values
        $stmt->bindParam(":id_twitter_user", $this->id_twitter_user);
        $stmt->bindParam(":user_name", $this->user_name);
        $stmt->bindParam(":following", $this->following);
        $stmt->bindParam(":followers", $this->followers);
        $stmt->bindParam(":link", $this->link);
        $stmt->bindParam(":location", $this->location);
        $stmt->bindParam(":verified", $this->verified);
        $stmt->bindParam(":followers_impact", $this->followers_impact);
        $stmt->bindParam(":user_credibility", $this->user_credibility);
        $stmt->bindParam(":extraction_method", $this->extraction_method);
    
    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;
        
    }

    
}
?>