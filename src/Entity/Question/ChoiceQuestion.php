<?php

namespace App\Entity\Question;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Table(name="q_choice")
 * @ORM\Entity(repositoryClass="App\Repository\ChoiceQuestionRepository")
 */
class ChoiceQuestion extends Question implements QuestionInterface
{
    /**
     * @ORM\Column(name="text", type="string", length=255)
     * @Groups({"default"})
     */
    private $text;

    /**
     * @ORM\Column(name="button_text", type="string", length=20, nullable = true)
     * @Groups({"default"})
     */
    private $buttonText;

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text): void
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getButtonText()
    {
        return $this->buttonText;
    }

    /**
     * @param mixed $text
     */
    public function setButtonText($buttonText): void
    {
        $this->buttonText = $buttonText;
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("type")
     * @Groups({"default"})
     */
    public function getType(): string
    {
        return Question::CHOICE;
    }
}
