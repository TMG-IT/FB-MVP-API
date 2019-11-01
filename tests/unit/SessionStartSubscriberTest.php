<?php namespace App\Tests;

use App\Entity\Log\SessionLog;
use App\Entity\Session;
use App\EventListener\SessionStartSubscriber;
use App\Repository\SessionRepository;
use App\Event\SessionStartEvent;
use App\Repository\SessionLogRepository;
use Codeception\Module\Symfony;
use Codeception\Test\Unit;

class SessionStartSubscriberTest extends Unit
{
    private const VALID_SESSION_CODE = '200000';

    /**
     * @var Symfony
     */
    private $symfony;

    /**
     * @var \App\Tests\UnitTester
     */
    protected $tester;

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    protected function _before()
    {
        /** @var Symfony $symfony */
        $this->symfony = $this->getModule('Symfony');
    }

    protected function _after()
    {
    }

    /**
     * @throws \Exception
     */
    public function testSessionStartEvent(): void
    {
        $sessionRepository = $this->symfony->grabService(SessionRepository::class);
        $sessionLogRepository = $this->symfony->grabService(SessionLogRepository::class);

        /** @var SessionStartSubscriber $sessionStartSubscriber */
        $sessionStartSubscriber = $this->symfony->grabService(SessionStartSubscriber::class);

        /** @var Session $session */
        $session = $sessionRepository->findOneBySessionCode(self::VALID_SESSION_CODE);

        /** @var SessionStartEvent $sessionStartEvent */
        $sessionStartEvent = new SessionStartEvent($session);

        $sessionStartSubscriber->onSessionStarted($sessionStartEvent);

        /** @var SessionLog $sessionLog */
        $sessionLog = $sessionLogRepository->findOneBy([
            'uuid' => $session->getUuid(),
            'session' => $session
        ]);

        $this->tester->assertNotNull($sessionLog);
        $this->assertNotNull($sessionLog->getStartedAt());
        $this->assertNull($sessionLog->getFinishedAt());
    }
}
