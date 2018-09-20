<?php
require_once 'BaseDAO.php';

class ProductRatingReviewsDAO
{
    private $con;
    private $msg;
    private $data;

    // Attempts to initialize the database connection using the supplied info.
    public function ProductRatingReviewsDAO()
    {
        $baseDAO = new BaseDAO();
        $this->con = $baseDAO->getConnection();
    }

    public function addRatingNReview($productratingreview)
    {
        try {
            $sql = "INSERT INTO productratingreview(userId,productId,rating,review,entryDate)
                        VALUES ('" . $productratingreview->getUserId() . "', '" . $productratingreview->getProductId() . "', '" . $productratingreview->getRatings() . "', '" . $productratingreview->getReview() . "', '" . $productratingreview->getEntryDate() . "')";

            $isInserted = mysqli_query($this->con, $sql);
            if ($isInserted) {
                $this->data = "RATING_REVIEW_ADDED_SUCCESSFULLY";
            } else {
                $this->data = "ERROR";
            }
        } catch (Exception $e) {
            echo 'SQL Exception: ' . $e->getMessage();
        }
        return $this->data;
    }

    public function showRatingReviewList($productratingreview)
    {
        try {
            $sql = "SELECT * FROM productratingreview WHERE productId= '" . $productratingreview->getProductId() . "'";

            $isValidating = mysqli_query($this->con, $sql);
            $count = mysqli_num_rows($isValidating);
            if ($count >= 1) {
                $this->data = array();
                while ($rowdata = mysqli_fetch_assoc($isValidating)) {
                    $this->data[] = $rowdata;
                }
            } else {
                $this->data = "Review_Not_Available.";
            }
        } catch (Exception $e) {
            echo 'SQL Exception: ' . $e->getMessage();
        }
        return $this->data;
    }

    public function deleteReview($productratingreview)
    {
        try {
            $sql = "DELETE FROM productratingreview WHERE productId='" . $productratingreview->getProductId() . "' AND userId='" . $productratingreview->getUserId() . "' ";
            $isDeleted = mysqli_query($this->con, $sql);
            if ($isDeleted) {
                $this->data = "RATING_REVIEW_DELETED_SUCCESSFULLY";
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