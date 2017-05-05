
<?php
/**
 * Created by PhpStorm.
 * User: chanw
 * Date: 4/25/2017
 * Time: 8:55 PM
 */
include ('..\phpseclib\crypto.php');
include('..\phpseclib\Math\BigInteger.php');




require_once "..\include\DbConnect.php";
$db = new DbConnect();
$conn = $db->connect();

// preparation for cryptosystem
// random generate based on selected bit
$g = new Math_BigInteger(generate1024bit());
$p = new Math_BigInteger(generate1024bit());
$c = new Math_BigInteger(generate80bit());
$d = new Math_BigInteger(1);
$key = generateKey($g,$p,$c);

$answerId = $_POST['answerId'];
$queryGetAnswerHash = "select Answer_Hash from crypto_table where AnswerId =".$answerId;
$result = mysqli_query($conn, $queryGetAnswerHash);
$result = mysqli_fetch_assoc($result);

$q = new Math_BigInteger($result['Answer_Hash']);

$cipherText = new Math_BigInteger(encryption($d, $key, $q));

$response = array();
$response['cipherText'] = $cipherText->value;
$response['key'] = $key->value;

echo json_encode($response);

