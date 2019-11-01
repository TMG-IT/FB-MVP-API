<?php

namespace App\Entity\Question;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Table(name="q_rating")
 * @ORM\Entity(repositoryClass="App\Repository\RatingQuestionRepository")
 */
class RatingQuestion extends Question implements QuestionInterface
{
    /**
     * @ORM\Column(name="text", type="string", length=255)
     * @Groups({"default"})
     */
    private $text;

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
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("type")
     * @Groups({"default"})
     */
    public function getType(): string
    {
        return Question::RATING;
    }
}
