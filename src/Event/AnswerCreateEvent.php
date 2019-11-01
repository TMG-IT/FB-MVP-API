<?php

namespace App\Event;

use Symfony\Component\EventDispatcher\Event;
use App\Entity\Answer;

class AnswerCreateEvent extends Event
{
    private $answer;
    private $uuid;

    public function __construct(Answer $answer)
    {
        $this->answer = $answer;
    }

    public function getAnswer(): Answer
    {
        return $this->answer;
    }

    public function setAnswer(Answer $answer): self
    {
        $this->answer = $answer;

        return $this;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }
}
