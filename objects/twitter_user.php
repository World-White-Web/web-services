<?php
class TwitterUser{
    // database connection and table name
    private $conn;
    private $table_name = "Twitter_User";
 
    private $tweet_table = "Tweets";

    private $tweet_history_table = "Tweet_History";

    // object properties
    public $id;
    public $id_twitter;
    public $joined_date;
    public $created_at;
    public $socialCredibility;
    public $maxRetweets;
    public $maxFav;
    public $retweets;
    public $fav;


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

    function consultRetweets(){

        
        $this->id_twitter=htmlspecialchars(strip_tags($this->id_twitter));

        $query = "SELECT COUNT( DISTINCT c.id) 
                FROM " . $this->table_name . " p
                INNER JOIN Tweet c 
                    ON p.id = c.id_twitter_user 
                INNER JOIN Tweet_History h 
                    ON c.id = h.id_tweet 
                WHERE 
                    p.id_twitter = ?
                    AND h.retweets > 0 
                    AND h.created_at=  (SELECT MAX( x.created_at) 
                    FROM Tweet_History x
                        WHERE x.id_tweet= h.id_tweet)
        ";


        // prepare query statement
        $stmtRow = $this->conn->prepare( $query );
    
        // bind id of product to be updated
        $stmtRow->bindParam(1, $this->id_twitter);

        $stmtRow->execute();
        
        // get retrieved row
        $row = $stmtRow->fetch(PDO::FETCH_ASSOC);
        


        $this->retweets = $row['COUNT( DISTINCT c.id)'];
        //echo 'console.log('. json_encode( $this ) .')';
        return $stmtRow;

    }


    function consultFav(){

        $this->id_twitter=htmlspecialchars(strip_tags($this->id_twitter));
 
            
        $query = "SELECT  
                 COUNT(DISTINCT c.id) FROM Twitter_User p 
                INNER JOIN Tweet c 
                    ON p.id = c.id_twitter_user 
                INNER JOIN Tweet_History h 
                    ON c.id = h.id_tweet 
                WHERE 
                    p.id_twitter = ?
                    AND h.favorites > 0 
                    AND h.created_at=  (SELECT MAX( x.created_at) 
                    FROM Tweet_History x
                        WHERE x.id_tweet= h.id_tweet)

        ";


        // prepare query statement
        $stmtRow = $this->conn->prepare( $query );
    
        // bind id of product to be updated
        $stmtRow->bindParam(1, $this->id_twitter);
        
        $stmtRow->execute();
        
        // get retrieved row
        $row = $stmtRow->fetch(PDO::FETCH_ASSOC);
    
        // execute query
        //echo 'console.log('. json_encode( $row ) .')';

        $this->fav = $row['COUNT(DISTINCT c.id)'];
        //echo 'console.log('. json_encode( $this ) .')';
        return $stmtRow;
    }

    function maxRetweets(){

        
        $this->id_twitter=htmlspecialchars(strip_tags($this->id_twitter));

        $query = "SELECT DISTINCT  MAX(h.retweets)  
                FROM " . $this->table_name . " p
                INNER JOIN Tweet c 
                    ON p.id = c.id_twitter_user 
                INNER JOIN Tweet_History h 
                    ON c.id = h.id_tweet 
                WHERE 
                    p.id_twitter = ?
                    AND h.created_at=  (SELECT MAX( x.created_at) 
                    FROM Tweet_History x
                        WHERE x.id_tweet= h.id_tweet)
        ";


        // prepare query statement
        $stmtRow = $this->conn->prepare( $query );
    
        // bind id of product to be updated
        $stmtRow->bindParam(1, $this->id_twitter);

        $stmtRow->execute();
        
        // get retrieved row
        $row = $stmtRow->fetch(PDO::FETCH_ASSOC);
    

        $this->maxRetweets = $row['MAX(h.retweets)'];
       // echo 'console.log('. json_encode( $this ) .')';

        return $stmtRow;
    }

    function maxFav(){

        $this->id_twitter=htmlspecialchars(strip_tags($this->id_twitter));

            
        $query = "SELECT DISTINCT  MAX(h.favorites)  
                FROM " . $this->table_name . " p
                INNER JOIN Tweet c 
                    ON p.id = c.id_twitter_user 
                INNER JOIN Tweet_History h 
                    ON c.id = h.id_tweet 
                WHERE 
                    p.id_twitter = ?
                    AND h.created_at=  (SELECT MAX( x.created_at) 
                    FROM Tweet_History x
                        WHERE x.id_tweet= h.id_tweet)
        ";


        // prepare query statement
        $stmtRow = $this->conn->prepare( $query );
    
        // bind id of product to be updated
        $stmtRow->bindParam(1, $this->id_twitter);

        $stmtRow->execute();
        
        // get retrieved row
        $row = $stmtRow->fetch(PDO::FETCH_ASSOC);
        // execute query
        // echo 'console.log('. json_encode( $row['MAX(h.favorites)'] ) .')';
         $this->maxFav = $row['MAX(h.favorites)'];
         //echo 'console.log('. json_encode( $this ) .')';

        return $stmtRow;
    }



    function socialCredibility(){
        $this->id_twitter=htmlspecialchars(strip_tags($this->id_twitter));
        
            $query = "SELECT
            c.*
            FROM
                " . $this->table_name . " p
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
            $row = $stmtRow->fetch(PDO::FETCH_ASSOC);
            $followers=$row['followers'];



            $queryTweets = "SELECT COUNT( DISTINCT c.id) 
                FROM " . $this->table_name . " p
                INNER JOIN Tweet c 
                    ON p.id = c.id_twitter_user 
                WHERE 
                    p.id_twitter = ?
            ";


            // prepare queryTweets statement
            $stmttweets = $this->conn->prepare( $queryTweets );

            // bind id of product to be updated
            $stmttweets->bindParam(1, $this->id_twitter);

            $stmttweets->execute();

            // get retrieved row
            $rowTweets = $stmttweets->fetch(PDO::FETCH_ASSOC);
            // echo 'console.log('. json_encode( $rowTweets['COUNT( DISTINCT c.id)'] ) .')';
            $tweetsNumber= $rowTweets['COUNT( DISTINCT c.id)'];
            // get retrieved row
        
            $this->maxFav();    
            $this->maxRetweets();
            $this->consultRetweets();
            $this->consultFav();
            $lambdat=1.0/500000;

            $popularity=(min(array(1,1.0-(pow(2.71828,((-1)*$lambdat*$followers))))))*50;
            $proportion=(min(array(1,((((int)($this->maxRetweets)+(int)($this->maxFav))/(int)($followers))+(((int)($this->retweets)+(int)($this->fav))/(int)($tweetsNumber)))/2 )))*50;

            echo 'console.log('. json_encode( $popularity ) .')';
            echo 'console.log('. json_encode( $proportion ) .')';

            
            $socialCredibilityProv=$popularity+$proportion;
            
            // echo 'console.log('. json_encode( $socialCredibilityProv ) .')';

            $this->socialCredibility = $socialCredibilityProv;

            return $socialCredibilityProv;

    }
}
?>

