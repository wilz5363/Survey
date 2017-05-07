<?php
/**
 * Created by PhpStorm.
 * User: chanw
 * Date: 4/24/2017
 * Time: 3:29 PM
 */

require_once "..\include\DbConnect.php";
include('..\phpseclib\Math\BigInteger.php');
include ('..\phpseclib\crypto.php');
$db = new DbConnect();
$conn = $db->connect();

$answerId = $_POST['answerId'];
$cipherText = new Math_BigInteger($_POST['cipherText']);
$curKey = new Math_BigInteger($_POST['key']);

$email = $_POST['email'];
$questionId = $_POST['questionId'];
$lastQuestion = $_POST['lastQuestion'];

$sqlGetUserId = "select ID from users where Email = '".$email."'";
$userId = mysqli_query($conn, $sqlGetUserId);
$userId = mysqli_fetch_assoc($userId);



$sqlGetAnotherAnswerHash = "select AnswerId,Answer_Hash from crypto_table where AnswerId = (select ID from answers where ID != ".$answerId." and questionId = (select QuestionId from answers where ID =".$answerId."))";
$anotherAnswer = mysqli_query($conn, $sqlGetAnotherAnswerHash);
$anotherAnswer = mysqli_fetch_assoc($anotherAnswer);
$anotherAnswerHash = new Math_BigInteger($anotherAnswer['Answer_Hash']);

$noCountPlainText = new Math_BigInteger(0);
$noCountG = new Math_BigInteger(generate1024bit());
$noCountP = new Math_BigInteger(generate1024bit());
$noCountC = new Math_BigInteger(generate80bit());
$noCountKey = generateKey($noCountG, $noCountP, $noCountC);

$noCountCipherText = new Math_BigInteger(encryption($noCountPlainText,$noCountKey, $anotherAnswerHash ));


if($anotherAnswer['AnswerId'] > $answerId){
    //A is selected
    $sqlInsertAggregateData  = "insert into aggregate_table (UserId, QuestionId, FirstSelection, FirstKey, SecondSelection, SecondKey) ".
                                    "values (".$userId['ID'].",".$questionId.",'".$cipherText."','".$curKey."','".$noCountCipherText."','".$noCountKey."')";

}else{
    //B is selected
    $sqlInsertAggregateData  = "insert into aggregate_table (UserId, QuestionId, FirstSelection, FirstKey, SecondSelection, SecondKey) ".
                                    "values (".$userId['ID'].",".$questionId.",'".$noCountCipherText."','".$noCountKey."','".$cipherText."','".$curKey."')";
}

if (mysqli_query($conn, $sqlInsertAggregateData)){
    echo "Answer Accepted";
}else{
    echo "Error: ".mysqli_error($conn);
}

if($lastQuestion){
    $sqlUpdateUserSession = "update user_sessions set Completed = TRUE ";
    mysqli_query($conn, $sqlUpdateUserSession);
}

