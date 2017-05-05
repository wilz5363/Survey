<?php
/**
 * Created by PhpStorm.
 * User: eenah
 * Date: 16/3/2017
 * Time: 12:21 AM
 */

require_once "..\include\DbConnect.php";
$db = new DbConnect();
$conn = $db->connect();

$quesId = $_GET['surveyId'];
$index = $_GET['index'];

$queryGetQuestion = "select ID, Question FROM questions where SurveyId = ".$quesId." LIMIT ".$index.",1;";


$qResult = mysqli_query($conn, $queryGetQuestion);

$response = array();

if(mysqli_num_rows($qResult) > 0){
    $response['result'] = true;
    $response['question'] = array();
    $response['answers'] = array();

    $qResult = mysqli_fetch_assoc($qResult);
    $question['ID'] = $qResult['ID'];
    $question['Question'] = $qResult['Question'];

    array_push($response['question'], $question);

    $queryGetAnswers = "Select ID, Answer from answers where QuestionId =".$qResult['ID'];
    $aResult = mysqli_query($conn, $queryGetAnswers);

    while( $value = mysqli_fetch_assoc($aResult)) {
        $answer['ID'] = $value['ID'];
        $answer['Answer'] = $value['Answer'];
        array_push($response['answers'], $answer);
    }

    $lastQuestionQuery = "select ID from questions where SurveyId=".$quesId." ORDER BY ID desc limit 1";
    $lastQues = mysqli_query($conn,$lastQuestionQuery);
    $lastQues = mysqli_fetch_assoc($lastQues);

    if($lastQues['ID'] === $question['ID']){
        $response['lastQuestion'] = true;
    }else{
        $response['lastQuestion'] = false;
    }
}

echo json_encode($response);
mysqli_close($conn);