<?php
require_once '../dao/CartListDetailsDAO.php';
class CartListDetails
{
	private $cartListId;
	private $email;
	private $productId;
	private $userId;
	private $qty;
	private $productPrice;
	private $totalPrice;
	private $deliveryCharges;
	private $currentPage;
	
	
	public function setCartListId($cartListId) {
        $this->cartListId = $cartListId;
    }   
    public function getCartListId() {
        return $this->cartListId;
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
	
	public function setTotalPrice($totalPrice) {
        $this->totalPrice = $totalPrice;
    }   
    public function getTotalPrice() {
        return $this->totalPrice;
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

	  
     public function addingCartList($userId, $productId, $qty, $productPrice, $deliveryCharges,$totalPrice) {
        $objDAO = new CartListDetailsDAO();    		
		$this->setUserId($userId);
		$this->setProductId($productId);
		$this->setQty($qty);
		$this->setProductPrice($productPrice);
		$this->setDeliveryCharges($deliveryCharges);		
		$this->setTotalPrice($totalPrice);
        $returnMessage = $objDAO->addCartList($this);
        return $returnMessage;
    }	
	public function updatingCartList($userId, $productId, $qty, $cartId, $totalPrice) {
        $objDAO = new CartListDetailsDAO();
		$this->setUserId($userId);
		$this->setProductId($productId);
		$this->setQty($qty);
		$this->setCartListId($cartId);
		$this->setTotalPrice($totalPrice);
        $returnMessage = $objDAO->updateCartList($this);
        return $returnMessage;
    }

	public function showingCartList($userId,$currentPage){
        $objDAO = new CartListDetailsDAO();
		$this->setUserId($userId);
		$this->setCurrentPage($currentPage);		
        $returnMessage = $objDAO->showCartList($this);
        return $returnMessage;
	}
	public function deletingCartList($userId,$productId) {
        $objDAO = new CartListDetailsDAO();
		$this->setUserId($userId);
		$this->setProductId($productId);
        $returnMessage = $objDAO->deleteCartList($this);
        return $returnMessage;
    }	
}
?>