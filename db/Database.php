<?php
namespace db;
use models\ResponseModel;
use mysqli;

class Database {
    public static function connect_db() {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "tuvan";
        $false_response = new ResponseModel(false, "Connection failed: ". mysqli_connect_error());
        $conn = mysqli_connect($servername, $username, $password, $dbname);
        mysqli_set_charset($conn,'utf8');
        if (!$conn) {
            return $false_response;
        }
    
        return new ResponseModel(true, $conn);
    }
}
?>