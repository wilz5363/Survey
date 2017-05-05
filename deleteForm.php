<?php
/**
 * Created by PhpStorm.
 * User: chanw
 * Date: 3/18/2017
 * Time: 10:05 PM
 */
session_start();

$id = $_GET['id'];
$query = "delete from Surveys where ID = '$id'";
include dirname(__FILE__) . "/include/DbConnect.php";
$db = new DbConnect();
$conn = $db->connect();

echo "Deleting...";
mysqli_query($conn, $query);
mysqli_close($conn);

header("Location: index.php");