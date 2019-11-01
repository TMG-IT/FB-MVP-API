<?php

namespace App\Controller\Api;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\SessionService;
use App\Event\SessionStartEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\Events;

class SessionController extends BaseController
{
    /**
     * @SWG\Post(
     *   tags={"Session"},
     *   summary="Validate session code and return session data",
     *   @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="JSON body",
     *     required=true,
     *     @SWG\Schema(
     *          @SWG\Property(
     *              property="session_code",
     *              type="string",
     *              example="200000"
     *          )
     *        )
     *    ),
     *    @SWG\Response(
     *     response=200,
     *     description="Session validate success"
     *    ),
     *    @SWG\Response(
     *     response=403,
     *     description="Session expired"
     *    ),
     *    @SWG\Response(
     *     response=404,
     *     description="Session not found or invalid"
     *    ),
     * )
     *
     * @Route(path="/sessions", methods={"POST"})
     *
     * @param Request                  $request
     * @param SessionService           $sessionService
     * @param EventDispatcherInterface $dispatcher
     *
     * @return JsonResponse
     *
     * @throws \App\Exception\ExpiredSessionCodeException
     */
    public function validate(Request $request, SessionService $sessionService, EventDispatcherInterface $dispatcher): JsonResponse
    {
        $data = $this->extractDataFromRequest(['session_code'], $request);

        $session = $sessionService->validate($data['session_code']);

        $sessionStartEvent = new SessionStartEvent($session);
        $dispatcher->dispatch(Events::SessionStartEvent, $sessionStartEvent);

        return $this->serializeToJsonResponse($session);
    }
}
