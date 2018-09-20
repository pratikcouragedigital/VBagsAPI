<?php
require_once 'EmailGenarator.php';
class OrderConfirmationEmail2
{
	private $orderId,$finalAmountDetails,$email,$userName;
	private $userPersonalDetails;
	private$emailProductDetails,$postDate;
	

    public function setorderId($orderId) {
        $this->orderId = $orderId;
    }
    public function getorderId() {
        return $this->orderId;
    }
	public function setemail($email) {
        $this->email = $email;
    }
    public function getemail() {
        return $this->email;
    }
	public function setuserName($userName) {
        $this->userName = $userName;
    }
    public function getuserName() {
        return $this->userName;
    }
	
	
	public function setuserPersonalDetails($userPersonalDetails) {
        $this->userPersonalDetails = $userPersonalDetails;
    }
    public function getuserPersonalDetails() {
        return $this->userPersonalDetails;
    }
	
	public function setemailProductDetails($emailProductDetails) {
        $this->emailProductDetails = $emailProductDetails;
    }
    public function getemailProductDetails() {
        return $this->emailProductDetails;
    }
	

	public function setPostDate($postDate) {
        $this->postDate = $postDate;
    }    
    public function getPostDate() {
        return $this->postDate;
    }
	
    public function GenerateEmailForBuyerAndSeller($userName,$email,$orderId,$userPersonalDetails,$emailProductDetails,$finalAmountDetails,$postDate){
		$this->setuserName($userName);
		$this->setemail($email);
		$this->setorderId($orderId);
        $this->setuserPersonalDetails($userPersonalDetails);
		$this->setemailProductDetails($emailProductDetails);       
		$this->setPostDate($postDate);

		//email for customer	
		$returnEmailForUser = new OrderConfirmationEmail2();		
        $returnEmailForUser -> GenerateEmailForBuyer($userName,$email,$orderId,$userPersonalDetails,$emailProductDetails,$finalAmountDetails,$postDate);
		//email for us		
		$returnEmailForVendor = new OrderConfirmationEmail2();		
        $returnEmailForVendor -> GenerateEmailForSeller($orderId,$userPersonalDetails,$emailProductDetails,$finalAmountDetails,$postDate);
		$returnEmailSuccessMessage = "EMAIL_SUCCESSFUULY_SENT";
		return $returnEmailSuccessMessage;					
    }
	// send email to user for order conformation..
	public function GenerateEmailForBuyer($userName,$email,$orderId,$userPersonalDetails,$emailProductDetails,$finalAmountDetails,$postDate){
		$emailSender = new EmailGenarator();
        $emailSender->setTo($email);//write user mail id
        $emailSender->setFrom('From: vgbas18@gmail.com' . "\r\n" . 'Reply-To: no-reply@app.com' . "\r\n" . 'X-Mailer: PHP/' . phpversion());//write pet App mail id
        $emailSender->setMessage($this->createMessageToSendBuyer($userName,$orderId,$userPersonalDetails,$emailProductDetails,$finalAmountDetails,$postDate));
        $emailSender->setSubject("VBgas Order Confirmation!");
        $returnEmailForUser = $emailSender->sendEmail($emailSender);
		if($returnEmailForUser==true){
			return $returnEmailForUser;
		}else {
			$emailSender->sendEmail($emailSender);
		}		
    } 
    public function createMessageToSendBuyer($userName,$orderId,$userPersonalDetails,$emailProductDetails,$finalAmountDetails,$postDate){
        $emailMessage="Hi $userName,  \n\nOrder successfully placed.\nBelow are your order details.In case you have any questions please contact us with these order details. We will do our best to provide you all the information you need. Thank you once again. \n\n OrderDetals: \n  Order Id : $orderId \n\n ProductDetails : \n$emailProductDetails \n $finalAmountDetails \n\n ";
		//print_r($emailMessage);
		return $emailMessage;		
    }
	
	public function GenerateEmailForSeller($orderId,$userPersonalDetails,$emailProductDetails,$finalAmountDetails,$postDate){
        $emailSender = new EmailGenarator();
        $emailSender->setTo('vbags18@gmail.com');//write user mail id
        $emailSender->setFrom('From: mobitechs17@gmail.com' . "\r\n" . 'Reply-To: no-reply@app.com' . "\r\n" . 'X-Mailer: PHP/' . phpversion());//write pet App mail id
        $emailSender->setMessage($this->createMessageToSendSeller($orderId,$userPersonalDetails,$emailProductDetails,$finalAmountDetails,$postDate));
        $emailSender->setSubject("New Order Confirmation");// from petapp email      
		$returnEmailForPeto =  $emailSender->sendEmail($emailSender);		
		if($returnEmailForPeto==true){
			return $returnEmailForPeto;
		}else {
			$emailSender->sendEmail($emailSender);
		}      
    } 
    public function createMessageToSendSeller($orderId,$userPersonalDetails,$emailProductDetails,$finalAmountDetails,$postDate){
        $emailMessage="Hi   \n\n New order Generated!  \n\nCustomer Details : $userPersonalDetails \nProduct Details: \n Order Id : $orderId \n $emailProductDetails \n $finalAmountDetails \n\n ";
		//print_r($emailMessage);
		return $emailMessage;
    }
    
}
?>