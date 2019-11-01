<?php

namespace App\EventListener;

use App\Entity\Log\SessionLog;
use App\Repository\AnswerRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Entity\Log\AnswerLog;
use App\Repository\AnswerLogRepository;
use App\Repository\SessionLogRepository;
use App\Event\AnswerCreateEvent;
use Ramsey\Uuid\Uuid;
use App\Service\DateService;
use App\Events;

class AnswerSubscriber implements EventSubscriberInterface
{
    private $answerLogRepository;
    private $sessionLogRepository;
    private $answerRepository;

    public function __construct(AnswerLogRepository $answerLogRepository, SessionLogRepository $sessionLogRepository, AnswerRepository $answerRepository)
    {
        $this->answerLogRepository = $answerLogRepository;
        $this->sessionLogRepository = $sessionLogRepository;
        $this->answerRepository = $answerRepository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            Events::AnswerCreateEvent => 'onAnswerCreate',
        ];
    }

    /**
     * @param AnswerCreateEvent $event
     */
    public function onAnswerCreate(AnswerCreateEvent $event): void
    {
        $answerLog = new AnswerLog();

        $answerLog
            ->setAnswer($event->getAnswer())
            ->setAnsweredAt(DateService::generateUTCDate())
            ->setUuid($event->getUuid());

        $this->answerLogRepository->save($answerLog);

        /**
         * This is a logic for end of single session iteration check
         * We will start with assumption that all questions for session and current UUID are finished
         */
        $sessionFinished = true;

        /*
         * We will loop through all the questions in current session
         */
        foreach ($event->getAnswer()->getQuestion()->getSession()->getQuestions() as $question) {
            /*
             * These questions don't have any answers
             */
            if ($question->getIsPrompt()) {
                continue;
            }

            /**
             * We will find all answer logs with current UUID
             */
            $answerLogs = $this->answerLogRepository->findByUuid($answerLog->getUuid());

            /**
             * We will then loop through answer logs and check if question is answered
             */
            $questionAnswered = false;

            foreach ($answerLogs as $answerLog) {
                /** @var AnswerLog $answerLog */
                if ($answerLog->getAnswer()->getQuestion() === $question) {
                    $questionAnswered = true;
                }
            }

            /*
             * If any of the questions is not answered yet, then session is not finished
             */
            if (!$questionAnswered) {
                $sessionFinished = false;
            }
        }

        if ($sessionFinished) {
            /** @var SessionLog $sessionLog */
            $sessionLog = $this->sessionLogRepository->findOneByUuid($answerLog->getUuid());

            $sessionLog->setFinishedAt(DateService::generateUTCDate());

            $this->sessionLogRepository->merge($sessionLog);
        }
    }
}
