<?php
/***********************************************************************************/
/* This file contains all the functions necessary for processing a new matchup     */
/* request.                                                                        */
/***********************************************************************************/
        
function handle_new_matchup_request($user_id, $deal_id, $user_contribution) {
    
		$sql = "SELECT interests FROM working_matches WHERE deal_id = " . $deal_id;
	  	$result = $conn->query($sql);

		if ($result->num_rows > 0) {
    // output data of each row
			$row = $result->fetch_assoc();
      	echo "Interests: " . $row["interests"] . "<br>";
    	} else {
			echo "0 results";
		} 
}
    
?>