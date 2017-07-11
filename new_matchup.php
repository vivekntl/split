<?php
/***********************************************************************************/
/* This file contains all the functions necessary for processing a new matchup     */
/* request.                                                                        */
/***********************************************************************************/

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


function INSERT_USER_PENDING_MATCHES ($dealId, $userId, $userContribution, $matchStatus, $noOfUsersMatched, $userId1, $userId2) {
	
	global $conn;
	
	if($noOfUsersMatched == 0) {
		$sql = "INSERT INTO  user_pending_matches (deal_id, user_id, X, match_status) VALUES (" . $dealId . "," . $userId . "," . $userContribution . "," .  "'UNMATCHED'" . ")";
        $msg = "(" . dealId . "," . $userId . "," . $userContribution . "," .  "'UNMATCHED'" . ")";
	} else if($noOfUsersMatched == 1) {
		$sql = "INSERT INTO  user_pending_matches (deal_id, user_id, X, no_of_matchers, match_status, user_id1) VALUES (" . $dealId . "," . $userId . "," . $userContribution . "," . 1 .  "," .  "'MATCHED'," . $userId1 .  ")";
        $msg = "(" . $dealId . "," . $userId . "," . $userContribution . "," . 1 .  "," .  "'MATCHED'," . $userId1 .  ")";
	} else if($noOfUsersMatched == 2) {
		$sql = "INSERT INTO  user_pending_matches (deal_id, user_id, X, no_of_matchers, match_status, user_id1, user_id2) VALUES (" . $dealId . "," . $userId . "," . $userContribution . "," . 2 .  "," .  "'MATCHED'," . $userId1 . "," . $userId2 . ")";
        $msg = "(" . $dealId . "," . $userId . "," . $userContribution . "," . 2 .  "," .  "'MATCHED'," . $userId1 . "," . $userId2 . ")";
	}
	echo($sql);
	if ($conn->query($sql) === TRUE) {
 			trigger_error( "Intested into USER_PENDING_MATHUPS " . $msg ." successfully");
	} else {
		trigger_error( "Error: " . $sql . " " . $conn->error);
	}	
 	
}

function UPDATE_USER_PENDING_MATCHES($noOfUsersMatched, $matchStatus, $dealId, $userId, $userId1, $userId2) {
    
    global $conn;

	if($noOfUsersMatched == 1) {
        $sql = "UPDATE user_pending_matches set no_of_matchers =" . $noOfUsersMatched . ",match_status = " . $matchStatus . ",user_id1 = " . $userId1 . " WHERE user_id = " . $userId . " AND deal_id = " . $dealId ;
        $msg = "(" . $noOfUsersMatched . "match_status = " . $matchStatus . "user_id1 = " . $userId1 . ") for (" .  $userId . "," . $dealId . ")";
    }
	else if($noOfUsersMatched == 2) {
        $sql = "UPDATE user_pending_matches set no_of_matchers =" . $noOfUsersMatched . ",match_status = " . $matchStatus . ",user_id1 = " . $userId1 . ",user_id2 = " . $userId2 . " WHERE user_id = " . $userId . " AND deal_id = " . $dealId ;
        $msg = "(" . $noOfUsersMatched . ",match_status = " . $matchStatus . ",user_id1 = " . $userId1 . ",user_id2 = " . $userId2 . ") for (" .  $userId . "," . $dealId . ")";
    }    
    trigger_error ($sql);
    if ($conn->query($sql) === TRUE) {
        trigger_error( "Updated USER_PENDING_MATCHES with " . $msg ."successfully");
    } else {
        trigger_error( "Error: " . $sql . " " . $conn->error);
    }	
 }


function process_first_interest_for_deal($userId, $dealId, $userContribution, $matchStatus) {
	
	$interests = "'{" . $userId . "," . $userContribution . "}'";
	
	INSERT_WORKING_MATCHES($dealId, $interests);
	
	INSERT_USER_PENDING_MATCHES($dealId, $userId, $userContribution, $matchStatus, 0, NULL, NULL);

}  

function check_for_match($userContribution, $userArray, $userInterestArray, $dealId) {

	global $conn;
	$matched_userId = 0;
	
	$sql = "SELECT X1, Y1, X2, Y2, X3, Y3, type_id FROM deals where deal_id = " . $dealId;
	
	$result = $conn->query($sql);
	
	if($result === FALSE) {
		trigger_error( "Error: " . $sql . " " . $conn->error, E_USER_ERROR);
	}	

	$row = $result->fetch_assoc();	
	
	$X_array = array($row['X1'], $row['X2'], $row['X3']);		
	$Y_array = array($row['Y1'], $row['Y2'], $row['Y3']);
	$dealType = $row['type_id'];
	
	rsort($X_array);		
	rsort($Y_array);
	
	foreach ($Y_array as $i) {
		trigger_error($i);
		echo($i);
	}		

	switch($dealType) {
		
		case DEAL_TYPE_X_UNITS_Y_UNITS:
            for($interestIndex=0; $interestIndex < count($userInterestArray); $interestIndex++) {
                if($userInterestArray[$interestIndex] + $userContribution >= $Y_array[0] ||
                   $userInterestArray[$interestIndex] + $userContribution >= $Y_array[1] ||
                   $userInterestArray[$interestIndex] + $userContribution >= $Y_array[2]) {
                    
                    $matched_userId = $userArray[$interestIndex];
                    trigger_error( "Found match " . $matched_userId);
                    break 2;
                }
			}
            
            for($i = 0; $i < count($userArray) - 1; $i++) {
                for($j=$i+1; $j<count($userArray); $j++) {
                    if($userInterestArray[$i] + $userInterestArray[$j] + $userContribution >= $Y_array[0] ||
                       $userInterestArray[$i] + $userInterestArray[$j] + $userContribution >= $Y_array[1] ||
                       $userInterestArray[$i] + $userInterestArray[$j] + $userContribution >= $Y_array[2]) {
                
                        $matched_userId = $userArray[$i] . "," . $userArray[$j];
                        trigger_error( "Found match " . $matched_userId);
                        break 2;
                    }                                
                }
            }
		break;
		
		case DEAL_TYPE_X_UNITS_Y_PERCENT:
		case DEAL_TYPE_X_AMOUNT_Y_AMOUNT:
		case DEAL_TYPE_X_AMOUNT_Y_PERCENT:
				for($interestIndex=0; $interestIndex < count($userInterestArray); $interestIndex++) {
					if($userInterestArray[$interestIndex] + $userContribution >= $X_array[0] ||
                       $userInterestArray[$interestIndex] + $userContribution >= $X_array[1] ||
                       $userInterestArray[$interestIndex] + $userContribution >= $X_array[2]) {
						$matched_userId = $userArray[$interestIndex];
						trigger_error( "Found match " . $matched_userId);
						break 2;
					}
			}
            
            for($i = 0; $i < count($userArray) - 1; $i++) {
                for($j=$i+1; $j<count($userArray); $j++) {
                    if($userInterestArray[$i] + $userInterestArray[$j] + $userContribution >= $Y_array[0] ||
                       $userInterestArray[$i] + $userInterestArray[$j] + $userContribution >= $Y_array[1] ||
                       $userInterestArray[$i] + $userInterestArray[$j] + $userContribution >= $Y_array[2]) {
                
                        $matched_userId = $userArray[$i] . "," . $userArray[$j];
                        trigger_error( "Found match " . $matched_userId);
                        break 2;
                    }                                
                }	
            }
		break;	
	}
    

	return $matched_userId;
}
 
function handle_new_matchup_request($userId, $dealId, $userContribution) {
	
		global $conn;	
	
		include('db_connect.php');
		
		$matchStatus = MATCH_STATUS_UNMATCHED;
		$sql = "SELECT interests FROM working_matches WHERE deal_id = " . $dealId;
	  	$result = $conn->query($sql);
	  	
	  	if($result === FALSE) {
			trigger_error( "Error: " . $sql . " " . $conn->error);
		}
		echo "Dude" . $result->num_rows;
		if ($result->num_rows > 0) {

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

            $matched_userId = check_for_match($userContribution, $userArray, $userInterestArray, $dealId);
         
            $usersMatched = explode(",", $matched_userId);
            $noOfUsersMatched = count($usersMatched);                
            
            if($matched_userId == 0) {
                $newInterest = "{" . $userId . "," . $userContribution . "}";
                $interests = "'" . $prevInterests . ";" . $newInterest . "'";
                trigger_error( $interests);

                $sql = "UPDATE working_matches set interests=" . $interests . " WHERE deal_id = " . $dealId;

                if ($conn->query($sql) === TRUE) {
                    trigger_error( "Record updated successfully");
                } else {
                    trigger_error( "Error: " . $sql . " " . $conn->error, E_USER_ERROR);
                }
                INSERT_USER_PENDING_MATCHES($dealId, $userId, $userContribution, 'UNMATCHED', 0	, NULL, NULL);                           

            } else {
         		 $matchStatus = MATCH_STATUS_MATCHED;

            	 trigger_error("Matched user id " . $matched_userId);
                trigger_error( "Found match with " . $matched_userId);
                $prevCount = substr_count($prevInterests, ";") + 1;
              
                if($noOfUsersMatched == $prevCount) {
                	//delete working matches row
                	 $sql = "DELETE FROM working_matches WHERE deal_id = " . $dealId;
	                if ($conn->query($sql) === TRUE) {
	                    trigger_error( "Row deleted from  working matches successfully");
	                } else {
	                    trigger_error( "Error: " . $sql . " " . $conn->error, E_USER_ERROR);
	                }
                } else {
						 // modify working matches row
	                //$new_interest = "{" . $userId . "," . $userContribution . "}";
													
						 $flagAdd = 1;
						 $interests = "";
						 	
						 for($i=0; $i<count($userArray); $i++) { 
						 	for($j=0; $j<count($usersMatched); $j++) {
						 		echo "<br>Matching " . $i . " and " . $j;				 		
						 		if($userArray[$i] == $usersMatched[$j]) {
						 			$flagAdd = 0;
						 			break;		
						 		}
						 	}       
						 	if($flagAdd == 1) {
						 		$interests = $interests . "{" . $userArray[$i] . "," . $userInterestArray[$i] . "};";
						 	}
						 	$flagAdd = 1;        
	                }
                //var_dump($userArray);
	                trigger_error($interests . " " . strlen($interests));
	                $interests = rtrim($interests, ";");
	                $interests = "'" . $interests . "'"; 
	                trigger_error($interests . " " . strlen($interests));
                
                	$sql = "UPDATE working_matches set interests = " . $interests . "WHERE deal_id = " . $dealId;
						if ($conn->query($sql) === TRUE) {
							trigger_error( "Updated working_matches successfully");
						} else {
							trigger_error( "Error: " . $sql . " " . $conn->error);
						}
						echo $sql;
					 }
					 $alpha   = str_shuffle('0123456789');
					 $code = substr($alpha, 0, 4);
					 $prevId = $conn->insert_id;
					 //$prevId = 567;
					 if($noOfUsersMatched == 1) {
					 	$sql = "INSERT INTO success_matchups (match_id, deal_id, code, num_matches, user1_id, user2_id, matchup_status) VALUES (" . $prevId .  "," . $dealId .  "," . "'" . $code . "'" . "," . "2" . "," . $usersMatched[0] .  "," . $userId . "," . "'MATCHED'" . ")"; 						
					 } else {
					 	$sql = "INSERT INTO success_matchups (match_id, deal_id, code, num_matches, user1_id, user2_id, user3_id, matchup_status) VALUES (" . $prevId .  "," . $dealId .  "," . "'" .  $code. "'" . "," . "3" . "," . $usersMatched[0] .  "," . $usersMatched[1] . "," .  $userId . "," . "'MATCHED'" .")"; 						
					 }
					 echo ($sql);
					 trigger_error($sql);
              	 	 if ($conn->query($sql) === TRUE) {
				 		trigger_error( "Intested into success matchup successfully");
				      } else {
						trigger_error( "Error: " . $sql . " " . $conn->error);
					  }


					if($noOfUsersMatched == 1) {
                        UPDATE_USER_PENDING_MATCHES($noOfUsersMatched, 'MATCHED', $dealId, $userId, $usersMatched[0], NULL);
						/*$sql = "UPDATE user_pending_matches set no_of_matchers = " . 1 . ", match_status = " . "'MATCHED'" . ", user_id1 = "  . $userId . " WHERE user_id = " . $usersMatched[0] . " AND deal_id = " . $dealId ;
						echo ($sql); 
					   if ($conn->query($sql) === TRUE) {
				 			trigger_error( "Updated user_pending_matches  successfully");
				   	} else {
							trigger_error( "Error: " . $sql . " " . $conn->error);
						}*/
  
                        INSERT_USER_PENDING_MATCHES($dealId, $userId, $userContribution, 'MATCHED', $noOfUsersMatched, $usersMatched[0], NULL);

					} else {
						
					
						for($i=0; $i<$noOfUsersMatched; $i++) {

                            UPDATE_USER_PENDING_MATCHES($noOfUsersMatched, 'MATCHED', $dealId, $userId, $usersMathed[0], $usersMathed[1]);
							/*$sql = "UPDATE user_pending_matches set no_of_matchers = 2, match_status = 'MATCHED', user_id1 =" . $usersMatched[1-$i] . ", user_id2 = " . $userId . " WHERE user_id = " . $usersMatched[$i] . " AND deal_id = " . $dealId ;
							trigger_error ($sql);
							if ($conn->query($sql) === TRUE) {
					 			trigger_error( "Updated user_pending_matches successfully");
							} else {
								trigger_error( "Error: " . $sql . " " . $conn->error);
							}*/	
		
						}
	                   INSERT_USER_PENDING_MATCHES($dealId, $userId, $userContribution, 'MATCHED', $noOfUsersMatched, $usersMatched[0], $usersMatched[1]);

					}	
     	 
           }     
      	
		} else {
		
			process_first_interest_for_deal($userId, $dealId, $userContribution, 'UNMATCHED');
		}
			       
		include('db_close.php');   
}

    
?>