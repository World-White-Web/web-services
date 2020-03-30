<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../config/database.php';
  
// instantiate tweet object
include_once '../objects/tweet.php';
include_once '../objects/tweet_aux.php';

  
$database = new Database();
$db = $database->getConnection();
  
$tweet = new Tweet($db);

$tweetAux = new TweetAux($db);
  
// get posted data
$data = json_decode(file_get_contents("php://input"));

// // make sure data is not empty
    if( 
        !empty($data->text) &&        
        !empty($data->extraction_method)
    
    ){
    
        // set userTW property values
        //$tweetAux->id_twitter_user = $data->id_twitter_user;
        $tweetAux->id_tweet_api = $data->id_tweet_api;
        $tweetAux->text = $data->text;
        $tweetAux->bad_words_filter = $data->bad_words_filter;
        $tweetAux->spam_filter = $data->spam_filter;
        $tweetAux->misspelling_filter = $data->misspelling_filter;
        $tweetAux->extraction_method = $data->extraction_method;


        $tweetAux->tweet_credibility= $data->tweet_credibility;
        $tweetAux->retweets= $data->retweets ;
        $tweetAux->favorites= $data->favorites ;
        $tweetAux->replies= $data->replies;

        $tweetAux->tweet_url=$data->tweet_url;
        $tweetAux->type=$data->type;

        $tweetAux->userAPIid=$data->userAPIid;
        
        // create the tweet
        if($tweetAux->create()){
    
            // set response code - 201 created
            http_response_code(201);
    
            // tell the user
            echo json_encode(array("message" => "Tweet was created."));
        }
    
        // if unable to create the tweet, tell the user
        else{
    
            // set response code - 503 service unavailable
            http_response_code(503);
    
            // tell the user
            echo json_encode(array("message" => "Unable to create Tweet."));
        }
    }
    
    // tell the user data is incomplete
    else{
    
        // set response code - 400 bad request
        http_response_code(400);
    
        // tell the user
        echo json_encode(array("message" => "Unable to create user. Data is incomplete."));
    }
?>