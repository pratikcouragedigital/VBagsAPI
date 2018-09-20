<?php

//$con = new mysqli("localhost", "appcom_petuser", "pet@pp2015!", "appcom_petapp");
//$con = new mysqli("103.21.59.166:3306", "appcom_petuser", "pet@pp2015!", "appcom_petapp");

$con = new mysqli("localhost", "appcom_peto_prod", "peto_prod", "appcom_peto_prod");


if ($con->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}

	$con->options(MYSQLI_OPT_CONNECT_TIMEOUT, 500);
	date_default_timezone_set('Asia/Kolkata');
	$currentDateTime = date("Y-m-d H:i:s");
	$endDateTime = date("Y-m-d 23:59:59");
	echo $currentDateTime;
	echo $endDateTime;	
	
	$sql = "DELETE FROM appcom_peto_prod.petapp WHERE post_date >= DATE_SUB('$currentDateTime', INTERVAL 1 MONTH) AND post_date <= DATE_SUB('$endDateTime',INTERVAL 1 MONTH)";
	$isDeleted = mysqli_query($con,$sql);						
		if($isDeleted) {
			echo "Pet List Deleted";
		} else{
			echo "Error";
		} 
		
	$sql = "DELETE FROM appcom_peto_prod.petmate WHERE post_date >= DATE_SUB('$currentDateTime', INTERVAL 1 MONTH) AND post_date <= DATE_SUB('$endDateTime',INTERVAL 1 MONTH)";
	$isDeleted = mysqli_query($con,$sql);						
		if($isDeleted) {
			echo "Pet Mate List Deleted";
		} else{
			echo "Error";
		} 
  //mysqli_close($con);
?>