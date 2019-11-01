<?php

namespace App\EventListener;

use App\Service\DateService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Entity\Log\SessionLog;
use App\Repository\SessionLogRepository;
use App\Event\SessionStartEvent;
use Ramsey\Uuid\Uuid;

class SessionStartSubscriber implements EventSubscriberInterface
{
    private $sessionLogRepository;

    public function __construct(SessionLogRepository $sessionLogRepository)
    {
        $this->sessionLogRepository = $sessionLogRepository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'session.started' => 'onSessionStarted',
        ];
    }

    /**
     * @param SessionStartEvent $event
     *
     * @throws \Exception
     */
    public function onSessionStarted(SessionStartEvent $event): void
    {
        $session = $event->getSession();

        $sessionLog = new SessionLog();

        $sessionLog
            ->setStartedAt(DateService::generateUTCDate())
            ->setSession($event->getSession())
            ->setUuid(Uuid::uuid1()->toString());

        /*
         * Return UUID as part of session when started
         */
        $session->setUuid($sessionLog->getUuid());

        $this->sessionLogRepository->save($sessionLog);
    }
}
