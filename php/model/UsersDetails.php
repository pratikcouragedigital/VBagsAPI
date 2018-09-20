<?php
require_once '../dao/UsersDetailsDAO.php';
require_once 'RandomNoGenarator.php';
require_once 'EmailGenarator.php';

class UsersDetails
{
    private $userId, $firstName, $lastName, $mobileno, $email, $userLoginId, $password, $otp, $userEntryDate;
    private $addressId, $addressLine1, $addressLine2, $city, $state, $pincode, $country;
    private $randomNoForUser, $activationCode;

    public function setRandomNoForUser($randomNoForUser)
    {
        $this->randomNoForUser = $randomNoForUser;
    }

    public function getRandomNoForUser()
    {
        return $this->randomNoForUser;
    }

    public function setActivationCode($activationCode)
    {
        $this->activationCode = $activationCode;
    }

    public function getActivationCode()
    {
        return $this->activationCode;
    }


    public function setAddressId($addressId)
    {
        $this->addressId = $addressId;
    }

    public function getAddressId()
    {
        return $this->addressId;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserLoginId($userLoginId)
    {
        $this->userLoginId = $userLoginId;
    }

    public function getUserLoginId()
    {
        return $this->userLoginId;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setMobileno($mobileno)
    {
        $this->mobileno = $mobileno;
    }

    public function getMobileno()
    {
        return $this->mobileno;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setOtp($otp)
    {
        $this->otp = $otp;
    }

    public function getOtp()
    {
        return $this->otp;
    }

    public function setAddressLine1($addressLine1)
    {
        $this->addressLine1 = $addressLine1;
    }

    public function getAddressLine1()
    {
        return $this->addressLine1;
    }

    public function setAddressLine2($addressLine2)
    {
        $this->addressLine2 = $addressLine2;
    }

    public function getAddressLine2()
    {
        return $this->addressLine2;
    }

    public function setCity($city)
    {
        $this->city = $city;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setState($state)
    {
        $this->state = $state;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setCountry($country)
    {
        $this->country = $country;
    }

    public function getCountry()
    {
        return $this->country;
    }


    public function setPincode($pincode)
    {
        $this->pincode = $pincode;
    }

    public function getPincode()
    {
        return $this->pincode;
    }

    public function setUserEntryDate($userEntryDate)
    {
        $this->userEntryDate = $userEntryDate;
    }

    public function getUserEntryDate()
    {
        return $this->userEntryDate;
    }

//    public function mapIncomingUserDetailsParams($firstName, $lastName, $mobileno, $email, $password, $userEntryDate, $addressLine1Line1, $addressLine1Line2, $city, $state, $country, $pinCode)
    public function mapIncomingUserDetailsParams($firstName, $lastName, $mobileno, $email, $password, $userEntryDate)
    {
        $this->setfirstName($firstName);
        $this->setLastName($lastName);
        $this->setMobileno($mobileno);
        $this->setEmail($email);
        $this->setPassword($password);
        $this->setUserEntryDate($userEntryDate);
//        $this->setAddressLine1($addressLine1Line1);
//        $this->setAddressLine2($addressLine1Line2);
//        $this->setCity($city);
//        $this->setState($state);
//        $this->setCountry($country);
//        $this->setPincode($pinCode);
    }

    public function mapIncomingAddressDetailsParams($userId, $addressLine1Line1, $addressLine1Line2, $city, $state, $country, $pinCode)
    {
        $this->setUserId($userId);
        $this->setAddressLine1($addressLine1Line1);
        $this->setAddressLine2($addressLine1Line2);
        $this->setCity($city);
        $this->setState($state);
        $this->setCountry($country);
        $this->setPincode($pinCode);
    }

    public function mapIncomingUpdateAddressDetailsParams($addressId, $userId, $addressLine1Line1, $addressLine1Line2, $city, $state, $country, $pinCode)
    {
        $this->setAddressId($addressId);
        $this->setUserId($userId);
        $this->setAddressLine1($addressLine1Line1);
        $this->setAddressLine2($addressLine1Line2);
        $this->setCity($city);
        $this->setState($state);
        $this->setCountry($country);
        $this->setPincode($pinCode);
    }

    public function SavingAddress()
    {
        $objDAO = new UsersDetailsDAO();
        $returnMessage = $objDAO->saveAddress($this);
        return $returnMessage;
    }

    public function updatingAddress()
    {
        $objDAO = new UsersDetailsDAO();
        $returnMessage = $objDAO->updateAddress($this);
        return $returnMessage;
    }

    public function SavingUsersDetails()
    {
        $objDAO = new UsersDetailsDAO();
        $returnMessage = $objDAO->saveRegistrationDetails($this);
        return $returnMessage;
    }

    public function FetchingUsersDetails($userId, $email, $mobileNo, $password)
    {
        $objDAO = new UsersDetailsDAO();
        $this->setEmail($email);
        $this->setMobileno($mobileNo);
        $this->setPassword($password);
        $this->setUserId($userId);
        $returnMessage = $objDAO->fetchUserDetail($this);
        return $returnMessage;
    }

    public function CheckingLoginDetails($userLoginId, $password)
    {
        $this->setUserLoginId($userLoginId);
        $this->setPassword($password);
        $objDAO = new UsersDetailsDAO();
        $returnMessage = $objDAO->loginDetail($this);
        return $returnMessage;
    }
	
	public function CheckingEmail($email, $userId)
    {
        $this->setEmail($email);
        $this->setUserId($userId);
        $objDAO = new UsersDetailsDAO();
        $returnMessage = $objDAO->EmailCheck($this);
        return $returnMessage;
    }
	
    public function PasswordChecking($userId, $password)
    {
        $this->setPassword($password);
        $this->setUserId($userId);
        $objDAO = new UsersDetailsDAO();
        $returnMessage = $objDAO->checkPassword($this);
        return $returnMessage;
    }

    public function GettingVerificationCode($email, $userId)
    {
        $this->setEmail($email);
        $this->setUserId($userId);
        $objDAO = new UsersDetailsDAO();
        $returnMessage = $objDAO->GetVerificationCode($this);
        return $returnMessage;
    }

    public function ChangingName($firstName, $lastName, $userId)
    {
        $this->setfirstName($firstName);
        $this->setLastName($lastName);
        $this->setUserId($userId);
        $objDAO = new UsersDetailsDAO();
        $returnMessage = $objDAO->ChangeName($this);
        return $returnMessage;
    }

    public function SettingNewPassword($userId, $otp, $newPassword)
    {
        $this->setOtp($otp);
        $this->setPassword($newPassword);
        $this->setUserId($userId);
        $objDAO = new UsersDetailsDAO();
        $returnMessage = $objDAO->setNewPassword($this);
        return $returnMessage;
    }

    public function showingAddressDetails($userId)
    {
        $this->setUserId($userId);
        $objDAO = new UsersDetailsDAO();
        $returnMessage = $objDAO->showAddressDetails($this);
        return $returnMessage;
    }

    public function GenarateRandomNo($email,$userId)
    {
        //Call RandomNoGenarator class to create Random no
        $this->setEmail($email);
        $this->setUserId($userId);
        $randomno = new RandomNoGenarator();
        $genaratedRandomNo = $randomno->GenarateCode(6);
        $this->setRandomNoForUser($genaratedRandomNo);

        // call GenarateEmailForUSer to send Randomno to user
        $returnSuccessRandomNo = $this->GenarateEmailForUSer();

        //call LoginDetailsDAO to save random no as per user 
        $saveRandomNoDAO = new UsersDetailsDAO();
        $returnSuccessRandomNo = $saveRandomNoDAO->savingRandomNo($this);
        return $returnSuccessRandomNo;
    }

    //Call Email Class to create email for user
    public function GenarateEmailForUSer()
    {
        $emailSender = new EmailGenarator();
        $emailSender->setTo($this->getEmail());//write user mail id
        $emailSender->setFrom('From: vags17@gmaail.com' . "\r\n" . 'Reply-To: no-reply@app.com' . "\r\n" . 'X-Mailer: PHP/' . phpversion());//write pet App mail id
        $emailSender->setMessage($this->createMessageToSendUser());
        $emailSender->setSubject("OTP VBags");
        return $emailSender->sendEmail($emailSender);
    }

    public function createMessageToSendUser()
    {
        $emailMessage = "Your verification code is " . $this->getRandomNoForUser();
        return $emailMessage;
    }


}

?>