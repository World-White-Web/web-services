<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../config/database.php';
  
// instantiate userTW object
include_once '../objects/twitter_user.php';
include_once '../objects/twitter_user_history.php';
include_once '../objects/twitter_user_aux.php';

$database = new Database();
$db = $database->getConnection();
  
$userTW = new TwitterUser($db);
$userHistoryTW = new TwitterUserHistory($db);
$userTWAux = new TwitterUserAux($db);
  
// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
    if( 
        !empty($data->joined_date)  
    ){

        // set userTW property values
        $userTW->id_twitter = $data->id_twitter;
        $userTW->joined_date = $data->joined_date;

        $userHistoryTW->user_name = $data->user_name;
        $userHistoryTW->following = $data->following;
        $userHistoryTW->followers = $data->followers;
        $userHistoryTW->link = $data->link;   
        $userHistoryTW->location = $data->location;
        $userHistoryTW->verified = $data->verified;
        $userHistoryTW->followers_impact = $data->followers_impact;
        $userHistoryTW->user_credibility = $data->user_credibility;
        $userHistoryTW->extraction_method = $data->extraction_method;

        
        //set userTW property values
        $userTWAux->id_twitter = $data->id_twitter;
        $userTWAux->joined_date = $data->joined_date;

        $userTWAux->user_name = $data->user_name;
        $userTWAux->following = $data->following;
        $userTWAux->followers = $data->followers;
        $userTWAux->link = $data->link;   
        $userTWAux->location = $data->location;
        $userTWAux->verified = $data->verified;
        $userTWAux->followers_impact = $data->followers_impact;
        $userTWAux->user_credibility = $data->user_credibility;
        $userTWAux->extraction_method = $data->extraction_method;

        if($userTWAux-> create()){
            // set response code - 201 created
            http_response_code(201);

            // Create new record twitter User
            echo json_encode(true);

        }else{

            http_response_code(503);
            // Create new record twitter User
            echo json_encode(false);

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