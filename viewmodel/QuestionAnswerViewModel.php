<?php

/**
 * Created by PhpStorm.
 * User: chanw
 * Date: 5/20/2017
 * Time: 1:25 PM
 */
class QuestionAnswerViewModel
{
    private $QuestionId;
    private $Question;
    private $AnswerId;
    private $Answer;
    private $selectionCount;

    /**
     * @return mixed
     */
    public function getSelectionCount()
    {
        return $this->selectionCount;
    }

    /**
     * @param mixed $selectionCount
     */
    public function setSelectionCount($selectionCount)
    {
        $this->selectionCount = $selectionCount;
    }

    /**
     * @return mixed
     */
    public function getQuestionId()
    {
        return $this->QuestionId;
    }

    /**
     * @param mixed $QuestionId
     */
    public function setQuestionId($QuestionId)
    {
        $this->QuestionId = $QuestionId;
    }

    /**
     * @return mixed
     */
    public function getQuestion()
    {
        return $this->Question;
    }

    /**
     * @param mixed $Question
     */
    public function setQuestion($Question)
    {
        $this->Question = $Question;
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
    public function getAnswer()
    {
        return $this->Answer;
    }

    /**
     * @param mixed $Answer
     */
    public function setAnswer($Answer)
    {
        $this->Answer = $Answer;
    }




}