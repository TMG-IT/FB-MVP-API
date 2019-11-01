<?php

namespace App\Entity;

use App\Entity\Log\AnswerLog;
use App\Entity\Question\Question;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnswerRepository")
 */
class Answer implements EntityInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @Groups({"default"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"default"})
     */
    private $text;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Question\Question", inversedBy="answers")
     * @ORM\JoinColumn(name="question_id", nullable=false)
     */
    private $question;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AnswerPlaceholder", cascade={"persist", "remove"})
     * @Groups({"default"})
     */
    private $answerPlaceholder;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Log\AnswerLog", mappedBy="answer")
     */
    private $answerLog;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getAnswerPlaceholder(): ?AnswerPlaceholder
    {
        return $this->answerPlaceholder;
    }

    public function setAnswerPlaceholder(?AnswerPlaceholder $answerPlaceholder): self
    {
        $this->answerPlaceholder = $answerPlaceholder;

        return $this;
    }

    /**
     * @return AnswerLog
     */
    public function getAnswerLog(): AnswerLog
    {
        return $this->answerLog;
    }
}
