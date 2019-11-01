<?php

namespace App\Command;

use App\Service\LockService;
use Symfony\Component\Lock\LockInterface;

trait LockTrait
{
    /**
     * @var LockService
     */
    private $lockService;

    /**
     * @var int
     */
    private $maxThreads = 1;

    /**
     * @var LockInterface[]
     */
    private $locks = [];

    public function setLockService(LockService $lockService): void
    {
        $this->lockService = $lockService;
    }

    public function setMaxThreads(int $maxThreads): void
    {
        $this->maxThreads = $maxThreads;
    }

    public function checkLock(string $lockName = '', int $maxThreads = null): void
    {
        $lockName = $this->determineLockName($lockName);

        if ($maxThreads === null) {
            $maxThreads = $this->maxThreads;
        }

        for ($i = 0; $i < $maxThreads; ++$i) {
            $lock = $this->lockService->getLock('command:'.str_replace(':', '_', $lockName).':'.$i);
            if ($lock->acquire()) {
                $this->locks[$lockName] = $lock;

                return;
            }
        }

        // failed to get a lock
        exit;
    }

    public function releaseLock(string $lockName = ''): void
    {
        $lockName = $this->determineLockName($lockName);

        if (isset($this->locks[$lockName])) {
            $this->locks[$lockName]->release();
            unset($this->locks[$lockName]);
        }
    }

    private function determineLockName(string $lockName): string
    {
        if ($lockName === '') {
            if (method_exists($this, 'getName') && $this->getName() !== '') {
                return $this->getName();
            }

            throw new \RuntimeException('Unable to determine lock name.');
        }

        return $lockName;
    }
}
