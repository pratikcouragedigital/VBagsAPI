<?php
require_once 'BaseDAO.php';
require_once '../model/OrderConfirmationEmail2.php';
class OrderDetailsDAO
{    
    private $con;
    private $msg;
    private $data;   
    // Attempts to initialize the database connection using the supplied info.
    public function OrderDetailsDAO() {
        $baseDAO = new BaseDAO();
        $this->con = $baseDAO->getConnection();
    }
    public function SaveOrderDetails($OrderDetail) {

		$cartIdArray = array();
		$productIds = array();
		$productNames = array();
		$productQtys = array();
		$productPrices = array();
		$productTotalPrices = array();
		$productDelCharges = array();
		
        $cartIdArray = json_decode($OrderDetail->getCartIdArray());	
        $productIds = json_decode($OrderDetail->getProductId());	
        $productNames = json_decode($OrderDetail->getProductName());	
        $productQtys = json_decode($OrderDetail->getQty());	
        $productPrices = json_decode($OrderDetail->getProductPrice());
        $productTotalPrices = json_decode($OrderDetail->getTotalPriceWithDelCharges());
        $productDelCharges = json_decode($OrderDetail->getDeliveryCharges());
		
		//print_r($productIds);
		$length = count($productIds);		
		
		$paymentType = $OrderDetail->getPaymentType();
		$userName = $OrderDetail->getUserName();
		$email = $OrderDetail->getEmail();
		$mobileno = $OrderDetail->getMobileno();
		$address = $OrderDetail->getfullAddress();
		
		$orderId="";
		$emailProductDetails = "\n";		
		$userPersonalDetails = "\n\n  Customer Name : $userName \n  Customer Email : $email \n  Customer Contact No : $mobileno \n  Address : $address \n";		
		
		for ($i = 0; $i < $length; $i++) {
		  $pId = $productIds[$i];
		  if(empty($cartIdArray)){
			 
		  }
		  else{
			$cId = $cartIdArray[$i];  
		  }		  
		  $pName = $productNames[$i];
		  $pQty = $productQtys[$i];
		  $pPrice = $productPrices[$i];
		  $pTotalPrice = $productTotalPrices[$i];
		  $pDeliveryChages = $productDelCharges[$i];
		  $orderDetails = array();
		  // print_r("cart id: ".$cId);		 		
		
		  $srNo = $i + 1;
		  $emailProductDetails = $emailProductDetails. " No. $srNo \n Product Name: $pName  \n Qty: $pQty \n Product Price: $pPrice Rs. \n Delivery Charge: $pDeliveryChages Rs. \n Total Price: $pTotalPrice Rs.  \n\n";
			try {			
						if($i == 0){				
							//print_r(" i: ".$i);
							$sql = "INSERT INTO orderdetails(productId,userId,qty,productPrice,orderDeliveryChages,priceWithDelCharges,luckyDrawPrice,totalPrice,shippingAddressId,entryDate)
								VALUES (
								'".$pId."',							
								'".$OrderDetail->getUserId()."',
								'".$pQty."',
								'".$pPrice."',
								'".$pDeliveryChages."',						
								'".$pTotalPrice."',						
								'".$OrderDetail->getLuckyDrawPrice()."',								
								'".$OrderDetail->getTotalPayableAmount()."',								
								'".$OrderDetail->getShipmentAddressId()."',
								'".$OrderDetail->getPostDate()."')";
				
							$isInserted = mysqli_query($this->con, $sql);
							if ($isInserted) {	
								$orderId = mysqli_insert_id($this->con);						
								// write update query for ordersKu = orderId
								$update = "UPDATE orderdetails 
											SET orderBulkId = '".$orderId."' 
											WHERE orderId = '".$orderId."'";
								$isUpdate = mysqli_query($this->con, $update);
								
								$sqlPayment = "INSERT INTO paymentdetails(orderBulkId,userId,paymentType,razorpayPaymentID,finalPaymentAmount,entryDate)
								VALUES (
								'".$orderId."',							
								'".$OrderDetail->getUserId()."',
								'".$OrderDetail->getPaymentType()."',
								'".$OrderDetail->getRazorpayPaymentID()."',
								'".$OrderDetail->getTotalPayableAmount()."',
								'".$OrderDetail->getPostDate()."')";
				
								$isPaymentInserted = mysqli_query($this->con, $sqlPayment);
								
								if(empty($cartIdArray)){
									
								}else{
									$cartDelete = "DELETE FROM cart WHERE cartId = '".$cId."'";
									$isDeleted = mysqli_query($this->con, $cartDelete);									
								}															
							}
							else{
								$this->data = "ERROR";
							}
						}
						else{
							//print_r(" i: ".$i);
							$sql = "INSERT INTO orderdetails(productId,orderBulkId,userId,qty,productPrice,orderDeliveryChages,priceWithDelCharges,luckyDrawPrice,totalPrice,shippingAddressId,entryDate)
								VALUES (
								'".$pId."',
								'".$orderId."',
								'".$OrderDetail->getUserId()."',
								'".$pQty."',
								'".$pPrice."',					  
								'".$pDeliveryChages."',						
								'".$pTotalPrice."',		
								'".$OrderDetail->getLuckyDrawPrice()."',
								'".$OrderDetail->getTotalPayableAmount()."',								
								'".$OrderDetail->getShipmentAddressId()."',
								'".$OrderDetail->getPostDate()."')";
				
							$isInserted = mysqli_query($this->con, $sql);	
							if ($isInserted) {									
								$this->data = "ORDER_GENERATED";	

								if(empty($cartIdArray)){
									
								}else{
									$cartDelete = "DELETE FROM cart WHERE cartId = '".$cId."'";
									$isDeleted = mysqli_query($this->con, $cartDelete);									
								}

								// send notification to seller new order genarated
								$this->msg =array(								
									'NOTIFICATION_PRODUCT_ID' => $productId,																		
									'NOTIFICATION_TITLE' => 'Order VBags',																		
									'NOTIFICATION_DESCRIPTION' => 'New Order Genarated',																		
								);
						
						$this->fetchFirebaseTokenUsers($this->msg);
							}
							else{
								$this->data = "ERROR";
							}						
						}																				
			}
			catch(Exception $e) {
				echo 'SQL Exception: ' .$e->getMessage();
			}
		}	
		
		// print_r($userPersonalDetails);
		// print_r($emailProductDetails);
		
		//configure send sms and uncomment next 4lines
		
		$postDate = $OrderDetail->getPostDate();		
		$luckyDrawAmount = $OrderDetail->getLuckyDrawPrice();
		$amountPayable = $OrderDetail->getTotalPayableAmount() ;
		$totalAmount = $luckyDrawAmount +  $amountPayable;
		$finalAmountDetails = "Amount Details: \n\n Total Price : $totalAmount Rs. \n You Won : $luckyDrawAmount Rs. \n Payable Amount : $amountPayable Rs. \n";
		
		$sendOrderConfirmationEmail = new OrderConfirmationEmail2();
		$this->data = $sendOrderConfirmationEmail -> GenerateEmailForBuyerAndSeller($userName,$email,$orderId,$userPersonalDetails,$emailProductDetails,$finalAmountDetails,$postDate);
					
        return $this->data = $orderId;
    }	
	
	public function showGeneratedOrderList($order) {
    
		$sql = "SELECT o.*, os.*,u.*,a.*
							FROM orderdetails o 
							INNER JOIN orderstatus os ON os.orderStatusId = o.orderStatusId 
							INNER JOIN userdetails u ON u.userId = o.userId 
							INNER JOIN addressdetails a ON a.addressId = o.shippingAddressId
                            WHERE o.orderStatusId != 5
							GROUP BY o.orderBulkId DESC";
							
				$result = mysqli_query($this->con, $sql);
                $count=mysqli_num_rows($result);
                
                if($count >  0) {                    
					$this->data=array();
					while ($rowdata = mysqli_fetch_assoc($result)) {
						$this->data[]=$rowdata;
					} 					
				} else {
					$this->data = "Order Not Available.";
				}
        return $this->data; 
    }
	
	public function showGeneratedOrderWiseProductList($order) {
        $sql = "SELECT u.*,o.*, os.*,a.*,p.*,c.*,IFNULL(r.rating , 0) AS rating
					FROM orderdetails o
					INNER JOIN userdetails u ON u.userId = o.userId 
					INNER JOIN addressdetails a ON a.addressId = o.shippingAddressId
					INNER JOIN orderstatus os ON os.orderStatusId = o.orderStatusId
					INNER JOIN productdetails p ON p.productId = o.productId 
					INNER JOIN category c ON c.categoryId = p.productCategory 
					LEFT JOIN (SELECT r.productId, CAST(AVG(r.rating) AS DECIMAL(10,2)) as rating FROM productratingreview r 
					GROUP BY r.productId) r ON (p.productId = r.productId) 				
					WHERE o.orderBulkId='".$order->getorderBulkId()."'";

        $result = mysqli_query($this->con, $sql);
                $count=mysqli_num_rows($result);
                
                if($count >  0) {                    
					$this->data=array();
					while ($rowdata = mysqli_fetch_assoc($result)) {
						$this->data[]=$rowdata;
					} 					
				} else {
					$this->data = "Order Not Available.";
				} 
        return $this->data; 
    }
	
	public function showOrderList($order) {
    
		$sql = "SELECT o.*,os.*,u.*,a.*
							FROM orderdetails o 
							INNER JOIN orderstatus os ON os.orderStatusId = o.orderStatusId 
							INNER JOIN userdetails u ON u.userId = o.userId 
							INNER JOIN addressdetails a ON a.addressId = o.shippingAddressId
							WHERE o.userId='".$order->getUserId()."' GROUP BY o.orderBulkId DESC";
							
							

                $result = mysqli_query($this->con, $sql);
                $count=mysqli_num_rows($result);
                
                if($count >  0) {                    
					$this->data=array();
					while ($rowdata = mysqli_fetch_assoc($result)) {
						$this->data[]=$rowdata;
					} 					
				} else {
					$this->data = "Order Not Available.";
				}
        return $this->data; 
    }
	public function showOrderWiseProductList($order) {
        $sql = "SELECT u.*,o.*, os.*,a.*,p.*,c.*,IFNULL(r.rating , 0) AS rating
					FROM orderdetails o
					INNER JOIN userdetails u ON u.userId = o.userId 
					INNER JOIN addressdetails a ON a.addressId = o.shippingAddressId
					INNER JOIN orderstatus os ON os.orderStatusId = o.orderStatusId
					INNER JOIN productdetails p ON p.productId = o.productId 
					INNER JOIN category c ON c.categoryId = p.productCategory 
					LEFT JOIN (SELECT r.productId, CAST(AVG(r.rating) AS DECIMAL(10,2)) as rating FROM productratingreview r 
					GROUP BY r.productId) r ON (p.productId = r.productId) 					
					WHERE o.userId='".$order->getUserId()."'  AND o.orderBulkId='".$order->getorderBulkId()."'";

        $result = mysqli_query($this->con, $sql);
                $count=mysqli_num_rows($result);
                
                if($count >  0) {                    
					$this->data=array();
					while ($rowdata = mysqli_fetch_assoc($result)) {
						$this->data[]=$rowdata;
					} 					
				} else {
					$this->data = "Order Not Available.";
				} 
        return $this->data; 
    }	
	
	public function showAllOrderList($order) {
		
        $sql = "SELECT u.*,o.*, os.*,a.*,p.*,c.*,IFNULL(r.rating , 0) AS rating
					FROM orderdetails o
					INNER JOIN userdetails u ON u.userId = o.userId 
					INNER JOIN addressdetails a ON a.addressId = o.shippingAddressId
					INNER JOIN orderstatus os ON os.orderStatusId = o.orderStatusId
					INNER JOIN productdetails p ON p.productId = o.productId 
					INNER JOIN category c ON c.categoryId = p.productCategory 
					LEFT JOIN (SELECT r.productId, CAST(AVG(r.rating) AS DECIMAL(10,2)) as rating FROM productratingreview r 
					GROUP BY r.productId) r ON (p.productId = r.productId) 				
					WHERE o.userId='".$order->getUserId()."'";

        try {
            $result = mysqli_query($this->con, $sql);
            $numOfRows = mysqli_num_rows($result);
            
            $rowsPerPage = 10;
            $totalPages = ceil($numOfRows / $rowsPerPage);
            
            $this->con->options(MYSQLI_OPT_CONNECT_TIMEOUT, 500);
            
            if (is_numeric($order->getCurrentPage())) {
                $currentPage = (int) $order->getCurrentPage();
            }            
            if ($currentPage >= 1 && $currentPage <= $totalPages) {
                $offset = ($currentPage - 1) * $rowsPerPage;
            
                $sql = "SELECT u*,o.*, os.*,a.*,p.*,c.*,IFNULL(r.rating , 0) AS rating
					FROM orderdetails o
					INNER JOIN userdetails u ON u.userId = o.userId 
					INNER JOIN addressdetails a ON a.addressId = o.shippingAddressId
					INNER JOIN orderstatus os ON os.orderStatusId = o.orderStatusId
					INNER JOIN productdetails p ON p.productId = o.productId 
					INNER JOIN category c ON c.categoryId = p.productCategory 
					LEFT JOIN (SELECT r.productId, CAST(AVG(r.rating) AS DECIMAL(10,2)) as rating FROM productratingreview r 
					GROUP BY r.productId) r ON (p.productId = r.productId) 					
					WHERE o.userId='".$order->getUserId()."' ORDER BY o.orderId DESC
						    LIMIT $offset, $rowsPerPage";

                $result = mysqli_query($this->con, $sql);
                $count=mysqli_num_rows($result);
                
                if($count >  0) {                    
					$this->data=array();
					while ($rowdata = mysqli_fetch_assoc($result)) {
						$this->data[]=$rowdata;
					} 					
				} else {
					$this->data = "Order Not Available.";
				} 
            }                      
        } catch(Exception $e) {
            echo 'SQL Exception: ' .$e->getMessage();
        }
        return $this->data; 
    }
	
	public function GetOrderStatusList($order) {
		 try {            
			
			$sql = "SELECT * FROM orderstatus";
			$result = mysqli_query($this->con, $sql);
            $count = mysqli_num_rows($result);
                
                if($count >  0) {                    
					$this->data=array();
					while ($rowdata = mysqli_fetch_assoc($result)) {
						$this->data[]=$rowdata;
					} 					
				} else {
					$this->data = "StatusList_Not_Available.";
				} 
			 
		} catch(Exception $e) {
            echo 'SQL Exception: ' .$e->getMessage();
        }
        return $this->data;
	}
	public function UpdateOrderStatus($order) {
		 try {            
			// $sql = "DELETE FROM orderdetails WHERE orderId='".$order->getOrderId()."' AND userId='".$order->getUserId()."' ";
			$sql = "UPDATE orderdetails 
					SET orderStatusId = '".$order->getOrderStatusId()."'
					WHERE orderBulkId = '".$order->getOrderBulkId()."'";
			$isDeleted = mysqli_query($this->con, $sql);
            if ($isDeleted) {
                $this->data = "Order_Status_Changed";
				
				$orderId = $order->getOrderStatusId();
				$this->msg =array(								
						'NOTIFICATION_ORDER_ID' => $orderId,																		
						'NOTIFICATION_TITLE' => 'Order Status',																		
						'NOTIFICATION_DESCRIPTION' => 'Your order status is changed.',																		
				);				
							
				$this->fetchFirebaseTokenUsersForUpdate($this->msg,$orderId);                
            } else {
                $this->data = "ERROR";
            }
			 
		} catch(Exception $e) {
            echo 'SQL Exception: ' .$e->getMessage();
        }
        return $this->data;
	}
	
	function fetchFirebaseTokenUsersForUpdate($message,$orderId)
    {	
        $query = "SELECT d.tokenNo
					FROM devicedetails d 
					INNER JOIN userdetails u ON u.userId = d.userId
					INNER JOIN orderdetails o ON o.userId = d.userId
					WHERE o.orderId='$orderId'";
        $fcmRegIds = array();
        if ($query_run = mysqli_query($this->con, $query)) {
            while ($query_row = mysqli_fetch_assoc($query_run)) {
                //$fcmRegIds[] = $query_row['tokenNo'];
                array_push($fcmRegIds, $query_row['tokenNo']);
            }
        }

        define('GOOGLE_API_KEY', 'AIzaSyBDldgTNGEJczF9r89mYxNH3iQsFHXfeSU');

        if (isset($fcmRegIds)) {
            foreach ($fcmRegIds as $key => $token) {
                $pushStatus = $this->sendPushNotification($token, $message);
			
            }
        }
    }
	
	
	 function fetchFirebaseTokenUsers($message)
    {	
        $query = "SELECT d.tokenNo
					FROM devicedetails d 
					INNER JOIN userdetails u ON u.userId = d.userId
					WHERE u.userType=Admin";
        $fcmRegIds = array();
        if ($query_run = mysqli_query($this->con, $query)) {
            while ($query_row = mysqli_fetch_assoc($query_run)) {
                //$fcmRegIds[] = $query_row['tokenNo'];
                array_push($fcmRegIds, $query_row['tokenNo']);
            }
        }

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