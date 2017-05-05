<?php
/**
 * Created by PhpStorm.
 * User: chanw
 * Date: 3/26/2017
 * Time: 9:40 PM
 */

require_once "..\include\DbConnect.php";
$db = new DbConnect();
$conn = $db->connect();

$query = "SELECT ID,SurveyName,SurveyDesc, ExpiryDate from surveys where ExpiryDate > CURRENT_TIMESTAMP ";

$result = mysqli_query($conn,$query);
$response = array();

if(mysqli_num_rows($result) > 0){
    $response['result'] = true;
    $response['survey'] = array();
    while ($row = mysqli_fetch_assoc($result)){
        $survey = array();
        $survey['ID'] = $row['ID'];
        $survey['SurveyName'] = $row['SurveyName'];
        $survey['SurveyDesc'] = $row['SurveyDesc'];
        $survey['ExpiryDate'] = $row['ExpiryDate'];
        array_push($response['survey'],$survey);
    }
    echo json_encode($response);
}else{
    $response['result'] = false;
    echo json_encode($response);
}
mysqli_close($conn);