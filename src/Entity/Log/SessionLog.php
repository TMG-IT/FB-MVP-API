<?php

namespace App\Entity\Log;

use App\Entity\Session;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\EntityInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SessionLogRepository")
 */
class SessionLog extends Log implements EntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Session", inversedBy="sessionLogs")
     * @ORM\JoinColumn(name="session_id", referencedColumnName="id", nullable=false)
     */
    private $session;

    /**
     * @ORM\Column(name="started_at", type="datetime", nullable=false)
     */
    private $startedAt;

    /**
     * @ORM\Column(name="finished_at", type="datetime", nullable=true)
     */
    private $finishedAt;

    public function getId(): int
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

    public function getStartedAt(): \DateTime
    {
        return $this->startedAt;
    }

    public function setStartedAt(\DateTime $startedAt): self
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    public function getFinishedAt(): ?\DateTime
    {
        return $this->finishedAt;
    }

    public function setFinishedAt(\DateTime $finishedAt): self
    {
        $this->finishedAt = $finishedAt;

        return $this;
    }
}
