<?php
class TweetHistory{
    // database connection and table name
    private $conn;
    private $table_name = "Tweet_History";
 
    // object properties
    public $id;
    public $id_tweet;
    public $tweet_credibility;
    public $retweets;
    public $favorites;
    public $replies;
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
                id_tweet=:id_tweet, 
                tweet_credibility=:tweet_credibility, retweets=:retweets ,favorites=:favorites,
                replies=:replies";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->id_tweet=htmlspecialchars(strip_tags($this->id_tweet));
        $this->tweet_credibility=htmlspecialchars(strip_tags($this->tweet_credibility));
        $this->retweets=htmlspecialchars(strip_tags($this->retweets));
        $this->favorites=htmlspecialchars(strip_tags($this->favorites));
        $this->replies=htmlspecialchars(strip_tags($this->replies));
    
    
        // bind values
        $stmt->bindParam(":id_tweet", $this->id_tweet);
        $stmt->bindParam(":tweet_credibility", $this->tweet_credibility);
        $stmt->bindParam(":retweets", $this->retweets);
        $stmt->bindParam(":favorites", $this->favorites);
        $stmt->bindParam(":replies", $this->replies);
        

    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;
        
    }


    
}
?>