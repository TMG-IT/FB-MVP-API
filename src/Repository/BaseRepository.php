<?php

namespace App\Repository;

use App\Entity\EntityInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;

/**
 * BaseRepository
 *
 * @author Zwer<ante@q-software.com>
 */
abstract class BaseRepository extends EntityRepository implements ServiceEntityRepositoryInterface
{
    public const ENTITY_CLASS_NAME = '';

    public function __construct(EntityManagerInterface $entityManager, ?ClassMetadata $metadata = null, ?RegistryInterface $registry = null)
    {
        if ($this::ENTITY_CLASS_NAME === '') {
            throw new \RuntimeException('Repository entity class name is empty');
        }

        if ($registry) {
            /** @var EntityManager $manager */
            $manager = $registry->getManagerForClass($this::ENTITY_CLASS_NAME);

            parent::__construct($manager, $manager->getClassMetadata($this::ENTITY_CLASS_NAME));
        } elseif ($entityManager instanceof EntityManager && $metadata instanceof ClassMetadata) {
            parent::__construct($entityManager, $metadata);
        } else {
            throw new \RuntimeException('Failed to initialize repository.');
        }
    }

    public function save(EntityInterface $entity, $flush = true): EntityInterface
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    public function merge(EntityInterface $entity, bool $flush = true): EntityInterface
    {
        /** @var EntityInterface $entity */
        $entity = $this->_em->merge($entity);
        if ($flush) {
            $this->_em->flush();
            $this->_em->refresh($entity);
        }

        return $entity;
    }

    public function flush(EntityInterface $entity = null)
    {
        $this->_em->flush($entity);
    }

    public function remove(EntityInterface $entity, $flush = true)
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findOr404(int $id): EntityInterface
    {
        /** @var EntityInterface $entity */
        $entity = $this->find($id);
        $entityName = null;

        if (null !== $entity) {
            try {
                $entityName = str_replace('Repository', '', array_values(\array_slice(explode('\\', \get_class($this)), -1))[0]);
            } catch (\Exception $e) {
                $entityName = 'Entity';
            } finally {
                if (\is_array($entityName)) {
                    $entityName = 'Entity';
                }
            }

            $message = sprintf('Resource of type %s and ID %s could not be found!', $entityName, $id);
            throw new NotFoundHttpException($message, null, Response::HTTP_NOT_FOUND);
        }

        return $entity;
    }
}
