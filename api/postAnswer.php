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



$sqlGetAnswerSelectionCount = "select SelectionCount from answers where ID =".$answerId;
$selectionCountResult = mysqli_query($conn, $sqlGetAnswerSelectionCount);
$selectionCountResult = mysqli_fetch_assoc($selectionCountResult);



if($selectionCountResult['SelectionCount'] == null){

    $newSelectionCount = $cipherText;
    $newKey = $curKey;

}
else{

    //get data from crypto_table
    $sqlGetCryptoData = "select Answer_Hash, Answer_Key from crypto_table where AnswerId = ".$answerId;
    $cryptoData = mysqli_query($conn, $sqlGetCryptoData);
    $cryptoData = mysqli_fetch_assoc($cryptoData);

    //get the latest selection count
    $oldSelectionCount = new Math_BigInteger($selectionCountResult['SelectionCount']);
    $answerHash = new Math_BigInteger($cryptoData['Answer_Hash']);
    $newSelectionCount = CalculateNewSelectionCount($oldSelectionCount, $cipherText, $answerHash);

    //get the lastest key
    $oldKey = new Math_BigInteger($cryptoData['Answer_Key']);
    $newKey = CalculateNewKey($oldKey, $curKey);

}

$sqlUpdateData = "Update answers set SelectionCount = '".$newSelectionCount->value."' where ID = ".$answerId.";";
$sqlUpdateData .= "update crypto_table set Answer_Key = '".$newKey->value."' where AnswerId = ".$answerId;

if(mysqli_multi_query($conn,$sqlUpdateData)){
    echo "Answer Accepted";
}else{
    echo "Error: ".mysqli_error($conn);
}


