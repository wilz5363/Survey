<?php
/**
 * Created by PhpStorm.
 * User: chanw
 * Date: 4/24/2017
 * Time: 3:29 PM
 */

require_once "../include/DbConnect.php";
include ('../include/CipherDbConnect.php');
include('../phpseclib/Math/BigInteger.php');
include ('../phpseclib/crypto.php');

$db = new DbConnect();
$conn = $db->connect();

$cipherDb = new CipherDbConnect();
$cipherConn = $cipherDb->connect();



$answerId = $_POST['answerId'];
$curCipherText = new Math_BigInteger($_POST['cipherText']);
$curKey = new Math_BigInteger($_POST['key']);
$email = $_POST['email'];
$questionId = $_POST['questionId'];
$lastQuestion = $_POST['lastQuestion'];



//$sqlGetUserId = "select ID from users where Email = '".$email."'";
//$userId = mysqli_query($conn, $sqlGetUserId);
//$userId = mysqli_fetch_assoc($userId);



//$sqlGetAnotherAnswerHash = "select AnswerId,Answer_Hash from crypto_table where AnswerId = (select ID from answers where ID != ".$answerId." and questionId = (select QuestionId from answers where ID =".$answerId."))";
//$anotherAnswer = mysqli_query($conn, $sqlGetAnotherAnswerHash);
//$anotherAnswer = mysqli_fetch_assoc($anotherAnswer);
//$anotherAnswerHash = new Math_BigInteger($anotherAnswer['Answer_Hash']);
//
//$noCountPlainText = new Math_BigInteger(0);
//$noCountG = new Math_BigInteger(generate1024bit());
//$noCountP = new Math_BigInteger(generate1024bit());
//$noCountC = new Math_BigInteger(generate80bit());
//$noCountKey = generateKey($noCountG, $noCountP, $noCountC);
//
//$noCountCipherText = new Math_BigInteger(encryption($noCountPlainText,$noCountKey, $anotherAnswerHash ));

//Get old CryptoData
$sqlGetCryptoData = "select AnswerId, Answer_Key, Answer_CipherText from crypto_table where AnswerId = ".$answerId;
$cipherResult = mysqli_query($cipherConn, $sqlGetCryptoData);
$cipherResult = mysqli_fetch_assoc($cipherResult);

//add key
if($cipherResult['Answer_Key'] == null){
    $oldCipherKey = new Math_BigInteger(0);
}else{
    $oldCipherKey = new Math_BigInteger($cipherResult['Answer_Key']);
}
$newCipherKey = $curKey->add($oldCipherKey);

//add ciphertext
if($cipherResult['Answer_CipherText'] == null){
    $oldCipherText = new Math_BigInteger(0);
}else{
    $oldCipherText = new Math_BigInteger($cipherResult['Answer_CipherText']);
}
$newCipherText = $curCipherText->add($oldCipherText);


//Update new CryptoData
$sqlUpdateCryptoData = "update crypto_table set Answer_Key='".$newCipherKey->value."', Answer_CipherText = '".$newCipherText->value."' where AnswerId = ".$answerId;


if (mysqli_query($cipherConn, $sqlUpdateCryptoData)){
    echo "Answer Accepted";
}else{
    echo "Error: ".mysqli_error($conn);
}

if($lastQuestion){
    $sqlUpdateUserSession = "update user_sessions set Completed = TRUE ".
        "where SurveyId = (select SurveyId from questions where ID =".$questionId.") ".
        "and UserId = (select ID from users where email = '".$email."')";
    mysqli_query($conn, $sqlUpdateUserSession);
}

mysqli_close($conn);
mysqli_close($cipherConn);

