<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\SerializerInterface;

class BaseController extends AbstractController
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param $object
     * @param array $groups
     *
     * @return JsonResponse
     */
    protected function serializeToJsonResponse($object, array $groups = ['default']): JsonResponse
    {
        return new JsonResponse(
        $this->serializer->serialize($object, 'json', ['groups' => $groups]),
        JsonResponse::HTTP_OK,
        [],
        true
    );
    }

    /**
     * @param array   $requiredFields
     * @param Request $request
     *
     * @return array
     */
    protected function extractDataFromRequest(array $requiredFields, Request $request): array
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            throw new BadRequestHttpException('Invalid request body');
        }

        foreach ($requiredFields as $field) {
            if (!array_key_exists($field, $data)) {
                throw new BadRequestHttpException('Missing parameter: '.$field);
            }
        }

        return $data;
    }

    /**
     * @return JsonResponse
     */
    protected function generateNoContentResponse(): JsonResponse
    {
        return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
