<?php
class Tweet{
    // database connection and table name
    private $conn;
    private $table_name = "Tweet";
 
    // object properties
    public $id;
    public $id_twitter_user;
    public $id_tweet_api;
    public $text;
    public $bad_words_filter;
    public $spam_filter;
    public $misspelling_filter;
    public $created_at;
    public $extraction_method;

 
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
                id_twitter_user=:id_twitter_user, 
                id_tweet_api=:id_tweet_api, text=:text ,bad_words_filter=:bad_words_filter,
                spam_filter=:spam_filter, misspelling_filter=:misspelling_filter,
                extraction_method=:extraction_method";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->id_twitter_user=htmlspecialchars(strip_tags($this->id_twitter_user));
        $this->id_tweet_api=htmlspecialchars(strip_tags($this->id_tweet_api));
        $this->text=htmlspecialchars(strip_tags($this->text));
        $this->bad_words_filter=htmlspecialchars(strip_tags($this->bad_words_filter));
        $this->spam_filter=htmlspecialchars(strip_tags($this->spam_filter));
        $this->misspelling_filter=htmlspecialchars(strip_tags($this->misspelling_filter));
        $this->extraction_method=htmlspecialchars(strip_tags($this->extraction_method));
    
        // bind values
        $stmt->bindParam(":id_twitter_user", $this->id_twitter_user);
        $stmt->bindParam(":id_tweet_api", $this->id_tweet_api);
        $stmt->bindParam(":text", $this->text);
        $stmt->bindParam(":bad_words_filter", $this->bad_words_filter);
        $stmt->bindParam(":spam_filter", $this->spam_filter);
        $stmt->bindParam(":misspelling_filter", $this->misspelling_filter);
        $stmt->bindParam(":extraction_method", $this->extraction_method);

    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;
        
    }
}
?>