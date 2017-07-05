<?php
/***********************************************************************************/
/* This file is used for making connection to the database.                        */
/* based on the request type.                                                      */
/***********************************************************************************/

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "split";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
	echo "Connection made";
}

?>