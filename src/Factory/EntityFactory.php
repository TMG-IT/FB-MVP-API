<?php

namespace App\Factory;

use App\Entity\EntityInterface;
use App\Service\ValidatorService;
use Symfony\Component\Serializer\SerializerInterface;

final class EntityFactory
{
    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var ValidatorService
     */
    private $validator;

    /**
     * EntityFactory constructor.
     *
     * @param SerializerInterface $serializer
     * @param ValidatorService    $validator
     */
    public function __construct(
        SerializerInterface $serializer,
        ValidatorService $validator
    ) {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     * @param string $data
     * @param string $class
     * @param array  $groups
     * @param array  $context
     *
     * @return EntityInterface
     */
    public function createFromJson(string $data, string $class, array $groups = [], array $context = []): EntityInterface
    {
        return $this->create($data, $class, 'json', $groups, $context);
    }

    /**
     * @param array  $data
     * @param string $class
     * @param array  $groups
     * @param array  $context
     *
     * @return EntityInterface
     */
    public function createFromArray(array $data, string $class, array $groups = [], array $context = []): EntityInterface
    {
        return $this->create($data, $class, 'array', $groups, $context);
    }

    /**
     * @param $data
     * @param string $class
     * @param string $format
     * @param array  $groups
     * @param array  $context
     *
     * @return EntityInterface
     *
     * @throws \App\Exception\VerboseExceptionInterface
     */
    private function create($data, string $class, string $format, array $groups = [], array $context = []): EntityInterface
    {
        if (\count($groups)) {
            $context['groups'] = $groups;
        }

        /** @var EntityInterface $entity */
        $entity = $this->serializer->deserialize($data, $class, $format, $context);

        $this->validator->validate($entity, $groups);

        return $entity;
    }
}
