<?php

/**
 * Created by PhpStorm.
 * User: chanw
 * Date: 5/20/2017
 * Time: 2:01 PM
 */
class CryptoViewModel
{
    private $AnswerId;
    private $AnswerHash;
    private $AnswerKey;
    private $AnswerCipherText;
    private $Count;

    /**
     * CryptoViewModel constructor.
     * @param $AnswerId
     * @param $AnswerHash
     * @param $AnswerKey
     * @param $AnswerCipherText
     * @param $Count
     */
    public function __construct($AnswerId, $AnswerHash, $AnswerKey, $AnswerCipherText, $Count)
    {
        $this->AnswerId = $AnswerId;
        $this->AnswerHash = $AnswerHash;
        $this->AnswerKey = $AnswerKey;
        $this->AnswerCipherText = $AnswerCipherText;
        $this->Count = $Count;
    }


    /**
     * @return mixed
     */
    public function getAnswerId()
    {
        return $this->AnswerId;
    }

    /**
     * @param mixed $AnswerId
     */
    public function setAnswerId($AnswerId)
    {
        $this->AnswerId = $AnswerId;
    }

    /**
     * @return mixed
     */
    public function getAnswerHash()
    {
        return $this->AnswerHash;
    }

    /**
     * @param mixed $AnswerHash
     */
    public function setAnswerHash($AnswerHash)
    {
        $this->AnswerHash = $AnswerHash;
    }

    /**
     * @return mixed
     */
    public function getAnswerKey()
    {
        return $this->AnswerKey;
    }

    /**
     * @param mixed $AnswerKey
     */
    public function setAnswerKey($AnswerKey)
    {
        $this->AnswerKey = $AnswerKey;
    }

    /**
     * @return mixed
     */
    public function getAnswerCipherText()
    {
        return $this->AnswerCipherText;
    }

    /**
     * @param mixed $AnswerCipherText
     */
    public function setAnswerCipherText($AnswerCipherText)
    {
        $this->AnswerCipherText = $AnswerCipherText;
    }

    /**
     * @return mixed
     */
    public function getCount()
    {
        return $this->Count;
    }

    /**
     * @param mixed $Count
     */
    public function setCount($Count)
    {
        $this->Count = $Count;
    }




}