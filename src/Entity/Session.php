<?php

namespace App\Entity;

use App\Entity\Log\SessionLog;
use App\Entity\Question\Question;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SessionRepository")
 */
class Session implements EntityInterface
{
    public const SESSION_STATUS_VALID = 'valid';
    public const SESSION_STATUS_INVALID = 'invalid';
    public const SESSION_STATUS_EXPIRED = 'expired';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @Groups({"default"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=6)
     * @Assert\Length(
     *      min = 6,
     *      max = 6,
     *      minMessage = "Invalid Session Code format",
     *      maxMessage = "Invalid Session Code format"
     * )
     * @Groups({"default"})
     */
    private $sessionCode;

    /**
     * @ORM\OrderBy({"priority"="ASC"})
     * @ORM\OneToMany(targetEntity="App\Entity\Question\Question", mappedBy="session")
     * @Groups({"default"})
     */
    private $questions;

    /**
     * @ORM\Column(name="status", type="string", nullable=false)
     */
    private $status;

    /**
     * @ORM\Column(type="json_array")
     * @Groups({"default"})
     */
    private $coach;

    /**
     * @ORM\Column(type="string", length=40)
     * @Groups({"default"})
     */
    private $sessionName;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"default"})
     */
    private $collectContactData;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Log\SessionLog", mappedBy="session")
     */
    private $sessionLogs;

    /**
     * @Groups({"default"})
     */
    private $uuid;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->sessionLogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSessionCode(): ?string
    {
        return $this->sessionCode;
    }

    public function setSessionCode(string $sessionCode): self
    {
        $this->sessionCode = $sessionCode;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|Question[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setSession($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->contains($question)) {
            $this->questions->removeElement($question);
            // set the owning side to null (unless already changed)
            if ($question->getSession() === $this) {
                $question->setSession(null);
            }
        }

        return $this;
    }

    public function getCoach(): array
    {
        return $this->coach;
    }

    public function setCoach(array $coach): self
    {
        $this->coach = $coach;

        return $this;
    }

    public function getSessionName(): string
    {
        return $this->sessionName;
    }

    public function setSessionName(string $sessionName): self
    {
        $this->sessionName = $sessionName;

        return $this;
    }

    public function getCollectContactData(): bool
    {
        return $this->collectContactData;
    }

    public function setCollectContactData(bool $collectContactData): self
    {
        $this->collectContactData = $collectContactData;

        return $this;
    }

    /**
     * @return Collection|SessionLog[]
     */
    public function getSessionLogs(): Collection
    {
        return $this->sessionLogs;
    }
}
