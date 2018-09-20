<?php
require_once 'BaseDAO.php';
class wishlistDetailsDAO
{    
    private $con;
    private $msg;
    private $data;   
    // Attempts to initialize the database connection using the supplied info.
    public function wishlistDetailsDAO() {
        $baseDAO = new BaseDAO();
        $this->con = $baseDAO->getConnection();
    }
    
    public function addwishlist($wishlist) {		
        try {

            $sql2 = "SELECT * FROM wishlist WHERE productId='".$wishlist->getProductId()."' AND userId='".$wishlist->getUserId()."'";
            $isValidating = mysqli_query($this->con, $sql2);
            $count = mysqli_num_rows($isValidating);
            if ($count == 0) {
                $sql = "INSERT INTO wishlist(userId,productId)
                        VALUES ('".$wishlist->getUserId()."', '".$wishlist->getProductId()."')";

                $isInserted = mysqli_query($this->con, $sql);
                if ($isInserted) {
                    $this->data = "LIST_ADDED_SUCCESSFULLY";
                } else {
                    $this->data = "ERROR";
                }

            }else{
                $this->data = "ALREADY AVAILABLE";
            }

        } catch(Exception $e) {
            echo 'SQL Exception: ' .$e->getMessage();
        }
        return $this->data;
    }	
	
	public function showwishlist($wishlist) {		
     
		$sql = 	"SELECT w.*,p.*,c.*,IFNULL(r.rating , 0) AS rating
                            FROM productdetails p
                            INNER JOIN category c ON c.categoryId = p.productCategory   
                             INNER JOIN wishlist w ON w.productId = p.productId    
                            LEFT JOIN (SELECT r.productId, CAST(AVG(r.rating) AS DECIMAL(10,2)) as rating
                                        FROM productratingreview r
                                        GROUP BY r.productId) r
                            ON (p.productId = r.productId)                                                                                                            
                    WHERE w.userId = '".$wishlist->getUserId()."'";
        try {
            $result = mysqli_query($this->con, $sql);
            $numOfRows = mysqli_num_rows($result);          
            $rowsPerPage = 10;
            $totalPages = ceil($numOfRows / $rowsPerPage);
            
            $this->con->options(MYSQLI_OPT_CONNECT_TIMEOUT, 500);
            
            if (is_numeric($wishlist->getCurrentPage())) {
                $currentPage = (int) $wishlist->getCurrentPage();
            }            
            if ($currentPage >= 1 && $currentPage <= $totalPages) {
                $offset = ($currentPage - 1) * $rowsPerPage;
            
                $sql = 	"SELECT w.*,p.*,c.*,IFNULL(r.rating , 0) AS rating
                            FROM productdetails p
                            INNER JOIN category c ON c.categoryId = p.productCategory   
                             INNER JOIN wishlist w ON w.productId = p.productId    
                            LEFT JOIN (SELECT r.productId, CAST(AVG(r.rating) AS DECIMAL(10,2)) as rating
                                        FROM productratingreview r
                                        GROUP BY r.productId) r
                            ON (p.productId = r.productId)                                                                                                           
                    WHERE w.userId = '".$wishlist->getUserId()."'
                            LIMIT $offset, $rowsPerPage";
				$result = mysqli_query($this->con, $sql);
                $count = mysqli_num_rows($result);
				if($count !=  0) {
					$this->data=array();
					while ($rowdata = mysqli_fetch_assoc($result)) {
						$this->data[]=$rowdata;
					} 					
				} else {
					$this->data = "wishlist Not Available.";
				}
                return $this->data;
            }                      
        } catch(Exception $e) {
            echo 'SQL Exception: ' .$e->getMessage();
        }
        return $this->data=array();
    }
	
	public function deletewishlist($wishlist) {
		 try {            
			$sql = "DELETE FROM wishlist WHERE productId='".$wishlist->getProductId()."' AND userId='".$wishlist->getUserId()."'";
			$isDeleted = mysqli_query($this->con, $sql);
            if ($isDeleted) {
                $this->data = "wishlist_Deleted";                
            } else {
                $this->data = "ERROR";
            }
		} catch(Exception $e) {
            echo 'SQL Exception: ' .$e->getMessage();
        }
        return $this->data;
	}
	
	
   
}
?>