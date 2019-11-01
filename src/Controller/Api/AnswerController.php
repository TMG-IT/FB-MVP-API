<?php

namespace App\Controller\Api;

use App\Controller\BaseController;
use App\Entity\Answer;
use App\Entity\Question\Question;
use App\Factory\EntityFactory;
use App\Repository\AnswerRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\Event\AnswerCreateEvent;
use App\Events;

class AnswerController extends BaseController
{
    /**
     * @SWG\Post(
     *   tags={"Answer"},
     *   summary="Answer to the question",
     *   @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="JSON body",
     *     required=true,
     *     @SWG\Schema(
     *          @SWG\Property(
     *              property="text",
     *              type="string",
     *              example="Strongly agree"
     *          ),
     *          @SWG\Property(
     *              property="answer_placeholder",
     *              type="object",
     *              example={
     *                "id"=1
     *              }
     *          ),
     *          @SWG\Property(
     *              property="uuid",
     *              type="string",
     *              example="dcc7fe1c-f0e1-11e9-9bf6-8c8590bfa163"
     *          )
     *        )
     *    ),
     *    @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     description="question id",
     *     type="integer",
     *     required=true,
     *     default=4
     *   ),
     *    @SWG\Response(
     *        response=200,
     *        description="Success response",
     *    )
     *  )
     * )
     *
     * @Route(path="/questions/{id}/answers", methods={"POST"})
     *
     * @param Request                  $request
     * @param Question                 $question
     * @param EntityFactory            $factory
     * @param AnswerRepository         $answerRepository
     * @param EventDispatcherInterface $dispatcher
     *
     * @return JsonResponse
     */
    public function create(Request $request, Question $question, EntityFactory $factory, AnswerRepository $answerRepository, EventDispatcherInterface $dispatcher): JsonResponse
    {
        $data = $this->extractDataFromRequest(['text', 'answer_placeholder', 'uuid'], $request);

        /** @var Answer $answer */
        $answer = $factory->createFromArray($request->request->all(), Answer::class, ['default']);

        $answer->setQuestion($question);
        $answerRepository->save($answer);

        $answerCreateEvent = (new AnswerCreateEvent($answer))->setUuid($data['uuid']);
        $dispatcher->dispatch(Events::AnswerCreateEvent, $answerCreateEvent);

        return $this->serializeToJsonResponse($answer);
    }
}
