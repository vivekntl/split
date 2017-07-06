<?php
/***********************************************************************************/
/* This file contains all the functions necessary for processing a new matchup     */
/* request.                                                                        */
/***********************************************************************************/

function add_first_interest_for_deal($user_id, $deal_id, $user_contribution) {

	global $conn;
	
	$interests = "'{" . $user_id . "," . $user_contribution . "}'";
	    		//echo $interests;
	$sql = "INSERT INTO working_matches (deal_id, interests) VALUES (" . $deal_id . "," . $interests . ")";
	    								//echo $sql;
	if ($conn->query($sql) === TRUE) {
		echo "New record created successfully";
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}
}  

function check_for_match($user_contribution, $prev_interests, $deal_id) {

	global $conn;
	$matched_user_id = 0;
	
	$sql = "SELECT X1, Y1, X2, Y2, X3, Y3, type_id FROM deals where deal_id = " . $deal_id;
	
	$result = $conn->query($sql);
	
	if($result === FALSE) {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}	

	$row = $result->fetch_assoc();	
	
	$X_array = array($row['X1'], $row['X2'], $row['X3']);		
	$Y_array = array($row['Y1'], $row['Y2'], $row['Y3']);
	$dealType = $row['type_id'];
	
	rsort($X_array);		
	rsort($Y_array);
	
	foreach ($Y_array as $i) {
		echo "<br>" . $i;
	}		
	
	$interests1 = str_replace("}", "", $prev_interests);
	$interests1 = str_replace("{", "", $interests1);
	
	$userArray = array();
	$userInterestArray = array();
	
	$interestArray = explode(";", $interests1);
	foreach ($interestArray as  $interest) {
		echo "<br>" . $interest;  		
		$temp = explode(",", $interest);
		array_push($userArray, $temp[0]);	
		array_push($userInterestArray, $temp[1]);	
	}
	
	switch($dealType) {
		case DEAL_TYPE_X_UNITS_Y_UNITS:
            for($interestIndex=0; $interestIndex < count($userInterestArray); $interestIndex++) {
                if($userInterestArray[$interestIndex] + $user_contribution >= $Y_array[0] ||
                   $userInterestArray[$interestIndex] + $user_contribution >= $Y_array[1] ||
                   $userInterestArray[$interestIndex] + $user_contribution >= $Y_array[2]) {
                    
                    $matched_user_id = $userArray[$interestIndex];
                    echo "<br>" . "Found match " . $matched_user_id;
                    break 2;
                }
			}
            
            for($i = 0; $i < count($userArray) - 1; $i++) {
                for($j=$i+1; $j<count($userArray); $j++) {
                    if($userInterestArray[$i] + $userInterestArray[$j] + $user_contribution >= $Y_array[0] ||
                       $userInterestArray[$i] + $userInterestArray[$j] + $user_contribution >= $Y_array[1] ||
                       $userInterestArray[$i] + $userInterestArray[$j] + $user_contribution >= $Y_array[2]) {
                
                        $matched_user_id = $userArray[$i] . "," . $userArray[$j];
                        echo "<br>" . "Found match " . $matched_user_id;
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
						echo "<br>" . "Found match " . $matched_user_id;
						break 2;
					}
			}
            
            for($i = 0; $i < count($userArray) - 1; $i++) {
                for($j=$i+1; $j<count($userArray); $j++) {
                    if($userInterestArray[$i] + $userInterestArray[$j] + $user_contribution >= $Y_array[0] ||
                       $userInterestArray[$i] + $userInterestArray[$j] + $user_contribution >= $Y_array[1] ||
                       $userInterestArray[$i] + $userInterestArray[$j] + $user_contribution >= $Y_array[2]) {
                
                        $matched_user_id = $userArray[$i] . "," . $userArray[$j];
                        echo "<br>" . "Found match " . $matched_user_id;
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
			echo "Error: " . $sql . "<br>" . $conn->error;
		}

		if ($result->num_rows > 0) {

			$row = $result->fetch_assoc();
			$prev_interests  = $row['interests'];
    
    		$matched_user_id = check_for_match($user_contribution, $prev_interests, $deal_id);
            
            if($matched_user_id == 0) {
                $new_interest = "{" . $user_id . "," . $user_contribution . "}";
                $interests = "'" . $prev_interests . ";" . $new_interest . "'";
                echo $interests;

                $sql = "UPDATE working_matches set interests=" . $interests . " WHERE deal_id = " . $deal_id;

                if ($conn->query($sql) === TRUE) {
                    echo "Record updated successfully";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }

            }
            else {
                echo "<br>Found match with " . $matched_user_id;
            }
      	//echo "Interests: " . $row["interests"] . "<br>";
/*    		$new_interest = "{" . $user_id . "," . $user_contribution . "}";
    		$interests = "'" . $prev_interests . ";" . $new_interest . "'";
    		echo $interests;

			$sql = "UPDATE working_matches set interests=" . $interests . " WHERE deal_id = " . $deal_id;

			if ($conn->query($sql) === TRUE) {
				echo "Record updated successfully";
			} else {
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
			echo $sql;*/
      	
    	} else {
			
			add_first_interest_for_deal($user_id, $deal_id, $user_contribution);
	
		} 	
      
		include('db_close.php');   
}

    
?>