<?php

/**
 * Created by PhpStorm.
 * User: eenah
 * Date: 20/3/2017
 * Time: 9:55 PM
 */
session_start();

$sId = $_GET['sId'];
$qId = $_GET['qId'];
$aId = $_GET['aId'];

if(isset($_POST['sQues']) && isset($_POST['sAns'])){
    $sQues = $_POST['sQues'];
    $sAns = $_POST['sAns'];

    require_once dirname(__FILE__) . '/include/DbConnect.php';
    $db = new DbConnect();
    $conn = $db->connect();

    $queryUpdateQues = " insert into questions( ID,Question, SurveyId)
      VALUES ('$qId', '$sQues', '$sId')
        ON DUPLICATE KEY UPDATE Question = VALUES(Question)";

    if (mysqli_query($conn, $queryUpdateQues)) {
        $quesId = mysqli_insert_id($conn);

        foreach ($sAns as $ans) {
            $queryUpdateAns = " insert into questions( ID,Answer, QuestionId)
             VALUES ('$aId', '$ans', '$qId')
               ON DUPLICATE KEY UPDATE Question = VALUES(Answer)";

            if (!mysqli_query($conn, $queryUpdateAns)) {
                mysqli_error($conn);
            }

        }
    }
}
header("Location: createForm.php");