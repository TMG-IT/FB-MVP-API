<?php

namespace App\Event;

use Symfony\Component\EventDispatcher\Event;
use App\Entity\Session;

class SessionStartEvent extends Event
{
    private $session;
    private $uuid;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function getSession(): Session
    {
        return $this->session;
    }

    public function setSession(Session $session): SessionStartEvent
    {
        $this->session = $session;

        return $this;
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    public function setUuid($uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }
}
