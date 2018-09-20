<?php
require_once '../dao/ProductRatingReviewsDAO.php';
class ProductRatingReviews
{

	private $email;
	private $productId;
	private $userId;
	private $currentPage;
	private $ratings;
	private $review;
	private $entryDate;

	public function setReview($review) {
        $this->review = $review;
    }   
    public function getReview() {
        return $this->review;
    }
	public function setRatings($ratings) {
        $this->ratings = $ratings;
    }   
    public function getRatings() {
        return $this->ratings;
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

    public function setEntryDate($entryDate) {
        $this->entryDate = $entryDate;
    }
    public function getEntryDate() {
        return $this->entryDate;
    }
	
	  public function mapIncomingProductRatingReviews($ratings,$review,$userId,$productId,$entryDate){
        $this->setUserId($userId);	
        $this->setProductId($productId);
		$this->setRatings($ratings);        
		$this->setReview($review);        
		$this->setEntryDate($entryDate);
    }
	
	public function addProductRatingsReviewsDetails() {
        $objDAO = new ProductRatingReviewsDAO();
        $returnMessage = $objDAO->addRatingNReview($this);
        return $returnMessage;
    }
    
	public function showingProductReviews($currentPage,$productId) {
        $objDAO = new ProductRatingReviewsDAO();		
		$this->setProductId($productId);
		$this->setCurrentPage($currentPage);		
        $returnMessage = $objDAO->showRatingReviewList($this);
        return $returnMessage;
	}
	public function deletingReview($productId,$userId) {
        $objDAO = new ProductRatingReviewsDAO();
		$this->setProductId($productId);	
		$this->setUserId($userId);
        $returnMessage = $objDAO->deleteReview($this);
        return $returnMessage;
    }	
}
?>