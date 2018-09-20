<?php
require_once '../model/DonationEmails.php';
$con = new mysqli("localhost", "appcom_petuser", "pet@pp2015!", "appcom_petapp");
//$con = new mysqli("103.21.59.166:3306", "appcom_petuser", "pet@pp2015!", "appcom_petapp");
//$con = new mysqli("103.21.59.166:3306", "appcom_peto_prod", "peto_prod", "appcom_peto_prod");

	if ($con->connect_errno) {
		printf("Connect failed: %s\n", $mysqli->connect_error);
		exit();
	}

	$con->options(MYSQLI_OPT_CONNECT_TIMEOUT, 500);
		$emptyValue;	
		try {
			//when expiray of booking is reached.
			$sql = "UPDATE trainer set date_booking_to='$emptyValue',date_booking_from='$emptyValue',list_position='Free',list_price='0',adv_booked='Not Available'
					WHERE date_booking_to = curDate()";    
			$isRemoved = mysqli_query($con, $sql);
			if ($isRemoved) {
				echo "List Successfully Updated Premium to Free now. ";				
			} else {
				echo "ERROR_WHILE_UPDATING ";
			}
        } catch(Exception $e) {
            echo 'SQL Exception: ' .$e->getMessage();
        }
		
		try {
			//Premium service started from today who booked adv.
			$sql = "UPDATE trainer set date_booking_from = curdate(), date_booking_to = DATE_ADD(curdate(), INTERVAL 1 MONTH),
					adv_date_booking_from = '$emptyValue',  adv_date_booking_to = '$emptyValue',adv_booked = 'No'
					WHERE adv_date_booking_from = curDate();";    
			$isRemoved = mysqli_query($con, $sql);
			if ($isRemoved) {
				echo "List Successfully Updated.Premium Service Started From Today for 1 Month.";				
			} else {
				echo "ERROR_WHILE_UPDATING";
			}
        } catch(Exception $e) {
            echo 'SQL Exception: ' .$e->getMessage();
        }
		
	
	
?>