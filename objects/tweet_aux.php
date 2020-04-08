<?php
class TweetAux{
    // database connection and table name
    private $conn;
                 
    
    private $tweet = "Tweet";
    private $tweet_history = "Tweet_History";
    private $tweet_media = "Tweet_Media";
    private $twitter_user = "Twitter_User";


    public $text;
    public $bad_words_filter;
    public $spam_filter;
    public $misspelling_filter;
    public $extraction_method;

    // object history
    public $id_tweet;
    public $tweet_credibility;
    public $retweets;
    public $favorites;
    public $replies;

    // object media
    public $tweet_url;
    public $type;
    
    //user 
    public $userAPIid;

 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }


    function create(){


        
        // Tweet
        //$this->id_twitter_user=htmlspecialchars(strip_tags($this->id_twitter_user));
        $this->id_tweet_api=htmlspecialchars(strip_tags($this->id_tweet_api));
        $this->text=htmlspecialchars(strip_tags($this->text));
        $this->bad_words_filter=htmlspecialchars(strip_tags($this->bad_words_filter));
        $this->spam_filter=htmlspecialchars(strip_tags($this->spam_filter));
        $this->misspelling_filter=htmlspecialchars(strip_tags($this->misspelling_filter));
        $this->extraction_method=htmlspecialchars(strip_tags($this->extraction_method));
    
        // Tweet History
        //$this->id_tweet=htmlspecialchars(strip_tags($this->;
        $this->tweet_credibility=htmlspecialchars(strip_tags($this->tweet_credibility));
        $this->retweets=htmlspecialchars(strip_tags($this->retweets));
        $this->favorites=htmlspecialchars(strip_tags($this->favorites));
        $this->replies=htmlspecialchars(strip_tags($this->replies));
    
        //Tweet media 
        $this->tweet_url=htmlspecialchars(strip_tags($this->tweet_url));
        $this->type=htmlspecialchars(strip_tags($this->type));
        
        // tweet User

        $this->userAPIid= htmlspecialchars(strip_tags($this->userAPIid));
        // verificar si ya existe el tweet 


        $row = '';

        if($this->extraction_method ==0 ){

            $query = "SELECT
                c.*
            FROM
                " . $this->tweet . " p
                INNER JOIN
                    Tweet_History c
                        ON p.id = c.id_tweet
                WHERE
                    c.text = ? 
                ORDER BY c.created_at  DESC            
                LIMIT
                    0,1";

            $stmtRow = $this->conn->prepare( $query );
        
            // bind id of product to be updated
            $stmtRow->bindParam(1, $this->text);
    
            $stmtRow->execute();
        
            // get retrieved row
            $row = $stmtRow->fetch(PDO::FETCH_ASSOC);

            $var= $row['created_at'];
            // strtotime($var);
            // $var=time() - strtotime($var);
            if((time()-(60*60*1)) - strtotime($var) < 1800){
                // echo 'console.log('. json_encode( "existe y la hora es cercana" ) .')';
                // echo 'console.log('. json_encode( $row ) .')';

                return false;
            }


        }else{

            $query = "SELECT
                c.*
            FROM
                " . $this->tweet . " p
                INNER JOIN
                    Tweet_History c
                        ON p.id = c.id_tweet
                WHERE
                    p.id_tweet_api = ? 
                ORDER BY c.created_at DESC             
                LIMIT
                    0,1";

            // prepare query statement
            $stmtRow = $this->conn->prepare( $query );
          
            // bind id of product to be updated
            $stmtRow->bindParam(1, $this->id_tweet_api);

            $stmtRow->execute();
            
            // get retrieved row
            $row = $stmtRow->fetch(PDO::FETCH_ASSOC);
            $this->id_tweet=$row['id_tweet'];
            $var= $row['created_at'];

            if((time()-(60*60*1)) - strtotime($var) < 1800){
                //echo 'console.log('. json_encode( "existe y la hora es cercana" ) .')';
                // echo 'console.log('. json_encode( $row ) .')';

                return false;
            }
        }



        if(count($row)>=4){

            // query to insert record
            //echo 'console.log('. json_encode( $this ) .')';

            $queryHistory = "INSERT INTO
                " . $this->tweet_history . "
                SET
                id_tweet=:id_tweet, 
                tweet_credibility=:tweet_credibility, retweets=:retweets ,favorites=:favorites,
                replies=:replies";

            // prepare query
            $stmtHistory = $this->conn->prepare($queryHistory);

            // bind values
            $stmtHistory->bindParam(":id_tweet", $this->id_tweet);
            $stmtHistory->bindParam(":tweet_credibility", $this->tweet_credibility);
            $stmtHistory->bindParam(":retweets", $this->retweets);
            $stmtHistory->bindParam(":favorites", $this->favorites);
            $stmtHistory->bindParam(":replies", $this->replies);

            // execute query
            if($stmtHistory->execute()){

               return true;
            }
            return false;

        }else{

            $queryUser = "SELECT
                *
                FROM
                " . $this->twitter_user . " 
                WHERE
                    id_twitter = ?
                ORDER BY created_at                 
                LIMIT
                    0,1";

            // prepare queryUser statement
            $stmtRowUser = $this->conn->prepare( $queryUser );
                
            // bind id of product to be updated
            $stmtRowUser->bindParam(1, $this->userAPIid);

            $stmtRowUser->execute();

            // get retrieved row
            $rowUser = $stmtRowUser->fetch(PDO::FETCH_ASSOC);

            $this->id_twitter_user= $rowUser['id'];
            
            
            // query to insert record
            $queryTweet = "INSERT INTO
                    " . $this->tweet . "
                SET
                id_twitter_user=:id_twitter_user, 
                id_tweet_api=:id_tweet_api, text=:text ,bad_words_filter=:bad_words_filter,
                spam_filter=:spam_filter, misspelling_filter=:misspelling_filter,
                extraction_method=:extraction_method";
                
                // prepare query
                $stmt = $this->conn->prepare($queryTweet);
                    

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
                
                
                $this->id_tweet = $this->conn->lastInsertId();
                
                // query to insert record
                $queryHistory = "INSERT INTO
                    " . $this->tweet_history . "
                    SET
                    id_tweet=:id_tweet, 
                    tweet_credibility=:tweet_credibility, retweets=:retweets ,favorites=:favorites,
                    replies=:replies";
                    
                    // prepare query
                    $stmtHistory = $this->conn->prepare($queryHistory);
                    
                    // bind values
                    $stmtHistory->bindParam(":id_tweet", $this->id_tweet);
                    $stmtHistory->bindParam(":tweet_credibility", $this->tweet_credibility);
                    $stmtHistory->bindParam(":retweets", $this->retweets);
                    $stmtHistory->bindParam(":favorites", $this->favorites);
                    $stmtHistory->bindParam(":replies", $this->replies);
                    

                // execute query
                if($stmtHistory->execute()){


                    $queryMedia = "INSERT INTO
                        " . $this->tweet_media . "
                        SET
                        id_tweet=:id_tweet, 
                        tweet_url=:tweet_url, type=:type ";

                    // prepare query
                    $stmtMedia = $this->conn->prepare($queryMedia);
                    
                    // bind values
                    $stmtMedia->bindParam(":id_tweet", $this->id_tweet);
                    $stmtMedia->bindParam(":tweet_url", $this->tweet_url);
                    $stmtMedia->bindParam(":type", $this->type);


                    if($stmtMedia->execute()){
                        return true;
                    }
                    return false;

                }
                return false;
            }


        }


     
        
    }
}
?>