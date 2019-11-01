<?php

namespace App\Command;

use App\Service\LockService;

interface LockUsingInterface
{
    public function setLockService(LockService $lockService): void;
}
