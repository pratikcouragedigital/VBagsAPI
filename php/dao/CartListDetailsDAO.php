<?php
require_once 'BaseDAO.php';

class CartListDetailsDAO
{
    private $con;
    private $msg;
    private $data;

    // Attempts to initialize the database connection using the supplied info.
    public function CartListDetailsDAO()
    {
        $baseDAO = new BaseDAO();
        $this->con = $baseDAO->getConnection();
    }

    public function updateCartList($cartList_details)
    {
        try {
//            $sql = "INSERT INTO cart(userId,productId,qty,productPrice,deliveryCharges,totalPrice)
//                        VALUES ('" . $cartList_details->getUserId() . "', '" . $cartList_details->getProductId() . "', '" . $cartList_details->getQty() . "', '" . $cartList_details->getProductPrice() . "', '" . $cartList_details->getDeliveryCharges() . "', '" . $cartList_details->getTotalPrice() . "')";

            $sql = "UPDATE cart
                        SET 
                        qty = '".$cartList_details->getQty()."',
                        totalPrice = '".$cartList_details->getTotalPrice()."'                        
                        WHERE cartId = '".$cartList_details->getCartListId()."' ";
                        // AND productId = '".$cartList_details->getProductId()."'
                        // AND cartId = '".$cartList_details->getCartListId()."'  ";

            $isInserted = mysqli_query($this->con, $sql);
            if ($isInserted) {
                $this->data = "LIST_UPDATED_SUCCESSFULLY";
            } else {
                $this->data = "ERROR";
            }
        } catch (Exception $e) {
            echo 'SQL Exception: ' . $e->getMessage();
        }
        return $this->data;
    }

    public function addCartList($cartList_details)
    {
        try {
            $sql = "INSERT INTO cart(userId,productId,qty,productPrice,deliveryCharges,totalPrice)
                        VALUES ('".$cartList_details->getUserId()."', '".$cartList_details->getProductId() . "', '" . $cartList_details->getQty() . "', '" . $cartList_details->getProductPrice() . "', '" . $cartList_details->getDeliveryCharges() . "', '" . $cartList_details->getTotalPrice() . "')";

            $isInserted = mysqli_query($this->con, $sql);
            if ($isInserted) {
                $this->data = "LIST_ADDED_SUCCESSFULLY";
            } else {
                $this->data = "ERROR";
            }
        } catch (Exception $e) {
            echo 'SQL Exception: ' . $e->getMessage();
        }
        return $this->data;
    }

    public function showCartList($cartList_details)
    {
        $sql = "SELECT u.userId,u.userFirstname,u.userLastName,ct.*,p.*,c.*,IFNULL(r.rating , 0) AS rating
                            FROM productdetails p
                            INNER JOIN category c ON c.categoryId = p.productCategory                         
                            LEFT JOIN (SELECT r.productId, CAST(AVG(r.rating) AS DECIMAL(10,2)) as rating
                            FROM productratingreview r
                            GROUP BY r.productId) r ON (p.productId = r.productId)                             
                            
                            INNER JOIN cart ct ON ct.productId = p.productId                                              
                            INNER JOIN userdetails u ON u.userId = ct.userId
                           WHERE ct.userId = '".$cartList_details->getUserId()."'";

        try {
            $result = mysqli_query($this->con, $sql);
            $numOfRows = mysqli_num_rows($result);

            $rowsPerPage = 10;
            $totalPages = ceil($numOfRows / $rowsPerPage);

            $this->con->options(MYSQLI_OPT_CONNECT_TIMEOUT, 500);

            if (is_numeric($cartList_details->getCurrentPage())) {
                $currentPage = (int)$cartList_details->getCurrentPage();
            }
            if ($currentPage >= 1 && $currentPage <= $totalPages) {
                $offset = ($currentPage - 1) * $rowsPerPage;

                $sql = "SELECT u.userId, u.userFirstname,u.userLastName,ct.*,p.*,c.*,IFNULL(r.rating , 0) AS rating
                            FROM productdetails p
                            INNER JOIN category c ON c.categoryId = p.productCategory                         
                            LEFT JOIN (SELECT r.productId, CAST(AVG(r.rating) AS DECIMAL(10,2)) as rating
                            FROM productratingreview r
                            GROUP BY r.productId) r ON (p.productId = r.productId)                                                         
                            INNER JOIN cart ct ON ct.productId = p.productId                                              
                            INNER JOIN userdetails u ON u.userId = ct.userId
                            WHERE ct.userId = '".$cartList_details->getUserId()."'
                            LIMIT $offset, $rowsPerPage";

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

    public function deleteCartList($cartList_details)
    {
        try {
            $sql = "DELETE FROM cart WHERE productId='" . $cartList_details->getProductId() . "' AND userId='" . $cartList_details->getUserId() . "' ";
            $isDeleted = mysqli_query($this->con, $sql);
            if ($isDeleted) {
                $this->data = "Deleted_From_Cart";
            } else {
                $this->data = "ERROR";
            }

        } catch (Exception $e) {
            echo 'SQL Exception: ' . $e->getMessage();
        }
        return $this->data;
    }


}

?>