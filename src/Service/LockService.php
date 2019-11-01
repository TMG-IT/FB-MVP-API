<?php

namespace App\Service;

use Symfony\Component\Lock\Factory;
use Symfony\Component\Lock\Lock;

class LockService
{
    private $factory;

    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    public function getLock(string $key): Lock
    {
        return $this->factory->createLock($key);
    }
}
