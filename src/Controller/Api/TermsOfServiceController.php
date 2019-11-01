<?php

namespace App\Controller\Api;

use App\Controller\BaseController;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\TermsOfServiceRepository;

class TermsOfServiceController extends BaseController
{
    /**
     * @Route(path="/terms", methods={"GET"})
     * @SWG\Get(
     *   tags={"TermsOfService"},
     *   summary="Fetch terms of service from database"
     * )
     *
     *
     * @SWG\Response(
     *     response=200,
     *     description="Requested TOS"
     * )
     *
     * @param TermsOfServiceRepository $termsOfServiceRepository
     *
     * @return JsonResponse
     */
    public function terms(TermsOfServiceRepository $termsOfServiceRepository): JsonResponse
    {
        $termsOfService = $termsOfServiceRepository->findAll();

        return $this->serializeToJsonResponse($termsOfService);
    }
}
