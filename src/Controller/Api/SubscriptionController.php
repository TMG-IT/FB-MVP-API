<?php

namespace App\Controller\Api;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;
use App\Entity\Subscription;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Factory\EntityFactory;
use App\Repository\SubscriptionRepository;

class SubscriptionController extends BaseController
{
    /**
     * @SWG\Post(
     *   tags={"Subscription"},
     *   summary="Record subscription for newsletter",
     *   @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="JSON body",
     *     required=true,
     *     @SWG\Schema(
     *          @SWG\Property(
     *              property="first_name",
     *              type="string",
     *              example="Bob"
     *          ),
     *          @SWG\Property(
     *              property="last_name",
     *              type="string",
     *              example="Gomez"
     *          ),
     *          @SWG\Property(
     *              property="email",
     *              type="string",
     *              example="bobgomez@example.com"
     *          )
     *        )
     *    ),
     *    @SWG\Response(
     *        response=200,
     *        description="Success response, subscription successfully added",
     *    )
     *  )
     * )
     *
     * @Route(path="/subscribe", methods={"POST"})
     *
     * @param Request                $request
     * @param EntityFactory          $factory
     * @param SubscriptionRepository $subscriptionRepository
     *
     * @return JsonResponse
     */
    public function create(Request $request, EntityFactory $factory, SubscriptionRepository $subscriptionRepository): JsonResponse
    {
        /** @var Subscription $subscription */
        $subscription = $factory->createFromArray($request->request->all(), Subscription::class, ['default']);

        $subscriptionRepository->save($subscription);

        return $this->serializeToJsonResponse($subscription);
    }
}
