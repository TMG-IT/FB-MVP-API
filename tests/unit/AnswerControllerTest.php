<?php namespace App\Tests;

use App\Controller\Api\AnswerController;
use App\Entity\Log\AnswerLog;
use App\Factory\EntityFactory;
use App\Service\ValidatorService;
use Codeception\Module\Symfony;
use Codeception\Test\Unit;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\AnswerRepository;
use App\Repository\QuestionRepository;
use App\Entity\Answer;

class AnswerControllerTest extends Unit
{
    private const QUESTION_ID = 1;
    private const ANSWER_PLACEHOLDER_ID = 21;

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
     * Test create answer on question
     */
    public function testCreateAnswerOnSingleQuestion(): void
    {
        $serializer = $this->symfony->grabService('serializer');
        $validator = $this->symfony->grabService(ValidatorService::class);
        $eventDispatcher = $this->symfony->grabService(EventDispatcherInterface::class);
        $questionRepository = $this->symfony->grabService(QuestionRepository::class);
        $answerRepository = $this->symfony->grabService(AnswerRepository::class);

        $factory = new EntityFactory($serializer, $validator);
        $question = $questionRepository->findOneById(self::QUESTION_ID);

        $data = [
            'text' => 'Excellent',
            'answer_placeholder' => [
                'id' => self::ANSWER_PLACEHOLDER_ID
            ],
            'uuid' => '1234-5678-9101'
        ];

        /** @var AnswerController $answerController */
        $answerController = new AnswerController($serializer);

        /** @var Request $request */
        $request = new Request([], $data, [], [], [], [], json_encode($data));

        /** @var JsonResponse $response */
        $response = $answerController->create($request, $question, $factory, $answerRepository, $eventDispatcher);

        /** @var \stdClass $answerObject */
        $answerObject = json_decode($response->getContent(), false);

        /** @var Answer $answer */
        $answer = $this->tester->grabEntityFromRepository(Answer::class, ['id' => $answerObject->id]);

        /** @var AnswerLog $answerLog */
        $answerLog = $this->tester->grabEntityFromRepository(AnswerLog::class, ['answer' => $answer]);

        $this->tester->assertNotNull($answer);
        $this->tester->assertNotNull($answerLog);
        $this->tester->assertSame($answer->getAnswerPlaceholder()->getId(), self::ANSWER_PLACEHOLDER_ID);
        $this->tester->assertSame($answer->getQuestion()->getId(), self::QUESTION_ID);
    }
}
