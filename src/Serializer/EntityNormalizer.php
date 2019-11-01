<?php

namespace App\Serializer;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * Entity normalizer.
 */
class EntityNormalizer implements DenormalizerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(
        EntityManagerInterface $em
    ) {
        $this->em = $em;
    }

    /**
     * Denormalizes data back into an object of the given class.
     *
     * @param mixed  $data    Data to restore
     * @param string $class   The expected class to instantiate
     * @param string $format  Format the given data was extracted from
     * @param array  $context Options available to the denormalizer
     *
     * @return object
     *
     * @throws EntityNotFoundException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        $entity = null;

        if (\is_array($data) && isset($data['id'])) {
            $entity = $this->em->find($class, $data['id']);
        } elseif (\is_object($data)) {
            $entity = $data;
        }

        if (!$entity) {
            throw new EntityNotFoundException(sprintf('Entity for %s class and id %s not found', $class, $data['id']));
        }

        return $entity;
    }

    /**
     * Checks whether the given class is supported for denormalization by this normalizer.
     *
     * @param mixed  $data   Data to denormalize from
     * @param string $type   The class to which the data should be denormalized
     * @param string $format The format being deserialized from
     *
     * @return bool
     */
    public function supportsDenormalization($data, $type, $format = null): bool
    {
        if (strpos($type, 'App\\Entity\\') !== 0) {
            return false;
        }

        if (\is_array($data) && isset($data['id'])) {
            return true;
        }

        if (\is_object($data) && method_exists($data, 'getId')) {
            return true;
        }

        return false;
    }
}
