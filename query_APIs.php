<?php

/************************WORKING_MATCHES QUERIES*************************/

function INSERT_WORKING_MATCHES($dealId, $interests) {
	
	global $conn;

	$sql = "INSERT INTO working_matches (deal_id, interests) VALUES (" . $dealId . "," . $interests . ")";
	    								//trigger_error( $sql;
	if ($conn->query($sql) === TRUE) {
		trigger_error( "Inserted into WORKING MATCHES (" . $dealId . "," . $interests . ") created successfully");
	} else {
		trigger_error( "Error: " . $sql . " " . $conn->error, E_USER_ERROR);
	}
	
}

function DELETE_WORKING_MATCHES($dealId) {
		
	 global $conn;
	 
	 $sql = "DELETE FROM working_matches WHERE deal_id = " . $dealId;
    if ($conn->query($sql) === TRUE) {
        trigger_error( "Deleted from  WORKING_MATCHES successfully");
    } else {
        trigger_error( "Error: " . $sql . " " . $conn->error, E_USER_ERROR);
    }
}
function UPDATE_WORKING_MATCHES($dealId, $interests) {

		
    global $conn;
    
    $sql = "UPDATE working_matches set interests = " . $interests . " WHERE deal_id = " . $dealId;

    if ($conn->query($sql) === TRUE) {
        trigger_error( "Updated WORKING_MATCHES successfully");
    } else {
        trigger_error( "Error: " . $sql . " " . $conn->error, E_USER_ERROR);
    }
}













/***********************USER_PENDING__MATCHES QUERIES***************************/


function INSERT_USER_PENDING_MATCHES ($dealId, $userId, $matchId, $userContribution, $matchStatus, $noOfUsersMatched, $userId1, $userId2) {
	
	global $conn;
	
	if($noOfUsersMatched == 0) {
		$sql = "INSERT INTO  user_pending_matches (deal_id, user_id, match_id, X, match_status) VALUES (" . $dealId . "," . $userId . ","  . $matchId . "," . $userContribution . "," .  "'UNMATCHED'" . ")";
        $msg = "(" . $dealId . "," . $userId . "," . $matchId . ","  . $userContribution . "," .  "'UNMATCHED'" . ")";
	} else if($noOfUsersMatched == 1) {
		$sql = "INSERT INTO  user_pending_matches (deal_id, user_id, match_id, X, no_of_matchers, match_status, user_id1) VALUES (" . $dealId . "," . $userId . ","  . $matchId . "," . $userContribution . "," . 1 .  "," .  "'MATCHED'," . $userId1 .  ")";
        $msg = "(" . $dealId . "," . $userId . "," . $userContribution . "," . 1 .  "," .  "'MATCHED'," . $userId1 .  ")";
	} else if($noOfUsersMatched == 2) {
		$sql = "INSERT INTO  user_pending_matches (deal_id, user_id, match_id, X, no_of_matchers, match_status, user_id1, user_id2) VALUES (" . $dealId . "," . $userId . ","  . $matchId . "," . $userContribution . "," . 2 .  "," .  "'MATCHED'," . $userId1 . "," . $userId2 . ")";
        $msg = "(" . $dealId . "," . $userId . ","  . $matchId . "," . $userContribution . "," . 2 .  "," .  "'MATCHED'," . $userId1 . "," . $userId2 . ")";
	}
	echo($sql);
	if ($conn->query($sql) === TRUE) {
 			trigger_error( "Intested into USER_PENDING_MATHUPS " . $msg ." successfully");
	} else {
		trigger_error( "Error: " . $sql . " " . $conn->error);
	}	
 	
}

function DELETE_USER_PENDING_MATCHES($userId, $dealId) { 
	
	 global $conn;
	 
	 
	 $sql = "DELETE FROM user_pending_matches WHERE deal_id = " . $dealId . " AND user_id = " . $userId;
	 echo ($sql);
	 trigger_error($sql);
  	 	 if ($conn->query($sql) === TRUE) {
 			trigger_error( "Deleted from USER_PENDING_MATCHUP successfully");
       } else {
			trigger_error( "Error: " . $sql . " " . $conn->error);
	    }
}

function UPDATE_USER_PENDING_MATCHES($matchId, $noOfUsersMatched, $matchStatus, $dealId, $userId, $userId1, $userId2) {
    
    global $conn;

	if($noOfUsersMatched == 1) {
        $sql = "UPDATE user_pending_matches set match_id = " . $matchId . "," . "no_of_matchers =" . $noOfUsersMatched . ",match_status = '" . $matchStatus . "',user_id1 = " . $userId1 . " WHERE user_id = " . $userId . " AND deal_id = " . $dealId ;
        $msg = "(" . $noOfUsersMatched . "match_status = '" . $matchStatus . "', user_id1 = " . $userId1 . ") for (" .  $userId . "," . $dealId . ")";
    }
	else if($noOfUsersMatched == 2) {
        $sql = "UPDATE user_pending_matches set match_id = ". $matchId . ",no_of_matchers =" . $noOfUsersMatched . ",match_status = '" . $matchStatus . "',user_id1 = " . $userId1 . ",user_id2 = " . $userId2 . " WHERE user_id = " . $userId . " AND deal_id = " . $dealId ;
        $msg = "(" . $noOfUsersMatched . ",match_status = '" . $matchStatus . "',user_id1 = " . $userId1 . ",user_id2 = " . $userId2 . ") for (" .  $userId . "," . $dealId . ")";
    }    
    trigger_error ($sql);
    if ($conn->query($sql) === TRUE) {
        trigger_error( "Updated USER_PENDING_MATCHES with " . $msg ."successfully");
    } else {
        trigger_error( "Error: " . $sql . " " . $conn->error);
    }	
 }







/************************SUCCESS_MATCHES QUERIES*************************/


function INSERT_SUCCESS_MATCHUPS($dealId, $code, $noOfUsersMatched, $userId1, $userId2, $userId3, $matchStatus) {
	
	 global $conn;
	 
	 if($noOfUsersMatched == 1) {
	 	$sql = "INSERT INTO success_matchups (deal_id, code, num_matches, user1_id, user2_id, matchup_status) VALUES (" . $dealId .  "," . "'" . $code . "'" . "," . "2" . "," . $userId1 .  "," . $userId2 . ",'" . $matchStatus . "')"; 						
	 } else {
	 	$sql = "INSERT INTO success_matchups (deal_id, code, num_matches, user1_id, user2_id, user3_id, matchup_status) VALUES (" . $dealId .  "," . "'" . $code . "'" . "," . "3" . "," . $userId1 .  "," . $userId2 . "," .$userId3 . ",'" . $matchStatus . "')"; 						
	 }
	 echo ($sql);
	 trigger_error($sql);
  	 	 if ($conn->query($sql) === TRUE) {
 			trigger_error( "Intested into SUCCESS_MATCHUP successfully");
       } else {
			trigger_error( "Error: " . $sql . " " . $conn->error);
	    }
	 return $conn->insert_id;
}


?>