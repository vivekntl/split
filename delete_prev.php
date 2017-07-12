<?php
/***********************************************************************************/
/* This file contains all the functions necessary for processing a new matchup     */
/* request.                                                                        */
/***********************************************************************************/
 
function delete_prev_request($userId, $dealId) {
	
    global $conn;	

    $sql = "SELECT interests FROM working_matches WHERE deal_id = " . $dealId;
    $result = $conn->query($sql);

    if($result === FALSE) {
        trigger_error( "Error: " . $sql . " " . $conn->error);
    }
    echo "Dude" . $result->num_rows;

    $row = $result->fetch_assoc();
    $prevInterests  = $row['interests'];

    $interests1 = str_replace("}", "", $prevInterests);
    $interests1 = str_replace("{", "", $interests1);

    $userArray = array();
    $userInterestArray = array();

    $interestArray = explode(";", $interests1);
    
    foreach ($interestArray as  $interest) {
        trigger_error( $interest);  		
        $temp = explode(",", $interest);
        array_push($userArray, $temp[0]);	
        array_push($userInterestArray, $temp[1]);	
    }
    
    $newInterests = "";
    for ($i = 0; $i < count($userArray); $i++) {
        if($userArray[$i] != $userId) {
            $newInterests = $newInterests . "{" . $userArray[$i] . "," . $userInterestArray[$i] . "};";
        }			
    }
    
    $newInterests = rtrim($newInterests, ";");
    
    echo "Dude " . $newInterests . "Dude";
    if($newInterests == "") {
       DELETE_WORKING_MATCHES($dealId); 
    } else {
        $newInterests = "'" . $newInterests . "'";
        UPDATE_WORKING_MATCHES($dealId, $newInterests);        
    }
    DELETE_USER_PENDING_MATCHES($userId, $dealId);
	   		   
}

    
?>