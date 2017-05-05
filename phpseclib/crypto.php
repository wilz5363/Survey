<?php


function generate1024bit()
{
    $a = new Math_BigInteger(2);
    $b = new Math_BigInteger(2);
    for($i=0;$i<1024;$i++)
    {
        $b=$b->multiply($a);
    }
    $b=$b->random($b,$b->multiply($a));
    return $b;

}

function generate80bit()
{
    $a = new Math_BigInteger(2);
    $b = new Math_BigInteger(2);
    for($i=0;$i<80;$i++)
    {
        $b=$b->multiply($a);
    }
    $b=$b->random($b,$b->multiply($a));
    return $b;

}

function CalculateNewSelectionCount($oldCipherText, $newCipherText, $answer_hash){

    list($quotient, $remainder) = ($oldCipherText->add($newCipherText))->divide($answer_hash);
    $latestSelectionCount = new Math_BigInteger($remainder);
    return $latestSelectionCount;

}

function CalculateNewKey($oldKey, $curKey){
    return $oldKey->add($curKey);
}

function encryption($plainTextString,$keyString,$question_hash)
{
    $plainText = new Math_BigInteger($plainTextString);
    $key = new Math_BigInteger($keyString,10);
    list($quotient, $remainder) = ($plainText->add($key))->divide($question_hash);
    $cipherText = $remainder;
    return $cipherText;
}

function decryption($cipherTextString,$keyString,$question_hash)
{

    $cipherText = new Math_BigInteger($cipherTextString);
    $key = new Math_BigInteger($keyString, 10);
    list($quotient, $remainder) = ($cipherText->subtract($key))->divide($question_hash);
    $plainText = new Math_BigInteger($remainder);

    return $plainText;
}

function generateKey($g,$p,$d)
{
    $key = new Math_BigInteger($g->powMod($d,$p));
    return $key;
}
