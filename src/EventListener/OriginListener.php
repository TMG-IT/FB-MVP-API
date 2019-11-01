<?php

namespace App\EventListener;

use GuzzleHttp\Psr7\Uri;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class OriginListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }

    /**
     * Add Access-Control-Allow headers.
     *
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event): void
    {
        $requestReferer = $event->getRequest()->headers->get('Referer');

        $referers = [];
        if (\is_array($requestReferer)) {
            $referers = $requestReferer;
        } elseif (\is_string($requestReferer) && '' !== $requestReferer) {
            $referers = [$requestReferer];
        }

        if (\count($referers)) {
            foreach ($referers as $referer) {
                try {
                    // remove query and path from referer
                    $origin = (string) (new Uri($referer))->withQuery('')->withPath('');

                    $event->getResponse()->headers->set('Access-Control-Allow-Origin', $origin);
                } catch (\InvalidArgumentException $exception) {
                    // skip broken referer
                }
            }
        } else {
            $event->getResponse()->headers->set('Access-Control-Allow-Origin', '*');
        }

        $event->getResponse()->headers->set('Access-Control-Allow-Credentials', 'true');
        $event->getResponse()->headers->set('Access-Control-Allow-Headers', 'content-type, *');
    }
}
