<?php
require_once '../dao/ProductDetailsDAO.php';
class ProductDetails
{
    private $productId;
	private $first_image_tmp;
    private $second_image_tmp;
	private $third_image_tmp;
    private $fourth_image_tmp;
    
    private $first_image_target_path;
    private $second_image_target_path;
    private $third_image_target_path;
    private $fourth_image_target_path;

    private $productName;
    private $category;
    private $categoryName;
    private $productPrice;
    private $productShortDescription;
    private $productLongDescription;
    private $color;
    private $size;
    private $currentPage;
	private $post_date;
	private $userId;
	
	
	public function setColor($color) {
        $this->color = $color;
    }    
    public function getColor() {
        return $this->color;
    } 

	public function setUserId($userId) {
        $this->userId = $userId;
    }    
    public function getUserId() {
        return $this->userId;
    } 

	public function setFirstImageTemporaryName($first_image_tmp) {
        $this->first_image_tmp = $first_image_tmp;
    }    
    public function getFirstImageTemporaryName() {
        return $this->first_image_tmp;
    }    
    public function setSecondImageTemporaryName($second_image_tmp) {
        $this->second_image_tmp = $second_image_tmp;
    }    
    public function getSecondImageTemporaryName() {
        return $this->second_image_tmp;
    }    
    public function setThirdImageTemporaryName($third_image_tmp) {
        $this->third_image_tmp = $third_image_tmp;
    }    
    public function getThirdImageTemporaryName() {
        return $this->third_image_tmp;
    }
	
	public function setFourthImageTemporaryName($fourth_image_tmp) {
        $this->fourth_image_tmp = $fourth_image_tmp;
    }    
    public function getFourthImageTemporaryName() {
        return $this->fourth_image_tmp;
    }
	
    public function setTargetPathOfFirstImage($first_image_target_path) {
        $this->first_image_target_path = $first_image_target_path;
    }    
    public function getTargetPathOfFirstImage() {
        return $this->first_image_target_path;
    }    
    public function setTargetPathOfSecondImage($second_image_target_path) {
        $this->second_image_target_path = $second_image_target_path;
    }    
    public function getTargetPathOfSecondImage() {
        return $this->second_image_target_path;
    }    
    public function setTargetPathOfThirdImage($third_image_target_path) {
        $this->third_image_target_path = $third_image_target_path;
    }    
    public function getTargetPathOfThirdImage() {
        return $this->third_image_target_path;
    }
	
	
	public function setTargetPathOfFourthImage($fourth_image_target_path) {
        $this->fourth_image_target_path = $fourth_image_target_path;
    }    
    public function getTargetPathOfFourthImage() {
        return $this->fourth_image_target_path;
    }
    public function setCategory($category) {
        $this->category = $category;
    }    
    public function getCategory() {
        return $this->category;
    }

    public function setCategoryName($categoryName) {
        $this->categoryName = $categoryName;
    }
    public function getCategoryName() {
        return $this->categoryName;
    }
    public function setProductName($productName) {
        $this->productName = $productName;
    }    
    public function getProductName() {
        return $this->productName;
    }
    	
	 public function setSize($size) {
        $this->size = $size;
    }
    public function getSize() {
        return $this->size;
    }
    public function setProductShortDescription($productShortDescription) {
        $this->productShortDescription = $productShortDescription;
    }    
    public function getProductShortDescription() {
        return $this->productShortDescription;
    }
    public function setProductLongDescription($productLongDescription) {
        $this->productLongDescription = $productLongDescription;
    }    
    public function getProductLongDescription() {
        return $this->productLongDescription;
    }
    public function setProductPrice($productPrice) {
        $this->productPrice = $productPrice;
    }    
    public function getProductPrice() {
        return $this->productPrice;
    }
    public function setProductId($productId) {
        $this->productId = $productId;
    }    
    public function getProductId() {
        return $this->productId;
    }    
    public function setCurrentPage($currentPage) {
        $this->currentPage = $currentPage;
    }    
    public function getCurrentPage() {
        return $this->currentPage;
    }
    public function setPostDate($post_date) {
        $this->post_date = $post_date;
    }    
    public function getPostDate() {
        return $this->post_date;
    }        	     

    public function mapIncomingProductDetailsParams($first_image_tmp, $first_image_target_path, $second_image_tmp, $second_image_target_path, $third_image_tmp, $third_image_target_path,$fourth_image_tmp, $fourth_image_target_path,$productName, $category, $productPrice, $productShortDescription, $productLongDescription, $size, $color, $post_date, $userId){

		$this->setFirstImageTemporaryName($first_image_tmp);
		$this->setTargetPathOfFirstImage($first_image_target_path);
		
        $this->setSecondImageTemporaryName($second_image_tmp);
		$this->setTargetPathOfSecondImage($second_image_target_path);
		
        $this->setThirdImageTemporaryName($third_image_tmp);               
        $this->setTargetPathOfThirdImage($third_image_target_path);	
		
		$this->setFourthImageTemporaryName($fourth_image_tmp);
        $this->setTargetPathOfFourthImage($fourth_image_target_path);
				
        $this->setProductName($productName);
        $this->setCategory($category);
        $this->setProductPrice($productPrice);
        $this->setSize($size);
		$this->setColor($color);
        $this->setProductShortDescription($productShortDescription);
        $this->setProductLongDescription($productLongDescription);
        $this->setUserId($userId);
        $this->setPostDate($post_date);       
    }

    public function AddProductDetails() {
        $objDAO = new ProductDetailsDAO();
        $returnMessage = $objDAO->saveProductDetails($this);
        return $returnMessage;
    }

    public function AddingCategory($categoryName) {
        $this->setCategoryName($categoryName);
        $objDAO = new ProductDetailsDAO();
        $returnMessage = $objDAO->saveCategory($this);
        return $returnMessage;
    }
	
	public function productList($currentPage) {
         $objDAO = new ProductDetailsDAO();
         $this->setCurrentPage($currentPage);        
         $returnMessage = $objDAO->showProductDetails($this);
         return $returnMessage;
    }
	public function ProductWiseDetails($productId) {
         $objDAO = new ProductDetailsDAO();
         $this->setProductId($productId);        
         $returnMessage = $objDAO->showProductWiseDetails($this);
         return $returnMessage;
    }
	public function topProductList() {
         $objDAO = new ProductDetailsDAO();              
         $returnMessage = $objDAO->showTopProductDetails($this);
         return $returnMessage;
    }

    public function userWishList($userId) {
         $objDAO = new ProductDetailsDAO();
         $this->setUserId($userId);
         $returnMessage = $objDAO->showUserWishList($this);
         return $returnMessage;
    }
    public function productImage ($productId){
        $objDAO = new ProductDetailsDAO();
        $this->setProductId($productId);
        $returnMessage = $objDAO->showProductImages($this);
        return $returnMessage;
    }
    public function productRatingReviews ($productId){
        $objDAO = new ProductDetailsDAO();
        $this->setCurrentPage($productId);
        $returnMessage = $objDAO->showProductRatingReviews($this);
        return $returnMessage;
    }


    public function categoryWiseProductList($category,$currentPage) {
         $objDAO = new ProductDetailsDAO();
         $this->setCategory($category);
         $this->setCurrentPage($currentPage);
         $returnMessage = $objDAO->showCategoryWiseProductDetails($this);
         return $returnMessage;
    }
	
	public function showingCategories() {
         $objDAO = new ProductDetailsDAO();       
         $returnMessage = $objDAO->showCategoriesList($this);
         return $returnMessage;
    }
    
   
    
}
?>