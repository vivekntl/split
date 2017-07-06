<?php
/***********************************************************************************/
/* This file contains all the functions necessary for processing a new matchup     */
/* request.                                                                        */
/***********************************************************************************/

function add_first_interest_for_deal($user_id, $deal_id, $user_contribution) {

	global $conn;
	
	$interests = "'{" . $user_id . "," . $user_contribution . "}'";
	    		//trigger_error( $interests;
	$sql = "INSERT INTO working_matches (deal_id, interests) VALUES (" . $deal_id . "," . $interests . ")";
	    								//trigger_error( $sql;
	if ($conn->query($sql) === TRUE) {
		trigger_error( "New record created successfully");
	} else {
		trigger_error( "Error: " . $sql . " " . $conn->error, E_USER_ERROR);
	}
}  

function check_for_match($user_contribution, $userArray, $userInterestArray, $deal_id) {

	global $conn;
	$matched_user_id = 0;
	
	$sql = "SELECT X1, Y1, X2, Y2, X3, Y3, type_id FROM deals where deal_id = " . $deal_id;
	
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
/*	
	$interests1 = str_replace("}", "", $prev_interests);
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
*/	
	switch($dealType) {
		
		case DEAL_TYPE_X_UNITS_Y_UNITS:
            for($interestIndex=0; $interestIndex < count($userInterestArray); $interestIndex++) {
                if($userInterestArray[$interestIndex] + $user_contribution >= $Y_array[0] ||
                   $userInterestArray[$interestIndex] + $user_contribution >= $Y_array[1] ||
                   $userInterestArray[$interestIndex] + $user_contribution >= $Y_array[2]) {
                    
                    $matched_user_id = $userArray[$interestIndex];
                    trigger_error( "Found match " . $matched_user_id);
                    break 2;
                }
			}
            
            for($i = 0; $i < count($userArray) - 1; $i++) {
                for($j=$i+1; $j<count($userArray); $j++) {
                    if($userInterestArray[$i] + $userInterestArray[$j] + $user_contribution >= $Y_array[0] ||
                       $userInterestArray[$i] + $userInterestArray[$j] + $user_contribution >= $Y_array[1] ||
                       $userInterestArray[$i] + $userInterestArray[$j] + $user_contribution >= $Y_array[2]) {
                
                        $matched_user_id = $userArray[$i] . "," . $userArray[$j];
                        trigger_error( "Found match " . $matched_user_id);
                        break 2;
                    }                                
                }
            }
		break;
		
		case DEAL_TYPE_X_UNITS_Y_PERCENT:
		case DEAL_TYPE_X_AMOUNT_Y_AMOUNT:
		case DEAL_TYPE_X_AMOUNT_Y_PERCENT:
				for($interestIndex=0; $interestIndex < count($userInterestArray); $interestIndex++) {
					if($userInterestArray[$interestIndex] + $user_contribution >= $X_array[0] ||
                       $userInterestArray[$interestIndex] + $user_contribution >= $X_array[1] ||
                       $userInterestArray[$interestIndex] + $user_contribution >= $X_array[2]) {
						$matched_user_id = $userArray[$interestIndex];
						trigger_error( "Found match " . $matched_user_id);
						break 2;
					}
			}
            
            for($i = 0; $i < count($userArray) - 1; $i++) {
                for($j=$i+1; $j<count($userArray); $j++) {
                    if($userInterestArray[$i] + $userInterestArray[$j] + $user_contribution >= $Y_array[0] ||
                       $userInterestArray[$i] + $userInterestArray[$j] + $user_contribution >= $Y_array[1] ||
                       $userInterestArray[$i] + $userInterestArray[$j] + $user_contribution >= $Y_array[2]) {
                
                        $matched_user_id = $userArray[$i] . "," . $userArray[$j];
                        trigger_error( "Found match " . $matched_user_id);
                        break 2;
                    }                                
                }	
            }
		break;	
	}
    

	return $matched_user_id;
}
 
function handle_new_matchup_request($user_id, $deal_id, $user_contribution) {
	
		global $conn;	
	
		include('db_connect.php');
		
		$sql = "SELECT interests FROM working_matches WHERE deal_id = " . $deal_id;
	  	$result = $conn->query($sql);
	  	
	  	if($result === FALSE) {
			trigger_error( "Error: " . $sql . " " . $conn->error);
		}
		echo "Dude" . $result->num_rows;
		if ($result->num_rows > 0) {

			$row = $result->fetch_assoc();
			$prev_interests  = $row['interests'];
			
			$interests1 = str_replace("}", "", $prev_interests);
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

    		$matched_user_id = check_for_match($user_contribution, $userArray, $userInterestArray, $deal_id);
         
         $usersMatched = explode(",", $matched_user_id);
         $noOfUsersMatched = count($usersMatched);                
            
            if($matched_user_id == 0) {
                $new_interest = "{" . $user_id . "," . $user_contribution . "}";
                $interests = "'" . $prev_interests . ";" . $new_interest . "'";
                trigger_error( $interests);

                $sql = "UPDATE working_matches set interests=" . $interests . " WHERE deal_id = " . $deal_id;

                if ($conn->query($sql) === TRUE) {
                    trigger_error( "Record updated successfully");
                } else {
                    trigger_error( "Error: " . $sql . " " . $conn->error, E_USER_ERROR);
                }

            } else {
            	 trigger_error("Matched user id " . $matched_user_id);
                trigger_error( "Found match with " . $matched_user_id);
                $prevCount = substr_count($prev_interests, ";") + 1;
              
                if($noOfUsersMatched == $prevCount) {
                	//delete row
                } else {

	                $new_interest = "{" . $user_id . "," . $user_contribution . "}";
													
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
                
                	/*$sql = "UPDATE working_matches set interests = " . $interests . "WHERE deal_id = " . $deal_id;
						if ($conn->query($sql) === TRUE) {
							trigger_error( "Updated working_matches successfully";
						} else {
							trigger_error( "Error: " . $sql . " " . $conn->error;
						}*/
					 }
					 $alpha   = str_shuffle('0123456789');
					 $code = substr($alpha, 0, 4);
					//$prevId = mysql_insert_id();
					 $prevId = 567;
					 if($noOfUsersMatched == 1) {
					 	$sql = "INSERT INTO success_matchups (match_id, deal_id, code, num_matches, user1_id, user2_id, matchup_status) VALUES (" . $prevId .  " , " . $deal_id .  " , " .  " , " . "'" . $code . "'" .  "2" . " , " . $usersMatched[0] .  " , " . $user_id . "," . "1" . ")"; 						
					 } else {
					 	$sql = "INSERT INTO success_matchups (match_id, deal_id, code, num_matches, user1_id, user2_id, user3_id, matchup_status) VALUES (" . $prevId .  " , " . $deal_id .  " , " . "'" .  $code. "'" . " , " . "3" . "," . $usersMatched[0] .  "," . $usersMatched[1] . " , " .  $user_id . " , " . "1" .")"; 						
					 }
					 trigger_error($sql);
              	 $sql = "UPDATE working_matches set interests = " . $interests . "WHERE deal_id = " . $deal_id;
					 if ($conn->query($sql) === TRUE) {
				 		trigger_error( "Updated working_matches successfully";
				    } else {
						trigger_error( "Error: " . $sql . " " . $conn->error;
					 }
            	 
           }     
      	//trigger_error( "Interests: " . $row["interests"] . "<br>";
/*    		$new_interest = "{" . $user_id . "," . $user_contribution . "}";
    		$interests = "'" . $prev_interests . ";" . $new_interest . "'";
    		trigger_error( $interests;

			$sql = "UPDATE working_matches set interests=" . $interests . " WHERE deal_id = " . $deal_id;

			if ($conn->query($sql) === TRUE) {
				trigger_error( "Record updated successfully";
			} else {
				trigger_error( "Error: " . $sql . " " . $conn->error;
			}
			trigger_error( $sql;*/
      	
    	} else {
			
			add_first_interest_for_deal($user_id, $deal_id, $user_contribution);
	
		} 	
      
		include('db_close.php');   
}

    
?>