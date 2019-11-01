<?php

namespace App\Entity\Question;

use App\Entity\EntityInterface;
use App\Entity\Session;
use App\Entity\Answer;
use App\Entity\AnswerPlaceholder;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuestionRepository")
 * @Table(name="question")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="type", type="string", length=255)
 * @DiscriminatorMap({
 *     "question"="Question",
 *     "text"="FreeTypeQuestion",
 *     "choice"="ChoiceQuestion",
 *     "rating"="RatingQuestion",
 * })
 */
class Question implements EntityInterface
{
    protected const FREE_TYPE = 'free_type_question';
    protected const CHOICE = 'choice_question';
    protected const RATING = 'rating_question';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @Groups({"default"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Session", inversedBy="questions")
     * @ORM\JoinColumn(name="session_id", nullable=false)
     */
    private $session;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Answer", mappedBy="question")
     */
    private $answers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AnswerPlaceholder", mappedBy="question")
     * @Groups({"default"})
     */
    private $answerPlaceholders;

    /**
     * @ORM\Column(name="priority", type="integer", nullable=false)
     */
    private $priority = 1;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"default"})
     */
    private $isPrompt;


    public function __construct()
    {
        $this->answers = new ArrayCollection();
        $this->answerPlaceholders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSession(): Session
    {
        return $this->session;
    }

    public function setSession(Session $session): self
    {
        $this->session = $session;

        return $this;
    }

    /**
     * @return Collection|Answer[]
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    /**
     * @return Collection|AnswerPlaceholder[]
     */
    public function getAnswerPlaceholders(): Collection
    {
        return $this->answerPlaceholders;
    }

    public function addAnswerPlaceholder(AnswerPlaceholder $answerPlaceholder): self
    {
        if (!$this->answerPlaceholders->contains($answerPlaceholder)) {
            $this->answerPlaceholders[] = $answerPlaceholder;
            $answerPlaceholder->setQuestion($this);
        }

        return $this;
    }

    public function removeAnswerPlaceholder(AnswerPlaceholder $answerPlaceholder): self
    {
        if ($this->answerPlaceholders->contains($answerPlaceholder)) {
            $this->answerPlaceholders->removeElement($answerPlaceholder);
            // set the owning side to null (unless already changed)
            if ($answerPlaceholder->getQuestion() === $this) {
                $answerPlaceholder->setQuestion(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param mixed $priority
     *
     * @return Question
     */
    public function setPriority($priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    public function getIsPrompt(): ?bool
    {
        return $this->isPrompt;
    }

    public function setIsPrompt(bool $isPrompt): self
    {
        $this->isPrompt = $isPrompt;

        return $this;
    }

}
