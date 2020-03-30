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
    include_once '../objects/twitter_user.php';

    
    $database = new Database();
    $db = $database->getConnection();
    
    $userTW = new TwitterUser($db);


    // $tweetAux = new TweetAux($db);
    
    // get posted data
    $data = json_decode(file_get_contents("php://input"));

    // // make sure data is not empty
    if( 
        !empty($data->id_twitter)
    
    ){
    
        $userTW-> id_twitter = $data-> id_twitter;

        if($userTW->consultRetweets()){
    
            // set response code - 201 created
            http_response_code(201);
    
            // tell the user
            echo json_encode($userTW->retweets);
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