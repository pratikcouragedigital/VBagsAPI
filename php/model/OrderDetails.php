<?php
require_once '../dao/OrderDetailsDAO.php';
class OrderDetails
{
	private $orderId;
	private $orderStatusId;
	private $orderBulkId;
	private $email;
	private $cartIdArray;
	private $productId;
	private $userId;
	private $qty;
	private $deliveryCharges;
	private $currentPage;	
	private $totalPayableAmount;
	private $totalPayableAmountWithDelCharges;
	private $productPrice;
	private $productName;
	private $userName;
	private $mobileno;
	private $postDate;
	private $shipmentAddressId;
	private $fullAddress;
	private $razorpayPaymentID;
	private $paymentType;
	private $luckyDrawPrice;

	
	public function setLuckyDrawPrice($luckyDrawPrice) {
        $this->luckyDrawPrice = $luckyDrawPrice;
    }   
    public function getLuckyDrawPrice() {
        return $this->luckyDrawPrice;
    }
	
	public function setOrderStatusId($orderStatusId) {
        $this->orderStatusId = $orderStatusId;
    }   
    public function getOrderStatusId() {
        return $this->orderStatusId;
    }
	
	public function setCartIdArray($cartIdArray) {
        $this->cartIdArray = $cartIdArray;
    }   
    public function getCartIdArray() {
        return $this->cartIdArray;
    }
	
    public function setorderBulkId($orderBulkId) {
        $this->orderBulkId = $orderBulkId;
    }
    public function getorderBulkId() {
        return $this->orderBulkId;
    }
	
	public function setfullAddress($fullAddress) {
        $this->fullAddress = $fullAddress;
    }
    public function getfullAddress() {
        return $this->fullAddress;
    }

    public function setShipmentAddressId($shipmentAddressId) {
        $this->shipmentAddressId = $shipmentAddressId;
    }
    public function getShipmentAddressId() {
        return $this->shipmentAddressId;
    }

	
	public function setProductName($productName) {
        $this->productName = $productName;
    }   
    public function getProductName() {
        return $this->productName;
    }

	public function setPostDate($postDate) {
        $this->postDate = $postDate;
    }   
    public function getPostDate() {
        return $this->postDate;
    }

	public function setMobileno($mobileno) {
        $this->mobileno = $mobileno;
    }   
    public function getMobileno() {
        return $this->mobileno;
    }
	
	public function setUserName($userName) {
        $this->userName = $userName;
    }   
    public function getUserName() {
        return $this->userName;
    }
	
	public function setTotalPayableAmount($totalPayableAmount) {
        $this->totalPayableAmount = $totalPayableAmount;
    }   
    public function geTtotalPayableAmount() {
        return $this->totalPayableAmount;
    }
	
	
	public function setTotalPriceWithDelCharges($totalPriceWithDelCharges) {
        $this->totalPriceWithDelCharges = $totalPriceWithDelCharges;
    }   
    public function getTotalPriceWithDelCharges() {
        return $this->totalPriceWithDelCharges;
    }
	
	public function setOrderId($orderId) {
        $this->orderId = $orderId;
    }   
    public function getOrderId() {
        return $this->orderId;
    }
	
	public function setDeliveryCharges($deliveryCharges) {
        $this->deliveryCharges = $deliveryCharges;
    }   
    public function getDeliveryCharges() {
        return $this->deliveryCharges;
    }
	
	public function setProductPrice($productPrice) {
        $this->productPrice = $productPrice;
    }   
    public function getProductPrice() {
        return $this->productPrice;
    }
	
	public function setQty($qty) {
        $this->qty = $qty;
    }   
    public function getQty() {
        return $this->qty;
    }
	
	public function setUserId($userId) {
        $this->userId = $userId;
    }   
    public function getUserId() {
        return $this->userId;
    }	
	public function setEmail($email) {
        $this->email = $email;
    }   
    public function getEmail() {
        return $this->email;
    }	
	
	public function setCurrentPage($currentPage) {
        $this->currentPage = $currentPage;
    }   
    public function getCurrentPage() {
        return $this->currentPage;
    }
	
	public function setProductId($productId) {
        $this->productId = $productId;
    }   
    public function getProductId() {
        return $this->productId;
    }
	
	public function setRazorpayPaymentID($razorpayPaymentID) {
        $this->razorpayPaymentID = $razorpayPaymentID;
    }   
    public function getRazorpayPaymentID() {
        return $this->razorpayPaymentID;
    }
	
	
	public function setPaymentType($paymentType) {
        $this->paymentType = $paymentType;
    }   
    public function getPaymentType() {
        return $this->paymentType;
    }


	public function mapIncomingOrderDetailsParams($cartIdArray,$productId,$razorpayPaymentID,$setPaymentType,$productName,$qty,$productPrice,$deliveryCharges,$totalPriceWithDelCharges,$totalPayableAmount,$luckyDrawPrice, $userId,$userName,$mobileno,$email,$shipmentAddressId,$fullAddress,$postDate) {
        $this->setCartIdArray($cartIdArray);
        $this->setProductId($productId);
        $this->setRazorpayPaymentID($razorpayPaymentID);
        $this->setPaymentType($setPaymentType);
        $this->setProductName($productName);
		$this->setQty($qty);
        $this->setProductPrice($productPrice);
		$this->setDeliveryCharges($deliveryCharges);	
		$this->setTotalPriceWithDelCharges($totalPriceWithDelCharges);
		$this->setTotalPayableAmount($totalPayableAmount);
		$this->setLuckyDrawPrice($luckyDrawPrice);

        $this->setUserId($userId);
		$this->setUserName($userName);
		$this->setMobileno($mobileno);
        $this->setEmail($email);

        $this->setShipmentAddressId($shipmentAddressId);
        $this->setfullAddress($fullAddress);
		$this->setPostDate($postDate);

    }	
	
	 public function SavingOrderDetails() {
        $objDAO = new OrderDetailsDAO();
        $returnMessage = $objDAO->SaveOrderDetails($this);
		//print_r($this);
        return $returnMessage;
    }
	
	public function showingAllOrderedList($userId,$currentPage){
        $objDAO = new OrderDetailsDAO();	
		$this->setUserId($userId);
		$this->setCurrentPage($currentPage);		
        $returnMessage = $objDAO->showAllOrderList($this);
        return $returnMessage;
	}
	public function showingGeneratedOrderedList($currentPage){
        $objDAO = new OrderDetailsDAO();	
		$this->setCurrentPage($currentPage);		
        $returnMessage = $objDAO->showGeneratedOrderList($this);
        return $returnMessage;
	}
		
	public function showingGeneratedOrderWiseProductList($orderBulkId){
        $objDAO = new OrderDetailsDAO();		
		$this->setorderBulkId($orderBulkId);		
        $returnMessage = $objDAO->showGeneratedOrderWiseProductList($this);
        return $returnMessage;
	}
	
	public function showingOrderedList($userId,$currentPage){
        $objDAO = new OrderDetailsDAO();	
		$this->setUserId($userId);
		$this->setCurrentPage($currentPage);		
        $returnMessage = $objDAO->showOrderList($this);
        return $returnMessage;
	}
	
	public function showingOrderWiseProductList($userId,$orderBulkId){
        $objDAO = new OrderDetailsDAO();	
		$this->setUserId($userId);
		$this->setorderBulkId($orderBulkId);		
        $returnMessage = $objDAO->showOrderWiseProductList($this);
        return $returnMessage;
	}
	public function GettingOrderStatusList() {
        $objDAO = new OrderDetailsDAO();
        $returnMessage = $objDAO->GetOrderStatusList($this);
        return $returnMessage;
    }
	public function UpdatingOrderStatus($orderId,$orderBulkId, $orderStatusId) {
        $objDAO = new OrderDetailsDAO();
        $this->setOrderId($orderId);
        $this->setOrderBulkId($orderBulkId);
		$this->setOrderStatusId($orderStatusId);
        $returnMessage = $objDAO->UpdateOrderStatus($this);
        return $returnMessage;
    }	
}
?>