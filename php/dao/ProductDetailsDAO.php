<?php
require_once 'BaseDAO.php';

class ProductDetailsDAO
{
    private $con;
    private $msg;
    private $data;
    private $googleAPIKey;

    // Attempts to initialize the database connection using the supplied info.
    public function ProductDetailsDAO()
    {
        $baseDAO = new BaseDAO();
        $this->con = $baseDAO->getConnection();
        $this->googleAPIKey = $baseDAO->getGoogleAPIKey();	
	
    }

    public function saveProductDetails($productdetails)
    {
        try {			
				$status = 0;
				$tempNames = array($productdetails->getFirstImageTemporaryName(), $productdetails->getSecondImageTemporaryName(), $productdetails->getThirdImageTemporaryName(), $productdetails->getFourthImageTemporaryName());
				$targetPaths = array($productdetails->getTargetPathOfFirstImage(), $productdetails->getTargetPathOfSecondImage(), $productdetails->getTargetPathOfThirdImage(),  $productdetails->getTargetPathOfFourthImage());
				foreach ($tempNames as $index => $tempName) {
					
					if(move_uploaded_file($tempName, $targetPaths[$index])) {
						$status = 1;
					}
				}
				if($status = 1) {
					$sql = "INSERT INTO productdetails(productName, productCategory, img1,img2,img3,img4,size, color, productShortDescription,
					productLongDescription, productPrice,productPostDate,userId,isProductAvailable,isProductDelete)
										VALUES('" . $productdetails->getProductName() . "',
										'" . $productdetails->getCategory() . "',
										'" . $productdetails->getTargetPathOfFirstImage() . "',
										'" . $productdetails->getTargetPathOfSecondImage() . "',
										'" . $productdetails->getTargetPathOfThirdImage() . "',
										'" . $productdetails->getTargetPathOfFourthImage() . "',
										'" . $productdetails->getSize() . "',
										'" . $productdetails->getColor() . "',
										'" . $productdetails->getProductShortDescription() . "',
										'" . $productdetails->getProductLongDescription() . "',
										'" . $productdetails->getProductPrice() . "',
										'" . $productdetails->getPostDate() . "',
										'" . $productdetails->getUserId() . "',
										1,
										0)";
					$isInserted = mysqli_query($this->con, $sql);
					if ($isInserted) {
						$this->data = "PRODUCT_DETAILS_SAVED";
						$productId = mysqli_insert_id($this->con);	
						// $productId = mysqli_insert_id($this->con);
										
						// foreach ($tempNames as $index => $tempName) {							
							// print_r($targetPaths[$index]);
							// $sqlProductImage4 = "INSERT INTO productimage(productId, productImageName, entryDate)
										// VALUES('".$productId."',
										// '".$targetPaths[$index]."',
										// '".$productdetails->getPostDate()."'
										// )";
							// $isImageInserted4 = mysqli_query($this->con, $sqlProductImage4);
						// }
						
						$this->msg =array(
									'NOTIFICAION_TYPE' => 'OPEN_ACTIVITY_PRODUCT_DETAILS',						
									'PRODUCT_FIRST_IMAGE' => 'http://mobitechs.in/VBagsAPI/product_images/'.$productdetails->getTargetPathOfFirstImage(),
									'PRODUCT_SECOND_IMAGE' => 'http://mobitechs.in/VBagsAPI/product_images/'.$productdetails->getTargetPathOfSecondImage(),
									'PRODUCT_THIRD_IMAGE' => 'http://mobitechs.in/VBagsAPI/product_images/'.$productdetails->getTargetPathOfThirdImage(),
									'PRODUCT_FORTH_IMAGE' => 'http://mobitechs.in/VBagsAPI/product_images/'.$productdetails->getTargetPathOfFourthImage(),
									'NOTIFICATION_PRODUCT_ID' => $productId,									
									'PRODUCT_NAME' => $productdetails->getProductName(),									
									'PRODUCT_CATEGORY' => $productdetails->getCategory(),									
									'PRODUCT_SIZE'	=> $productdetails->getSize(),
									'PRODUCT_COLOUR' => $productdetails->getColor(),
									'PRODUCT_SHORT_DESCRIPTION' => $productdetails->getProductShortDescription(),
									'PRODUCT_LONG_DESCRIPTION' => $productdetails->getProductLongDescription(),
									'PRODUCT_PRICE' => $productdetails->getProductPrice(),
						);
						$this->fetchFirebaseTokenUsers($this->msg);
					}
					else{
						$this->data = "ERROR";
					}
				} else {
					$this->data = "ERROR";
				}
        } 
		catch (Exception $e) {
            echo 'SQL Exception: ' . $e->getMessage();
        }
        return $this->data;
    }

    public function saveCategory($productdetails)
    {
        try {
			$sql = "SELECT * FROM category WHERE  categoryName = '" . $productdetails->getCategoryName() . "'";
            $check = mysqli_query($this->con, $sql);
			$count = mysqli_num_rows($check);
			if($count > 0){
				 $this->data = "CATEGORY_ALREADY_EXIST";
			}
			else{
				  $sql = "INSERT INTO category(categoryName) VALUES('" . $productdetails->getCategoryName() . "')";
				$isInserted = mysqli_query($this->con, $sql);
				if ($isInserted) {
					$this->data = "CATEGORY_SAVED";
				} else {
					$this->data = "ERROR";
				}
			}
			        
        } catch (Exception $e) {
            echo 'SQL Exception: ' . $e->getMessage();
        }
        return $this->data;
    }

		public function showProductDetails($pageWiseData)
		{
			try {
					$sql = "SELECT p.*,c.*,IFNULL(r.rating , 0) AS rating
								FROM productdetails p
								INNER JOIN category c ON c.categoryId = p.productCategory                          
								LEFT JOIN (SELECT r.productId, CAST(AVG(r.rating) AS DECIMAL(10,2)) as rating
											FROM productratingreview r
											GROUP BY r.productId) r
								ON (p.productId = r.productId)                                                         
								ORDER By p.productPostDate DESC";
				$result = mysqli_query($this->con, $sql);
				$numOfRows = mysqli_num_rows($result);

				$rowsPerPage = 10;
				$totalPages = ceil($numOfRows / $rowsPerPage);

				$this->con->options(MYSQLI_OPT_CONNECT_TIMEOUT, 500);

				if (is_numeric($pageWiseData->getCurrentPage())) {
					$currentPage = (int)$pageWiseData->getCurrentPage();
				}

				if ($currentPage >= 1 && $currentPage <= $totalPages) {
					$offset = ($currentPage - 1) * $rowsPerPage;

					$sql = "SELECT p.*,c.*,IFNULL(r.rating , 0) AS rating
								FROM productdetails p
								INNER JOIN category c ON c.categoryId = p.productCategory                          
								LEFT JOIN (SELECT r.productId, CAST(AVG(r.rating) AS DECIMAL(10,2)) as rating
											FROM productratingreview r
											GROUP BY r.productId) r
								ON (p.productId = r.productId)
								ORDER By p.productPostDate DESC LIMIT $offset, $rowsPerPage";

					$result = mysqli_query($this->con, $sql);
					$this->data = array();
					while ($rowdata = mysqli_fetch_assoc($result)) {
						$this->data[] = $rowdata;
					}
					return $this->data;
				}
			} catch (Exception $e) {
				echo 'SQL Exception: ' . $e->getMessage();
			}
			return $this->data = array();
		}
		
		public function showTopProductDetails($pageWiseData)
			{
				try {               
						$sql = "SELECT  p.*,c.*,IFNULL(r.rating , 0) AS rating
									FROM productdetails p
									INNER JOIN category c ON c.categoryId = p.productCategory                          
									LEFT JOIN (SELECT r.productId, CAST(AVG(r.rating) AS DECIMAL(10,2)) as rating
												FROM productratingreview r
												GROUP BY r.productId) r
									ON (p.productId = r.productId)
									ORDER By p.productPostDate DESC limit 10";

						$result = mysqli_query($this->con, $sql);
						$this->data = array();
						while ($rowdata = mysqli_fetch_assoc($result)) {
							$this->data[] = $rowdata;
						}
						return $this->data;
					
				} catch (Exception $e) {
					echo 'SQL Exception: ' . $e->getMessage();
				}
				return $this->data = array();
			}
			public function showProductWiseDetails($pageWiseData)
			{
				try {               

						$sql = "SELECT  p.*,c.*,IFNULL(r.rating , 0) AS rating
									FROM productdetails p
									INNER JOIN category c ON c.categoryId = p.productCategory                          
									LEFT JOIN (SELECT r.productId, CAST(AVG(r.rating) AS DECIMAL(10,2)) as rating
												FROM productratingreview r
												GROUP BY r.productId) r
									ON (p.productId = r.productId)
									WHERE p.productId='".$pageWiseData->getProductId()."'";

						$result = mysqli_query($this->con, $sql);
						$this->data = array();
						while ($rowdata = mysqli_fetch_assoc($result)) {
							$this->data[] = $rowdata;
						}
						return $this->data;
					
				} catch (Exception $e) {
					echo 'SQL Exception: ' . $e->getMessage();
				}
				return $this->data = array();
			}

    public function showCategoryWiseProductDetails($categoryWise)
    {
        try {
            $sql = "SELECT p.*,c.*,IFNULL(r.rating , 0) AS rating
                            FROM productdetails p
                            INNER JOIN category c ON c.categoryId = p.productCategory                          
                            LEFT JOIN (SELECT r.productId, CAST(AVG(r.rating) AS DECIMAL(10,2)) as rating
                                        FROM productratingreview r
                                        GROUP BY r.productId) r
                            ON (p.productId = r.productId)
                        ORDER By p.productPostDate DESC";
            $result = mysqli_query($this->con, $sql);
            $numOfRows = mysqli_num_rows($result);

            $rowsPerPage = 10;
            $totalPages = ceil($numOfRows / $rowsPerPage);

            $this->con->options(MYSQLI_OPT_CONNECT_TIMEOUT, 500);

            if (is_numeric($categoryWise->getCurrentPage())) {
                $currentPage = (int)$categoryWise->getCurrentPage();
            }

            if ($currentPage >= 1 && $currentPage <= $totalPages) {
                $offset = ($currentPage - 1) * $rowsPerPage;

                $sql = "SELECT p.*,c.*,IFNULL(r.rating , 0) AS rating
                            FROM productdetails p
                            INNER JOIN category c ON c.categoryId = p.productCategory                          
                            LEFT JOIN (SELECT r.productId, CAST(AVG(r.rating) AS DECIMAL(10,2)) as rating
                                        FROM productratingreview r
                                        GROUP BY r.productId) r
                            ON (p.productId = r.productId)                                                         
                            WHERE p.productCategory = '".$categoryWise->getCategory()."'
                            ORDER By p.productPostDate DESC LIMIT $offset, $rowsPerPage";

                $result = mysqli_query($this->con, $sql);
                $this->data = array();
                while ($rowdata = mysqli_fetch_assoc($result)) {
                    $this->data[] = $rowdata;
                }
                return $this->data;
            }
        } catch (Exception $e) {
            echo 'SQL Exception: ' . $e->getMessage();
        }
        return $this->data = array();
    }

    public function showProductImages($productImages)
    {
        try {
            $sql = "SELECT * FROM productimage WHERE productId = '".$productImages->getProductId()."'";

            $result = mysqli_query($this->con, $sql);
            $this->data = array();
            while ($rowdata = mysqli_fetch_assoc($result)) {
                $this->data[] = $rowdata;
            }
            return $this->data;
        } catch (Exception $e) {
            echo 'SQL Exception: ' . $e->getMessage();
        }
        return $this->data = array();
    }

    public function showUserWishList($wishList)
    {
        try {
            $sql = "SELECT * FROM wishlist WHERE userId	 = '".$wishList->getUserId()."'";

            $result = mysqli_query($this->con, $sql);
            $this->data = array();
            while ($rowdata = mysqli_fetch_assoc($result)) {
                $this->data[] = $rowdata;
            }
            return $this->data;
        } catch (Exception $e) {
            echo 'SQL Exception: ' . $e->getMessage();
        }
        return $this->data = array();
    }
    public function showProductRatingReviews($productRatingReviews)
    {
        try {
            $sql = "SELECT r.*, u.userFirstname,u.userLastName
                        FROM productratingreview r
                        INNER JOIN userdetails u ON r.userId = u.userId
                        WHERE productId = '".$productRatingReviews->getProductId()."'";

            $result = mysqli_query($this->con, $sql);
            $this->data = array();
            while ($rowdata = mysqli_fetch_assoc($result)) {
                $this->data[] = $rowdata;
            }
            return $this->data;
        } catch (Exception $e) {
            echo 'SQL Exception: ' . $e->getMessage();
        }
        return $this->data = array();
    }



//    public function showRefreshListDetail()
//    {
//        try {
//            $sql = "SELECT p.productId,p.productCategory,p.productPrice,p.productShortDescription,p.productLongDescription,p.color,p.size,i.productImageName,c.categoryId,c.categoryName,r.rating,r.review,u.userFirstname,u.userLastName
//                        FROM productdetails p
//                        INNER JOIN productimage i
//                        ON p.productId = i.productId
//                        INNER JOIN category c
//                        ON p.productCategory = c.categoryId
//                        INNER JOIN productratingreview r
//                        ON p.productId = r.productId
//                        INNER JOIN userdetails u
//                        ON r.userId = u.userId
//						ORDER BY p.productPostDate DESC";
//
//            $result = mysqli_query($this->con, $sql);
//            $this->data = array();
//            while ($rowdata = mysqli_fetch_assoc($result)) {
//                $this->data[] = $rowdata;
//            }
//        } catch (Exception $e) {
//            echo 'SQL Exception: ' . $e->getMessage();
//        }
//        return $this->data;
//    }

    public function showCategoriesList()
    {
        try {
            $sql = "SELECT * FROM category";
            $result = mysqli_query($this->con, $sql);
            $count = mysqli_num_rows($result);
            if ($count != 0) {
                $this->data = array();
                while ($rowdata = mysqli_fetch_assoc($result)) {
                    $this->data[] = $rowdata;
                }
            } else {
                $this->data = "CATEGORY_NOT_AVAILABLE";
            }

        } catch (Exception $e) {
            echo 'SQL Exception: ' . $e->getMessage();
        }
        return $this->data;
    }

    //to send notification for new product

    function fetchFirebaseTokenUsers($message)
    {
		
        $query = "SELECT tokenNo FROM devicedetails";
        $fcmRegIds = array();
        if ($query_run = mysqli_query($this->con, $query)) {
            while ($query_row = mysqli_fetch_assoc($query_run)) {
                //$fcmRegIds[] = $query_row['tokenNo'];
                array_push($fcmRegIds, $query_row['tokenNo']);
            }
        }
				
        // if ($deviceId != null) {
            // $query = "SELECT tokenNo FROM devicedetails WHERE device_id = '$deviceId'";
            // if ($query_run = mysqli_query($this->con, $query)) {
                // while ($query_row = mysqli_fetch_array($query_run)) {
                    // $fcmToken = $query_row['tokenNo'];
                    // if (($key = array_search($fcmToken, $fcmRegIds)) !== false) {
                        // unset($fcmRegIds[$key]);
                    // }
                // }
            // }
        // }

        define('GOOGLE_API_KEY', 'AIzaSyBDldgTNGEJczF9r89mYxNH3iQsFHXfeSU');

        if (isset($fcmRegIds)) {
            foreach ($fcmRegIds as $key => $token) {
                $pushStatus = $this->sendPushNotification($token, $message);
			
            }
        }
    }

    function sendPushNotification($registration_id, $message)
    {
        ignore_user_abort();
        ob_start();

        $url = 'https://fcm.googleapis.com/fcm/send';
				
        $fields = array(
            'to' => $registration_id,
            'data' => $message,
        );

        $headers = array(
            'Authorization:key='.$this->googleAPIKey,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        $result = curl_exec($ch);
        if ($result === false)
            die('Curl failed ' . curl_error());

        curl_close($ch);
        ob_flush();
    }
}

?>