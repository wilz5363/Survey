<?php
/**
 * Created by PhpStorm.
 * User: chanw
 * Date: 3/29/2017
 * Time: 10:10 PM
 */

require_once "..\include\DbConnect.php";
$db = new DbConnect();
$conn = $db->connect();

$surveyId = $_GET['surveyId'];
$query = "select ID from questions where SurveyId=".$surveyId." ORDER BY ID desc limit 1";

$result = mysqli_query($conn, $query);
$result = mysqli_fetch_assoc($result);

echo json_encode($result);