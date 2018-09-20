<?php
class BaseDAO {
    
    
   // private $db_host = 'shareddb-i.hosting.stackcp.net'; //hostname
    // private $db_user = 'v-bags'; // username
    // private $db_password = 'v-bags@2017'; // password
    // private $db_name = 'v-bags-3335609c'; //database name
    // private $con = null;
		
	 private $db_host = 'localhost'; 
     private $db_user = 'root'; 
     private $db_password = '';
     private $db_name = 'vbags'; 
     private $con = null;
    
    private $googleAPIKey = 'AIzaSyBDldgTNGEJczF9r89mYxNH3iQsFHXfeSU';    
    
    public function getConnection() {
        $this->con=mysqli_connect($this->db_host,$this->db_user,$this->db_password,$this->db_name) or die("Failed to connect to MySQL:".mysql_error());

        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
        return $this->con;
    }
    
    public function getGoogleAPIKey() {
        return $this->googleAPIKey;
    }
}
?>