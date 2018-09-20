<?php
require_once 'EmailGenarator.php';
class NewRegistrationEmails
{	
	//For User Registration
	public function SendNewUserRegistrationEmail($name,$email,$mobileNo,$numOfRows){
				
		//email for User		
		$returnEmailForNgoOwner = new NewRegistrationEmails();
        $returnEmailForNgoOwner -> GenarateEmailForUser($name,$email,$mobileNo);	
		
		//email to vbags
		$returnEmailForPeto= new NewRegistrationEmails();		
        $returnEmailForPeto -> GenarateUserRegistrationEmailForVBags($name,$email,$mobileNo,$numOfRows);	
		
		$returnEmailSuccessMessage = "EMAIL_SUCCESSFULLY_SENT";
		return $returnEmailSuccessMessage;
    }
	
	// send email to User for New Registration..
	public function GenarateEmailForUser($name,$email,$mobileNo){
        $emailSender = new EmailGenarator();
        $emailSender->setTo($email);//write user mail id
        $emailSender->setFrom('From: vbags18@gmail.com' . "\r\n" . 'Reply-To:  vbags18@gmail.com' . "\r\n" . 'X-Mailer: PHP/' . phpversion());
        $emailSender->setMessage($this->createMessageToSendUser($name,$email));
        $emailSender->setSubject("Welcome to VBags");// from petapp email      
		$returnEmailForVendor =  $emailSender->sendEmail($emailSender);		
		if($returnEmailForVendor==true){
			return $returnEmailForVendor;
		}else {
			$emailSender->sendEmail($emailSender);
		}      
    } 
    public function createMessageToSendUser($name,$email){
        $emailMessage="Hey there !\n\n\nThank you so much for downloading and Registering with VBags. We hope to keep you updated with new products as well as your orders. \nWe are constantly striving hard to aggregate all possible information for you. \nPlease do let us know if you have any suggestions or feedback regarding our offerings as we are always open to new ideas and suggestions. \n\nThanking you,\nTeam VBags";	      
		return $emailMessage;
    }
	
	//email to vbags for New User Registration.
	public function GenarateUserRegistrationEmailForVBags($name,$email,$mobileNo,$numOfRows){
        $emailSender = new EmailGenarator();
        $emailSender->setTo('mobitechs17@gmail.com');//write user mail id
        $emailSender->setFrom('From: vbags18@gmail.com' . "\r\n" . 'Reply-To: no-reply@app.com' . "\r\n" . 'X-Mailer: PHP/' . phpversion());
        $emailSender->setMessage($this->createUserReistrationMessageToSendVBags($name,$email,$mobileNo,$numOfRows));
        $emailSender->setSubject("New User Registration");// from petapp email      
		$returnEmailForVendor =  $emailSender->sendEmail($emailSender);		
		if($returnEmailForVendor==true){
			return $returnEmailForVendor;
		}else {
			$emailSender->sendEmail($emailSender);
		}      
    } 
    public function createUserReistrationMessageToSendVBags($name,$email,$mobileNo,$numOfRows){
        $emailMessage="New User Registration Done,\n Username = $name.\nEmail = $email \nMobile No = $mobileNo \nTotal User = $numOfRows.";	 
		return $emailMessage;
    }
    
}
?>