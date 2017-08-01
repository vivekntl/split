<?php
/***********************************************************************************/
/* This file contains all the functions necessary for processing a new matchup     */
/* request.                                                                        */
/***********************************************************************************/

function process_first_interest_for_deal($userId, $dealId, $userContribution, $matchStatus) {
	
    $interests = "'{" . $userId . "," . $userContribution . "}'";
	
	INSERT_WORKING_MATCHES($dealId, $interests);
	
	INSERT_USER_PENDING_MATCHES($dealId, $userId, 0, $userContribution, $matchStatus, 0, NULL, NULL);

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

    $X_array = array($row['X1'], $row['X2'] == 0 ? 9999999 : $row['X2'], $row['X3'] == 0 ? 9999999 : $row['X3']);		
    $Y_array = array($row['Y1'], $row['Y2'] == 0 ? 9999999 : $row['Y2'], $row['Y3'] == 0 ? 9999999 : $row['Y3']);
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

//		include('db_connect.php');

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

            UPDATE_WORKING_MATCHES($dealId, $interests);

            INSERT_USER_PENDING_MATCHES($dealId, $userId, 0, $userContribution, 'UNMATCHED', 0	, NULL, NULL);                           

        } else {
            $matchStatus = MATCH_STATUS_MATCHED;

            trigger_error("Matched user id " . $matched_userId);
            trigger_error( "Found match with " . $matched_userId);
            $prevCount = substr_count($prevInterests, ";") + 1;

            if($noOfUsersMatched == $prevCount) {
                //delete working matches row
                DELETE_WORKING_MATCHES($dealId);

            } else {

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

                trigger_error($interests . " " . strlen($interests));
                $interests = rtrim($interests, ";");
                $interests = "'" . $interests . "'"; 
                trigger_error($interests . " " . strlen($interests));

                UPDATE_WORKING_MATCHES($dealId, $interests);
            }
                $alpha   = str_shuffle('0123456789');
                $code = substr($alpha, 0, 4);

                echo "<br>No ofuser matched" . $noOfUsersMatched . "<br>";
                if($noOfUsersMatched == 1) {
                    $prevId = INSERT_SUCCESS_MATCHUPS($dealId, $code, $noOfUsersMatched, $userId, $usersMatched[0], NULL, 'MATCHED', $userContribution, $userInterestArray[0]);					
                } else {
                    $prevId = INSERT_SUCCESS_MATCHUPS($dealId, $code, $noOfUsersMatched, $userId, $usersMatched[0], $usersMatched[1], 'MATCHED', $userContribution, $userInterestArray[0], $userInterestArray[1]);	
                }

                if($noOfUsersMatched == 1) {

                UPDATE_USER_PENDING_MATCHES($prevId, $noOfUsersMatched, 'MATCHED', $dealId, $usersMatched[0], $userId, NULL);
                INSERT_USER_PENDING_MATCHES($dealId, $userId, $prevId, $userContribution, 'MATCHED', $noOfUsersMatched, $usersMatched[0], NULL);

                } else {

                    for($i=0; $i<$noOfUsersMatched; $i++) {

                        UPDATE_USER_PENDING_MATCHES($prevId, $noOfUsersMatched, 'MATCHED', $dealId, $usersMatched[$i], $userId, $usersMatched[1-$i]);
                    }
                    INSERT_USER_PENDING_MATCHES($dealId, $userId, $prevId, $userContribution, 'MATCHED', $noOfUsersMatched, $usersMatched[0], $usersMatched[1]);
                }	
        }     

    } else {

        process_first_interest_for_deal($userId, $dealId, $userContribution, 'UNMATCHED');
    }

//		include('db_close.php');   
}

    
?>