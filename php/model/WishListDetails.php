<?php
require_once '../dao/WishListDetailsDAO.php';
class WishListDetails
{
	private $wishListId;
	private $email;
	private $productId;
	private $userId;
	private $currentPage;
		
	public function setWishListId($wishListId) {
        $this->wishListId = $wishListId;
    }   
    public function getWishListId() {
        return $this->wishListId;
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
	  
     public function addingWishList($userId,$productId){
        $objDAO = new WishListDetailsDAO();    	
		$this->setUserId($userId);
		$this->setProductId($productId);
        $returnMessage = $objDAO->addWishList($this);
        return $returnMessage;
    }	
	
	public function showingWishList($userId,$currentPage) {
        $objDAO = new WishListDetailsDAO();		
		$this->setUserId($userId);
		$this->setCurrentPage($currentPage);		
        $returnMessage = $objDAO->showWishList($this);
        return $returnMessage;
	}
	public function deletingWishList($userId,$productId) {
        $objDAO = new WishListDetailsDAO();       
		$this->setUserId($userId);
		$this->setProductId($productId);
        $returnMessage = $objDAO->deleteWishList($this);
        return $returnMessage;
    }	
}
?>