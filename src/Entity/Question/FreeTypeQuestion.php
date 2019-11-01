<?php

namespace App\Entity\Question;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Table(name="q_free_type")
 * @ORM\Entity(repositoryClass="App\Repository\FreeTypeQuestionRepository")
 */
class FreeTypeQuestion extends Question implements QuestionInterface
{
    /**
     * @ORM\Column(name="text", type="string", length=255)
     * @Groups({"default"})
     */
    private $text;

    /**
     * @ORM\Column(name="is_name_prompt", type="boolean", length=255, nullable=true)
     * @Groups({"default"})
     */
    private $isNamePrompt = false;

    /**
     * @ORM\Column(name="skip_answer", type="boolean", length=255, nullable=true)
     * @Groups({"default"})
     */
    private $skipAnswer = false;

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
     * @return bool
     */
    public function getSkipAnswer(): ?bool
    {
        return $this->skipAnswer;
    }

    /**
     * @param bool $skipAnswer
     */
    public function setSkipAnswer(bool $skipAnswer): void
    {
        $this->skipAnswer = $skipAnswer;
    }

    /**
     * @return bool
     */
    public function getIsNamePrompt(): ?bool
    {
        return $this->isNamePrompt;
    }

    /**
     * @param bool $isNamePrompt
     */
    public function setIsNamePrompt(bool $isNamePrompt): void
    {
        $this->isNamePrompt = $isNamePrompt;
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("type")
     * @Groups({"default"})
     */
    public function getType(): string
    {
        return Question::FREE_TYPE;
    }
}
