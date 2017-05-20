<?php


function generate1024bit()
{
    $a = new Math_BigInteger(3);
    $b = new Math_BigInteger(3);
    for($i=0;$i<1024;$i++)
    {
        $b=$b->multiply($a);
    }
    $b=$b->random($b,$b->multiply($a));
    return $b;

}

function generate1024bithash()
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

function generate512bit()
{
    $a = new Math_BigInteger(23);
    $b = new Math_BigInteger(23);
    for($i=0;$i<512;$i++)
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
    $key = new Math_BigInteger($keyString);
    $hash = new Math_BigInteger($question_hash);
    list($quotient, $remainder) = ($plainText->add($key))->divide($hash);
    $cipherText = $remainder;

    return $cipherText;
}

function decryption($cipherTextString,$keyString,$question_hash)
{

    $cipherText = new Math_BigInteger($cipherTextString);
    $key = new Math_BigInteger($keyString, 10);
    list($quotient, $remainder) = ($cipherText->subtract($key))->divide($question_hash);
    $plainText = new Math_BigInteger($remainder);

    return $plainText->value;
}

function generateKey($g,$p,$c)
{
    $key = new Math_BigInteger($g->powMod($c,$p));
    return $key;
}
