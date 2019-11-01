<?php namespace App\Tests;

use App\Factory\EntityFactory;
use App\Service\ValidatorService;
use App\Controller\Api\SubscriptionController;
use App\Repository\SubscriptionRepository;
use Codeception\Module\Symfony;
use Codeception\Test\Unit;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Subscription;

class SubscriptionControllerTest extends Unit
{
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
     * Test create subscription
     */
    public function testSubscribeUserByEmailFirstNameAndLastName(): void
    {
        $serializer = $this->symfony->grabService('serializer');
        $validator = $this->symfony->grabService(ValidatorService::class);
        $subscriptionRepository = $this->symfony->grabService(SubscriptionRepository::class);
        $factory = new EntityFactory($serializer, $validator);

        $data = [
            'first_name' => 'Bob',
            'last_name' => 'Gomez',
            'email' => 'bobgomez@example.com'
        ];

        /** @var SubscriptionController $subscriptionController */
        $subscriptionController = new SubscriptionController($serializer);

        /** @var Request $request */
        $request = new Request([], $data, [], [], [], [], json_encode($data));

        $response = $subscriptionController->create($request, $factory, $subscriptionRepository);

        /** @var \stdClass $subscriptionObject */
        $subscriptionObject = json_decode($response->getContent(), false);

        /** @var Subscription $subscription */
        $subscription = $this->tester->grabEntityFromRepository(Subscription::class, ['id' => $subscriptionObject->id]);

        $this->tester->assertNotNull($subscription);
        $this->tester->assertSame('Bob', $subscription->getFirstName());
        $this->tester->assertSame('Gomez', $subscription->getLastName());
        $this->tester->assertSame('bobgomez@example.com', $subscription->getEmail());
    }
}
