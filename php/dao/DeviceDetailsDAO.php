<?php
require_once 'BaseDAO.php';
class DeviceDetailsDAO
{
    
    private $con;
    private $msg;
    private $data;
    
    // Attempts to initialize the database connection using the supplied info.
    public function DeviceDetailsDAO() {
        $baseDAO = new BaseDAO();
        $this->con = $baseDAO->getConnection();
    }

    public function AddDeviceDetails($FirebaseDetails) {
        try {
            $sql = "SELECT * FROM devicedetails WHERE deviceId='".$FirebaseDetails->getDeviceId()."'";
            $isChecking = mysqli_query($this->con, $sql);
            $count = mysqli_num_rows($isChecking);
            if($count == 0) {
                $this->data = $this->saveFirebaseToken($FirebaseDetails);
            } else {
                $this->data = $this->updateFirebaseToken($FirebaseDetails);
            }
        } catch(Exception $e) {
            echo 'SQL Exception: ' .$e->getMessage();
        }
        return $this->data;
    }

    public function saveFirebaseToken($FirebaseDetails) {
        try {
            $sql = "INSERT INTO devicedetails(userId,deviceId, tokenNo,deviceName,date)
                        VALUES ('".$FirebaseDetails->getUserId()."', '".$FirebaseDetails->getDeviceId()."',
								'".$FirebaseDetails->getTokenNo()."', '".$FirebaseDetails->getDeviceName()."',
								'".$FirebaseDetails->getEntryDate()."')";
            $isInserted = mysqli_query($this->con, $sql);
            if($isInserted) {
                $this->data = "DEVICE_DETAILS_SAVED";
            } else {
                $this->data = "DEVICE_DETAILS_NOT_SAVED";
            }
        } catch(Exception $e) {
            echo 'SQL Exception: ' .$e->getMessage();
        }
        return $this->data;
    }

    public function updateFirebaseToken($FirebaseDetails) {
        try {
            $sql="UPDATE devicedetails SET tokenNo='".$FirebaseDetails->getTokenNo()."',
											date='".$FirebaseDetails->getEntryDate()."'
                    WHERE deviceId='".$FirebaseDetails->getDeviceId()."' AND  userId='".$FirebaseDetails->getUserId()."'";
            $isUpdated = mysqli_query($this->con, $sql);
            if($isUpdated) {
                $this->data = "DEVICE_DETAILS_UPDATED";
            } else {
                $this->data = "DEVICE_DETAILS_NOT_UPDATED";
            }
        } catch(Exception $e) {
            echo 'SQL Exception: ' .$e->getMessage();
        }
        return $this->data;
    }
}
?>