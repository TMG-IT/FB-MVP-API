<?php

namespace App\Entity\Log;

use App\Entity\Answer;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\EntityInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnswerLogRepository")
 */
class AnswerLog extends Log implements EntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Answer", inversedBy="answerLog", cascade={"persist"})
     * @ORM\JoinColumn(name="answer_id", referencedColumnName="id", nullable=false)
     */
    private $answer;

    /**
     * @ORM\Column(name="answered_at", type="datetime", nullable=false)
     */
    private $answeredAt;

    public function getId(): int
    {
        return $this->id;
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

    public function getAnsweredAt(): \DateTime
    {
        return $this->answeredAt;
    }

    public function setAnsweredAt(\DateTime $answeredAt): self
    {
        $this->answeredAt = $answeredAt;

        return $this;
    }
}
