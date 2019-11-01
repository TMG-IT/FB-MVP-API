<?php namespace App\Tests;

use App\Entity\Session;
use App\Service\SessionService;
use Codeception\Module\Symfony;
use Codeception\Test\Unit;


class SessionServiceTest extends Unit
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
     * @throws \App\Exception\ExpiredSessionCodeException
     */
    public function testSessionServiceValidate(): void
    {
        /** @var SessionService $service */
        $service = $this->symfony->grabService(SessionService::class);

        /** @var Session $session */
        $session = $service->validate(self::VALID_SESSION_CODE);

        $this->tester->assertNotNull($session);
        $this->tester->assertEquals(Session::SESSION_STATUS_VALID, $session->getStatus());
        $this->tester->assertSame($session->getSessionCode(), self::VALID_SESSION_CODE);
    }
}
