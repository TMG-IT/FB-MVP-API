<?php namespace App\Tests;

use App\Entity\Log\SessionLog;
use App\Entity\Session;
use App\EventListener\AnswerSubscriber;
use App\EventListener\SessionStartSubscriber;
use App\Repository\SessionRepository;
use App\Event\SessionStartEvent;
use App\Event\AnswerCreateEvent;
use App\Repository\SessionLogRepository;
use App\Repository\AnswerLogRepository;
use App\Entity\Answer;
use Codeception\Module\Symfony;
use Codeception\Test\Unit;

class AnswerSubscriberTest extends Unit
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
     * This will test answers on all session questions.
     * We need to answer all questions, because last one will also
     * set finished_at value on related session log, which is a signal
     * that user actually finished chat.
     *
     * @throws \Exception
     */
    public function testOnAnswerCreatedEvent(): void
    {
        /** @var AnswerSubscriber $answerSubscriber */
        $answerSubscriber = $this->symfony->grabService(AnswerSubscriber::class);
        /** @var SessionStartSubscriber $sessionStartSubscriber */
        $sessionStartSubscriber = $this->symfony->grabService(SessionStartSubscriber::class);

        $sessionRepository = $this->symfony->grabService(SessionRepository::class);
        $sessionLogRepository = $this->symfony->grabService(SessionLogRepository::class);
        $answerLogRepository = $this->symfony->grabService(AnswerLogRepository::class);

        /** @var Session $session */
        $session = $sessionRepository->findOneBySessionCode(self::VALID_SESSION_CODE);

        $sessionStartEvent = new SessionStartEvent($session);
        $sessionStartSubscriber->onSessionStarted($sessionStartEvent);

        $nonPromptQuestions = [];
        foreach ($session->getQuestions() as $question) {
            if (! $question->getIsPrompt()) {
                $nonPromptQuestions[] = $question;
            }
        }

        foreach ($nonPromptQuestions as $question) {
            $answer = new Answer();
            $answer->setQuestion($question);
            $answerCreateEvent = (new AnswerCreateEvent($answer))->setUuid($session->getUuid());
            $answerSubscriber->onAnswerCreate($answerCreateEvent);

            $answerLog = $answerLogRepository->findOneByUuid(['uuid' => $session->getUuid(), 'answer' => $answer]);
            $this->assertNotNull($answerLog);
        }

        /** @var SessionLog $sessionLog */
        $sessionLog = $sessionLogRepository->findOneBy(['uuid' => $session->getUuid(), 'session' => $session]);
        $answerLogs = $answerLogRepository->findByUuid($session->getUuid());

        $this->tester->assertCount(count($answerLogs), $nonPromptQuestions);
        $this->tester->assertNotNull($sessionLog->getFinishedAt());
    }
}
