<?php

namespace App\Controller\Api;

use App\Controller\BaseController;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\PrivacyPolicyRepository;

class PrivacyPolicyController extends BaseController
{
    /**
     * @Route(path="/policy", methods={"GET"})
     * @SWG\Get(
     *   tags={"PrivacyPolicy"},
     *   summary="Fetch privacy policy from database"
     * )
     *
     *
     * @SWG\Response(
     *     response=200,
     *     description="Requested Privacy Policy"
     * )
     */
    public function policy(PrivacyPolicyRepository $repository): JsonResponse
    {
        $privacyPolicy = $repository->findAll();

        return $this->serializeToJsonResponse($privacyPolicy);
    }
}
