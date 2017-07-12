<?php
/***********************************************************************************/
/* This file receives the POST request and sends then to appropriate functions     */
/* based on the request type.                                                      */
/***********************************************************************************/

/**************************Include necessary files ***********************************/

include('constants.php');
include('query_APIs.php');
include('db_connect.php');
include('new_matchup.php');
include('modify_prev.php');

$request_type = $_POST['request_type'];
echo $request_type;
        
switch($request_type) {
    
    case REQUEST_TYPE_NEW_MATCH_REQUEST:
    	  $user_id = $_POST['user_id']; 	
    	  $deal_id = $_POST['deal_id']; 	
    	  $user_contribution = $_POST['user_contribution']; 	
    	  handle_new_matchup_request($user_id, $deal_id, $user_contribution);
        break;

    case REQUEST_TYPE_CANCEL_PREV_REQUEST:
        break;
    
    case REQUEST_TYPE_MODIFY_PREV_RQUEST:
    	  $user_id = $_POST['user_id']; 	
    	  $deal_id = $_POST['deal_id']; 	
    	  $user_contribution = $_POST['user_contribution']; 	
    	  modify_prev_request($user_id, $deal_id, $user_contribution);    
        break;

    case REQUEST_TYPE_UPDATE_PROFILE_REQUEST:
        break;
        
    default:
        echo "Unknown Request";
        break;
        
}

include('db_close.php');
 
?>