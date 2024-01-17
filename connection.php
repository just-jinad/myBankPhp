<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');
$localhost= 'localhost';
$username= 'root';
$password= '';
$database='bankdb';
$dbconnection = new mysqli($localhost, $username, $password, $database);

if ($dbconnection->connect_error) {
//    echo 'not connected';
}else{
    // echo 'connected ';
}

?>