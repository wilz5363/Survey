<?php
/**
 * Created by PhpStorm.
 * User: chanw
 * Date: 4/25/2017
 * Time: 9:02 PM
 */

require_once "/include/DbConnect.php";
$db = new DbConnect();
$conn = $db->connect();

$userEmail = $_POST['email'];
$surveyId = $_POST['surveyId'];

$queryCreateSession = "insert into user_sessions (UserId, SurveyId)  
                          select ID,".$surveyId." from users where Email = '".$userEmail."'
                          ON DUPLICATE KEY UPDATE DateTimeRespoded = CURRENT_TIMESTAMP()";

if(mysqli_query($conn,$queryCreateSession)){
    echo "User Session Created";
}else{
    echo mysqli_error($conn);
}