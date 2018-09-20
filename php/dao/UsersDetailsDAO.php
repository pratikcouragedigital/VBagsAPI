<?php
require_once 'BaseDAO.php';
require_once '../model/NewRegistrationEmails.php';

class UsersDetailsDAO
{

    private $con;
    private $msg;
    private $data;

    // Attempts to initialize the database connection using the supplied info.
    public function UsersDetailsDAO()
    {
        $baseDAO = new BaseDAO();
        $this->con = $baseDAO->getConnection();
    }

    public function saveRegistrationDetails($UsersDetail)
    {

        try {
            $sql = "SELECT * FROM  userdetails WHERE userEmail='" . $UsersDetail->getEmail() . "' OR userMobileNo='" . $UsersDetail->getMobileNo() . "'";

            $isValidating = mysqli_query($this->con, $sql);
            $count = mysqli_num_rows($isValidating);
            if ($count == 1) {
                $this->data = "Already_Registered";
            } else {
                $sql = "INSERT INTO userdetails(userFirstname,userLastName,userMobileNo,userEmail,password,userEntryDate,isEmailVerified,isUserActive)
                        VALUES ('".$UsersDetail->getFirstName()."', 
						'".$UsersDetail->getLastName()."',
						'".$UsersDetail->getMobileno()."', 
						'".$UsersDetail->getEmail()."', 
						'".$UsersDetail->getPassword()."',
						'".$UsersDetail->getUserEntryDate()."',
						'0',
						'1'
						)";

                $isInserted = mysqli_query($this->con, $sql);

                if ($isInserted) {			
					 
					$sqlCount = "SELECT * FROM userdetails";
					$queryUserCount = mysqli_query($this->con, $sqlCount);
					$numOfRows = mysqli_num_rows($queryUserCount);
					
					$userName = $UsersDetail->getFirstName()." ".$UsersDetail->getLastName();
					$email = $UsersDetail->getEmail();
					$mobileNo = $UsersDetail->getMobileno();
					
					$sendNewRegistrationEmails = new NewRegistrationEmails();
					$this->data = $sendNewRegistrationEmails -> SendNewUserRegistrationEmail($userName,$email,$mobileNo,$numOfRows);
				
					$this->data = "USERS_DETAILS_SAVED";
                   
                } else {
                    $this->data = "ERROR";
                }
            }
        } catch (Exception $e) {
            echo 'SQL Exception: ' . $e->getMessage();
        }
        return $this->data;
    }

    public function ChangeName($ChangeName)
    {
        try {

            $sql = "UPDATE userdetails
                        SET 
                        userFirstname = '".$ChangeName->getFirstName()."',
                        userLastName = '".$ChangeName->getLastName()."'                        
                        WHERE userId = '".$ChangeName->getUserId()."'";

            $isUpdated = mysqli_query($this->con, $sql);
            if ($isUpdated) {
                $this->data = "NAME_UPDATED";
                $sql = "SELECT * FROM  userdetails WHERE userId='" . $ChangeName->getUserId() . "'";
                $isValidating = mysqli_query($this->con, $sql);
                $count = mysqli_num_rows($isValidating);
                if ($count == 1) {
                    $this->data = array();
                    while ($rowdata = mysqli_fetch_assoc($isValidating)) {
                        $this->data[] = $rowdata;
                    }
                }
            } else {
                $this->data = "ERROR";
            }
        } catch (Exception $e) {
            echo 'SQL Exception: ' . $e->getMessage();
        }
        return $this->data;
    }

    public function saveAddress($addresDetails)
    {

        try {

            $sql = "INSERT INTO addressdetails(userId,addressLine1,addressLine2,city,state,country,pincode)
                        VALUES ('".$addresDetails->getUserId()."', '".$addresDetails->getAddressLine1()."', '".$addresDetails->getAddressLine2()."', '".$addresDetails->getCity()."', '".$addresDetails->getState()."','".$addresDetails->getCountry()."','".$addresDetails->getPincode()."')";
            $isInserted = mysqli_query($this->con, $sql);

            if ($isInserted) {
                $this->data = "ADDRESS_SAVED";

            } else {
                $this->data = "ERROR";
            }

        } catch (Exception $e) {
            echo 'SQL Exception: ' . $e->getMessage();
        }
        return $this->data;


    }

    public function updateAddress($addresDetails)
    {
        try {
            $sql = "UPDATE addressdetails
                        SET 
                        addressLine1 = '".$addresDetails->getAddressLine1()."',
                        addressLine2 = '".$addresDetails->getAddressLine2()."',
                        city = '".$addresDetails->getCity()."',
                        state = '".$addresDetails->getState()."',
                        pincode = '".$addresDetails->getCountry()."',
                        country = '".$addresDetails->getPincode()."'                        
                        WHERE userId = '".$addresDetails->getUserId()."' AND addressId = '".$addresDetails->getAddressId()."' ";

            $isUpdated = mysqli_query($this->con, $sql);
            if ($isUpdated) {
                $this->data = "ADDRESS_UPDATED";

            } else {
                $this->data = "ERROR";
            }

        } catch (Exception $e) {
            echo 'SQL Exception: ' . $e->getMessage();
        }
        return $this->data;
    }
	
	public function showAddressDetails($addressDetails)
	{
        try {
            $sql = "SELECT a.*,u.*
                            FROM addressdetails a
                            INNER JOIN userdetails u ON u.userId = a.userId
                            WHERE a.userId = '".$addressDetails->getUserId()."'";
            $result = mysqli_query($this->con, $sql);
            $count = mysqli_num_rows($result);
            if ($count != 0) {
                $this->data = array();
                while ($rowdata = mysqli_fetch_assoc($result)) {
                    $this->data[] = $rowdata;
                }
            } else {
                $this->data = "ADDRESS_NOT_AVAILABLE";
            }

        } catch (Exception $e) {
            echo 'SQL Exception: ' . $e->getMessage();
        }
        return $this->data;
    }


    public function fetchUserDetail($fetchDetails)
    {
        try {
            $sql = "SELECT * FROM  userdetails               
                    WHERE userId='" . $fetchDetails->getUserId() . "' OR userEmail='" . $fetchDetails->getEmail() . "' OR userMobileNo='" . $fetchDetails->getMobileNo() . "' AND password='" . $fetchDetails->getPassword() . "' ";
            $isValidating = mysqli_query($this->con, $sql);
            $count = mysqli_num_rows($isValidating);
            if ($count == 1) {
                $this->data = "VALID_PASSWORD";
                $this->data = array();
                while ($rowdata = mysqli_fetch_assoc($isValidating)) {
                    $this->data[] = $rowdata;
                }
            } else {
                $this->data = "INVALID_PASSWORD";
            }
        } catch (Exception $e) {
            echo 'SQL Exception: ' . $e->getMessage();
        }
        return $this->data;
    }

    public function loginDetail($LoginDetails)
    {
        try {
            $sql = "SELECT *
                    FROM userdetails
                    WHERE userEmail='" . $LoginDetails->getUserLoginId() . "' 
                    OR  userMobileNo='" . $LoginDetails->getUserLoginId() . "' 
                    AND password='" . $LoginDetails->getPassword() . "'";

            $isValidating = mysqli_query($this->con, $sql);
            $count = mysqli_num_rows($isValidating);
            if ($count != 0) {
                $this->data = "VALID_PASSWORD";
                $this->data = array();
                while ($rowdata = mysqli_fetch_assoc($isValidating)) {
                    $this->data[] = $rowdata;
                }
            } else {
                $this->data = "LOGIN_FAILED";
            }
        } catch (Exception $e) {
            echo 'SQL Exception: ' . $e->getMessage();
        }
        return $this->data;
    }

    public function checkPassword($CheckPassword)
    {
        try {
            $sql = "SELECT * FROM userdetails WHERE userId='" . $CheckPassword->getUserId() . "' AND password='" . $CheckPassword->getPassword() . "' ";
            $isValidating = mysqli_query($this->con, $sql);
            $count = mysqli_num_rows($isValidating);
            if ($count == 1) {
                $this->data = "VALID_PASSWORD";
            } else {
                $this->data = "INVALID_PASSWORD";
            }
        } catch (Exception $e) {
            echo 'SQL Exception: ' . $e->getMessage();
        }
        return $this->data;
    }

    public function EmailCheck($emailDetail)
    {
        try {
            $this->con->options(MYSQLI_OPT_CONNECT_TIMEOUT, 500);

            $sql = "SELECT * FROM userdetails WHERE userEmail='".$emailDetail->getEmail()."' AND userId='".$emailDetail->getUserId()."'";
            $isValidating = mysqli_query($this->con, $sql);
            $count = mysqli_num_rows($isValidating);
            if ($count == 1) {
                $this->data = "VALID_EMAIL";
                $resetPassword = new UsersDetails();
                $this->data = $resetPassword -> GenarateRandomNo($emailDetail->getEmail(),$emailDetail->getUserId());			
			} 
			else {
                $this->data = "INVALID_EMAIL";
            }

        } catch (Exception $e) {
            echo 'SQL Exception: ' . $e->getMessage();
        }
        return $this->data;
    }

	public function savingRandomNo($savingRandomNo)
    {
        try {
            //$sql = "UPDATE userdetails SET otp='" . $savingRandomNo->getRandomNoForUser() . "' WHERE userEmail='" . $savingRandomNo->getEmail() . "' OR userMobileNo='" . $savingRandomNo->getMobileNo() . "'";
            $sql = "UPDATE userdetails SET otp='".$savingRandomNo->getRandomNoForUser()."' WHERE userId='".$savingRandomNo->getUserId()."'";
            $isUpdate = mysqli_query($this->con, $sql);
            if ($isUpdate) {
                //$count = mysqli_affected_rows($this->con);
                //if ($count==1) {
                $this->data = "RANDOM_NO_SUCCESSFULLY_UPDATED";
            } else {
                $this->data = "RANDOM_NO_NOT_UPDATED";
            }
        } catch (Exception $e) {
            echo 'SQL Exception: ' . $e->getMessage();
        }
        return $this->data;
    
	}
	public function setNewPassword($setNewPassword)
    {
        try {
            $sql = "UPDATE userdetails SET password='" . $setNewPassword->getPassword() . "' WHERE userId='" . $setNewPassword->getUserId() . "' AND otp='".$setNewPassword->getOtp()."'";
            //$isUpdate = mysqli_query($this->con, $sql);
            mysqli_query($this->con, $sql);
            if (mysqli_affected_rows($this->con) >= 1) {
                $this->data = "NEW_PASSWORD_SUCCESSFULLY_SET";
            } else {
                $this->data = "ENTER_VALID_ACTIVATION_CODE";
            }
        } catch (Exception $e) {
            echo 'SQL Exception: '.$e->getMessage();
        }
        return $this->data;
    }
	
    public function GetVerificationCode($emailDetail)
    {
        try {
            $this->con->options(MYSQLI_OPT_CONNECT_TIMEOUT, 500);

            $sql = "SELECT * FROM userdetails WHERE userEmail='".$emailDetail->getEmail()."'";
            $isValidating = mysqli_query($this->con, $sql);
            $count = mysqli_num_rows($isValidating);
            if ($count == 1) {
                $this->data = "VALID_EMAIL";
                $resetPassword = new UsersDetails();
                $this->data=$resetPassword -> GenarateRandomNo($emailDetail->getEmail(),$emailDetail->getUserId());
            } else {
                $this->data = "INVALID_EMAIL";
            }

        } catch (Exception $e) {
            echo 'SQL Exception: ' . $e->getMessage();
        }
        return $this->data;
    }

}

?>