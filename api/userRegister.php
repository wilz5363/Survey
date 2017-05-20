<?php
/**
 * Created by PhpStorm.
 * User: chanw
 * Date: 5/10/2017
 * Time: 7:26 PM
 */
$email = $_POST['Email'];
$password = $_POST['Password'];

require_once "../include/DbConnect.php";
$db = new DbConnect();
$conn = $db->connect();

$sqlInsertUser = "insert into users(Email, Password) VALUES ('".$email."', '".$password."')";

if(mysqli_query($conn, $sqlInsertUser)){
    echo "true";
}else{
    echo "false";
}