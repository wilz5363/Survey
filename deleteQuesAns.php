<?php

/**
 * Created by PhpStorm.
 * User: eenah
 * Date: 20/3/2017
 * Time: 9:17 PM
 */
session_start();

$sId = $_GET['id'];
/*
 * the query was wrong. now correct liao
 */
$query = "delete FROM questions 
WHERE ID = '$sId'";

include dirname(__FILE__) . "/include/DbConnect.php";
$db = new DbConnect();
$conn = $db->connect();

echo "Deleting...";
mysqli_query($conn, $query) or die("Smth wrong");
mysqli_close($conn);

header("Location: createForm.php?id=".$surveyId);