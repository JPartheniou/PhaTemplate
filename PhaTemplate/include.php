<?php
function connectDB(){
	global $mysqli;
	
	//connect to server and select database
	$mysqli = mysqli_connect("localhost", "parthe_root", "!root123#", "parthe_class");
	
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}
	
	/*function emailChecker($email) {
	global $mysqli
	}*/
}




?>