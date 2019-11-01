<?php namespace App\Tests;

use App\Controller\Api\SessionController;
use App\Entity\Session;
use Codeception\Module\Symfony;
use Codeception\Test\Unit;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\Service\SessionService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class SessionControllerTest extends Unit
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
    public function testSessionValidationAction(): void
    {
        $eventDispatcher = $this->symfony->grabService(EventDispatcherInterface::class);
        $serializer = $this->symfony->grabService('serializer');
        $service = $this->symfony->grabService(SessionService::class);

        $data = [
            'session_code' => self::VALID_SESSION_CODE
        ];

        /** @var SessionController $sessionController */
        $sessionController = new SessionController($serializer);

        /** @var Request $request */
        $request = new Request([], $data, [], [], [], [], json_encode($data));

        /** @var JsonResponse $response */
        $response = $sessionController->validate($request, $service, $eventDispatcher);

        /** @var \stdClass $sessionObject */
        $sessionObject = json_decode($response->getContent(), false);

        $this->tester->grabEntityFromRepository(Session::class, ['id' => $sessionObject->id]);
        $this->tester->assertSame(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->tester->assertSame(self::VALID_SESSION_CODE, $sessionObject->session_code);
    }
}
