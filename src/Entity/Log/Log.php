<?php

namespace App\Entity\Log;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\MappedSuperclass() */
class Log
{
    /**
     * @ORM\Column(name="uuid", type="string", nullable=false)
     */
    private $uuid;

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
