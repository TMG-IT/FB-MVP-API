<?php

namespace App\Validator;

use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Mapping\Loader\LoaderInterface;
use Symfony\Component\Validator\Mapping\PropertyMetadata;

class SerializerAnnotationLoader implements LoaderInterface
{
    /**
     * @var Reader
     */
    protected $reader;

    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    public function loadClassMetadata(ClassMetadata $metadata): bool
    {
        $success = false;

        $reflClass = $metadata->getReflectionClass();
        $className = $reflClass->name;
        $defaultGroup = $metadata->defaultGroup;

        // get serialization groups
        $serializationGroups = [];
        foreach ($reflClass->getProperties() as $property) {
            if ($property->getDeclaringClass()->name === $className) {
                $serializationGroups[$property->name] = [];
                foreach ($this->reader->getPropertyAnnotations($property) as $annotation) {
                    if ($annotation instanceof Groups) {
                        $serializationGroups[$property->name] = $annotation->getGroups();
                    }
                }
            }
        }

        foreach ($metadata->getConstrainedProperties() as $property) {
            if (
                isset($serializationGroups[$property]) &&
                \count($serializationGroups[$property])
            ) {
                $success = true;
                /** @var PropertyMetadata[] $members */
                $members = $metadata->getPropertyMetadata($property);

                foreach ($members as $member) {
                    /** @var Constraint $constraint */
                    foreach ($member->getConstraints() as $constraint) {
                        // append serialization groups only if there are no explicit groups set
                        if (empty(array_diff($constraint->groups, [Constraint::DEFAULT_GROUP, $defaultGroup]))) {
                            foreach ($serializationGroups[$property] as $serializationGroup) {
                                // add group to constraint
                                $constraint->addImplicitGroupName($serializationGroup);

                                // add constraint to sort by groups
                                if (!isset($member->constraintsByGroup[$serializationGroup])) {
                                    $member->constraintsByGroup[$serializationGroup] = [];
                                }

                                $member->constraintsByGroup[$serializationGroup][] = $constraint;
                            }
                        }
                    }
                }
            }
        }

        return $success;
    }
}
