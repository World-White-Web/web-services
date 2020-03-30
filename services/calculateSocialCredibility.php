<?php
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    // get database connection
    include_once '../config/database.php';
    
    // instantiate tweet object
    include_once '../objects/twitter_user.php';
    include_once '../objects/twitter_user_history.php';

    
    $database = new Database();
    $db = $database->getConnection();
    
    $userTW = new TwitterUser($db);
    // $userHistoryTW = new TwitterUserHistory($db);
    $idTwitter=isset($_GET["id_twitter"]) ? $_GET["id_twitter"] : "";
  
    // query products
    $userTW-> id_twitter = $idTwitter;

    // $tweetAux = new TweetAux($db);
    
    // get posted data
    $data = json_decode(file_get_contents("php://input"));

    // // make sure data is not empty
    if( 
        !empty($idTwitter)  
    ){
    

        //echo 'console.log('. json_encode( $userTW ) .')';
        $userTW->socialCredibility();


        if($userTW->socialCredibility!=null){
            // create array

            $social_cred_arr = array(
                "socialCredibility" =>  $userTW->socialCredibility
            );

            // set response code - 200 OK
            http_response_code(200);
        
            // make it json format
            echo json_encode($userTW->socialCredibility);
        } else{
    
            // set response code - 503 service unavailable
            http_response_code(503);
    
            // tell the user
            echo json_encode(array("message" => "Unable to create Tweet."));
        }
    }else{
    
        // set response code - 400 bad request
        http_response_code(400);
    
        // tell the user
        echo json_encode(array("message" => "Unable to create user. Data is incomplete."));
    }
?>