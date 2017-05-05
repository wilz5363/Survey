<?php
/**
 * Created by PhpStorm.
 * User: chanw
 * Date: 3/26/2017
 * Time: 4:03 PM
 */
require_once "..\include\DbConnect.php";
$db = new DbConnect();
$conn = $db->connect();

$email = $_GET['email'];
$password = $_GET['password'];
$query = "SELECT email from users WHERE Email = '$email' and Password = '$password'";

$result = mysqli_query($conn,$query);
$response = array();
if(mysqli_num_rows($result) > 0){
    $response['result'] = true;
    echo json_encode($response);
}else{
    $response['result'] = false;
    echo json_encode($response);
}
mysqli_close($conn);