<?php
require_once '../model/ProductDetails.php';
require_once '../model/UsersDetails.php';
require_once '../model/WishListDetails.php';
require_once '../model/CartListDetails.php';
require_once '../model/OrderDetails.php';
require_once '../model/OrderConfirmationEmail2.php';
require_once '../model/ProductRatingReviews.php';
require_once '../model/DeviceDetails.php';


function deliver_response($format, $api_response, $isSaveQuery)
{

    // Define HTTP responses
    $http_response_code = array(200 => 'OK', 400 => 'Bad Request', 401 => 'Unauthorized', 403 => 'Forbidden', 404 => 'Not Found');

    // Set HTTP Response
    header('HTTP/1.1 ' . $api_response['status'] . ' ' . $http_response_code[$api_response['status']]);

    // Process different content types
    if (strcasecmp($format, 'json') == 0) {

        ignore_user_abort();
        ob_start();

        // Set HTTP Response Content Type
        header('Content-Type: application/json; charset=utf-8');

        // Format data into a JSON response
        $json_response = json_encode($api_response);

        // Deliver formatted data
        echo $json_response;

        ob_flush();

    } elseif (strcasecmp($format, 'xml') == 0) {

        // Set HTTP Response Content Type
        header('Content-Type: application/xml; charset=utf-8');

        // Format data into an XML response (This is only good at handling string data, not arrays)
        $xml_response = '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<response>' . "\n" . "\t" . '<code>' . $api_response['code'] . '</code>' . "\n" . "\t" . '<data>' . $api_response['data'] . '</data>' . "\n" . '</response>';

        // Deliver formatted data
        echo $xml_response;

    } else {

        // Set HTTP Response Content Type (This is only good at handling string data, not arrays)
        header('Content-Type: text/html; charset=utf-8');

        // Deliver formatted data
        echo $api_response['data'];

    }

    // End script process
    exit;
}

// Define whether an HTTPS connection is required
$HTTPS_required = FALSE;

// Define whether user authentication is required
$authentication_required = FALSE;

// Define API response codes and their related HTTP response
$api_response_code = array(0 => array('HTTP Response' => 400, 'Message' => 'Unknown Error'), 1 => array('HTTP Response' => 200, 'Message' => 'Success'), 2 => array('HTTP Response' => 403, 'Message' => 'HTTPS Required'), 3 => array('HTTP Response' => 401, 'Message' => 'Authentication Required'), 4 => array('HTTP Response' => 401, 'Message' => 'Authentication Failed'), 5 => array('HTTP Response' => 404, 'Message' => 'Invalid Request'), 6 => array('HTTP Response' => 400, 'Message' => 'Invalid Response Format'));

// Set default HTTP response of 'ok'
$response['code'] = 0;
$response['status'] = 404;

// --- Step 2: Authorization

// Optionally require connections to be made via HTTPS
if ($HTTPS_required && $_SERVER['HTTPS'] != 'on') {
    $response['code'] = 2;
    $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
    $response['data'] = $api_response_code[$response['code']]['Message'];

    // Return Response to browser. This will exit the script.
    deliver_response("json", $response);
}

// Optionally require user authentication
if ($authentication_required) {

    if (empty($_POST['username']) || empty($_POST['password'])) {
        $response['code'] = 3;
        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
        $response['data'] = $api_response_code[$response['code']]['Message'];

        // Return Response to browser
        deliver_response("json", $response);

    }

    // Return an error response if user fails authentication. This is a very simplistic example
    // that should be modified for security in a production environment
    elseif ($_POST['username'] != 'foo' && $_POST['password'] != 'bar') {
        $response['code'] = 4;
        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
        $response['data'] = $api_response_code[$response['code']]['Message'];

        // Return Response to browser
        deliver_response("json", $response);
    }
}

// --- Step 3: Process Request

// Switch based on incoming method
$checkmethod = $_SERVER['REQUEST_METHOD'];
$var = file_get_contents("php://input");
$string = json_decode($var, TRUE);
$method = $string['method'];

if (isset($_POST['method']) || $checkmethod == 'POST') {

    if (strcasecmp($method, 'Add_Device_Details') == 0) {
        $response['code'] = 1;
        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
        $objModel = new DeviceDetails();
        $deviceId = $string['deviceId'];
        $tokenNo = $string['tokenNo'];
        $userId = $string['userId'];
        $deviceName = $string['deviceName'];
		date_default_timezone_set('Asia/Kolkata');
        $entryDate = date("Y-m-d H:i:s");
        $response['AddDeviceDetailsResponse'] = $objModel->AddingDeviceDetails($deviceId, $tokenNo,$userId,$deviceName,$entryDate);
        deliver_response("json", $response, false);
    }
    else if (strcasecmp($method, 'userRegistration') == 0) {
        $response['code'] = 1;
        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
        $objModel = new UsersDetails();
        $firstName = $string['firstName'];
        $lastName = $string['lastName'];
        $mobileno = $string['mobileNo'];
        $email = $string['email'];
        $password = $string['confirmPassword'];

        date_default_timezone_set('Asia/Kolkata');
        $userEntryDate = date("Y-m-d H:i:s");

        $objModel->mapIncomingUserDetailsParams($firstName, $lastName, $mobileno, $email, $password, $userEntryDate);
        $response['userRegistrationResponse'] = $objModel->SavingUsersDetails();
        //deliver_response($format[1],$response,false);
        deliver_response("json", $response, false);
    }

    else if (strcasecmp($method, 'AddAddressDetails') == 0) {
        $response['code'] = 1;
        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
        $objModel = new UsersDetails();
        $addressLine1 = $string['addressLine1'];
        $addressLine2 = $string['addressLine2'];
        $city = $string['city'];
        $state = $string['state'];
        $country = $string['country'];
        $pinCode = $string['pinCode'];
        $userId = $string['userId'];

        $objModel->mapIncomingAddressDetailsParams($userId,$addressLine1, $addressLine2, $city, $state, $country, $pinCode);
        $response['AddAddressDetailsResponse'] = $objModel->SavingAddress();
        deliver_response("json", $response, true);
    }
    else if (strcasecmp($method, 'UpdateAddressDetails') == 0) {
        $response['code'] = 1;
        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
        $objModel = new UsersDetails();
        $addressLine1 = $string['addressLine1'];
        $addressLine2 = $string['addressLine2'];
        $city = $string['city'];
        $state = $string['state'];
        $country = $string['country'];
        $pinCode = $string['pinCode'];
        $userId = $string['userId'];
        $addressId = $string['addressId'];

        $objModel->mapIncomingUpdateAddressDetailsParams($addressId,$userId,$addressLine1, $addressLine2, $city, $state, $country, $pinCode);
        $response['UpdateAddressDetailsResponse'] = $objModel->updatingAddress();
        deliver_response("json", $response, true);
    }
    //{"method":"fetchUserDetails","userId":"3","email":"gawas.prarthana@gmail.com","confirmPassword":"123","mobileNo":"9594017823"}
    else if (strcasecmp($method, 'fetchUserDetails') == 0) {
        $response['code'] = 1;
        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
        $objModel = new UsersDetails();
        $userId = $string['userId'];
        $email = $string['email'];
        $mobileNo = $string['mobileNo'];
        $password = $string['confirmPassword'];
        $response['fetchUserDetailsResponse'] = $objModel->FetchingUsersDetails($userId, $email, $mobileNo, $password);
        deliver_response("json", $response, false);
    }
    //{"method":"userLogin","userLoginId":"gawas.prarthana@gmail.com","confirmPassword":"123"}
    else if (strcasecmp($method, 'userLogin') == 0) {
        $response['code'] = 1;
        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
        $objModel = new UsersDetails();
        $userLoginId = $string['userLoginId'];
        $password = $string['confirmPassword'];
        $response['userLoginResponse'] = $objModel->CheckingLoginDetails($userLoginId, $password);
        deliver_response("json", $response, false);
    }
	//{"method":"checkEmail","userId":"1","email":"gawas.prarthana@gmail.com"}
    else if (strcasecmp($method, 'checkEmail') == 0) {
        $response['code'] = 1;
        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
        $objModel = new UsersDetails();
        $email = $string['email'];
        $userId = $string['userId'];
        $response['checkEmailResponse'] = $objModel->CheckingEmail($email, $userId);
        deliver_response("json", $response, false);
    }
	
	 //{"method":"setNewPassword","userId":"1","password":"111","OTP":"321"}
    else if (strcasecmp($method, 'setNewPassword') == 0) {
        $response['code'] = 1;
        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
        $objModel = new UsersDetails();
        $userId = $string['userId'];
		$otp = $string['OTP'];       
        $newPassword = $string['password'];
        $response['setNewPasswordResponse'] = $objModel->SettingNewPassword($userId, $otp, $newPassword);
        deliver_response("json", $response, false);
    }
	
    //{"method":"checkPassword","userId":"3","confirmPassword":"123"}
    else if (strcasecmp($method, 'checkPassword') == 0) {
        $response['code'] = 1;
        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
        $objModel = new UsersDetails();
        $userId = $string['userId'];
        $password = $string['confirmPassword'];
        $response['checkPasswordResponse'] = $objModel->PasswordChecking($userId, $password);
        deliver_response("json", $response, false);
    }
   
    else if (strcasecmp($method, 'getVerificationCode') == 0) {
        $response['code'] = 1;
        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
        $objModel = new UsersDetails();
        $email = $string['email'];
        $userId = $string['userId'];
        $response['getVerificationCodeResponse'] = $objModel->GettingVerificationCode($email, $userId);
        deliver_response("json", $response, false);
    }

    else if (strcasecmp($method, 'changeUserName') == 0) {
        $response['code'] = 1;
        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
        $objModel = new UsersDetails();
        $firstName = $string['firstName'];
        $lastName = $string['lastName'];
        $userId = $string['userId'];
        $response['changeUserNameResponse'] = $objModel->ChangingName($firstName,$lastName, $userId);
        deliver_response("json", $response, false);
    }

//Product Rating Review
//{"method":"addProductRatingReview","userId":"3","rating":"2","review":"not a good product as i expected.","productId":"2"}
    else if (strcasecmp($method, 'addProductRatingReview') == 0) {
        $response['code'] = 1;
        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
        $objModel = new ProductRatingReviews();
        $ratings = $string['rating'];
        $review = $string['review'];
        $userId = $string['userId'];
        $productId = $string['productId'];
        date_default_timezone_set('Asia/Kolkata');
        $entryDate = date("Y-m-d H:i:s");

        $objModel->mapIncomingProductRatingReviews($ratings, $review, $userId, $productId, $entryDate);
        $response['addProductRatingReviewResponse'] = $objModel->addProductRatingsReviewsDetails();
        deliver_response("json", $response, false);
    }
   
    //{"method":"addCategory","categoryName":"abc"}
    else if (strcasecmp($method, 'addCategory') == 0) {
        $response['code'] = 1;
        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
        $objModel = new ProductDetails();
        $categoryName = $string['categoryName'];

//        $objModel->mapIncomingProductDetailsParams($categoryName);$objModel->mapIncomingProductDetailsParams($categoryName);
        $response['addCategoryResponse'] = $objModel->AddCategory($categoryName);
        deliver_response("json", $response, true);
    }
    //Order
    //{"method":"orderGeneration","userId":"1","productId":"7","productName":"abc","qty":"4","productPrice":"200","deliveryCharges":"100","totalPrice":"900","userName":"Pratik Sonawane","mobileNo":"8655883062","email":"sonawane.ptk@gmail.com","shipmentAddressId":"1","addressLine1":"d-11 narayan smruti","addressLine2":"gandhi nagar","state":"maharashtra","city":"dombivli","pinCode":"421203"}
    else if (strcasecmp($method, 'orderGeneration') == 0) {
        $response['code'] = 1;
        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
        $objModel = new OrderDetails();		
		
        $userId = $string['userId'];
        $userName = $string['userName'];
        $mobileno = $string['mobileNo'];
        $email = $string['email'];
        $shipmentAddressId = $string['shipmentAddressId'];
        $fullAddress = $string['fullAddress'];
		$razorpayPaymentID = $string['razorpayPaymentID'];
		$paymentType = $string['paymentType'];
		$totalPayableAmount = $string['totalPayableAmount'];
		$luckyDrawPrice = $string['luckyDrawPrice'];
		
		$cartIdArray = "";
		$productId = "";
		$productName = "";
		$productPrice = "";
		$qty = "";		
		$deliveryCharges = "";
		$totalPriceWithDelCharges = "";
		
		if(!empty($string['cartIdArray'])) {
            $cartIdArray = $string['cartIdArray'];
        }
		if(!empty($string['productId'])) {
            $productId = $string['productId'];
        }
		if(!empty($string['productName'])) {
            $productName = $string['productName'];
        }
		if(!empty($string['productPrice'])) {
            $productPrice = $string['productPrice'];
        }		
		if(!empty($string['qty'])) {
            $qty = $string['qty'];
        }
		if(!empty($string['deliveryCharges'])) {
            $deliveryCharges = $string['deliveryCharges'];
        }		      
		if(!empty($string['totalPriceWithDelCharges'])) {
            $totalPriceWithDelCharges = $string['totalPriceWithDelCharges'];
        }

        date_default_timezone_set('Asia/Kolkata');
        $postDate = date("Y-m-d H:i:s");
        $objModel->mapIncomingOrderDetailsParams($cartIdArray,$productId,$razorpayPaymentID,$paymentType, $productName, $qty, $productPrice, $deliveryCharges, $totalPriceWithDelCharges,$totalPayableAmount, $luckyDrawPrice,$userId, $userName, $mobileno, $email, $shipmentAddressId, $fullAddress,$postDate);
        $response['orderGenerationResponse'] = $objModel->SavingOrderDetails();
        //deliver_response($format[1],$response,false);
        deliver_response("json", $response, false);
    }
    else if (strcasecmp($method, 'sendOrderConfirmationEmail') == 0) {
        $response['code'] = 1;
        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
        $objOrderEmailDetails = new OrderConfirmationEmail();

        $orderedId = $string['orderedId'];
        $productId = $string['productId'];
        $productName = $string['productName'];
        $qty = $string['qty'];
        $productPrice = $string['productPrice'];
        $deliveryCharges = $string['deliveryCharges'];
        $totalPrice = $string['totalPrice'];

        $userId = $string['userId'];
        $userName = $string['userName'];
        $mobileno = $string['mobileNo'];
        $email = $string['email'];

        $fullAddress = $string['fullAddress'];
        $postDate = $string['orderGenerationDate'];

        $response['sendOrderConfirmationEmailResponse'] = $objOrderEmailDetails->GenerateEmailForBuyerAndSeller($orderedId, $productId, $productName, $qty, $productPrice, $deliveryCharges, $totalPrice, $userId, $userName, $mobileno, $email, $fullAddress, $postDate);
        //deliver_response($format[1],$response,false);
        deliver_response("json", $response, false);
    }
	
	//{"method":"addProductDetails","userId":"1","productName":"abc","categoryId":"4","productPrice":"200","productShortDescription":"vasxga hcaghx","productLongDescription":"ghjga bwif jvfue jvfef vef jvueyvfej vfyeuwf fvewuff jfvuwf","size":"Large","color":"Magenda"}
    else if (strcasecmp($_POST['method'], 'addProductDetails') == 0) {
        $response['code'] = 1;
        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
        $objModel = new ProductDetails();
        $productName = $_POST['productName'];
        $category = $_POST['categoryId'];
        $productPrice = $_POST['productPrice'];
        $productShortDescription = $_POST['shortDescription'];
        $productLongDescription = $_POST['longDescription'];
        $size = $_POST['size'];
        $color = $_POST['color'];
        $userId = $_POST['userId'];
        date_default_timezone_set('Asia/Kolkata');
        $post_date = date("Y-m-d H:i:s");
		
		$first_image_tmp = "";
        $first_image_target_path = "";
        $second_image_tmp = "";
        $second_image_target_path = "";
        $third_image_tmp = "";
		$third_image_target_path = "";       
		$fourth_image_tmp = "";
		$fourth_image_target_path = "";
		
		if(isset($_FILES['imgFile1'])){
            $first_image_tmp = $_FILES['imgFile1']['tmp_name'];
            $first_image_name = $_FILES['imgFile1']['name'];
            $first_image_target_path = "../product_images/".basename($first_image_name);
        }
        if(isset($_FILES['imgFile2'])){
            $second_image_tmp = $_FILES['imgFile2']['tmp_name'];
            $second_image_name = $_FILES['imgFile2']['name'];
            $second_image_target_path = "../product_images/".basename($second_image_name);
        }
        if(isset($_FILES['imgFile3'])){
            $third_image_tmp = $_FILES['imgFile3']['tmp_name'];
            $third_image_name = $_FILES['imgFile3']['name'];
            $third_image_target_path = "../product_images/".basename($third_image_name);
        }
		if(isset($_FILES['imgFile4'])){
            $fourth_image_tmp = $_FILES['imgFile4']['tmp_name'];
            $fourth_image_name = $_FILES['imgFile4']['name'];
            $fourth_image_target_path = "../product_images/".basename($fourth_image_name);
        }

        $objModel->mapIncomingProductDetailsParams($first_image_tmp, $first_image_target_path, $second_image_tmp, $second_image_target_path, $third_image_tmp, $third_image_target_path,$fourth_image_tmp, $fourth_image_target_path,$productName, $category, $productPrice, $productShortDescription, $productLongDescription, $size, $color, $post_date, $userId);
        $response['addProductDetailsResponse'] = $objModel->AddProductDetails();
        deliver_response("json", $response, true);
    }

} else {

    //http://localhost/php\api\vbags_api.php?method=productList&currentPage=1&XDEBUG_SESSION_START=PHPSTORM
// Product Categories
    //http://localhost/VBags\php\api\vbags_api.php?method=showCategoryList
    if (strcasecmp($_GET['method'], 'AddCategory') == 0) {
        $response['code'] = 1;
        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
        $objModel = new ProductDetails();
		$categoryName = $_GET['categoryName'];
        $response['showCategoriesResponse'] = $objModel->AddingCategory($categoryName);
        deliver_response("json", $response, false);
    }
	else if (strcasecmp($_GET['method'], 'showCategoryList') == 0) {
        $response['code'] = 1;
        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
        $objModel = new ProductDetails();
        $response['showCategoriesResponse'] = $objModel->showingCategories();
        deliver_response("json", $response, false);
    }

    //http://localhost/VBags\php\api\vbags_api.php?method=getAddressDetails&userId=1
    else if(strcasecmp($_GET['method'],'getAddressDetails') == 0){
        $response['code'] = 1;
        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
        $objModel = new UsersDetails();
        $userId = $_GET['userId'];
        $response['getAddressDetailsResponse'] = $objModel->showingAddressDetails($userId);
        deliver_response("json", $response, false);
    }

    //category Wise product list
    //http://localhost/VBags\php\api\vbags_api.php?method=categoryWiseProductList&categoryId=1&currentPage=1
    else if (strcasecmp($_GET['method'], 'categoryWiseProductList') == 0) {
        $response['code'] = 1;
        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
        $objModel = new ProductDetails();
        $category = $_GET['categoryId'];
        $currentPage = $_GET['currentPage'];
        $userId = $_GET['userId'];
        if($currentPage == 1 && $userId != ""){
            $response['userWishListResponse'] = $objModel->userWishList($userId);
        }
        $response['categoryWiseProductListResponse'] = $objModel->categoryWiseProductList($category, $currentPage);

        deliver_response("json", $response, false);
    }
//product list
    //http://localhost/VBags\php\api\vbags_api.php?method=productList&currentPage=1
    else if (strcasecmp($_GET['method'], 'productList') == 0) {
        $response['code'] = 1;
        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
        $objModel = new ProductDetails();
        $currentPage = $_GET['currentPage'];
        $userId = $_GET['userId'];
        if($currentPage == 1 && $userId != ""){
            $response['userWishListResponse'] = $objModel->userWishList($userId);
        }
        $response['productListResponse'] = $objModel->productList($currentPage);
        deliver_response("json", $response, false);
    }
	else if (strcasecmp($_GET['method'], 'TopProductList') == 0) {
        $response['code'] = 1;
        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
        $objModel = new ProductDetails();               
        $response['productListResponse'] = $objModel->topProductList();
        deliver_response("json", $response, false);
    }
	else if (strcasecmp($_GET['method'], 'ProductWiseDetails') == 0) {
        $response['code'] = 1;
        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
        $objModel = new ProductDetails();
		$productId = $_GET['productId'];		
        $response['productListResponse'] = $objModel->ProductWiseDetails($productId);
        deliver_response("json", $response, false);
    }
    //http://localhost/VBags\php\api\vbags_api.php?method=productWiseDetails&productId=1
    else if (strcasecmp($_GET['method'], 'productWiseDetails') == 0) {
        $response['code'] = 1;
        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
        $objModel = new ProductDetails();
        $productId = $_GET['productId'];
        $response['productImagesResponse'] = $objModel->productImage($productId);
        $response['productRatingReviewsResponse'] = $objModel->productRatingReviews($productId);
        deliver_response("json", $response, false);
    }

// rating review
//    else if (strcasecmp($_GET['method'], 'showProductReviews') == 0) {
//        $response['code'] = 1;
//        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
//        $objModel = new ProductRatingReviews();
//        $productId = $_GET['productId'];
//        $currentPage = $_GET['currentPage'];
//        $response['showProductReviewsResponse'] = $objModel->showingProductReviews($currentPage, $productId);
//        deliver_response("json", $response, false);
//    }
    else if (strcasecmp($_GET['method'], 'deleteProductRatingReview') == 0) {
        $response['code'] = 1;
        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
        $objModel = new ProductRatingReviews();
        $productId = $_GET['productId'];
        $userId = $_GET['userId'];
        $response['deleteReviewResponse'] = $objModel->deletingReview($productId, $userId);
        deliver_response("json", $response, false);
    }

//Wish list
    //http://localhost/VBags\php\api\vbags_api.php?method=addWishList&userId=1&productId=1
    else if (strcasecmp($_GET['method'], 'addWishList') == 0) {
        $response['code'] = 1;
        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
        $objModel = new WishListDetails();
        $userId = $_GET['userId'];
        $productId = $_GET['productId'];
        $response['addWishListResponse'] = $objModel->addingWishList($userId, $productId);
        deliver_response("json", $response, false);
    }
    //http://localhost/VBags\php\api\vbags_api.php?method=showWishList&userId=1&currentPage=1
    else if (strcasecmp($_GET['method'], 'showWishList') == 0) {
        $response['code'] = 1;
        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
        $objModel = new WishListDetails();
        $userId = $_GET['userId'];
        $currentPage = $_GET['currentPage'];
        $response['showWishListResponse'] = $objModel->showingWishList($userId, $currentPage);
        deliver_response("json", $response, false);
    }
    else if (strcasecmp($_GET['method'], 'deleteWishList') == 0) {
        $response['code'] = 1;
        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
        $objModel = new WishListDetails();       
        $userId = $_GET['userId'];
        $productId = $_GET['productId'];
        $response['deleteWishListResponse'] = $objModel->deletingWishList($userId, $productId);
        deliver_response("json", $response, false);
    }
// Cart
    //http://localhost/VBags\php\api\vbags_api.php?method=addToCart&productId=1&qty=2&productPrice=450&deliveryCharges=100&totalPrice=1000&userId=2
    else if (strcasecmp($_GET['method'], 'addToCart') == 0) {
        $response['code'] = 1;
        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
        $objModel = new CartListDetails();
        $userId = $_GET['userId'];
        $productId = $_GET['productId'];
        $qty = $_GET['qty'];
        $productPrice = $_GET['productPrice'];
        $deliveryCharges = $_GET['deliveryCharges'];
        $totalPrice = $_GET['totalPrice'];
        $response['addToCartResponse'] = $objModel->addingCartList($userId, $productId, $qty, $productPrice, $deliveryCharges, $totalPrice);
        deliver_response("json", $response, false);
    }
    // update cart
    //http://localhost/VBags\php\api\vbags_api.php?method=updateToCart&productId=1&qty=2&productPrice=450&totalPrice=1000&userId=2
    else if (strcasecmp($_GET['method'], 'updateToCart') == 0) {
        $response['code'] = 1;
        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
        $objModel = new CartListDetails();
        $userId = $_GET['userId'];
        $productId = $_GET['productId'];
        $qty = $_GET['qty'];
        $totalPrice = $_GET['totalPrice'];
        $cartId = $_GET['cartId'];

        $response['updateToCartResponse'] = $objModel->updatingCartList($userId, $productId, $qty, $cartId, $totalPrice);
        deliver_response("json", $response, false);
    }
  //  http://localhost/VBags\php\api\vbags_api.php?method=showUserCartList&currentPage=1&userId=1
    else if (strcasecmp($_GET['method'], 'showUserCartList') == 0) {
        $response['code'] = 1;
        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
        $objModel = new CartListDetails();
        $userId = $_GET['userId'];
        $currentPage = $_GET['currentPage'];
        if($currentPage == 1 && $userId != ""){
            $objModel2 = new ProductDetails();
            $response['userWishList'] = $objModel2->userWishList($userId);
        }
        $response['showUserCartListResponse'] = $objModel->showingCartList($userId, $currentPage);
        deliver_response("json", $response, false);
    }
    //http://localhost/VBags\php\api\vbags_api.php?method=deleteFromCart&productId=1&userId=1
    else if (strcasecmp($_GET['method'], 'deleteFromCart') == 0) {
        $response['code'] = 1;
        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
        $objModel = new CartListDetails();
        $productId = $_GET['productId'];
        $userId = $_GET['userId'];
        $response['deleteCartListResponse'] = $objModel->deletingCartList($userId, $productId);
        deliver_response("json", $response, false);
    }
    //http://localhost/VBags\php\api\vbags_api.php?method=showUserOrders&currentPage=1&userId=1
    else if (strcasecmp($_GET['method'], 'showUserAllOrders') == 0) {
        $response['code'] = 1;
        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
        $objModel = new OrderDetails();
        $userId = $_GET['userId'];
        $currentPage = $_GET['currentPage'];
        $response['showUserOrdersResponse'] = $objModel->showingAllOrderedList($userId, $currentPage);
        deliver_response("json", $response, false);
    }
	else if (strcasecmp($_GET['method'], 'showUserOrders') == 0) {
        $response['code'] = 1;
        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
        $objModel = new OrderDetails();
        $userId = $_GET['userId'];
        $currentPage = $_GET['currentPage'];	
        $response['showUserOrdersResponse'] = $objModel->showingOrderedList($userId, $currentPage);
        deliver_response("json", $response, false);
    }
	else if (strcasecmp($_GET['method'], 'showOrdersWiseProductList') == 0) {
        $response['code'] = 1;
        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
        $objModel = new OrderDetails();
        $userId = $_GET['userId'];
        $orderBulkId = $_GET['orderBulkId'];	
        $response['showUserOrdersResponse'] = $objModel->showingOrderWiseProductList($userId, $orderBulkId);
        deliver_response("json", $response, false);
    }
	
	else if (strcasecmp($_GET['method'], 'showGeneratedOrders') == 0) {
        $response['code'] = 1;
        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
        $objModel = new OrderDetails();       
        $currentPage = $_GET['currentPage'];	
        $response['showUserOrdersResponse'] = $objModel->showingGeneratedOrderedList($currentPage);
        deliver_response("json", $response, false);
    }
	
	else if (strcasecmp($_GET['method'], 'showGeneratedOrdersWiseProductList') == 0) {
        $response['code'] = 1;
        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
        $objModel = new OrderDetails();
        $orderBulkId = $_GET['orderBulkId'];	
        $response['showUserOrdersResponse'] = $objModel->showingGeneratedOrderWiseProductList($orderBulkId);
        deliver_response("json", $response, false);
    }
	//http://localhost/VBagsAPI/php/api/vbags_api.php?method=OrderStatusList
	else if (strcasecmp($_GET['method'], 'OrderStatusList') == 0) {
        $response['code'] = 1;
        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
        $objModel = new OrderDetails();
        $response['OrderStatusResponse'] = $objModel->GettingOrderStatusList();
        deliver_response("json", $response, false);
    }
	
    //http://localhost\VBags\php\api\vbags_api.php?method=cancelOrderList&orderId=5&userId=2
    else if (strcasecmp($_GET['method'], 'UpdateOrderStatus') == 0) {
        $response['code'] = 1;
        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
        $objModel = new OrderDetails();
        $orderId = $_GET['orderId'];
        $orderBulkId = $_GET['orderBulkId'];       
        $orderStatusId = $_GET['orderStatusId'];
        $response['cancelOrderResponse'] = $objModel->UpdatingOrderStatus($orderId,$orderBulkId, $orderStatusId);
        deliver_response("json", $response, false);
    }	
	
}
?>
