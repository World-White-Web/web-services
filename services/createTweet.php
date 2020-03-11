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
  
$database = new Database();
$db = $database->getConnection();
  
$tweet = new Tweet($db);

  
// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
    if( 
        !empty($data->id_twitter_user)  &&
        !empty($data->text) &&
        !empty($data->bad_words_filter) &&
        !empty($data->spam_filter) &&
        !empty($data->misspelling_filter) &&
        !empty($data->extraction_method)
    
    ){
    
        // set userTW property values
        $tweet->id_twitter_user = $data->id_twitter_user;
        $tweet->id_tweet_api = $data->id_tweet_api;
        $tweet->text = $data->text;
        $tweet->bad_words_filter = $data->bad_words_filter;
        $tweet->spam_filter = $data->spam_filter;
        $tweet->misspelling_filter = $data->misspelling_filter;
        $tweet->extraction_method = $data->extraction_method;

    
        // create the tweet
        if($tweet->create()){
    
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