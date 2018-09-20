<?php
require_once '../dao/DeviceDetailsDAO.php';

class DeviceDetails
{
    private $deviceId;
    private $deviceName;
    private $tokenNo;
    private $userId;
    private $entryDate;
    
    
    public function setEntryDate($entryDate) {
        $this->entryDate = $entryDate;
    }
    
    public function getEntryDate() {
        return $this->entryDate;
    } 
 public function setUserId($userId) {
        $this->userId = $userId;
    }
    
    public function getUserId() {
        return $this->userId;
    } 

	public function setDeviceName($deviceName) {
        $this->deviceName = $deviceName;
    }
    
    public function getDeviceName() {
        return $this->deviceName;
    } 
	
	public function setDeviceId($deviceId) {
        $this->deviceId = $deviceId;
    }
    
    public function getDeviceId() {
        return $this->deviceId;
    }
    
    public function setTokenNo($tokenNo) {
        $this->tokenNo = $tokenNo;
    }
    
    public function getTokenNo() {
        return $this->tokenNo;
    }

    public function AddingDeviceDetails($deviceId, $tokenNo,$userId,$deviceName,$entryDate) {
        $this->setDeviceId($deviceId);
        $this->setDeviceName($deviceName);
		$this->setTokenNo($tokenNo);
        $this->setUserId($userId);
        $this->setEntryDate($entryDate);
       
        $saveFirebaseTokenDAO = new DeviceDetailsDAO();
        $returnSaveFirebaseTokenMessage = $saveFirebaseTokenDAO->AddDeviceDetails($this);
        return $returnSaveFirebaseTokenMessage;
    }
}
?>