<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../config/database.php';
  
// instantiate userHistoryTW object
include_once '../objects/twitter_user_history.php';
  
$database = new Database();
$db = $database->getConnection();
  
$userHistoryTW = new TwitterUserHistory($db);

  
// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
    if( 
        !empty($data->id_twitter_user)  
    ){
    
        // set userHistoryTW property values
        $userHistoryTW->id_twitter_user = $data->id_twitter_user ;
        $userHistoryTW->user_name= $data->user_name;
        $userHistoryTW->following= $data->following ;
        $userHistoryTW->followers= $data->followers ;
        $userHistoryTW->link= $data->link;
        $userHistoryTW->location=$data->location;
        $userHistoryTW->verified=$data->verified;
        $userHistoryTW->followers_impact=$data->followers_impact;
        $userHistoryTW->user_credibility=$data->user_credibility;
        $userHistoryTW->extraction_method=$data->extraction_method;
       
    
        // create the userHistoryTW
        if($userHistoryTW->create()){
    
            // set response code - 201 created
            http_response_code(201);
    
            // tell the user
            echo json_encode(array("message" => "History was created."));
        }
    
        // if unable to create the userHistoryTW, tell the user
        else{
    
            // set response code - 503 service unavailable
            http_response_code(503);
    
            // tell the user
            echo json_encode(array("message" => $data));
        }
    }
    
    // tell the user data is incomplete
    else{
    
        // set response code - 400 bad request
        http_response_code(400);
    
        // tell the user
        echo json_encode(array("message" => "Unable to create user. Data is incomplete." ));
    }
?>