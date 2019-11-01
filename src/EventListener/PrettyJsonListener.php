<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\KernelInterface;

class PrettyJsonListener implements EventSubscriberInterface
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => 'makeJsonResponsePretty',
        ];
    }

    public function makeJsonResponsePretty(FilterResponseEvent $event): void
    {
        $response = $event->getResponse();

        if (!$response instanceof JsonResponse) {
            return;
        }

        $request = $event->getRequest();

        if (!$this->kernel->isDebug() && $request->get('pretty') === null) {
            return;
        }

        $response->setEncodingOptions($response->getEncodingOptions() | JSON_PRETTY_PRINT);
    }
}
